<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 4/7/2018
 * Time: 8:24 AM
 */

class Client extends Utilisateur {
    private $_dateDeNaissance;
    private $_sexe;
    private $_numeroTelephone;

    /**
     * @return mixed
     */
    public function getDateDeNaissance()
    {
        return $this->_dateDeNaissance;
    }

    /**
     * @param mixed $dateDeNaissance
     */
    public function setDateDeNaissance($dateDeNaissance)
    {
        $this->_dateDeNaissance = $dateDeNaissance;
    }

    /**
     * @return mixed
     */
    public function getSexe()
    {
        return $this->_sexe;
    }

    /**
     * @param mixed $sexe
     */
    public function setSexe($sexe)
    {
        $this->_sexe = $sexe;
    }

    /**
     * @return mixed
     */
    public function getNumeroTelephone()
    {
        return $this->_numeroTelephone;
    }

    /**
     * @param mixed $numero_telephone
     */
    public function setNumeroTelephone($numero_telephone)
    {
        $this->_numeroTelephone = $numero_telephone;
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