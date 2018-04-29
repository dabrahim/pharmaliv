<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 4/3/2018
 * Time: 12:26 PM
 */

interface UtilisateurDAO {
    public function create (Utilisateur $utilisateur);

    public function findByEmail ($email);
}