<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 4/9/2018
 * Time: 11:58 AM
 */

class LivreurService implements  LivreurDAO {
    private $_db;

    public function __construct()
    {
        $this->_db = CustomPDO::getInstance();
    }

    public function create(Livreur $livreur) {
        try {
            $this->_db->beginTransaction();

            $pdoStatement = $this->_db->prepare( 'INSERT INTO utilisateur SET email = :email, mot_de_passe = :motDePasse, date_inscription = NOW(), type = "LIVREUR"' );
            $pdoStatement->bindValue( ':email', $livreur->getEmail(), PDO::PARAM_STR );
            $pdoStatement->bindValue( ':motDePasse', $livreur->getMotDePasse(), PDO::PARAM_STR );

            if ($pdoStatement->execute()){
                $idLivreur = $this->_db->lastInsertId();
                $zones = $livreur->getZones();

                $ps = $this->_db->prepare( 'INSERT INTO livreur SET id_utilisateur = :idUtilisateur, nom_complet = :nomComplet, cle_inscription = :clefInscription' );
                $ps->bindValue(':idUtilisateur', $idLivreur, PDO::PARAM_INT);
                $ps->bindValue(':nomComplet', $livreur->getNomComplet(), PDO::PARAM_STR);
                $ps->bindValue(':clefInscription', $livreur->getCleInscription(), PDO::PARAM_STR);
                $ps->execute();

                $ps1 = $this->_db->prepare('INSERT INTO livreur_zone SET id_utilisateur = :idUtilisateur, id_zone = :idZone');
                $ps1->bindValue(':idUtilisateur', $idLivreur, PDO::PARAM_INT);

                foreach ($zones as $zone){
                    $ps1->bindValue( ':idZone', $zone->getIdZone(), PDO::PARAM_INT );
                    $ps1->execute();
                }
            }

            $this->_db->commit();
        } catch (PDOException $e){
            $this->_db->rollback();
            throw $e;
        }
    }

    public function isClefValide($clef) {
        $pdoStatement = $this->_db->prepare( 'SELECT id_utilisateur FROM livreur WHERE cle_inscription = :clef' );
        $pdoStatement->bindValue( ':clef', $clef, PDO::PARAM_STR);
        $pdoStatement->execute();
        return $pdoStatement->fetch(PDO::FETCH_ASSOC);
    }

    public function register(Livreur $livreur) {
        try {
            $this->_db->beginTransaction();

            $pdoStatement = $this->_db->prepare('UPDATE livreur SET nom_complet = :nomComplet WHERE id_utilisateur = :idUser');
            $pdoStatement->bindValue(':idUser', $livreur->getIdUtilisateur(), PDO::PARAM_STR);
            $pdoStatement->bindValue(':nomComplet', $livreur->getNomComplet(), PDO::PARAM_STR);
            $rslt = $pdoStatement->execute();

            if ($rslt){
                $pdoStatement = $this->_db->prepare( 'UPDATE utilisateur SET email = :email, mot_de_passe = :motDePasse WHERE id_utilisateur = :idLivreur' );
                $pdoStatement->bindValue( ':email', $livreur->getEmail(), PDO::PARAM_STR );
                $pdoStatement->bindValue( ':motDePasse', $livreur->getMotDePasse(), PDO::PARAM_STR );
                $pdoStatement->bindValue( ':idLivreur', $livreur->getIdUtilisateur(), PDO::PARAM_INT  );

                if ($pdoStatement->execute()){
                    $pdoStatement = $this->_db->prepare( 'UPDATE livreur SET cle_inscription = NULL WHERE id_utilisateur = :idUser' );
                    $pdoStatement->bindValue( ':idUser', $livreur->getIdUtilisateur(), PDO::PARAM_INT );
                    $pdoStatement->execute();
                }
            }

            $this->_db->commit();
        } catch (PDOException $e){
            $this->_db->rollback();
            throw  $e;
        }
    }


}