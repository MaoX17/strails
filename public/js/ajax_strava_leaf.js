$(document).ready(function() {

    //$ani = getElementById('loader');
    //$ani.visibility = true;
    $("#loader").show();

        lat = document.getElementById('lat').value;
        lng = document.getElementById('lon').value;

        //console.log("LAT = "+ lat);
        //console.log("LNG = " + lng);
        //strava_start = [lng, lat];


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
        //console.log(div);
        return div;
    };

    legend.addTo(map);


    function stravastyle(feature) {
        return {
            fillColor: 'orange',
            weight: 3,
            opacity: 1,
            color: 'orange',  //Outline color
            fillOpacity: 0.7
        };
    }


    //Strava activity
    var activities = document.getElementsByClassName('bottone');
    //serve il for perchè il getelementS restituisce array
    for (var i = 0; i < activities.length; ++i) {
        var item = activities[i];

        var url_strava = "https://" + window.location.hostname + "/strava/activity/" + item.id;
        var newHTML = '<p>Segmenti Strava Attraversati</p>';
        $("#strava_segment_efforts").append(newHTML);

        $.getJSON(url_strava, function (data) {
            //console.log('data: ' + JSON.stringify(data));
            var geojson = L.geoJson(data, {
                style: stravastyle,
                onEachFeature: function (feature, layer) {
                    layer.bindPopup('La tua attività su Strava');
                    feature.properties.segment_efforts.forEach(element => {
                        var newHTML =
                            '<a href="https://' + window.location.hostname + '/stravasegmentView/' + element.segment.id + '" ' +
                            'class="btn btn-primary btn-xs" ' +
                            'title="Apri" ' +
                            '<i class="fa fa-map-marker" aria-hidden="true"></i> ' +
                            element.segment.name +
                            '</a> &nbsp; ';
                        newHTML +=
                            '<a href="#" ' +
                            'class="btn btn-primary btn-xs" ' +
                            'title="Vedi" ' +
                            'data-toggle="modal" data-target="#myModalView" ' +
                        'data-eval_id="' + element.segment.id + '" ' +
                            'data-eval_type="StravaSegment">' +
                            '<i class="fa fa-search" aria-hidden="true"></i> ' +
                            '</a> &nbsp; ';
                        newHTML +=
                            '<a href="#" class="btn btn-primary btn-xs" ' +
                            'title="Valuta" ' +
                            'data-toggle="modal" data-target="#myModal" ' +
                        'data-eval_id="' + element.segment.id + '" ' +
                            'data-eval_type="StravaSegment">' +
                            '<i class="fa fa-comment-o" aria-hidden="true"></i> ' +
                            '</a> ' +
                            '<br>';
                        
                        
                        
                        
                        
                        
                        
                        
                        /*
                        var newHTML = 
                        '<a href="#" class="btn btn-xs btn-primary" ' +
                            'data-toggle="modal" data-target="#myModal" ' +
                            'data-eval_id="' + element.segment.id + '" ' +
                            'data-eval_type="StravaSegment">' +
                            '<i class="fa fa-map-marker" aria-hidden="true"></i> ' +
                            element.segment.name +
                            ' - Valuta' +
                            '</a>'
                            +
                            '<br>';
                        */
                        $("#strava_segment_efforts").append(newHTML);
                    });
                }
            });
            map.fitBounds(geojson.getBounds());
            geojson.addTo(map);
            //controlLayers.addOverlay(geojson, 'La tua Attività Strava');
        });

    } //fine del for


//segments_strava

    map.on('moveend', function () {
        var z = map.getZoom();
        console.log("zoom: " + z);

        if (z > 12) {
            
        
        //console.log(map.getBounds());
        var bound = map.getBounds();
        //console.log(bound._northEast.lat);
        //console.log(bound._northEast.lng);
        //console.log(bound._southWest.lat);
        //console.log(bound._southWest.lng);

        function stravastyle(feature) {
            return {
                fillColor: 'red',
                weight: 3,
                opacity: 1,
                color: 'red',  //Outline color
                fillOpacity: 0.7
            };
        }

        

            function highlightFeature(e) {
                var layer = e.target;

                layer.setStyle({
                    weight: 4,
                    color: '#666',
                    fillOpacity: 0.7
                });

                if (!L.Browser.ie && !L.Browser.opera && !L.Browser.edge) {
                    layer.bringToFront();
                }
            }
            function resetHighlight(e) {
                //geojson.resetStyle(e.target);
                var layer = e.target;

                layer.setStyle({
                    fillColor: 'red',
                    weight: 3,
                    opacity: 1,
                    color: 'red',  //Outline color
                    fillOpacity: 0.7
                });

            }
        

            var url_strava = "https://" + window.location.hostname + "/ajax/strava_segments2/" + bound._southWest.lat + "/" + bound._southWest.lng + "/" + bound._northEast.lat + "/" + bound._northEast.lng;


        $.getJSON(url_strava, function (data) {
            var newHTML = '<p>Segmenti Strava sulla Mappa</p>';
            $("#strava_segment").html(newHTML);
            var geojson = L.geoJson(data, {
                style: stravastyle,
                onEachFeature: function (feature, layer) {
                    //Aggiungo la lista a un div 
                    //console.log('data: ' + JSON.stringify(data));
                    
                    var newHTML =
                        '<a href="https://' + window.location.hostname + '/stravasegmentView/' + feature.id + '" ' +
                        'class="btn btn-default btn-xs" ' +
                        'title="Apri" ' +
                        '<i class="fa fa-map-marker" aria-hidden="true"></i> ' +
                        feature.properties.name +
                        '</a> &nbsp; ';
                    newHTML +=
                        '<a href="#" ' +
                        'class="btn btn-default btn-xs" ' +
                        'title="Vedi" ' +
                        'data-toggle="modal" data-target="#myModalView" ' +
                        'data-eval_id="' + feature.id + '" ' +
                        'data-eval_type="StravaSegment">' +
                        '<i class="fa fa-search" aria-hidden="true"></i> ' +
                        '</a> &nbsp; ';
                    newHTML +=
                        '<a href="#" class="btn btn-default btn-xs" ' +
                        'title="Valuta" ' +
                        'data-toggle="modal" data-target="#myModal" ' +
                        'data-eval_id="' + feature.id + '" ' +
                        'data-eval_type="StravaSegment">' +
                        '<i class="fa fa-comment-o" aria-hidden="true"></i> ' +
                        '</a> ' +
                        '<br>';


                    /*

                    var newHTML = 
                    '<a href="#" class="btn btn-info btn-xs" ' +
                        'data-toggle="modal" data-target="#myModal" ' +
                        'data-eval_id="' + feature.id + '" ' +
                        'data-eval_type="StravaSegment">' +
                        '<i class="fa fa-map-marker" aria-hidden="true"></i> ' +
                        feature.properties.name +
                        ' - Valuta' +
                        '</a>'
                        +
                        '<br>';
                    */

                    $("#strava_segment").append(newHTML);

                    layer.bindPopup(feature.properties.name + 
                        '</br><a href="https://' + window.location.hostname + '/eval/new/' + feature.id + '/StravaSegment/"> Commenta...</a>' +
                        '<br/>' + 
                        '</br><a href="https://' + window.location.hostname + '/stravasegmentView/' + feature.id + '"> Apri...</a>'
                        
                        );

                    layer.on({
                        mouseover: highlightFeature,
                        mouseout: resetHighlight
                    });

                    layer.url_strava_segment = "https://" + window.location.hostname + "/eval/new/" + feature.id + "/StravaSegment/";

                    layer.on('dblclick', function () {
                        window.location = (this.url_strava_segment);
                    });

                    //Test offset
                    /*
                    console.log(feature);
                    var coords = feature.geometry.coordinates;
                    var lengthOfCoords = feature.geometry.coordinates.length;
                    
                    for (i = 0; i < lengthOfCoords; i++) {
                        holdLon = coords[i][0];
                        coords[i][0] = coords[i][1];
                        coords[i][1] = holdLon;
                    }

                    var offset = L.polyline(coords, {
                        offset: 15,
                        color: 'black'
                    }).addTo(map);
                    */

                }
            });
            
            geojson.addTo(map);
            //controlLayers.addOverlay(geojson, 'Segmenti Strava');
        });


            var redIcon = new L.Icon({
                iconUrl: "https://" + window.location.hostname + "/img/marker-icon-2x-red.png",
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });


            var url_strava_pt = "https://" + window.location.hostname + "/ajax/strava_segments_pt/" + bound._southWest.lat + "/" + bound._southWest.lng + "/" + bound._northEast.lat + "/" + bound._northEast.lng;

            $.getJSON(url_strava_pt, function (data) {
                //var clusters = L.markerClusterGroup();
                // add GeoJSON layer to the map once the file is loaded
                var pt = L.geoJson(data, {
                    pointToLayer: function (feature, latlng) {
                        var marker = L.marker(latlng, { icon: redIcon });
                        marker.bindPopup('Nome: ' + feature.properties.name +
                            '<br/> <a href="https://' + window.location.hostname + '/eval/new/' + feature.id + '/StravaSegment"> Commenta... </a>' +
                            '<br/>' + 
                            '<br/> <a href="https://' + window.location.hostname + '/stravasegmentView/' + feature.id + '"> Apri... </a>'
                        );
                        marker.url = "https://" + window.location.hostname + "/stravasegmentView/" + feature.id;
                        marker.on('dblclick', function () {
                            window.location = (this.url);
                        });
                        return marker;
                    },
                    //onEachFeature: function (feature, layer) {
                    //    layer.addTo(clusters);
                    //}
                });
                //map.addLayer(clusters);
                map.addLayer(pt);
            });








        }
    });





    //new rel

    var id;
    var rels = document.getElementsByClassName('rel');
    for (var i = 0; i < rels.length; ++i) {
        var item = rels[i];
    
        var url = "https://" + window.location.hostname + "/rel/source3/" + item.value;
        id = item.value;
        var obj_id = {'id': id};

        $.getJSON(url, obj_id, function (data) {
            var geojson = L.geoJson(data, {

                //id_rel: rel_id,
                onEachFeature: function (feature, layer) {

                    //console.log(feature);
                    
                    layer.bindPopup(feature.properties.name + 
                        '</br><a href="https://' + window.location.hostname + '/eval/new/' + feature.properties.rel_id + '/Relation/"> Commenta...</a>' +
                        '<br/>' + 
                        '</br><a href="https://' + window.location.hostname + '/relationView/' + feature.properties.rel_id + '"> Apri...</a>'
                        );
                    layer.url_rel = "https://" + window.location.hostname + "/relationView/" + feature.properties.rel_id;
                    //console.log(obj_id);
                    //console.log("https://" + window.location.hostname + "/relationView/" + feature.properties.rel_id);
                    layer.on('dblclick', function () {
                        window.location = (this.url_rel);
                    });
                }
            });
            //map.fitBounds(geojson.getBounds());
            geojson.addTo(map);
            //controlLayers.addOverlay(geojson, 'Sentieri OSM');
        });
           
    } //fine for relations near

    //aggiungi mouse over e click



$("#loader").hide();








});

