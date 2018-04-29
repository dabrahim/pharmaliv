<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 4/9/2018
 * Time: 10:03 AM
 */

class Notification {
    private $_idNotification;
    private $_dateEnvoi;
    private $_etat;
    private $_objet;
    private $_message;
    private $_utilisateur;

    /**
     * @return mixed
     */
    public function getIdNotification()
    {
        return $this->_idNotification;
    }

    /**
     * @param mixed $idNotification
     */
    public function setIdNotification($idNotification)
    {
        $this->_idNotification = $idNotification;
    }

    /**
     * @return mixed
     */
    public function getDateEnvoi()
    {
        return $this->_dateEnvoi;
    }

    /**
     * @param mixed $dateEnvoi
     */
    public function setDateEnvoi($dateEnvoi)
    {
        $this->_dateEnvoi = $dateEnvoi;
    }

    /**
     * @return mixed
     */
    public function getEtat()
    {
        return $this->_etat;
    }

    /**
     * @param mixed $etat
     */
    public function setEtat($etat)
    {
        $this->_etat = $etat;
    }

    /**
     * @return mixed
     */
    public function getObjet()
    {
        return $this->_objet;
    }

    /**
     * @param mixed $objet
     */
    public function setObjet($objet)
    {
        $this->_objet = $objet;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->_message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->_message = $message;
    }

    /**
     * @return mixed
     */
    public function getUtilisateur()
    {
        return $this->_utilisateur;
    }

    /**
     * @param mixed $utilisateur
     */
    public function setUtilisateur(Utilisateur $utilisateur)
    {
        $this->_utilisateur = $utilisateur;
    }
}