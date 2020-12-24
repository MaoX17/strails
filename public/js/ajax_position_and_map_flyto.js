
$(document).ready(function() {

    if(geoPosition.init()){  // Geolocation Initialisation
        geoPosition.getCurrentPosition(success_callback,error_callback,{enableHighAccuracy:true});
    }else{
        // You cannot use Geolocation in this device
    }
    //geoPositionSimulator.init();

    // p : geolocation object
    function success_callback(p){
        // p.latitude : latitude value
        // p.longitude : longitude value
        console.debug(p);
        lat = p.coords.latitude;
        lng = p.coords.longitude;

        console.log(lat);
        console.log(lng);

        mapboxgl.accessToken = 'pk.eyJ1IjoibXByb2lldHRpIiwiYSI6ImNqYThxOGo2ZjBhMmYzMm83dnpvbmNhZGgifQ.VrhU74bBUuh-Mg9iOwatHg';
        var map = new mapboxgl.Map({
            container: 'map', // container id
            style: 'mapbox://styles/mapbox/outdoors-v10', // stylesheet location
            center: [lng, lat], // starting position [lng, lat]
            zoom: 14 // starting zoom
            //zoom: 10 // starting zoom
        });
        map.on('load', function () {

            map.addLayer({
                "id": "points",
                "type": "symbol",
                "source": {
                    "type": "geojson",
                    "data": {
                        "type": "FeatureCollection",
                        "features": [{
                            "type": "Feature",
                            "geometry": {
                                "type": "Point",
                                "coordinates": [lng, lat]
                            },
                            "properties": {
                                "title": "Sei qui",
                                "icon": "bicycle"
                            }
                        }]
                    }
                },
                "layout": {
                    "icon-image": "{icon}-15",
                    "text-field": "{title}",
                    "text-font": ["Open Sans Semibold", "Arial Unicode MS Bold"],
                    "text-offset": [0, 0.6],
                    "text-anchor": "top"
                }
            });


            var ways = document.getElementsByClassName('way');
            for (var i = 0; i < ways.length; ++i) {
                var item = ways[i];
                //item.innerHTML = 'this is value';

                //alert(item.value);

                var url = "https://" + window.location.hostname + "/way/source/" + item.value;
                //var url = "{{ url('way/source', [item.value]) }}";
                //var url = "{{ url('way/source/') }}" + item.value;

                //alert(url);

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
                        "visibility": "none",
                        "line-join": "round",
                        "line-cap": "round"
                    },
                    "paint": {
                        "line-color": "#000",
                        "line-width": 5

                    }
                });

            }

            //new rel
            var rels = document.getElementsByClassName('rel');
            for (var i = 0; i < rels.length; ++i) {
                var item = rels[i];
                //item.innerHTML = 'this is value';

                //alert(item.value);

                var url = "https://" + window.location.hostname + "/rel/source3/" + item.value;
                //var url = "{{ url('rel/source3/') }}" + item.value;
                //var url = "http://dvp.strails.it/rel/source3/" + item.value;
                //alert(url);

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
                        "visibility": "none",
                        "line-join": "round",
                        "line-cap": "round"
                    },
                    "paint": {
                        "line-color": "#f00",
                        "line-width": 5

                    }
                });

            }



            map.addControl(new mapboxgl.GeolocateControl({
                positionOptions: {
                    enableHighAccuracy: true
                },
                trackUserLocation: true,
            }));

            map.addControl(new mapboxgl.NavigationControl());
        });




        var way_visible = document.getElementsByClassName('way_visibility');
        for (var i = 0; i < way_visible.length; ++i) {
            var item = way_visible[i];
            //alert(item.className);
            item.onclick = function (e) {
                var clickedLayer = this.id;
                //alert(clickedLayer);
                e.preventDefault();
                e.stopPropagation();

                map.flyTo({
                    center: [11.1354857,43.8722849]
                });


                var visibility = map.getLayoutProperty(clickedLayer, 'visibility');
                //alert(visibility);

                if (visibility === 'visible') {
                    map.setLayoutProperty(clickedLayer, 'visibility', 'none');
                    this.className = '';
                } else {
                    this.className = 'active';
                    map.setLayoutProperty(clickedLayer, 'visibility', 'visible');
                }


            }
        }



//new rel

        $("[name='my-checkbox']").bootstrapSwitch({
            size: 'mini',
            onSwitchChange: function (event, state) {
                event.preventDefault()
                //return console.log(state, event.isDefaultPrevented())
                console.log(state, event.isDefaultPrevented())

                var clickedLayer = this.id;


                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    url: "/rel/node/" + this.id,
                    type:'GET',
                    dataType: 'json',
                    data: {
                        _token: CSRF_TOKEN
                    },
                    cache: false,
                    success: function(data) {
                        console.debug(data);
                        map.flyTo({
                            center: data
                        });
                        if($.isEmptyObject(data.error)){
                            console.debug(data);
                            console.debug(data.success);

                        }else{
                            console.log(data.error);
                        }
                    }
                });


                var visibility = map.getLayoutProperty(clickedLayer, 'visibility');

                if (visibility === 'visible') {
                    map.setLayoutProperty(clickedLayer, 'visibility', 'none');
                    this.className = '';
                } else {
                    this.className = 'active';
                    map.setLayoutProperty(clickedLayer, 'visibility', 'visible');
                }


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

    }

    function error_callback(p){
        // p.message : error message
        alert('Impossibile stabilire la posizione');
    }


});
