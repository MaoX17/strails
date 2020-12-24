
$(document).ready(function() {

    if(geoPosition.init()){  // Geolocation Initialisation
        geoPosition.getCurrentPosition(success_callback,error_callback,{enableHighAccuracy:true});
    }else{
        // You cannot use Geolocation in this device
    }

    // p : geolocation object
    function success_callback(p){
        // p.latitude : latitude value
        // p.longitude : longitude value
        console.debug(p);
        lat = p.coords.latitude;
        lng = p.coords.longitude;

        type = $("#type").val();

        console.log(lat);
        console.log(lng);

        //-----------------------store type in session-------------------------------------

        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: "/ajax/store_type",
            type:'POST',
            dataType: 'json',
            data: {
                _token: CSRF_TOKEN,
                type: type
            },
            cache: false,
            success: function(data) {
                console.debug(data._token);
                if($.isEmptyObject(data.error)){
                    console.debug(data);
                    console.debug(data.success);
                    console.log("Value added " + data.type);

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


    //type = $("#type").val();

    $('#type').change(function () {
        //alert('cambio');
        var type = $(this).val();
        console.log("Value change " + type);

        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url: "/ajax/store_type",
            type: 'POST',
            dataType: 'json',
            data: {
                _token: CSRF_TOKEN,
                type: type
            },
            cache: false,
            success: function (data) {
                
                if ($.isEmptyObject(data.error)) {
                    console.debug(data);
                    console.debug(data.success);
                    console.log("Value added " + data.type);

                } else {
                    console.log(data.error);
                }
            }
        });




    });



});



