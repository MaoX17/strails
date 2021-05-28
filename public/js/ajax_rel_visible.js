$(document).ready(function() {


    var lat = document.getElementById('lat').value;
    var lon = document.getElementById('lon').value;

        mapboxgl.accessToken = 'pk.eyJ1IjoibXByb2lldHRpIiwiYSI6ImNqYThxOGo2ZjBhMmYzMm83dnpvbmNhZGgifQ.VrhU74bBUuh-Mg9iOwatHg';
        var map = new mapboxgl.Map({
            container: 'map', // container id
            style: 'mapbox://styles/mapbox/outdoors-v10', // stylesheet location
            center: [lon, lat], // starting position [lng, lat]

            zoom: 14 // starting zoom
            //zoom: 10 // starting zoom
        });
        map.on('load', function () {

            var rel = document.getElementById('rel');
            var item = rel;

            var url = "https://" + window.location.hostname + "/rel/source3/" + item.value;

                map.addSource(item.value, {
                    type: "geojson",
                    data: url
                });
                // Create layer from source
                map.addLayer({
                    "id": item.value,
                    "type": "line",
                    "source": item.value,
                    "layout": {
                        //MODIFICA:
                        "visibility": "visible",
                        "line-join": "round",
                        "line-cap": "round"
                    },
                    "paint": {
                        "line-color": "#f00",
                        "line-width": 5

                    }
                });




        map.addControl(new mapboxgl.GeolocateControl({
            positionOptions: {
                enableHighAccuracy: true
            },
            trackUserLocation: true,
        }));

        map.addControl(new mapboxgl.NavigationControl());
        });




    var rels = document.getElementsByClassName('rel');

    var centro;

    for (var i = 0; i < rels.length; ++i) {
        var item = rels[i];
        alert(item.id);
        console.log(item);
        var clickedLayer = item.id;
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: "/rel/node/" + item.id,
            type: 'GET',
            dataType: 'json',
            data: {
                _token: CSRF_TOKEN
            },
            cache: false,
            success: function (data) {
                console.debug('CENTRO = '+data);
                centro = data;
                 map.flyTo({
                     center: data
                 });
                if ($.isEmptyObject(data.error)) {
                    console.debug(data);
                    console.debug(data.success);

                } else {
                    console.log(data.error);
                }
            }
        });
    }


});




