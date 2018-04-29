$(document).ready(function(){
    $('#form-publication-produit').on('submit', function (e){
        e.preventDefault();

        if ( $('#form-publication-produit input[name="type"]:checked').val() === 'medicament' ){
            $('#layer').show();
            $('#produit-notice').show();
        } else {
            submitForm();
        }
    });

    $('input[name="notice"]').on('keyup', function(){
       let recherche = $(this).val();
        $.post('./rest/notice/recherche', {recherche:recherche}, function (response) {
           let notices = response.data;

           let ct = "";
           for(notice of notices){
               ct += "<div class='notice' id='notice-"+notice.id_notice+"'><strong>"+notice.nom_medicament+": </strong>"+notice.contenu+"</div>";
           }

            $('#notices-rslt').html( $(ct) );
        }, 'json');
    });

    $('#layer').on('click', '.notice', function (){
        let idNotice = parseInt( $(this).attr('id').split('-')[1] );
        $('input[name="idNotice"]').val(idNotice);

        submitForm();
    });

    function submitForm () {

        $.ajax({
            url : './rest/produit/publier',
            type: 'POST',
            data : new FormData( $('#form-publication-produit')[0] ),
            dataType : 'json',
            success : function (sData){
                if ( sData.success ){
                    $('#form-publication-produit')[0].reset();
                    alert('Le produit a été publié avec succès !');
                } else {
                    alert(sData.message);
                }
                $('#layer').hide();
                $('#form-publication-produit').hide();
            },
            processData : false,
            contentType : false
        });

    }

    var idCommande = 0;
    var idClient = 0;

    $('#commandes .gerer-btn').on('click', function(){
        idCommande = parseInt( $(this).attr('id').split('-')[1] );
        var tds = $(this).closest('tr').find('td');

        var dateEmission = $(tds[1]).text();
        var dateLivraison = $(tds[2]).text();
        var heureLivraison = $(tds[3]).text();

        blurElement('#wrapper', 10);
        $('#layer').show();
        $('#gerer-commande').css({
           display: 'inline-block'
        });

        $.get('./rest/commande/'+idCommande, null, function (commande) {
            $('#products-summary table tbody').empty();

            var produits = commande.produits;
            var client = commande.client;
            var documents = commande.documents;

            idClient = client.id_utilisateur;

            var total = 0;

            $(produits).each(function () {
               var produit = $(this)[0];

               var qte = produit.quantite;
               var pu = produit.prix;

               var td  = '<td><img style="width: 100px" src="./uploads/'+produit.nom_fichier+'" alt="'+produit.titre+'"><br><strong>'+produit.titre+'</strong></td>'
                        +'<td>'+produit.type+'</td>'
                        +'<td>'+pu+'</td>'
                        +'<td>'+qte+'</td>'
                        +'<td>'+qte*pu+' FCFA</td>';
                $('#products-summary table tbody').append( $('<tr>'+td+'</tr>') );

                total += qte*pu;
            });

            $('#products-summary .total').html( '<strong>Total : </strong> '+total+'FCFA' );

            var elems = "<strong>Date d'émission: </strong>"+dateEmission+"<br>"
                +"<strong>Date de livraison: </strong>"+dateLivraison+"<br>"
                +"<strong>Heure de livraison :</strong>"+heureLivraison+"<br>";
            /*+"<strong>:</strong>'++'<br>"*/

            $('#details-livraison .content').html(elems);

            var detailsClient = line('Nom Client', client.nom_complet)
                                +line('Date de naissance', client.date_de_naissance)
                                +line('Sexe', client.sexe);

            $('#details-client .content').html(detailsClient);

            var listeDocuments = '';

            $(documents).each(function () {
               var document = $(this)[0];
               listeDocuments += '<li><a href="./uploads/'+document.nom_fichier+'" target="_blank">'+document.titre+'</a></li>';
            });

            if(listeDocuments != '') {
                $('#documents-joints .content').html('<ul>'+listeDocuments+'</ul>');
            }
        },'json');
    });

    $('#btn-publier-produit').on('click', function () {
        $('#layer').show();
        blurElement('#wrapper', 10);
        $('#form-publication-produit').css({
           display:'inline-block'
        });
    });

    $('#btn-valider-commande').on('click', function () {

        $.post('./rest/commandes/valider', {idCommande: idCommande, idClient: idClient}, function (response) {
            if(response.success){
                alert('Commande validée avec succes');
                window.location.reload();
            } else{
                alert('Une erreur est survenue');
            }
        }, 'json');

    });

    function line (title, content){
        return "<strong>"+title+": </strong> "+content+"<br>";
    }

    /*
    $('#layer').click(function(e) {
        var form = $("#form-publication-produit");
        var div = $('#gerer-commande');

        // if the target of the click isn't the container nor a descendant of the container
        if (!form.is(e.target) && form.has(e.target).length === 0 && !div.is(e.target) && div.has(e.target).length === 0 ) {
            unblurElement('#wrapper'); //On retire le flou de la page
            $('#layer').hide();
            $('#form-publication-produit')[0].reset();
            $('#form-publication-produit').hide();
            $('#gerer-commande').hide();
        }
    });
    */
});