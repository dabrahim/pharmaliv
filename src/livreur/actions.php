<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 4/11/2018
 * Time: 1:13 PM
 */

function inscription ( $action ) {
    $req = $action->request();

    if ( $req->type() === 'GET' ) {
        $get = $req->get();

        $lService = new LivreurService();
        $rslt = $lService->isClefValide($get['clef']);

        if ($rslt){
            $action->response()->render( 'inscription-livreur.html', array( 'idUser' => $rslt['id_utilisateur'] ) );

        } else {
            $action->response()->redirect('/');
        }


    } else if ($req->type() === 'POST'){
        //var_dump($req->post());
        $post = $req->post();

        $livreur = new Livreur();
        $livreur->setNomComplet( $post['nomComplet'] );
        $livreur->setIdUtilisateur( $post['idUser'] );
        $livreur->setEmail( $post['email'] );
        $livreur->setMotDePasse( $post['motDePasse'] );

        $lService = new LivreurService();
        try {
            $lService->register( $livreur );
            $action->response()->toJson( array('success' => true, 'email' => $livreur->getEmail()) );
        } catch (Exception $e){
            $action->response()->toJson( array('success' => false, 'message' => $e->getMessage() . ' at line ' .$e->getLine() . ' in ' .$e->getFile()) );
        }
    }
}

function verifierClef ($action) {
    $req = $action->request();

    $post = $req->post();

    $result = array();

    $lService = new LivreurService();
    $rslt = $lService->isClefValide($post['clef']);

    if ($rslt){
        $result['success'] = true;
    } else {
        $result['success'] = false;
        $result['message'] = "La clef saisie n'est pas valide";
    }

    $action->response()->toJson($result);
}

function home ($action) {
    $req = $action->response();

    if (!empty($_SESSION['uId']) && $_SESSION['uType'] == 'LIVREUR'){
        $livreur = new Livreur();
        $livreur->setIdUtilisateur($_SESSION['uId']);
        $cService = new CommandeService();

        $action->response()->render( 'accueil-livreur.html', array( 'commandes' => $cService->getCommandesLivreur($livreur) ) );

    } else {
        $action->response()->redirect( '/rest/deconnexion' );
    }
}