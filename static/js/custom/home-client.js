$(document).ready(function () {

    $('#btn-add-doc').on('click', function (){
        blurElement( '#wrapper', 10 );
        $('#layer').show();
        $('#form-add-doc').css({
            display : 'inline-block'
        });
    });

    $('#form-add-doc').on('submit', function(e){
        e.preventDefault();

        $.ajax({
            url  : './client/publier/document',
            type : 'POST',
            data : new FormData(this),
            dataType : 'json',
            success : function (sData){
               if (sData.success){
                   unblurElement( '#wrapper' );
                   $('#layer').hide();
                   $('#form-add-doc').hide();
                   $('#form-add-doc')[0].reset();
                   alert('Le document a été enregistré avec succès !');
               } else {
                   alert(sData.message);
               }
            },
            contentType : false,
            processData : false
        });
    });

    $('#btn-order').on('click', function() {
        blurElement( '#wrapper', 10 );
        $('#layer').show();
        $('#loader').show();

        //getLocation(function(position) {
            //cPos = [position.coords.latitude, position.coords.longitude];
            cPos = [14.716677,-17.467686099999998];

            //console.log(cPos);

            $.post('./rest/pharmacie/all', null, function(response) {
                $('#pharmacies .container').empty();

                var pharmacies = [];

                for(var pharm of response){
                    var coord = pharm.coord_geo.split(',');
                    dist = getDistanceFromLatLonInKm(coord[0], coord[1], cPos[0], cPos[1]);
                    pharm.dist = dist.toFixed(2);
                    pharmacies.push(pharm);
                }

                pharmacies.sort(function(a, b) {
                    return parseFloat(a.dist) - parseFloat(b.dist);
                });

                for (var pharmacie of pharmacies){
                    var block = "<div class='pharm-block' id='pharm-"+pharmacie.id_pharmacie+"'><div>Pharmacie</div><h1>"+pharmacie.nom+"</h1><p>"+pharmacie.adresse+"</p><span>Située à <strong>"+pharmacie.dist+"KM</strong> de vous</span></div>";
                    $('#pharmacies .container').append( $(block) );
                }

                $('#loader').hide();
                $('#form-order').show();

            }, 'json');

        //});
    });

    var drugs = [];
    var chart = [];
    var idPharmacie = 0;

    $('#pharmacies').on('click', '.pharm-block', function (){
       idPharmacie = parseInt( $(this).attr('id').split('-')[1] );

        $('#pharmacies').hide();
        $('#med-search').show();

        $.post('./rest/pharmacie/medicaments/all', {idPharmacie : idPharmacie}, function(response){
            drugs = response;
            populateProducts(drugs);
        }, 'json');
    });

    function populateProducts (products){
        $('#medicaments .container').empty();
        $('#parapharm .container').empty();

        for (medicament of products){
            let div = '<div class="medicament" id="produit-'+medicament.id_produit+'"><div class="medic-pic"><img src="./uploads/'+medicament.nom_fichier+'"></div><h1 class="medic-titre">'+medicament.titre+'</h1><p class="medic-description">'+medicament.description+'</p><p><strong class="medic-prix">'+medicament.prix+'F</strong></p><button type="button">Ajouter au Panier</button></div>';
            if ( medicament.type === 'PARAPHARMACIE' ){
                $('#parapharm .container').append( $(div) );
            } else if ( medicament.type === 'MEDICAMENT' ) {
                $('#medicaments .container').append(div);
            }
        }
    }

    $('#champ-filtre-produit').on('keyup', function () {
        var recherche = $(this).val();
        var filteredResults = drugs.filter(function(produit){
            return produit.titre.toLowerCase().indexOf(recherche.toLowerCase()) !== -1;
        });
        populateProducts(filteredResults);
    });

    $('#med-search').on('click', '.medicament button', function () {
       var idProduit = parseInt( $(this).closest('.medicament').attr('id').split('-')[1] );
       var p = findProductById(idProduit);
       var quant = parseInt( prompt( 'Combien d\'articles souhaitez vous acheter ?' ) );

       chart.push({
          product : p,
          quantity : quant
       });

        drugs = drugs.filter(function(drug){
           return parseInt( drug.id_produit) != idProduit;
        });

        populateProducts(drugs);
    });

    function findProductById(idProduct){
        for (var product of drugs){
            if (product.id_produit == idProduct){
                return product;
            }
        }
        return;
    }

    $('#form-order').on('submit',function (e) {
        e.preventDefault();

        var checkboxes = $('#documents tbody').find('input[type="checkbox"]:checked');
        var idDocs = [];

        $(checkboxes).each(function(){
           var idDoc = parseInt($(this).attr('id').split('-')[1]);
           idDocs.push(idDoc);
        });

        var data = {
            produits : JSON.stringify(chart),
            idDocs : JSON.stringify(idDocs),
            dateLivraison : $('#date-livraison-field').val(),
            heureLivraison : $('#heure-livraison-field').val(),
            modePaiement : $('#cloture-commande input[name="modePaiement"]:checked').val(),
            idPharmacie : idPharmacie
        };

        if ( idDocs.length < 1 && docRequired() ) {
            alert( 'Votre commande contient des produits pharmaceutiques. Vous devez joindre au moins un document' );

        } else {
            $.post('./client/commander', data, function(response){
                if(response.success) {
                    alert('Commande effectuée avec succès !');
                    window.location.reload();
                    /*
                    $('#cloture-commande').hide();
                    $('#pharmacies').show();
                    $('#form-order')[0].reset();
                    $('#form-order').hide();
                    $('#layer').hide();
                    unblurElement('#wrapper');
                    $('#documents').hide();
                    chart = [];
                    */
                } else {
                    alert( response.message );
                }
            }, 'json');
        }

    });

    function docRequired () {
        for (var cmd of chart){
            if (cmd.product.type === 'MEDICAMENT'){
                return true;
            }
        }
        return false;
    }

    $('#valider-choix-btn').on('click', function (){
        $('#med-search').hide();
        $('#cloture-commande').show();

        if ( docRequired() ){
            $('#documents').show();
            $.post('./client/document/all', null, function (documents) {
                for(var document of documents){
                    var td = '<td>'+document.titre+'</td>'
                            +'<td>'+document.type+'</td>'
                            +'<td>'+document.date_ajout+'</td>'
                            +'<td><input type="checkbox" id="doc-'+document.id_document+'"></td>';
                    $('#documents tbody').append( $('<tr>'+td+'</tr>') );
                }
            }, 'json');
        }
    });

    $('#parapharm-tab-btn').on('click', function() {
        $('#parapharm').show();
        $('#medicaments').hide();
    });

    $('#medoc-tab-btn').on('click', function(){
        $('#parapharm').hide();
        $('#medicaments').show();
    });

    $('#show-panier-btn').on('click', function (){
        $('#panier').show();
        updateChartVisualiser();
        setTimeout(function(){
            $('#panier').hide();
        }, 5000);
    });

    $('#panier').on('click', 'tbody button', function () {
       var idProduct = parseInt( $(this).attr('id').split('-')[2] );
        //$(this).closest('tr').remove();

        for(var cmd of chart){
            var product = cmd.product;
            if (product.id_produit == idProduct){
                drugs.push(product);
                populateProducts(drugs);
                break;
            }
        }

        chart = chart.filter(function(cmd){
           return cmd.product.id_produit != idProduct;
        });

        updateChartVisualiser();
    });

    function updateChartVisualiser() {
        $('#panier tbody').empty();
        $('#panier .total-price').remove();

        var prixTotal = 0;

        for(var article of chart){
            var pu = article.product.prix;
            var qte = article.quantity;
            var prix = qte*pu;
            prixTotal += prix;

            var td = '<td>'+article.product.titre+'</td>'
                +'<td>'+article.product.type+'</td>'
                +'<td>'+qte+'</td>'
                +'<td>'+pu+'</td>'
                +'<td>'+prix+'</td>'
                +'<td><button id="btn-rmv-'+article.product.id_produit+'">RMV</button></td>';

            $('#panier tbody').append( $('<tr>'+td+'</tr>') );
        }
        $('#panier').append( $("<span class='total-price'><strong>Total : </strong>" +prixTotal+" FCFA</span>") );
    }

});