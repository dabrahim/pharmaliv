<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 4/9/2018
 * Time: 11:55 AM
 */

class Livreur extends Utilisateur {
    private $_nomComplet;
    private $_cleInscription;
    private $_zones;

    public function __construct()
    {
        $this->_zones = array();
    }

    /**
     * @return array
     */
    public function getZones()
    {
        return $this->_zones;
    }

    /**
     * @param array $zones
     */
    public function setZones(array $zones)
    {
        $this->_zones = $zones;
    }

    /**
     * @return mixed
     */
    public function getNomComplet()
    {
        return $this->_nomComplet;
    }

    /**
     * @param mixed $nomComplet
     */
    public function setNomComplet($nomComplet)
    {
        $this->_nomComplet = $nomComplet;
    }

    /**
     * @return mixed
     */
    public function getCleInscription()
    {
        return $this->_cleInscription;
    }

    /**
     * @param mixed $cleInscription
     */
    public function setCleInscription($cleInscription)
    {
        $this->_cleInscription = $cleInscription;
    }
}