$(document).ready(function () {


    var lat = 43.8885465; //document.getElementById('lat').value;
    var lng = 11.0979917; //document.getElementById('lon').value;

    var rel = document.getElementById('segment');
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
            grades = ['Segmento Strava', 'Sentiero OSM', 'Attività strava'],
            labels = [],
            colors = ['#f00', '#00f', '#FFA500'];

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



    function stravastyle(feature) {
        return {
            fillColor: 'red',
            weight: 3,
            opacity: 1,
            color: 'red',  //Outline color
            fillOpacity: 0.7
        };
    }

    var url = "https://" + window.location.hostname + "/ajax/strava_getsegment/" + item.value;
    console.log(url);
   // https://dvp.strails.it/ajax/strava_getsegment/11419346

    $.getJSON(url, function (data) {
        var geojson = L.geoJson(data, {
            style: stravastyle,
            onEachFeature: function (feature, layer) {
                layer.bindPopup(feature.properties.name);
            }
        });
        map.fitBounds(geojson.getBounds());
        geojson.addTo(map);
    });


    /*
        // load GeoJSON from an external file
        $.getJSON(url, function (data) {
            //var clusters = L.markerClusterGroup();
            // add GeoJSON layer to the map once the file is loaded
            var rel = L.geoJson(data, {
                pointToLayer: function (feature, latlng) {
                    var marker = L.marker(latlng);
                    marker.bindPopup('Id della relation ' + feature.properties.rel_id + '<br/>' + 'Nome: ' + feature.properties.name + '<br/>' + 'Fai doppio click qui per aprire il sentiero.');
                    marker.url = "https://" + window.location.hostname + "/relationView/" + feature.properties.rel_id;
                    marker.on('dblclick', function () {
                        window.location = (this.url);
                    });
                    return marker;
                }
            });
            //var clusters = L.markerClusterGroup();
            //clusters.addLayer(incidents);
            map.addLayer(marker);
        });
    
    */

/*

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
                console.debug('CENTRO = ' + data);
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

*/

});




