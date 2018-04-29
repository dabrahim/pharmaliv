<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 4/7/2018
 * Time: 10:21 AM
 */

class DocumentService implements DocumentDAO {
    private $_db;

    public function __construct() {
        $this->_db = CustomPDO::getInstance();
    }

    public function create(Document $document) {
        $pdoStatement = $this->_db->prepare ( 'INSERT INTO document SET titre = :titre, description = :description, type = :type, nom_fichier = :nomFichier, date_ajout = NOW(), id_utilisateur = :idUtilisateur' );
        $pdoStatement->bindValue( ':titre', $document->getTitre(), PDO::PARAM_STR );
        $pdoStatement->bindValue( ':description', $document->getDescription(), PDO::PARAM_LOB );
        $pdoStatement->bindValue( ':type', $document->getType(), PDO::PARAM_STR );
        $pdoStatement->bindValue( ':nomFichier', $document->getNomFichier(), PDO::PARAM_STR );
        $pdoStatement->bindValue( ':idUtilisateur', $document->getUtilisateur()->getIdUtilisateur(), PDO::PARAM_INT );
        $pdoStatement->execute();
        return $this->_db->lastInsertId();
    }

    public function findByClient(Client $client) {
        $pdoStatement = $this->_db->prepare( 'SELECT * FROM document WHERE id_utilisateur = :idUtilisateur' );
        $pdoStatement->bindValue(':idUtilisateur', $client->getIdUtilisateur(), PDO::PARAM_INT);
        $pdoStatement->execute();
        return $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByCommande(Commande $commande) {
        $pdoStatement = $this->_db->prepare( 'SELECT d.titre, d.type, d.nom_fichier FROM document d INNER JOIN commande_document cd ON cd.id_document = d.id_document WHERE cd.id_commande = :idCommande' );
        $pdoStatement->bindValue( ':idCommande', $commande->getIdCommande(), PDO::PARAM_INT );
        $pdoStatement->execute();
        return $pdoStatement->fetchAll(PDO::FETCH_CLASS);
    }

}