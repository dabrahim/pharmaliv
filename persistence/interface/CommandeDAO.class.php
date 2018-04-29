<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 4/8/2018
 * Time: 6:55 PM
 */

interface CommandeDAO {
    public function create (Commande $commande);

    public function findCommandesNonTraitees(Pharmacie $pharmacie);

    public function validerCommande(Commande $commande);

    public  function getCommandesLivreur(Livreur $livreur);
}