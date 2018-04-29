<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 4/9/2018
 * Time: 10:08 AM
 */

class NotificationService implements NotificationDAO {
    private $_db;

    public function __construct()
    {
        $this->_db= CustomPDO::getInstance();
    }

    public function create(Notification $notification)
    {
        $pdoStatement = $this->_db->prepare('INSERT INTO notification SET date_envoi= NOW(), etat="NON_LU", objet=:objet, message=:message, id_destinataire=:idDestinataire');
        $pdoStatement->bindValue(':objet', $notification->getObjet(),PDO::PARAM_STR);
        $pdoStatement->bindValue(':message', $notification->getMessage(),PDO::PARAM_LOB);
        $pdoStatement->bindValue(':idDestinataire', $notification->getUtilisateur()->getIdUtilisateur(),PDO::PARAM_INT);
        return $pdoStatement->execute();
    }

    public function setVu(Notification $notification)
    {
        $pdoStatement = $this->_db->prepare('UPDATE notification SET etat="LU" WHERE id_notification=:idNotification');
        $pdoStatement->bindValue(':idNotification', $notification->getIdNotification(), PDO::PARAM_INT);
        return $pdoStatement->execute();
    }

    public function findByUtilisateur(Utilisateur $utilisateur)
    {
        $pdoStatement = $this-> _db->prepare('SELECT * FROM notification WHERE etat="NON_LU" AND id_destinataire=:idDestinataire');
        $pdoStatement->bindValue(':idDestinataire', $utilisateur->getIdUtilisateur(), PDO::PARAM_INT);
        return $pdoStatement->execute();
    }


}