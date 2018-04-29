<?php

class Pharmacie {
	private $_coordGeo;
	private $_heureOuverture;
	private $_heureFermeture;
	private $_adresse;
	private $_numFixe;
	private $_numMobile;
	private $_idPharmacie;
	private $_utilisateur;
	private $_nom;

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
    public function getHeureOuverture()
    {
        return $this->_heureOuverture;
    }

    /**
     * @param mixed $heureOuverture
     */
    public function setHeureOuverture($heureOuverture)
    {
        $this->_heureOuverture = $heureOuverture;
    }

    /**
     * @return mixed
     */
    public function getHeureFermeture()
    {
        return $this->_heureFermeture;
    }

    /**
     * @param mixed $heureFermeture
     */
    public function setHeureFermeture($heureFermeture)
    {
        $this->_heureFermeture = $heureFermeture;
    }

    /**
     * @return mixed
     */
    public function getAdresse()
    {
        return $this->_adresse;
    }

    /**
     * @param mixed $adresse
     */
    public function setAdresse($adresse)
    {
        $this->_adresse = $adresse;
    }

    /**
     * @return mixed
     */
    public function getNumFixe()
    {
        return $this->_numFixe;
    }

    /**
     * @param mixed $numFixe
     */
    public function setNumFixe($numFixe)
    {
        $this->_numFixe = $numFixe;
    }

    /**
     * @return mixed
     */
    public function getNumMobile()
    {
        return $this->_numMobile;
    }

    /**
     * @param mixed $numMobile
     */
    public function setNumMobile($numMobile)
    {
        $this->_numMobile = $numMobile;
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
}