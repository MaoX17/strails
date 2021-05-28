<?php

namespace App\Http\Controllers;

use App\Models\Map\Way;
use Illuminate\Http\Request;

// use Strava\API\OAuth;
// use Strava\API\Exception;
// use Strava\API\Client;

// use Strava\API\Service\REST;

use Strava;

use Illuminate\Support\Facades\Session;
use App\Models\Access\User\SocialLogin;
use App\Models\Access\User\User;




class AjaxController extends Controller
{


    

    public function inviaLog (Request $request)
    {
        \Log::info('Inizio log');
        $user = \Auth::user();  

        \Log::info('utente loggato $user:'.$user);
        \Log::info('request:'. json_encode($request));
        
        $result = [
            'inviato' => $request,
            'success' => true
        ];

        return response()->json($result);
    }





    public function saveSubscriptionNotify (Request $request)
    {
        \Log::info('Inizio saveSubscription');
        $user = \Auth::user();  

        \Log::info('utente loggato $user:'.$user);
        \Log::info('endpoint:'.$request->input('endpoint'));
        \Log::info('keys:'.$request->input('keys'));
        \Log::info('keys.p256dh:'.$request->input('keys.p256dh'));
        \Log::info('keys.auth:'.$request->input('keys.auth'));
        \Log::info('auth:'.$request->input('auth'));
        \Log::info('p256dh:'.$request->input('p256dh'));

        $json_obj = json_decode($request->input('json_data'));
        \Log::info('p256dh_2:'.$json_obj->keys->p256dh);

        

        //$user->updatePushSubscription($request->input('endpoint'), $request->input('keys.p256dh'), $request->input('keys.auth'));
        $user->updatePushSubscription($request->input('endpoint'), $json_obj->keys->p256dh, $json_obj->keys->auth);
        //$user->notify(new \App\Notifications\GenericNotification("Welcome To WebPush", "You will now get all of our push notifications"));

        $result = [
            'inviato' => $request,
            'success' => true
        ];

        return response()->json($result);
    }

    public function sendNotificationNotify (Request $request)
    {
        $user = \Auth::user();  

        \Log::info('utente loggato $user:'.$user);
        $user->updatePushSubscription($request->input('endpoint'), $request->input('keys.p256dh'), $request->input('keys.auth'));
        $user->notify(new \App\Notifications\GenericNotification("Welcome To WebPush", "You will now get all of our push notifications"));
        return response()->json([
            'success' => true
        ]);
    }





    public function stravaSegments(Request $request)
    {

        $user_me = User::find(\Auth::user()->id);
        \Log::info('user: ' . $user_me);
        $strava_token = $user_me->token_strava_access;
        $token = $strava_token;
      //  dd($token2);

        //$bounds = "$request->lat1,$request->lon1,$request->lat2,$request->lon2";
        $bounds = '43.855078,11.174469,43.872344,11.225023';
    

        $segments_multi = Strava::starredSegments($token, $bounds, 'riding');
        
        $array_result = array();
        //$array_result['success'] = 'success';
        //che succede se ho un array nullo?
        $i = 0;
        foreach($segments_multi as $segments)
        {
            foreach($segments as $segment)
            {
                $segment_array_pt = \Polyline::decode($segment->points);
                $array_coordinates = array();
                              
                $j = 0;
                for ($z=0; $z <= ((count($segment_array_pt)) -1 ); $z++) {
                    //indice 0 o pari -> Latitudine
                    if (($z % 2) == 0){
                        $array_coordinates[$j] = [$segment_array_pt[$z+1], $segment_array_pt[$z]];
                        $j++;
                    }                    
                }      

                $array_result[$i] = [
                    "id" => $segment->id,
                    "name" => $segment->name,
                    "avg_grade" => $segment->avg_grade,
                    "elev_difference" => $segment->elev_difference,
                    "distance" => $segment->distance,
                    "points" => $segment->points,
                    "array_pt" => $array_coordinates
                ];
                $i++;
            }
        }




        \Log::info('token: ' . $token);
        \Log::info('Lat1: ' . $request->lat1);
        \Log::info('Lon1: ' . $request->lon1);
        \Log::info('Lat2: ' . $request->lat2);
        \Log::info('Lon2: ' . $request->lon2);

        



        //$result = $array_result;
        //return response()->json($result);

        //dd(count($array_result));
        for ($j=0; $j<=((count($array_result))-1); $j++ ){

        
            $feature[$j] = array(
                    'id' => $array_result[$j]['id'],
                    'type' => 'Feature',
                    'geometry' => array(
                        'type' => 'LineString',
                        'coordinates' => $array_result[$j]['array_pt']
                    ),
                    # Pass other attribute columns here
                    'properties' => array(
                        "name" => $array_result[$j]['name'],
                        "avg_grade" => $array_result[$j]['avg_grade'],
                        "elev_difference" => $array_result[$j]['elev_difference'],
                        "distance" => $array_result[$j]['distance'],
                        'description' => $array_result[$j]['name']
                    )
                );

        //    $j++;
        }

        //}

        $geojson = array(
            'type'      => 'FeatureCollection',
            'features'  => $feature
        );

        if (version_compare(phpversion(), '7.1', '>=')) {
            ini_set( 'serialize_precision',  9);
        }

        header('Content-type: application/json');
        $result = json_encode($geojson, JSON_NUMERIC_CHECK);

        //echo($result);
        return $result;


    }




