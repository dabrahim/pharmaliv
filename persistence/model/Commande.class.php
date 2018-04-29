<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 4/8/2018
 * Time: 6:51 PM
 */

class Commande {
    private $_idCommande;
    private $_utilisateur;
    private $_pharmacie;
    private $_dateLivraison;
    private $_heureLivraison;
    private $_modePaiement;
    private $_documents;
    private $_produits;

    public function __construct() {
        $this->_documents = array();
        $this->_produits = array();
    }

    public function addDocument (Document $document){
        $this->_documents [] = $document;
    }

    public  function addProduit (Produit $produit){
        $this->_produits [] = $produit;
    }

    /**
     * @return mixed
     */
    public function getIdCommande()
    {
        return $this->_idCommande;
    }

    /**
     * @param mixed $idCommande
     */
    public function setIdCommande($idCommande)
    {
        $this->_idCommande = $idCommande;
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
    public function getPharmacie()
    {
        return $this->_pharmacie;
    }

    /**
     * @param mixed $pharmacie
     */
    public function setPharmacie(Pharmacie $pharmacie)
    {
        $this->_pharmacie = $pharmacie;
    }

    /**
     * @return mixed
     */
    public function getDateLivraison()
    {
        return $this->_dateLivraison;
    }

    /**
     * @param mixed $dateLivraison
     */
    public function setDateLivraison($dateLivraison)
    {
        $this->_dateLivraison = $dateLivraison;
    }

    /**
     * @return mixed
     */
    public function getHeureLivraison()
    {
        return $this->_heureLivraison;
    }

    /**
     * @param mixed $heureLivraison
     */
    public function setHeureLivraison($heureLivraison)
    {
        $this->_heureLivraison = $heureLivraison;
    }

    /**
     * @return mixed
     */
    public function getModePaiement()
    {
        return $this->_modePaiement;
    }

    /**
     * @param mixed $modePaiement
     */
    public function setModePaiement($modePaiement)
    {
        $this->_modePaiement = $modePaiement;
    }

    /**
     * @return array
     */
    public function getDocuments()
    {
        return $this->_documents;
    }

    /**
     * @param array $documents
     */
    public function setDocuments($documents)
    {
        $this->_documents = $documents;
    }

    /**
     * @return array
     */
    public function getProduits()
    {
        return $this->_produits;
    }

    /**
     * @param array $produits
     */
    public function setProduits($produits)
    {
        $this->_produits = $produits;
    }
}