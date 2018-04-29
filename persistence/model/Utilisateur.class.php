<?php

class Utilisateur {
	private $_email;
	private $_motDePasse;
	private $_nomComplet;
	private $_idUtilisateur = 0;
	private $_type;

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

	function __construct ($id = null, $email = null, $type = null) {
        $this->_idUtilisateur = $id;
        $this->_email = $email;
        $this->_type = $type;
    }

	/*
		MUTATEURS
	*/
	function setEmail ( $email ) {
		$this->_email = $email;
	}

	function setMotDePasse ( $motDePasse ) {
		$this->_motDePasse = sha1( $motDePasse );
	}

	function setNomComplet ( $nomComplet ) {
		$this->_nomComplet = $nomComplet;
	}

	function setIdUtilisateur ( $id ) {
		$this->_idUtilisateur = $id;
	}

	/*
		ACCESSEURS
	*/
	function getEmail () {
		return $this->_email;
	}

	function getMotDePasse () {
		return $this->_motDePasse;
	}

	function getNomComplet () {
		return $this->_nomComplet;
	}

	function getIdUtilisateur () {
		return $this->_idUtilisateur;
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