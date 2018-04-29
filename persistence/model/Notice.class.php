<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 4/4/2018
 * Time: 12:23 AM
 */

class Notice {
    private $_contenu;
    private $_nomMedicament;
    private $_idNotice;

    /**
     * @return mixed
     */
    public function getContenu()
    {
        return $this->_contenu;
    }

    /**
     * @param mixed $contenu
     */
    public function setContenu($contenu)
    {
        $this->_contenu = $contenu;
    }

    /**
     * @return mixed
     */
    public function getNomMedicament()
    {
        return $this->_nomMedicament;
    }

    /**
     * @param mixed $nomMedicament
     */
    public function setNomMedicament($nomMedicament)
    {
        $this->_nomMedicament = $nomMedicament;
    }

    /**
     * @return mixed
     */
    public function getIdNotice()
    {
        return $this->_idNotice;
    }

    /**
     * @param mixed $idNotice
     */
    public function setIdNotice($idNotice)
    {
        $this->_idNotice = $idNotice;
    }


}