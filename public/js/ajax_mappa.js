$(document).ready(function() {

    $("#loader").show();

    lat = document.getElementById('lat').value;
    lng = document.getElementById('lon').value;

    console.log("LAT = "+ lat);
    console.log("LNG = " + lng);

    var attr_osm = '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors';
    //var attr_overpass = 'POI via <a href="http://www.overpass-api.de/">Overpass API</a>';
    var attr_overpass = '';

    //var tiles = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    var tiles = L.tileLayer('https://{s}.tile.thunderforest.com/cycle/{z}/{x}/{y}{r}.png?apikey=73ccde1305884f6ca1a059aa28670fe8', {
        maxZoom: 18,
        attribution: [attr_osm, attr_overpass].join(', ')
    });
        latlng = L.latLng(lat, lng);

    var map = L.map('map', { 
        center: latlng, 
        zoom: 5, 
        layers: [tiles] 
    });

    
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
            'false': 'View Fullscreen',
            'true': 'Exit Fullscreen'
        }
    }));

    //var url = "https://" + window.location.hostname + "/storage/full_0_100.json";
    var url_pt = "https://" + window.location.hostname + "/storage/full_0_100_pt.json";

    $.getJSON(url_pt, function (data) {
        var clusters = L.markerClusterGroup();
        // add GeoJSON layer to the map once the file is loaded
        var pt = L.geoJson(data, {
            pointToLayer: function (feature, latlng) {

                //console.log('latlng = '+latlng);

                var marker = L.marker(latlng);
                marker.bindPopup('Nome: ' + feature.properties.name + 
                    
                    '<br/> <a href="https://' + window.location.hostname + '/eval/new/' + feature.properties.rel_id + '/Relation"> Commenta... </a>' +
                    '<br/>' +
                    '<br/> <a href="https://' + window.location.hostname + '/relationView/' + feature.properties.rel_id + '"> Apri... </a>'
                    );
                marker.url = "https://" + window.location.hostname + "/relationView/" + feature.properties.rel_id;
                marker.on('dblclick', function () {
                    window.location = (this.url);
                });
                return marker;
            },
            onEachFeature: function (feature, layer) {
                //console.log('feat = '+feature)
                layer.addTo(clusters);
            }
        });
        map.addLayer(clusters);
        //console.log('clu = ' + clusters)
        //console.log('clu = ' + JSON.stringify(clusters));
    });


    var legend = L.control({ position: 'topright' });

    legend.onAdd = function (map) {

        var div = L.DomUtil.create('div', 'legend'),
            grades = ['Segmento Strava', 'Sentiero OSM'],
            labels = [],
            colors = ['#f00', '#00f'];

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

    //segmenti strava
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
                        if (bound.contains(latlng))
                        {
                            //console.log('ok = '+latlng);
                            var newHTML = 
                                '<a href="https://' + window.location.hostname + '/relationView/' + feature.properties.rel_id + '" ' +
                                    'class="btn btn-info btn-xs" '+
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
                                '</a> &nbsp; ' ;
                                newHTML += 
                                    '<a href="#" class="btn btn-info btn-xs" ' +
                                    'title="Valuta" ' +
                                    'data-toggle="modal" data-target="#myModal" ' +
                                    'data-eval_id="' + feature.properties.rel_id + '" ' +
                                    'data-eval_type="Relation">' +
                                    '<i class="fa fa-comment-o" aria-hidden="true"></i> ' +
                                    '</a> ' +
                                    '<br>';
                                    
                                //console.log(newHTML);
                            $("#osm_relation").append(newHTML);

                        }	
                    }
                })
            });
        
        
        
        
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

            //console.log("AAA = " + url_strava);
            var newHTML = '<p>Segmenti Strava sulla Mappa</p>';
            $("#strava_segment").html(newHTML);

            $.getJSON(url_strava, function (data) {
                var newHTML = '<p>Segmenti Strava sulla Mappa</p>';
                $("#strava_segment").html(newHTML);

                var geojson = L.geoJson(data, {
                    style: stravastyle,
                    onEachFeature: function (feature, layer) {
                        
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
                        
                        '<a href="#" class="btn btn-default btn-xs" ' +
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

                        layer.bindPopup('Nome: ' + feature.properties.name + 
                        '<br/> <a href="https://' + window.location.hostname + '/eval/new/' + feature.id + '/StravaSegment"> Commenta... </a>' +
                        '<br/>' + 
                        '<br/> <a href="https://' + window.location.hostname + '/stravasegmentView/' + feature.id + '"> Apri... </a>'
                        );

                        layer.on({
                            mouseover: highlightFeature,
                            mouseout: resetHighlight //,
                            //click: zoomToFeature
                        });

                        layer.url_strava_segment = "https://" + window.location.hostname + "/stravasegmentView/" + feature.id;
                        
                        layer.on('dblclick', function () {
                            window.location = (this.url_strava_segment);
                        });

                        //Test offset
                        /*
                        console.log("feature = " +  feature);
                        var coords = feature.geometry.coordinates;
                        console.log("coords = " + coords[0][0]);
                        latlng = coords[0];
                        console.log(latlng);
                        var marker = L.marker(new L.LatLng(coords[0][0], coords[0][1])).addTo(map);
                        */
                        /*
                        var coords = feature.geometry.coordinates;
                        var lengthOfCoords = feature.geometry.coordinates.length;
                        
                        for (i = 0; i < lengthOfCoords; i++) {
                            holdLon = coords[i][0];
                            coords[i][0] = coords[i][1];
                            coords[i][1] = holdLon;
                        }

                        console.log(555);
    
                        var offset = L.polyline(coords, {
                            offset: 555,
                            color: 'black'
                        }).addTo(map);
                        */
                    }
                });

                geojson.addTo(map);

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

/*
        var opl = new L.OverPassLayer({
            debug: true,
            

            //endpoint: "http://overpass.osm.rambler.ru/cgi/",
            
            query: "node(BBOX)['amenity'='post_box'];out;",

            callback: function (data) {
                for (var i = 0; i < data.elements.length; i++) {
                    var e = data.elements[i];

                    if (e.id in this.instance._ids) return;
                    this.instance._ids[e.id] = true;
                    var pos = new L.LatLng(e.lat, e.lon);
                    var popup = this.instance._poiInfo(e.tags, e.id);
                    var color = e.tags.collection_times ? 'green' : 'red';
                    var circle = L.circle(pos, 50, {
                        color: color,
                        fillColor: '#fa3',
                        fillOpacity: 0.5
                    })
                        .bindPopup(popup);
                    this.instance.addLayer(circle);
                }
            },
            minZoomIndicatorOptions: {
                position: 'bottomleft',
                minZoomMessageNoLayer: "no layer assigned",
                minZoomMessage: "current Zoom-Level: CURRENTZOOM all data at Level: MINZOOMLEVEL"
            },

            
            
        });
        map.addLayer(opl);
*/

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
