<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 4/3/2018
 * Time: 12:02 PM
 */

class PharmacieService implements PharmacieDAO {
    private $_db;

    public function __construct(){
        $this->_db = CustomPDO::getInstance();
    }

    public function create(Pharmacie $pharmacie) {
        $pdoStatement = $this->_db->prepare( 'INSERT INTO pharmacie SET id_utilisateur = :idUtilisateur, coord_geo = :coordGeo, heure_ouverture = :heureOuverture, heure_fermeture = :heureFermeture, adresse = :adresse, num_fixe = :numFixe, num_mobile = :numMobile, nom = :nom' );
        $pdoStatement->bindValue( ':idUtilisateur', $pharmacie->getUtilisateur()->getIdUtilisateur(), PDO::PARAM_INT );
        $pdoStatement->bindValue( ':coordGeo', $pharmacie->getCoordGeo(), PDO::PARAM_STR );
        $pdoStatement->bindValue( ':heureOuverture', $pharmacie->getHeureOuverture(), PDO::PARAM_INT );
        $pdoStatement->bindValue( ':heureFermeture', $pharmacie->getHeureFermeture(), PDO::PARAM_INT );
        $pdoStatement->bindValue( ':adresse', $pharmacie->getAdresse(), PDO::PARAM_LOB );
        $pdoStatement->bindValue( ':numFixe', $pharmacie->getNumFixe(), PDO::PARAM_STR );
        $pdoStatement->bindValue( ':numMobile', $pharmacie->getNumMobile(), PDO::PARAM_STR );
        $pdoStatement->bindValue( ':nom', $pharmacie->getNom(), PDO::PARAM_STR );
        $pdoStatement->execute();
        return $this->_db->lastInsertId();
    }

    public function findByUtilisateur(Utilisateur $utilisateur) {
        $pdoStatement = $this->_db->prepare( 'SELECT * FROM pharmacie WHERE id_utilisateur = :idUtilisateur' );
        $pdoStatement->bindValue( ':idUtilisateur', $utilisateur->getIdUtilisateur(), PDO::PARAM_INT );
        $pdoStatement->execute();
        return $pdoStatement->fetch( PDO::FETCH_ASSOC );
    }

    public function getAll() {
        $pdoStatement = $this->_db->query( 'SELECT * FROM pharmacie' );
        return $pdoStatement->fetchAll( PDO::FETCH_ASSOC );
    }


}