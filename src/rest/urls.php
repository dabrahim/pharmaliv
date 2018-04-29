<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 4/3/2018
 * Time: 6:20 PM
 */

$urls = array(
    '/connexion' => 'connexion',
    '/deconnexion' => 'deconnexion',
    '/notice/recherche' => 'rechercheNotice',
    '/produit/publier' => 'publierProduit',
    '/pharmacie/all' => 'getAllPharmacies',
    '/pharmacie/medicaments/all' => 'getAllMedocs',
    '/commande/{:}' => 'getDetailsCommande',
    '/commandes/valider' => 'validerCommande',
    '/test' => 'test'
);