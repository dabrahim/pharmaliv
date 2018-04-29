<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 4/9/2018
 * Time: 11:43 AM
 */
class Zone {
    private $_idZone;
    private $_nom;
    private $_coordGeo;
    private $_rayon;

    /**
     * @return mixed
     */
    public function getCoordGeo()
    {
        return $this->_coordGeo;
    }

    /**
     * @param mixed $coordGeo
     */
    public function setCoordGeo($coordGeo)
    {
        $this->_coordGeo = $coordGeo;
    }

    /**
     * @return mixed
     */
    public function getRayon()
    {
        return $this->_rayon;
    }

    /**
     * @param mixed $rayon
     */
    public function setRayon($rayon)
    {
        $this->_rayon = $rayon;
    }

    /**
     * @return mixed
     */
    public function getIdZone()
    {
        return $this->_idZone;
    }

    /**
     * @param mixed $idZone
     */
    public function setIdZone($idZone)
    {
        $this->_idZone = $idZone;
    }

    /**
     * @return mixed
     */
    public function getNom()
    {
        return $this->_nom;
    }

    /**
     * @param mixed $nom
     */
    public function setNom($nom)
    {
        $this->_nom = $nom;
    }

    public function hydrate (array $data) {
        foreach ($data as $key => $value){
            $getter = 'set' . ucfirst($key);
            if (method_exists($this, $getter)){
                $this->$getter ( $value );
            }
        }
    }
}