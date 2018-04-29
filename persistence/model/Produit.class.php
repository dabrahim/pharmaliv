<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 4/4/2018
 * Time: 11:07 AM
 */

class Produit {
    private $_titre;
    private $_prix;
    private $_description;
    private $_type;
    private $_nomFichier;
    private $_idPharmacie;
    private $_idProduit;
    private $_quantite;

    /**
     * @return mixed
     */
    public function getQuantite()
    {
        return $this->_quantite;
    }

    /**
     * @param mixed $quantite
     */
    public function setQuantite($quantite)
    {
        $this->_quantite = $quantite;
    }

    public function hydrate (array $data) {
        foreach ($data as $key => $value){
            $getter = 'set' . ucfirst($key);
            if (method_exists($this, $getter)){
                $this->$getter ( $value );
            }
        }
    }

    /**
     * @return mixed
     */
    public function getTitre()
    {
        return $this->_titre;
    }

    /**
     * @param mixed $titre
     */
    public function setTitre($titre)
    {
        $this->_titre = $titre;
    }

    /**
     * @return mixed
     */
    public function getPrix()
    {
        return $this->_prix;
    }

    /**
     * @param mixed $prix
     */
    public function setPrix($prix)
    {
        $this->_prix = $prix;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->_description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->_description = $description;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->_type =  strtoupper( $type );
    }

    /**
     * @return mixed
     */
    public function getNomFichier()
    {
        return $this->_nomFichier;
    }

    /**
     * @param mixed $nomFichier
     */
    public function setNomFichier($nomFichier)
    {
        $this->_nomFichier = $nomFichier;
    }

    /**
     * @return mixed
     */
    public function getIdPharmacie()
    {
        return $this->_idPharmacie;
    }

    /**
     * @param mixed $idPharmacie
     */
    public function setIdPharmacie($idPharmacie)
    {
        $this->_idPharmacie = $idPharmacie;
    }

    /**
     * @return mixed
     */
    public function getIdProduit()
    {
        return $this->_idProduit;
    }

    /**
     * @param mixed $idProduit
     */
    public function setIdProduit($idProduit)
    {
        $this->_idProduit = $idProduit;
    }
}