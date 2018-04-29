<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 4/4/2018
 * Time: 11:08 AM
 */

interface ProduitDAO {
    public function create(Produit $produit);

    public function findByCommande(Commande $commande);

    public function findByPharmacien(Pharmacie $pharmacie);
}