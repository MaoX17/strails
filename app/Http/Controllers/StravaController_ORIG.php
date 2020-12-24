<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Strava\API\OAuth;
use Strava\API\Exception;
use Strava\API\Client;

use Strava\API\Service\REST;

use Illuminate\Support\Facades\Session;
use App\Models\Access\User\SocialLogin;
use App\Models\Access\User\User;

use Illuminate\Support\Facades\URL;


class StravaController extends Controller
{

    public function getAuthorization()
    {

        //quando arrivo qui sono autenticato
        //controllo se è necessario chiedere autorizzazione o già chiesta
        //dd(\Auth::user());
        $user_me = User::find(\Auth::user()->id);
        $strava_token = $user_me->token_strava;
        $token2 = $strava_token;
        //dd($strava_token);

        //se non ho mai richiesto autorizzazione
        if ($strava_token == "") {
            //dd($strava_token);
            //dd("autorizzazione non presente");
            $clientId = env('STRAVA_CLIENT_ID', false);
            $clientSecret = env('STRAVA_CLIENT_SECRET', false);
            //$redirect = 'https://tst.strails.it/login/strava';
            //$redirect = 'https://tst.strails.it/strava/authorization';
            $redirect = route('strava.getauthorization');


            $options = [
                'clientId'     => $clientId,
                'clientSecret' => $clientSecret,
                'redirectUri'  => $redirect
            ];
            $oauth = new OAuth($options);

            // $url_strava = $oauth->getAuthorizationUrl([
            //         // Uncomment required scopes.
            //         'scope' => [
            //             'public',
            //             'write',
            //             'view_private',
            //         ]
            //     ]);

            $url_strava = $oauth->getAuthorizationUrl([
                // Uncomment required scopes.
                'scope' => [
                    'write',
                    //'view_private',
                ]
            ]);

            
            //Il problema è qui!!!!!
            if (!isset($_GET['code'])) {
                return redirect($url_strava);
            }

            

            if (isset($_GET['code'])) {
               
            
                $token = $oauth->getAccessToken('authorization_code', [
                    'code' => $_GET['code']
                ]);
                //dd($token);
            // print $token->getToken();
                $token2 =  $token->getToken();
            
                Session::put('stravaToken', $token2);

                $user_me = User::find(\Auth::user()->id);
                $user_me->token_strava = $token2;
                $user_me->save();


            }
        
            
        }

        /* 11/05/2020
            $adapter = new \GuzzleHttp\Client(['base_uri' => 'https://www.strava.com/api/v3/']);
            $service = new REST($token2, $adapter);  // Define your user token here.
            $client = new Client($service);

            $athlete = $client->getAthlete();
            $me_id = $athlete['id'];
        
            $activities = $client->getAthleteActivities();


            //return view('frontend.stravaActivities')
            //->with('activities',$activities);
            
        */
            return redirect()->route('frontend.index');
        
            
    }






        public function index()
    {

        //quando arrivo qui sono autenticato
        //controllo se è necessario chiedere autorizzazione o già chiesta
        //dd(\Auth::user());
        $user_me = User::find(\Auth::user()->id);
        $strava_token = $user_me->token_strava;
        $token2 = $strava_token;
        //dd($strava_token);

        //se non ho mai richiesto autorizzazione
        if ($strava_token == "") {
            //dd($strava_token);
            //dd("autorizzazione non presente");
            $clientId = env('STRAVA_CLIENT_ID', false);
            $clientSecret = env('STRAVA_CLIENT_SECRET', false);
            //$redirect = 'https://tst.strails.it/login/strava';
            //$redirect = 'https://tst.strails.it/strava/authorization';
            $redirect = route('strava.getauthorization');


            $options = [
                'clientId'     => $clientId,
                'clientSecret' => $clientSecret,
                'redirectUri'  => $redirect
            ];
            $oauth = new OAuth($options);

            $url_strava = $oauth->getAuthorizationUrl([
                    // Uncomment required scopes.
                    'scope' => [
                        'public',
                        'write',
                        'view_private',
                    ]
                ]);

            if (!isset($_GET['code'])) {
                return redirect($url_strava);
            }

            if (isset($_GET['code'])) {
            
            $token = $oauth->getAccessToken('authorization_code', [
                'code' => $_GET['code']
            ]);
           // dd($token);
           // print $token->getToken();
            $token2 =  $token->getToken();
        
            Session::put('stravaToken', $token2);

            $user_me = User::find(\Auth::user()->id);
            $user_me->token_strava = $token2;
            $user_me->save();


            }
    
            
        }

            $adapter = new \GuzzleHttp\Client(['base_uri' => 'https://www.strava.com/api/v3/']);
            $service = new REST($token2, $adapter);  // Define your user token here.
            $client = new Client($service);

            $athlete = $client->getAthlete();
            $me_id = $athlete['id'];
        
            $activities = $client->getAthleteActivities();


            return view('frontend.stravaActivities')
            ->with('activities',$activities);
            //->with('coordinates',$array_coordinates)
            //->with('near_rels_array',$rels);
            
        
            
    }







