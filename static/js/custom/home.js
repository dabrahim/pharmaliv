$(document).ready(function(){

    $('#signup-btn').on('click', function () {
        $('#layer').show();
        $('#user-type-picker').show();
        blurElement( '#wrapper', 10 );
    });

    $('#login-btn').on('click', function () {
        blurElement( '#wrapper', 10 );
        $('#form-connexion').show();
        $('#layer').show();
    });

    //$()

    $('#form-connexion').on('submit', function (e) {
        e.preventDefault();

        $.post("./rest/connexion", $(this).serialize(), function (response) {
            if (response.success){
                switch (response.uType){
                    case "PHARMACIEN" :
                        window.location = "./pharmacien";
                        break;
                    case 'CLIENT':
                        window.location = './client';
                        break;
                    case 'MEDECIN' :
                        window.location = './medecin';
                        break;
                    case 'LIVREUR' :
                        window.location = './livreur';
                        break;
                    case 'ADMIN' :
                        window.location = './admin';
                        break;
                }
            } else {
                alert(response.message);
            }
        }, 'json');
    });

    //Lorsque que l'on clique en dehors du formulaire
    $('#layer').click(function(e) {
        var form = $("#form-connexion");
        var div = $('#user-type-picker');

        // if the target of the click isn't the container nor a descendant of the container
        if (!form.is(e.target) && form.has(e.target).length === 0 && !div.is(e.target) && div.has(e.target).length === 0 ) {
            unblurElement('#wrapper'); //On retire le flou de la page
            $('#layer').hide();
            $('#form-connexion')[0].reset();
            $('#form-connexion').hide();
            $('#user-type-picker').hide();
        }
    });

});