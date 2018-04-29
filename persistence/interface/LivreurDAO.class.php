<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 4/9/2018
 * Time: 11:57 AM
 */

interface LivreurDAO {
    public  function create(Livreur $livreur);

    public function isClefValide($clef);

    public function register(Livreur $livreur);
}