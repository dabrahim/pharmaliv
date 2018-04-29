<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 4/3/2018
 * Time: 6:20 PM
 */

function connexion ($action) {
    $req = $action->request();
    $result = array();

    if ($req->type() === 'POST'){
        $data = $req->post();

        $utilisateur = new Utilisateur();
        $utilisateur->setEmail( $data['email'] );
        $utilisateur->setMotDePasse( $data['password'] );

        $uService = new UtilisateurService();
        $utilisateur = $uService->exists( $utilisateur );

        if ( $utilisateur ){
            $result['success'] = true;
            $_SESSION['uId'] = $utilisateur->getIdUtilisateur();
            $_SESSION['uType'] = $utilisateur->getType();
            $result['uType'] = $utilisateur->getType();

            switch ( $utilisateur->getType() ){
                case 'PHARMACIEN' :
                    $pService = new PharmacieService();
                    $pharmacie = $pService->findByUtilisateur($utilisateur);

                    if ($pharmacie){
                        $_SESSION['pId'] = $pharmacie['id_pharmacie'];
                    }
                    break;
            }

        } else {
            $result['success'] = false;
            $result['message'] = "Identifiant ou mot de passe incorrect";
        }

        $action->response()->toJson($result);
    }
}

function deconnexion ($action) {
    session_destroy();
    session_unset();
    $action->response()->redirect( '/?show=login' );
    /*
    $result['success'] = true;
    $action->response()->toJson($result);
    */
}

function rechercheNotice ($action) {
    $req = $action->request();
    $result = array();

    if ($req->type() === 'POST'){
        $data = $req->post();
        $nService = new NoticeService( );
        $result['data'] = $nService->findByDrugName( $data['recherche'] );
        $action->response()->toJson($result);
        return;
    }
}

function publierProduit($action){
    $req = $action->request();
    if($req->type() === 'POST'){
        $post = $req->post();
        $files = $req->files();

        $result = array();
        $fileName = '';

        // ON VERIFIE LA TAILLE DU FICHIER
        if ($files['photo']['size'] <= 500000){

            // ON VERIFIE SI LE FICHIER EST UNE IMAGE
            $check = getimagesize($files["photo"]["tmp_name"]);
            if($check !== false) {
                $target_dir = "uploads/";
                $imageFileType = strtolower(pathinfo(basename($files['photo']['name']),PATHINFO_EXTENSION));
                $allowedExtensions = array('jpg', 'png', 'jpeg', 'JPG', 'PNG', 'JPEG');

                // ON VERIFIE QUE L'IMAGE A LA BONNE EXTENSION
                if (in_array($imageFileType, $allowedExtensions)){
                    $fileName = random_str(20) . '.' . $imageFileType;
                    $target_file = $target_dir . $fileName;
                    $result['success'] = move_uploaded_file($files['photo']['tmp_name'], $target_file);
                } else {
                    $result['success'] = false;
                    $result['message'] = "L'extension de l'image n'est pas autorisée.";
                }
            } else {
                $result['success'] = false;
                $result['message'] = "Le fichier envoyé n'est pas une image";
            }

        } else {
            $result['success'] = false;
            $result['message'] = "La taille du fichier doit être inférieure à 500kb";
        }

        // SI TOUT S'EST BIEN PASSE POUR L'INSTANT
        if ($result['success']){
            $produit = new Produit();
            $post['nomFichier'] = $fileName;
            $post['idPharmacie'] = $_SESSION['pId'];
            $produit->hydrate($post);

            $pService = new ProduitService();
            $pId = $pService->create($produit);

            if ( !$pId ) {
                $result['success'] = false;
                $result['message'] = "Une erreur est survenue lors de la mise en ligne du produit.";
            }
        }

        $action->response()->toJson($result);
    }

}

function getAllPharmacies ( $action ){
    $req = $action->request();

    if ( $req->type() === 'POST' ){
        $pService = new PharmacieService();
        $pharmacies = $pService->getAll();

        $action->response()->toJson($pharmacies);
    }
}

function getAllMedocs ($action) {
    $req = $action->request();

    if ( $req->type() === 'POST' ){
        $postData = $req->post();

        $pharmacie = new Pharmacie();
        $pharmacie->setIdPharmacie($postData['idPharmacie']);

        $pService = new ProduitService();
        $medicaments = $pService->findByPharmacie( $pharmacie );

        $action->response()->toJson( $medicaments );
    }
}

function getDetailsCommande ($action, $params) {
    $req = $action->request();

    if ( $req->type() === 'GET' ){
        $idCommande = $params[1];
        $commmande = new Commande();
        $commmande->setIdCommande($idCommande);

        $pService = new ProduitService();
        $dSerice = new DocumentService();
        $cService = new ClientService();

        $data = array();

        $data['produits'] = $pService->findByCommande($commmande);
        $data['documents'] = $dSerice->findByCommande($commmande);
        $data['client'] = $cService->findByCommande($commmande);

        $action->response()->toJson($data);
    }
}

function validerCommande($action) {
    $req = $action-> request();

    if( $req->type() === 'POST' ){
        $data = $req->post();
        $result = array();

        $idCommande = $data['idCommande'];

        $commande = new Commande();
        $commande->setIdCommande($idCommande);

        $cService = new CommandeService();
        $result['success'] = $cService->validerCommande($commande);

        if($result['success']){
            $idClient = $data['idClient'];

            $utilisateur = new Utilisateur();
            $utilisateur->setIdUtilisateur($idClient);

            $notification = new Notification();
            $notification->setUtilisateur($utilisateur);
            $notification->setObjet("Confirmation commande");
            $notification->setMessage("Votre commande a ete validee avec succes");

            $nService = new NotificationService();
            $nService->create($notification);
        }

        $action->response()->toJson($result);
    }
}

function test ($action) {
    $commande = new Commande();
    $commande->setIdCommande( 9 );

    $cService = new CommandeService();
    $cService->validerCommande($commande);
}