    public function stravaSegments2($lat1,$lng1,$lat2,$lng2)
    {

        //Controllo su login e token strava
        $result = null;
        if ((User::find(\Auth::user()->id)->token_strava_access) != "") {
            $user_me = User::find(\Auth::user()->id);
            \Log::info('user: ' . $user_me);
            $strava_token = $user_me->token_strava_access;
            $token = $strava_token;

            $feature = array();
        
                    
            //$bounds = '43.855078,11.174469,43.872344,11.225023';
            $lat1 = round($lat1, 6);
            $lng1 = round($lng1, 6);
            $lat2 = round($lat2, 6);
            $lng2 = round($lng2, 6);
            $bounds = "$lat1,$lng1,$lat2,$lng2";


            $segments_multi = Strava::exploreSegments($token, $bounds, 'riding');
            //$client->getSegmentExplorer($bounds, 'riding');
            \Log::info('seg: ' . serialize($segments_multi));

            $array_result = array();


            $i = 0;
            foreach($segments_multi as $segments)
            {
                foreach($segments as $segment)
                {
                    $segment_array_pt = \Polyline::decode($segment->points);
                    $array_coordinates = array();
                                
                    $j = 0;
                    for ($z=0; $z <= ((count($segment_array_pt)) -1 ); $z++) {
                        //indice 0 o pari -> Latitudine
                        if (($z % 2) == 0){
                            $array_coordinates[$j] = [$segment_array_pt[$z+1], $segment_array_pt[$z]];
                            $j++;
                        }                    
                    }      

                    $array_result[$i] = [
                        "id" => $segment->id,
                        "name" => $segment->name,
                        "avg_grade" => $segment->avg_grade,
                        "elev_difference" => $segment->elev_difference,
                        "distance" => $segment->distance,
                        "points" => $segment->points,
                        "array_pt" => $array_coordinates
                    ];
                    $i++;
                }
            }
/*
            \Log::info('token: ' . $token2);
            \Log::info('Lat1: ' . $lat1);
            \Log::info('Lon1: ' . $lng1);
            \Log::info('Lat2: ' . $lat2);
            \Log::info('Lon2: ' . $lng2);
*/
            for ($j=0; $j<=((count($array_result))-1); $j++ ){
        
                $feature[$j] = array(
                        'id' => $array_result[$j]['id'],
                        'type' => 'Feature',
                        'geometry' => array(
                            'type' => 'LineString',
                            'coordinates' => $array_result[$j]['array_pt']
                        ),
                        # Pass other attribute columns here
                        'properties' => array(
                            "name" => $array_result[$j]['name'],
                            "avg_grade" => $array_result[$j]['avg_grade'],
                            "elev_difference" => $array_result[$j]['elev_difference'],
                            "distance" => $array_result[$j]['distance'],
                            'description' => $array_result[$j]['name']
                        )
                    );

            }


            $geojson = array(
                'type'      => 'FeatureCollection',
                'features'  => $feature
            );

            if (version_compare(phpversion(), '7.1', '>=')) {
                ini_set( 'serialize_precision',  9);
            }

            header('Content-type: application/json');
            $result = json_encode($geojson, JSON_NUMERIC_CHECK);
        }


        return $result;


    }
    



