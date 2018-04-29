<?php
/*
	LISTE DES METHODES ACTION DE LA SECTION CLIENT
*/

function home ( $action ) {
    if ( !empty($_SESSION) ){
        $action->response()->render ( 'home-client.html' );
    } else {
        $action->response()->redirect( '/' );
    }
}

function inscription ( $action ) {
    $req = $action->request();

    if ( $req->type() === 'GET' ){
        $action->response()->render( 'inscription-client.html' );

    } else if ( $req->type() === 'POST' ){
        $postData = $req->post();

        $user = new Utilisateur();
        $user->hydrate($postData);
        $user->setType( 'CLIENT' );

        $uService = new UtilisateurService();
        $id = $uService->create($user);

        $client = new Client();
        $client->hydrate($postData);
        $client->setIdUtilisateur($id);

        $cService = new ClientService();
        $cService->create($client);

        $action->response()->redirect( '/?show=login&email='.$user->getEmail() );
    }
}

function publierDocument ($action){
    $req = $action->request();

    if ($req->type() === 'POST'){
        $postData = $req->post();
        $files = $req->files();


        $result = array();
        $fileName = '';

        $doc = $files['document'];

        // ON VERIFIE LA TAILLE DU FICHIER
        if ($doc['size'] <= 300000){

            $target_dir = "uploads/";
            $imageFileType = strtolower(pathinfo(basename($doc['name']),PATHINFO_EXTENSION));
            $allowedExtensions = array('PDF', 'pdf');

            // ON VERIFIE QUE L'IMAGE A LA BONNE EXTENSION
            if (in_array($imageFileType, $allowedExtensions)){
                $fileName = random_str(20) . '.' . $imageFileType;
                $target_file = $target_dir . $fileName;
                $result['success'] = move_uploaded_file($doc['tmp_name'], $target_file);
            } else {
                $result['success'] = false;
                $result['message'] = "L'extension du document n'est pas autorisée.";
            }

        } else {
            $result['success'] = false;
            $result['message'] = "La taille du fichier doit être inférieure à 300kb";
        }

        if (!empty($fileName)){
            $user = new Utilisateur();
            $user->setIdUtilisateur($_SESSION['uId']);

            $document = new Document();
            $document->hydrate($postData);
            $document->setNomFichier($fileName);
            $document->setUtilisateur($user);

            $dService = new DocumentService();
            $dId = $dService->create($document);

            if (!$dId){
                $result['success'] = false;
                $result['message'] = "L'enregistrement du document a échouée.";
            }
        }

        $action->response()->toJson( $result );
    }
}

function envoyerCommande ($action) {
    $req = $action->request();

    if ( $req->type() === 'POST' ){
        $data = $req->post();

        $result = array();

        $produitsArray = json_decode($data['produits'], true);
        $idDocs = json_decode($data['idDocs'], true);
        $dateLiv = $data['dateLivraison'];
        $heureLiv = $data['heureLivraison'];
        $modePaiement = $data['modePaiement'];
        $idPharmacie = $data['idPharmacie'];

        $documents = array();
        foreach ($idDocs as $idDoc){
            $document = new Document();
            $document->setIdDocument($idDoc);
            $documents [] = $document;
        }

        $produits = array();
        foreach ($produitsArray as $prod){
            $produit = new Produit();
            $produit->setIdProduit($prod['product']['id_produit']);
            $produit->setQuantite($prod['quantity']);
            $produits [] = $produit;
        }

        $user = new Utilisateur();
        $user->setIdUtilisateur($_SESSION['uId']);

        $pharmacie = new Pharmacie();
        $pharmacie->setIdPharmacie($idPharmacie);

        $commande = new Commande();

        $commande->setProduits($produits);
        $commande->setDocuments($documents);
        $commande->setDateLivraison($dateLiv);
        $commande->setHeureLivraison($heureLiv);
        $commande->setModePaiement($modePaiement);
        $commande->setUtilisateur($user);
        $commande->setPharmacie($pharmacie);

        $cService = new CommandeService();

        try {
            $cService->create($commande);
            $result['success'] = true;
        } catch (Exception $e){
            $result['success'] = false;
            $result['message'] = $e->getMessage();
        }

        $action->response()->toJson( $result );
    }
}

function getAllDocuments ( $action ) {
    $req = $action->request();

    if ($req->type() == 'POST'){
        $client = new Client();
        $client->setIdUtilisateur( $_SESSION['uId'] );

        $dService = new DocumentService();

        $action->response()->toJson( $dService->findByClient( $client ) );
    }
}