<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 4/9/2018
 * Time: 11:44 AM
 */

interface ZoneDAO {
    public function getAll();

    public function create(Zone $zone);
}