    public function stravaToGeoJson($activity_id){
        $user_me = User::find(\Auth::user()->id);
        $strava_token = $user_me->token_strava;
        $token = $strava_token;
        //$token = Session::get('stravaToken');
        $adapter = new \GuzzleHttp\Client(['base_uri' => 'https://www.strava.com/api/v3/']);
        $service = new REST($token, $adapter);  // Define your user token here.
        $client = new Client($service);
        //$activity_array_pt, 

        $activity = $client->getActivity($activity_id, $include_all_efforts = true);
        //dd($activity);
        $activity_array_pt = \Polyline::decode($activity['map']['polyline']);
        //dd($activity_array_pt);

                      
                $j = 0;
                for ($i=0; $i <= ((count($activity_array_pt)) -1 ); $i++) {
                    //indice 0 o pari -> Latitudine
                    if (($i % 2) == 0){
                        $array_coordinates[$j] = [$activity_array_pt[$i+1], $activity_array_pt[$i]];
                        $j++;
                    }                    
                }     



        //dd($array_coordinates);
                

        $j = 0;
        //foreach ($this->ways()->orderBy('relationmembers.s')->get() as $way) {
            
            $i = 0;
            
            

            $feature[$j] = array(
                'id' => $activity_id,
                'type' => 'Feature',
                'geometry' => array(
                    'type' => 'LineString',
                    'coordinates' => $array_coordinates
                ),
                # Pass other attribute columns here
                'properties' => array(
                    'name' => $activity_id,
                    'description' => $activity_id,
                    'segment_efforts' => $activity['segment_efforts']
                )
            );

            $j++;


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






    public function stravaNearTrailsLeaf($activity_id){
//        $token = Session::get('stravaToken');
//        dd($token);
        //$url_provenienza = "https://www.strails.it/strava/nearTrails/".$activity_id;
        $url_provenienza = URL::current();
        session(['url_provenienza' => $url_provenienza]);

        $user_me = User::find(\Auth::user()->id);
        $strava_token = $user_me->token_strava;
        $token = $strava_token;
        //dd($token2);

        $adapter = new \GuzzleHttp\Client(['base_uri' => 'https://www.strava.com/api/v3/']);
        $service = new REST($token, $adapter);  // Define your user token here.
        $client = new Client($service);
        //$activity_array_pt, 

        $activity = $client->getActivity($activity_id, $include_all_efforts = null);
        //dd($activity);
        $activity_array_pt = \Polyline::decode($activity['map']['polyline']);

        $j=0;
        for ($i=0; $i <= ((count($activity_array_pt)) -1 ); $i++) {
                    //indice 0 o pari -> Latitudine
                    if (($i % 2) == 0){
                        $array_coordinates[$j] = [$activity_array_pt[$i+1], $activity_array_pt[$i]];
                        $j++;
                    }                    
                }      


        //Posizione activity:
        $me = new \App\Models\Map\Position($array_coordinates[0][1],$array_coordinates[0][0]);        
        $nodes = $me->getNearNodes($array_coordinates[0][1],$array_coordinates[0][0],session('type'));




        $i = 0;
        $j = 0;
        $array_id_ways = array();
        $array_id_rels = array();


        foreach ($nodes as $node)
        {
            //se ho settato la tipologia di sentiero
            if (session('type') != ""){
                //filtro per tipo sentiero
                if ($node['type'] == session('type'))
                {
                    $array_id_rels[$j] = $node['relid'];
                    $j++;
                }
            }
            //altrimenti prendo tutti i sentieri 
            else {
                    $array_id_rels[$j] = $node['relid'];
                    $j++;
            }
        }
        $result = $array_id_rels;
        $rels = \App\Models\Map\Relation::whereIn('id', $result)->get();


        //dd($rels);

        //TODO: togliere le way comprese nella relation?????

        //return view('frontend.near_trails')
            //->with('near_rels_array',$rels);

            return view('frontend.strava_leaf')
            ->with('activity',$activity)
            ->with('coordinates',$array_coordinates)
            ->with('near_rels_array',$rels);



    }






}
