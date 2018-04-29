<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 4/4/2018
 * Time: 11:08 AM
 */

class ProduitService implements ProduitDAO {
    private $_db;

    public function __construct() {
        $this->_db = CustomPDO::getInstance();
    }

    public function create(Produit $produit) {
        $pdoStatement = $this->_db->prepare( 'INSERT INTO produit SET titre = :titre, prix = :prix, description = :description, type = :type,  nom_fichier = :nomFichier, id_pharmacie = :idPharmacie' );
        $pdoStatement->bindValue( ':titre', $produit->getTitre() , PDO::PARAM_STR );
        $pdoStatement->bindValue( ':prix', $produit->getPrix() , PDO::PARAM_INT );
        $pdoStatement->bindValue( ':description', $produit->getDescription() , PDO::PARAM_LOB );
        $pdoStatement->bindValue( ':type', $produit->getType() , PDO::PARAM_STR );
        $pdoStatement->bindValue( ':nomFichier', $produit->getNomFichier() , PDO::PARAM_STR );
        $pdoStatement->bindValue( ':idPharmacie', $produit->getIdPharmacie(), PDO::PARAM_STR );
        $pdoStatement->execute();
        return $this->_db->lastInsertId();
    }

    public function findByPharmacie ( Pharmacie $pharmacie ) {
        $pdoStatement = $this->_db->prepare( 'SELECT * FROM produit WHERE id_pharmacie = :idPharmacie' );
        $pdoStatement->bindValue( ':idPharmacie', $pharmacie->getIdPharmacie(), PDO::PARAM_INT );
        $pdoStatement->execute();
        return $pdoStatement->fetchAll( PDO::FETCH_ASSOC );
    }

    public function findByCommande(Commande $commande) {
        $pdoStatement = $this->_db->prepare( 'SELECT p.titre, p.type, p.nom_fichier, p.prix, cp.quantite FROM produit p INNER JOIN commande_produit cp ON p.id_produit = cp.id_produit WHERE cp.id_commande = :idCommande' );
        $pdoStatement->bindValue( ':idCommande', $commande->getIdCommande(), PDO::PARAM_INT );
        $pdoStatement->execute();
        return $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByPharmacien(Pharmacie $pharmacie) {
        $pdoStatement = $this->_db->prepare( 'SELECT * FROM produit WHERE id_pharmacie = :idPharmacie' );
        $pdoStatement->bindValue(':idPharmacie', $pharmacie->getIdPharmacie(), PDO::PARAM_INT);
        $pdoStatement->execute();
        return $pdoStatement->fetch(PDO::FETCH_ASSOC);
    }


}