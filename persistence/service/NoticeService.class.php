<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 4/4/2018
 * Time: 12:26 AM
 */

class NoticeService implements NoticeDAO {
    private $_db;

    public function __construct()
    {
        $this->_db = CustomPDO::getInstance();
    }

    public function create(Notice $notice) {
        $pdoStatement = $this->_db->prepare('INSERT INTO notice SET contenu = :contenu, nom_medicament = :nomMedicament');
        $pdoStatement->bindValue(':contenu', $notice->getContenu(), PDO::PARAM_STR);
        $pdoStatement->bindValue(':nomMedicament', $notice->getNomMedicament(), PDO::PARAM_STR);
        $pdoStatement->execute();
        return $this->_db->getLastInsertId();
    }

    public function findByDrugName($nomMedicament) {
        $pdoStatement = $this->_db->prepare( "SELECT * FROM notice WHERE nom_medicament LIKE :nomMedicament " );
        $pdoStatement->bindValue(':nomMedicament', '%'.$nomMedicament.'%', PDO::PARAM_STR);
        $pdoStatement->execute();
        return $pdoStatement->fetchAll(PDO::FETCH_ASSOC);
    }

}