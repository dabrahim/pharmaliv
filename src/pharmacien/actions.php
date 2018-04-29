<?php

function home ( $action ) {
    if ( !empty($_SESSION) ){
        if (!isset($_SESSION['pId'])){
            $action->response()->redirect( '/pharmacien/profile/complete' );
            return;
        }

        $pharmacie = new Pharmacie();
        $pharmacie->setIdPharmacie($_SESSION['pId']);

        $cService = new CommandeService();
        $commandes = $cService->findCommandesNonTraitees($pharmacie);

        $pService = new ProduitService();
        $produits = $pService->findByPharmacie($pharmacie);

        $action->response()->render ( 'accueil-pharmacien.html', array('commandes' => $commandes, 'produits' => $produits) );
    } else {
        $action->response()->redirect( '/' );
    }
}

/*
 * Controlleur responsable de la page d'inscription
 * */
function inscription ( $action ) {
    if (!empty($_SESSION['uId'])){
        $action->response()->redirect( '/pharmacien' );
        return;
    }

	$req = $action->request();

	// ON VERIFIE LE TYPE DE REQUETE
	/*
		REQUETE GET
	*/
	if ( $req->type() === 'GET' ) {
		$action->response()->render ( 'inscription.html' );

	/*
		REQUETE POST
	*/
	} else if ( $req->type() === 'POST' ) {
		$data = $req->post();
        $errors = array();

        if ( !empty($data) ){
            if ( $data['password'] == $data['password_conf'] ){
                $utilisateur = new Utilisateur();

                $utilisateur->setEmail( $data['email'] );
                $utilisateur->setMotDePasse( $data['password'] );
                $utilisateur->setNomComplet( $data['full_name'] );
                $utilisateur->setType( 'PHARMACIEN' );

                $uService = new UtilisateurService();
                if (!$uService->findByEmail( $utilisateur->getEmail() )) {
                    $uService->create( $utilisateur );

                    $action->response()->redirect( '/?show=login&email='.$utilisateur->getEmail()  );

                } else {
                    $errors['email'] = "Cette adresse mail est déjà utilisée";
                }

            } else {
                $errors['motDePasse'] = 'Les mots de passe ne correspondent pas';
            }

        } else {
            $errors['form'] = 'Aucune donnée n\'a été reçue du formulaire';
        }

        if (!empty($errors)){
            $action->response()->render ( 'inscription.html', array('errors' => $errors) );
        }
		//var_dump( $data );
	}
}

function completerProfil($action) {
    if ( !empty($_SESSION) ){

        if (isset($_SESSION['pId'])){
            $action->response()->redirect( '/pharmacien' );
            return;
        }

        $req = $action->request();
        if ( $req->type() === 'GET' ){
            $action->response()->render( 'pharm-profil-complete.html' );

        } else if ( $req->type() === 'POST' ){
            $postData = $req->post();

            $utilisateur = new Utilisateur();
            $utilisateur->setIdUtilisateur( $_SESSION['uId'] );

            $pharmacie = new Pharmacie();
            $pharmacie->setUtilisateur($utilisateur);
            $pharmacie->hydrate( $postData );

            $pharmacie->setCoordGeo( $postData['lat'] . ',' . $postData['lng'] );

            $pService = new PharmacieService();
            $rslt = $pService->create($pharmacie);

            if ($rslt){
                $_SESSION['pId'] = $rslt;
                $action->response()->redirect( '/pharmacien' );
            } else {
                echo "Ca a échoué !";
            }
        }

    } else {
        $action->response()->redirect( '/' );
    }
}