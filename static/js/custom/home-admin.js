//Set up some of our variables.
var map; //Will contain map object.
var marker = false; ////Has the user plotted their location marker?
var circle;

function myMap() {
    var lat = 14.694632395107812;
    var lng = -17.44843009547094;

    //The center location of our map.
    var centerOfMap = new google.maps.LatLng(lat, lng);

    $('#lat').val( lat );
    $('#lng').val( lng );

    //Map options.
    var options = {
        center: centerOfMap, //Set center.
        zoom: 12 //The zoom value.
    };

    //Create the map object.
    map = new google.maps.Map(document.getElementById('googleMap'), options);

    //Listen for any clicks on the map.
    google.maps.event.addListener(map, 'click', function(event) {
        //Get the location that the user clicked.
        var clickedLocation = event.latLng;

        //Marker has already been added, so just change its location.
        marker.setPosition(clickedLocation);

        //Get the marker's location.
        markerLocation();
    });

    marker = new google.maps.Marker({
        position: centerOfMap,
        map: map,
        draggable: true //make it draggable
    });

    circle = new google.maps.Circle({
        strokeColor: '#FF0000',
        strokeOpacity: 0.8,
        strokeWeight: 2,
        fillColor: '#FF0000',
        fillOpacity: 0.35,
        map: map,
        center: centerOfMap,
        radius: 1000
    });

    //Listen for drag events!
    google.maps.event.addListener(marker, 'dragend', function(event){
        markerLocation();
    });
}

//This function will get the marker's current location and then add the lat/long
//values to our textfields so that we can save the location.
function markerLocation(){
    //Get location.
    var currentLocation = marker.getPosition();

    circle.setOptions({
       center : currentLocation
    });
    //Add lat and lng values to a field that we can save.
    document.getElementById('lat').value = currentLocation.lat(); //latitude
    document.getElementById('lng').value = currentLocation.lng(); //longitude
}

$(document).ready(function () {
    $('#btn-creer-zone').on('click', function () {
        $('#form-creer-zone').show();
        $('#layer').show();
        blurElement('#wrapper', 10);

        $.post('./admin/zone/all', null, function (zones) {
            for (var zone of zones) {
                var coord = zone.coordGeo.split(',').map(parseFloat);
               var cityCircle = new google.maps.Circle({
                    strokeColor: '#008000',
                    strokeOpacity: 0.8,
                    strokeWeight: 2,
                    fillColor: '#008000',
                    fillOpacity: 0.35,
                    map: map,
                    center: {lat : coord[0], lng : coord[1]},
                    radius: parseInt(zone.rayon) * 1000
                });
            }
        }, 'json');
    });

    $('#form-creer-zone input[type="number"]').on('keyup', function (){
       var rayon = parseInt( $(this).val() );
        if ($.isNumeric(rayon)){
            circle.setOptions({
                radius : rayon * 1000
            });
        }
    });

    $('#btn-creer-clef').on('click', function () {
        showLayer();
        $('#creer-livreur').css({
           display:'inline-block'
        });

        $.post('./admin/zone/all', null, function (zones) {
            for(var zone of zones){
                $('#zones-select').append( $('<option value="'+zone.id_zone+'">'+zone.nom+'</option>') );
            }
        }, 'json');
    });

    $('#add-livreur-btn').on('click', function () {
        $.post('./admin/creer/livreur', {idZones : $('#zones-select').val()}, function (response) {
            if(response.success){
                alert("Clef d'inscription du livreur : " + response.clef);
                window.location.reload();
            } else {
                alert(response.message);
            }
        }, 'json');
    });

    $('#add-zone').on('click', function () {
        var data = {
            coordGeo : $('#lat').val() + ',' + $('#lng').val(),
            nom : $('#nomZone').val(),
            rayon : $('#rayonZone').val()
        };

        $.post('./admin/creer/zone', data, function (response) {
            if(response.success){
                alert('La zone a été créée avec succès');
                window.location.reload();
            } else {
                alert('Echec lors de la création de la zone');
            }
        }, 'json');
    });
});

