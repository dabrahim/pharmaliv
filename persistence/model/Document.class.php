<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 4/7/2018
 * Time: 10:16 AM
 */

class Document {
    private $_titre;
    private $_description;
    private $_nomFichier;
    private $_dateAjout;
    private $_utilisateur;
    private $_type;
    private $_idDocument;

    /**
     * @return mixed
     */
    public function getIdDocument()
    {
        return $this->_idDocument;
    }

    /**
     * @param mixed $idDocument
     */
    public function setIdDocument($idDocument)
    {
        $this->_idDocument = $idDocument;
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
    public function getDateAjout()
    {
        return $this->_dateAjout;
    }

    /**
     * @param mixed $dateAjout
     */
    public function setDateAjout($dateAjout)
    {
        $this->_dateAjout = $dateAjout;
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
        $this->_type = $type;
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