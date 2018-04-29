<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 4/3/2018
 * Time: 12:00 PM
 */

interface PharmacieDAO {
    public function create (Pharmacie $pharmacie);

    public function findByUtilisateur (Utilisateur $utilisateur);

    public function  getAll();
}