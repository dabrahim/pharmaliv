<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 4/7/2018
 * Time: 8:36 AM
 */

Interface ClientDAO {
    public function create (Client $client);

    public function findById($idClient);

    public function findByCommande(Commande $commande);
}