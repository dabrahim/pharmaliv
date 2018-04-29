<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 4/9/2018
 * Time: 11:45 AM
 */

class ZoneService implements ZoneDAO {
    private $_db;

    public function __construct() {
        $this->_db = CustomPDO::getInstance();
    }

    public function getAll() {
        $pdoStatement = $this->_db->query( 'SELECT * FROM zone' );
        return $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(Zone $zone) {
        $pdoStatement = $this->_db->prepare( 'INSERT INTO zone SET nom = :nom, coordGeo = :coordGeo, rayon = :rayon' );
        $pdoStatement->bindValue( ':nom', $zone->getNom(), PDO::PARAM_STR );
        $pdoStatement->bindValue( ':coordGeo', $zone->getCoordGeo(), PDO::PARAM_STR );
        $pdoStatement->bindValue( ':rayon',$zone->getRayon() , PDO::PARAM_INT );
        return $pdoStatement->execute();
    }

}