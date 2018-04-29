<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 4/9/2018
 * Time: 11:05 AM
 */

function home( $action ){
    if(isset($_SESSION['uId']) && $_SESSION['uType']==="ADMIN"){
        $action->response()->render('home-admin.html');
    } else{
        $action->response()->redirect('/?show=login');
    }
}

function creerZone ($action) {
    $req = $action->request();

    if ($req->type() === 'POST') {
        $zone = new Zone();
        $zone->hydrate($req->post());

        $zService = new ZoneService();
        $result = $zService->create($zone);

        $action->response()->toJson( array("success" => $result) );
    }
}

function getAllZones($action){
    $zService = new ZoneService();
    $action->response()->toJson($zService->getAll());
}

function creerLivreur($action){
    $req = $action->request();
    $resp = $action->response();

    if ($req->type() === 'POST') {
        $data = $req->post();

        $zones = array();
        foreach ($data['idZones'] as $idZone) {
            $zone = new Zone();
            $zone->setIdZone($idZone);
            $zones[] = $zone;
        }

        $livreur = new Livreur();
        $livreur->setEmail('');
        $livreur->setMotDePasse('');
        $livreur->setNomComplet('');
        $livreur->setCleInscription( random_str(50) );
        $livreur->setZones($zones);

        $lService = new LivreurService();
        try {
            $lService->create($livreur);
            $action->response()->toJson( array("success" => true, "clef" => $livreur->getCleInscription()) );

        } catch (Exception $e){
            $action->response()->toJson( array("success" => false, "message" => $e->getMessage() . 'at line ' . $e->getLine()) );
        }
    }

}