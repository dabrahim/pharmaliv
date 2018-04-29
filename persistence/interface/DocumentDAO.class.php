<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 4/7/2018
 * Time: 10:20 AM
 */

interface DocumentDAO {
    public function create (Document $document);

    public function findByClient(Client $client);

    public function findByCommande( Commande $commande );
}