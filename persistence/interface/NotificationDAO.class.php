<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 4/9/2018
 * Time: 10:05 AM
 */

interface NotificationDAO {
    public function create(Notification $notification);

    public function setVu(Notification $notification);

    public function findByUtilisateur(Utilisateur $utilisateur);
}