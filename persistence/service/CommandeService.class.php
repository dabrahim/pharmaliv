<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 4/8/2018
 * Time: 6:56 PM
 */

class CommandeService implements  CommandeDAO {
    private $_db;

    public function __construct() {
        $this->_db = CustomPDO::getInstance();
    }

    public function create(Commande $commande) {
        try {
            $this->_db->beginTransaction();

            $pdoStatement = $this->_db->prepare( 'INSERT INTO commande SET id_utilisateur = :idUtilisateur, id_pharmacie = :idPharmacie, date_livraison = :dateLivraison, heure_livraison = :heureLivraison, mode_paiement = :modePaiement, etat = "PENDING", date_envoi = NOW() ' );
            $pdoStatement->bindValue( ':idUtilisateur', $commande->getUtilisateur()->getIdUtilisateur(), PDO::PARAM_INT );
            $pdoStatement->bindValue( ':idPharmacie', $commande->getPharmacie()->getIdPharmacie(), PDO::PARAM_INT );
            $pdoStatement->bindValue( ':dateLivraison', $commande->getDateLivraison(), PDO::PARAM_STR );
            $pdoStatement->bindValue( ':heureLivraison', $commande->getHeureLivraison(), PDO::PARAM_INT );
            $pdoStatement->bindValue( ':modePaiement', $commande->getModePaiement(), PDO::PARAM_STR );

            if ($pdoStatement->execute()){
                $idCommande = $this->_db->lastInsertId();

                $produits = $commande->getProduits();
                $documents = $commande->getDocuments();

                $ps1 = $this->_db->prepare( 'INSERT INTO commande_document SET id_commande = :idCommande, id_document = :idDocument ' );
                foreach ($documents as $document){
                    $ps1->bindValue( ':idCommande', $idCommande, PDO::PARAM_INT );
                    $ps1->bindValue( 'idDocument', $document->getIdDocument(), PDO::PARAM_INT );
                    $ps1->execute();
                }

                $ps2 = $this->_db->prepare( 'INSERT INTO commande_produit SET id_commande = :idCommande, id_produit = :idProduit, quantite = :quantite' );
                foreach ($produits as $produit){
                    $ps2->bindValue( ':idCommande', $idCommande, PDO::PARAM_INT );
                    $ps2->bindValue( 'idProduit', $produit->getIdProduit(), PDO::PARAM_INT );
                    $ps2->bindValue( 'quantite', $produit->getQuantite(), PDO::PARAM_INT );
                    $ps2->execute();
                }
            }

            $this->_db->commit();

        } catch (PDOException $e){
            $this->_db->rollback();
            throw new Exception($e->getMessage());
        }
    }

    public function findCommandesNonTraitees(Pharmacie $pharmacie) {
        $pdoStatement = $this->_db->prepare( 'SELECT * FROM commande WHERE id_pharmacie = :idPharmacie AND etat = "PENDING"' );
        $pdoStatement->bindValue(':idPharmacie', $pharmacie->getIdPharmacie(), PDO::PARAM_INT);
        $pdoStatement->execute();
        return $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function validerCommande(Commande $commande) {
        $pdoStatement = $this->_db->prepare( 'SELECT p.coord_geo FROM pharmacie p INNER JOIN commande c ON p.id_pharmacie = c.id_pharmacie WHERE c.id_commande = :idCommande' );
        $pdoStatement->bindValue( ':idCommande', $commande->getIdCommande(), PDO::PARAM_INT);
        $pdoStatement->execute();

        $pharm = $pdoStatement->fetch(PDO::FETCH_ASSOC);
        $pharm = explode(',', $pharm['coord_geo']);

        $pdoStatement = $this->_db->prepare( 'SELECT l.id_utilisateur, z.coordGeo, z.rayon FROM livreur l INNER JOIN livreur_zone lz ON lz.id_utilisateur = l.id_utilisateur INNER JOIN zone z ON z.id_zone = lz.id_zone' );
        $pdoStatement->execute();

        $rslt = $pdoStatement->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rslt as $key => $value) {
            $rslt[$key]['coordGeo'] = explode(',', $rslt[$key]['coordGeo']);
        }

        foreach ($rslt as $key => $value){
            $rslt[$key]['dist'] = haversineGreatCircleDistance( $value['coordGeo'][0], $value['coordGeo'][1], $pharm[0], $pharm[1] );
        }

        usort($rslt, function ($item1, $item2) {
            if ($item1['dist'] == $item2['dist']) return 0;
            return $item1['dist'] < $item2['dist'] ? -1 : 1;
        });

        $idLivreur = $rslt[0]['id_utilisateur'];

        $pdoStatement = $this->_db->prepare( 'INSERT INTO livraison SET id_livreur = :idLivreur, id_commande = :idCommande' );
        $pdoStatement->bindValue( ':idLivreur', $idLivreur, PDO::PARAM_INT );
        $pdoStatement->bindValue( ':idCommande', $commande->getIdCommande(), PDO::PARAM_INT );
        $pdoStatement->execute();

        $pdoStatement = $this->_db-> prepare('UPDATE commande SET etat= :etat WHERE id_commande= :idCommande');
        $pdoStatement->bindValue(':etat', 'VALIDEE', PDO::PARAM_STR);
        $pdoStatement->bindValue(':idCommande', $commande->getIdCommande(), PDO::PARAM_INT);
        return $pdoStatement->execute();
    }

    public function getCommandesLivreur(Livreur $livreur) {
        $pdoStatement = $this->_db->prepare( 'SELECT c.id_commande, c.date_livraison, c.heure_livraison FROM commande c INNER JOIN livraison l ON l.id_commande = c.id_commande WHERE l.id_livreur = :idLivreur' );
        $pdoStatement->bindValue( ':idLivreur', $livreur->getIdUtilisateur(), PDO::PARAM_INT );
        $pdoStatement->execute( );
        return $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
    }


}