    public function stravaSegments_pt($lat1,$lng1,$lat2,$lng2)
    {

        //Controllo su login e token strava
        $result = null;
        if ((User::find(\Auth::user()->id)->token_strava_access) != "") {
            $user_me = User::find(\Auth::user()->id);
            \Log::info('user: ' . $user_me);
            $strava_token = $user_me->token_strava_access;
            $token = $strava_token;

            $feature = array();
        
          
            //$bounds = '43.855078,11.174469,43.872344,11.225023';
            $lat1 = round($lat1, 6);
            $lng1 = round($lng1, 6);
            $lat2 = round($lat2, 6);
            $lng2 = round($lng2, 6);
            $bounds = "$lat1,$lng1,$lat2,$lng2";

            //$segments_multi = $client->getSegmentExplorer($bounds, 'riding');
            $segments_multi = Strava::starredSegments($token, $bounds, 'riding');
            
            $array_result = array();

            $i = 0;
            foreach($segments_multi as $segments)
            {
                foreach($segments as $segment)
                {
                    $segment_array_pt = \Polyline::decode($segment->points);
                    $array_coordinates = array();
                                
                    $j = 0;
                    $z=0;
                    //for ($z=0; $z <= ((count($segment_array_pt)) -1 ); $z++) {
                        //indice 0 o pari -> Latitudine
                      //  if (($z % 2) == 0){
                            $array_coordinates = [$segment_array_pt[$z+1], $segment_array_pt[$z]];
                        //    $j++;
                        //}                    
                    //}      

                    $array_result[$i] = [
                        "id" => $segment->id,
                        "name" => $segment->name,
                        "avg_grade" => $segment->avg_grade,
                        "elev_difference" => $segment->elev_difference,
                        "distance" => $segment->distance,
                        "points" => $segment->points,
                        "array_pt" => $array_coordinates
                    ];
                    $i++;
                }
            }
/*
            \Log::info('token: ' . $token2);
            \Log::info('Lat1: ' . $lat1);
            \Log::info('Lon1: ' . $lng1);
            \Log::info('Lat2: ' . $lat2);
            \Log::info('Lon2: ' . $lng2);
*/
            for ($j=0; $j<=((count($array_result))-1); $j++ ){
        
                $feature[$j] = array(
                        'id' => $array_result[$j]['id'],
                        'type' => 'Feature',
                        'geometry' => array(
                            'type' => 'Point',
                            'coordinates' => $array_result[$j]['array_pt']
                        ),
                        # Pass other attribute columns here
                        'properties' => array(
                            "name" => $array_result[$j]['name'],
                            "avg_grade" => $array_result[$j]['avg_grade'],
                            "elev_difference" => $array_result[$j]['elev_difference'],
                            "distance" => $array_result[$j]['distance'],
                            'description' => $array_result[$j]['name']
                        )
                    );

            }


            $geojson = array(
                'type'      => 'FeatureCollection',
                'features'  => $feature
            );

            if (version_compare(phpversion(), '7.1', '>=')) {
                ini_set( 'serialize_precision',  9);
            }

            header('Content-type: application/json');
            $result = json_encode($geojson, JSON_NUMERIC_CHECK);
        }


        return $result;


    }










public function stravaGetSegment($segment_id)
    {

        $user_me = User::find(\Auth::user()->id);
        \Log::info('user: ' . $user_me);
        $strava_token = $user_me->token_strava_access;
        $token = $strava_token;

        $feature = array();
      //  dd($token2);

        

        //$segment = $client->getSegment($segment_id);
        $segment = Strava::segment($token, $segment_id);
        //dd($segment);
        
        $array_result = array();
        //$array_result['success'] = 'success';
        $i = 0;
        $segment_array_pt = \Polyline::decode($segment->map->polyline);
        $array_coordinates = array();
                              
        $j = 0;
        for ($z=0; $z <= ((count($segment_array_pt)) -1 ); $z++) {
        //indice 0 o pari -> Latitudine
            if (($z % 2) == 0){
                $array_coordinates[$j] = [$segment_array_pt[$z+1], $segment_array_pt[$z]];
                $j++;
            }                    
        }      

        $array_result[$i] = [
        "id" => $segment->id,
        "name" => $segment->name,
        "distance" => $segment->distance,
         "array_pt" => $array_coordinates
        ];
        


        for ($j=0; $j<=((count($array_result))-1); $j++ ){

            $feature[$j] = array(
                    'id' => $array_result[$j]['id'],
                    'type' => 'Feature',
                    'geometry' => array(
                        'type' => 'LineString',
                        'coordinates' => $array_result[$j]['array_pt']
                    ),
                    # Pass other attribute columns here
                    'properties' => array(
                        "name" => $array_result[$j]['name'],
                        "distance" => $array_result[$j]['distance'],
                        'description' => $array_result[$j]['name']
                    )
                );

        //    $j++;
        }

        //}

        $geojson = array(
            'type'      => 'FeatureCollection',
            'features'  => $feature
        );

        if (version_compare(phpversion(), '7.1', '>=')) {
            ini_set( 'serialize_precision',  9);
        }

        header('Content-type: application/json');
        $result = json_encode($geojson, JSON_NUMERIC_CHECK);

        //echo($result);
        return $result;


    }







