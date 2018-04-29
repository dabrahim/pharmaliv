<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 4/3/2018
 * Time: 12:25 PM
 */

class UtilisateurService implements UtilisateurDAO {
    private $_db;

    public function __construct() {
        $this->_db = CustomPDO::getInstance();
    }

    public function create(Utilisateur $utilisateur) {
        $pdoStatement = $this->_db->prepare( 'INSERT INTO utilisateur SET email = :email, mot_de_passe = :mdp, type = :type, date_inscription = NOW()' );
        $pdoStatement->bindValue ( ':email', $utilisateur->getEmail(), PDO::PARAM_STR );
        $pdoStatement->bindValue ( ':mdp', $utilisateur->getMotDePasse(), PDO::PARAM_STR );
        $pdoStatement->bindValue ( ':type', $utilisateur->getType(), PDO::PARAM_STR );
        $pdoStatement->execute();
        return $this->_db->lastInsertId ();
    }

    public function findByEmail( $email ) {
        $pdoStatement = $this->_db->prepare ( 'SELECT * FROM utilisateur WHERE email = :email' );
        $pdoStatement->bindValue ( ':email', $email, PDO::PARAM_STR );
        $pdoStatement->execute();
        if ( $user = $pdoStatement->fetch(PDO::FETCH_ASSOC) ){
            return $user;
        } else {
            return false;
        }
    }

    public function exists ( Utilisateur $utilisateur ) {
        $pdoStatement = $this->_db->prepare( 'SELECT * FROM utilisateur WHERE email = :email' );
        $pdoStatement->bindValue ( ':email', $utilisateur->getEmail(), PDO::PARAM_STR );
        $pdoStatement->execute();
        if ( $user = $pdoStatement->fetch(PDO::FETCH_ASSOC) ){
            if ( $user['email'] === $utilisateur->getEmail() && $user['mot_de_passe'] === $utilisateur->getMotDePasse() ){
                return new Utilisateur ($user['id_utilisateur'], $user['email'], $user['type']);
            }
        }
        return false;
    }

}