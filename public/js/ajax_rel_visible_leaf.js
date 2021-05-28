$(document).ready(function() {


    //var lat = document.getElementById('lat').value;
    //var lng = document.getElementById('lon').value;

    var lat = "43.7739766";
    var lng = "11.2864031";

    var rel = document.getElementById('rel');
    var item = rel;

    //var tiles = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    var tiles = L.tileLayer('https://{s}.tile.thunderforest.com/cycle/{z}/{x}/{y}{r}.png?apikey=73ccde1305884f6ca1a059aa28670fe8', {    
        maxZoom: 18,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }),
        latlng = L.latLng(lat, lng);

    var map = L.map('map', { center: latlng, zoom: 5, layers: [tiles] });

    map.addControl(L.control.locate({
        flyTo: true,
        strings: {
            title: "Dove mi trovo..."
        },
        locateOptions: {
            enableHighAccuracy: true,
            maxZoom: 14,
        }
    }));

    map.addControl(new L.Control.Fullscreen({
        title: {
            'false': 'Mappa a schermo intero',
            'true': 'Esci da mappa a schermo intero'
        }
    }));

    var legend = L.control({ position: 'topright' });

    legend.onAdd = function (map) {

        var div = L.DomUtil.create('div', 'legend'),
            grades = ['Sentiero OSM'],
            labels = [],
            colors = ['#00f'];

        for (var i = 0; i < grades.length; i++) {

            labels.push(
                '<i style="background:' + colors[i] + '"> &nbsp;&nbsp;  </i> ' + grades[i]
            );
        }

        div.innerHTML = labels.join('<br>');
        console.log(div);
        return div;
    };

    legend.addTo(map);


    var url = "https://" + window.location.hostname + "/rel/source3/" + item.value;

    $.getJSON(url, function (data) {
        var geojson = L.geoJson(data, {
            onEachFeature: function (feature, layer) {
                layer.bindPopup(feature.properties.name);
            }
        });
        map.fitBounds(geojson.getBounds());
        geojson.addTo(map);
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