    //
    public function storeSessionPosition(Request $request)
    {
        \Log::info('Lat: ' . $request->lat);
        \Log::info('Lng: ' . $request->lng);

        session([ 'lng' => $request->lng ]);
        session([ 'lat' => $request->lat ]);

        $result = [
            'latitude' => $request->lat,
            'longitude' => $request->lng,
            'success' => 'success'
        ];

        \Log::info('sess Lat: ' . session('lat'));
        \Log::info('sess Lng: ' . session('lng'));

        return response()->json($result);

    }


    public function getSessionPosition(Request $request) {

        $lat = session('lat');
        $lng = session('lng');

        \Log::info('sess Lat: ' . session('lat'));
        \Log::info('sess Lng: ' . session('lng'));


        $sql = "CALL dvpstrails.nodes_vicini($lat, $lng, 100, 3);";
        \Log::info('SQL: '. $sql);

        $result = \DB::select($sql);

        return response()->json($result);

    }


        public function storeSessionType(Request $request)
    {
        \Log::info('Type: ' . $request->type);
        

        session([ 'type' => $request->type ]);
        

        $result = [
            'type' => $request->type,
            'success' => 'success'
        ];

        \Log::info('sess type: ' . session('type'));


        return response()->json($result);

    }


    //Non capisco perchè ritormna quei valori
    public function getSessionType(Request $request) {

        $type = session('type');
        

        \Log::info('sess type: ' . session('type'));
        


        $sql = "CALL dvpstrails.nodes_vicini($lat, $lng, 100, 3);";
        \Log::info('SQL: '. $sql);

        $result = \DB::select($sql);

        return response()->json($result);

    }







    public function getWayNodes(Request $request) {

        $wayId = $request->wayid;
        $way = Way::find($wayId);
        $way_nodes = $way->nodes()->get();

        print_r($way_nodes);

        return response()->json($result);

    }
}
