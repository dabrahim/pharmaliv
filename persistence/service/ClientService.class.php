<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 4/7/2018
 * Time: 8:37 AM
 */

class ClientService implements  ClientDAO {
    private $_db;

    public function __construct() {
        $this->_db = CustomPDO::getInstance();
    }

    public function create(Client $client) {
        $pdoStatement = $this->_db->prepare( 'INSERT INTO client SET nom_complet = :nomComplet, numero_telephone = :numeroTelephone, sexe = :sexe, date_de_naissance = :dateDeNaissance, id_utilisateur = :idUtilisateur' );
        $pdoStatement->bindValue( ':nomComplet', $client->getNomComplet(), PDO::PARAM_STR );
        $pdoStatement->bindValue( ':numeroTelephone', $client->getNumeroTelephone(), PDO::PARAM_STR );
        $pdoStatement->bindValue( ':sexe', $client->getSexe(), PDO::PARAM_STR );
        $pdoStatement->bindValue( ':dateDeNaissance', $client->getDateDeNaissance(), PDO::PARAM_STR );
        $pdoStatement->bindValue( ':idUtilisateur', $client->getIdUtilisateur(), PDO::PARAM_INT );
        $pdoStatement->execute();
    }

    public function findById($idClient) {
        $pdoStatement = $this->_db->prepare( 'SELECT * FROM client WHERE id_utilisateur = :idUtilisateur' );
        $pdoStatement->bindValue( ':idUtilisateur', $idClient, PDO::PARAM_INT );
        $pdoStatement->execute();
        return $pdoStatement->fetch(PDO::PARAM_INT);
    }

    public function findByCommande(Commande $commande) {
        $pdoStatement = $this->_db->prepare( 'SELECT c.id_utilisateur ,c.nom_complet, c.sexe, c.date_de_naissance FROM client c INNER JOIN commande cm ON c.id_utilisateur = cm.id_utilisateur WHERE cm.id_commande = :idCommande' );
        $pdoStatement->bindValue( ':idCommande', $commande->getIdCommande(), PDO::PARAM_INT );
        $pdoStatement->execute();
        return $pdoStatement->fetch(PDO::FETCH_ASSOC);
    }


}