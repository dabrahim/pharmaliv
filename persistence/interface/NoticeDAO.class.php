<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 4/4/2018
 * Time: 12:25 AM
 */

Interface NoticeDAO {
    public function create(Notice $notice);

    public function findByDrugName($nomMedicament);
}