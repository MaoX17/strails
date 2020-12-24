$(document).ready(function() {


    //$ani = getElementById('loader');
    //$ani.visibility = true;
    $("#loader").show();

        lat = document.getElementById('lat').value;
        lng = document.getElementById('lon').value;

        console.log("LAT = "+ lat);
        console.log("LNG = " + lng);
        //strava_start = [lng, lat];

    //var tiles = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    var tiles = L.tileLayer('https://{s}.tile.thunderforest.com/cycle/{z}/{x}/{y}{r}.png?apikey=73ccde1305884f6ca1a059aa28670fe8', {
        maxZoom: 18,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }),
        latlng = L.latLng(lat, lng);

    var map = L.map('map', { center: latlng, zoom: 5, layers: [tiles] });
    
    //var controlLayers = L.control.layers().addTo(map);

    
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


            //var url = "https://" + window.location.hostname + "/rel/source3/" + item.value;
            var url = "https://" + window.location.hostname + "/storage/full_0_100.json";
            //var url_pt = "https://" + window.location.hostname + "/rel/node3/" + item.value;
            var url_pt = "https://" + window.location.hostname + "/storage/full_0_100_pt.json";
    

    //var geojsonLayer = new L.GeoJSON.AJAX(url_pt);
    //geojsonLayer.addTo(map);

    // load GeoJSON from an external file
    $.getJSON(url_pt, function (data) {
        var clusters = L.markerClusterGroup();
        // add GeoJSON layer to the map once the file is loaded
        var pt = L.geoJson(data, {
            pointToLayer: function (feature, latlng) {
                var marker = L.marker(latlng);
                marker.bindPopup('Nome: ' + feature.properties.name + '<br/>' + 'Fai doppio click qui per aprire il sentiero.' +
                    '<br/> <a href="https://' + window.location.hostname + '/eval/new/' + feature.properties.rel_id +'/Relation"> Commenta... </a>' +
                    '<br/> <a href="https://' + window.location.hostname + '/relationView/' + feature.properties.rel_id + '"> Apri... </a>'
                    );
                marker.url = "https://" + window.location.hostname + "/relationView/" + feature.properties.rel_id;
                marker.on('dblclick', function () {
                    window.location = (this.url);
                });
                return marker;
            },
            onEachFeature: function (feature, layer) {
                layer.addTo(clusters);
            }
        });
        //var clusters = L.markerClusterGroup();
        //clusters.addLayer(incidents);
        map.addLayer(clusters);
    });


    map.on('moveend', function () {
        var z = map.getZoom();
        console.log("zoom: " + z);

        //mostra solo sopra un certo zoom
        if (z > 13) {

            //console.log(map.getBounds());
            var bound = map.getBounds();
            //console.log(bound._northEast.lat);
            //console.log(bound._northEast.lng);
            //console.log(bound._southWest.lat);
            //console.log(bound._southWest.lng);

            //sentieri osm su mappa
            var newHTML = '<p>Sentieri OSM sulla Mappa</p>';
            $("#osm_relation").html(newHTML);

            $.getJSON(url_pt, function (data) {
                var clusters = L.markerClusterGroup();
                // add GeoJSON layer to the map once the file is loaded
                var pt = L.geoJson(data, {
                    pointToLayer: function (feature, latlng) {
                        //console.log('latlng = ' + latlng);
                        if (bound.contains(latlng)) {
                            //console.log('ok = '+latlng);
                            
                            var newHTML =
                                '<a href="https://' + window.location.hostname + '/relationView/' + feature.properties.rel_id + '" ' +
                                'class="btn btn-info btn-xs" ' +
                                'title="Apri" ' +
                                '<i class="fa fa-map-marker" aria-hidden="true"></i> ' +
                                feature.properties.name +
                                '</a> &nbsp; ';
                            newHTML +=
                                '<a href="#" ' +
                                'class="btn btn-info btn-xs" ' +
                                'title="Vedi" ' +
                                'data-toggle="modal" data-target="#myModalView" ' +
                                'data-eval_id="' + feature.properties.rel_id + '" ' +
                                'data-eval_type="Relation">' +
                                '<i class="fa fa-search" aria-hidden="true"></i> ' +
                                '</a> &nbsp; ';
                            newHTML +=
                                '<a href="#" class="btn btn-info btn-xs" ' +
                                'title="Valuta" ' +
                                'data-toggle="modal" data-target="#myModal" ' +
                                'data-eval_id="' + feature.properties.rel_id + '" ' +
                                'data-eval_type="Relation">' +
                                '<i class="fa fa-comment-o" aria-hidden="true"></i> ' +
                                '</a> ' +
                                '<br>';
                                
                            /*
                            var newHTML = 
                            
                            '<a href="#" class="btn btn-info btn-xs" ' +
                                'data-toggle="modal" data-target="#myModal" ' +
                                'data-eval_id="' + feature.properties.rel_id + '" ' +
                                'data-eval_type="Relation">' +
                                '<i class="fa fa-map-marker" aria-hidden="true"></i> ' +
                                feature.properties.name +
                                ' - Valuta' +
                                '</a>'
                                +
                                '<br>';
                            */
                            $("#osm_relation").append(newHTML);

                        }
                    }
                })
            });
        }
    });






        //-----------------------store pos in session-------------------------------------

        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: "/ajax/store_position",
            type:'POST',
            dataType: 'json',
            data: {
                _token: CSRF_TOKEN,
                lat: lat,
                lng: lng
            },
            cache: false,
            success: function(data) {
                console.debug(data._token);
                if($.isEmptyObject(data.error)){
                    console.debug(data);
                    console.debug(data.success);
                    console.log("Value added " + data.latitude);

                }else{
                    console.log(data.error);
                }
            }
        });





$("#loader").hide();




});
