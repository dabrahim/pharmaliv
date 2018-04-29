$(document).ready(function () {

    $('#form-inscription-livreur').on('submit', function (e) {
        e.preventDefault();

        $.post('./inscription', $(this).serialize(), function (response) {
            if (response.success){
                window.location = '/?show=login&email=' + response.email;
            } else {
                alert(response.message);
            }
        }, 'json');
    });

});