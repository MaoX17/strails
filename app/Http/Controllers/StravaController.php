<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// use Strava\API\OAuth;
// use Strava\API\Exception;
// use Strava\API\Client;

// use Strava\API\Service\REST;

use Strava;
use Carbon\Carbon;

use Illuminate\Support\Facades\Session;
use App\Models\Access\User\SocialLogin;
use App\Models\Access\User\User;

use Illuminate\Support\Facades\URL;

use Illuminate\Support\Facades\Log;


class StravaController extends Controller
{

    public function getAuthorization()
    {

        //dd($url_strava);

        //quando arrivo qui sono autenticato
        //controllo se è necessario chiedere autorizzazione o già chiesta
        //dd(\Auth::user());
        $user_me = User::find(\Auth::user()->id);
        $strava_token_access = $user_me->token_strava_access;
        $strava_token_refresh = $user_me->token_strava_refresh;


        // Check if current token has expired
        if (($user_me->expires_at) && (Carbon::now()->format('U') > $user_me->expires_at)) {
            // Token has expired, generate new tokens using the currently stored user refresh token
            $refresh = Strava::refreshToken($user_me->token_strava_refresh);
            //dd($refresh);

            // Update the users tokens
            User::where('id', $user_me->id)->update([
                'token_strava_access' => $refresh->access_token,
                'token_strava_refresh' => $refresh->refresh_token,
                'expires_at' => $refresh->expires_at
            ]);
        }

        else if (!isset($_GET['code'])) {
            return Strava::authenticate($scope='read_all,profile:read_all,activity:read_all');
        }

        if (isset($_GET['code'])) {

            $token = Strava::token($_GET['code']);
            
            Session::put('stravaToken', $token->access_token);

            $user_me = User::find(\Auth::user()->id);
            $user_me->token_strava_access = $token->access_token;
            $user_me->token_strava_refresh = $token->refresh_token;
            $user_me->expires_at = $token->expires_at;
            $user_me->save();


        }


            $athlete = Strava::athlete($user_me->token_strava_access);
            //dd($athlete);
            

            return redirect()->route('frontend.index');
        
            
    }




    public function index()
    {

        //quando arrivo qui sono autenticato
        //controllo se è necessario chiedere autorizzazione o già chiesta
        //dd(\Auth::user());
        $user_me = User::find(\Auth::user()->id);
        $strava_token = $user_me->token_strava_access;

        //$refresh = Strava::refreshToken($user_me->token_strava_refresh);
        //dd($refresh->expires_at);


        //Log::info("Carbon::now()".Carbon::now()->format('U'));
        // Check if current token has expired
        //if (Carbon::now()->format('U') > $user_me->expires_at) {
            // Token has expired, generate new tokens using the currently stored user refresh token
            $refresh = Strava::refreshToken($user_me->token_strava_refresh);
            //dd($refresh);

            // Update the users tokens
            User::where('id', $user_me->id)->update([
                'token_strava_access' => $refresh->access_token,
                'token_strava_refresh' => $refresh->refresh_token,
                'expires_at' => $refresh->expires_at
            ]);

            
           

            
        //}

        $strava_token = $user_me->token_strava_access;
        $athlete = Strava::athlete($user_me->token_strava_access);
        

        //dd($athlete);

        $me_id = $athlete->id;

        $activities = Strava::activities($user_me->token_strava_access);


        return view('frontend.stravaActivities')
            ->with('activities', $activities);



            
    }







    public function stravaToGeoJson($activity_id){
        $user_me = User::find(\Auth::user()->id);
        $strava_token = $user_me->token_strava_access;
        $token = $strava_token;


        //$activity = Strava::activities($token);
        $activity = Strava::activity($token, $activity_id);
        //dd($activity);
        $activity_array_pt = \Polyline::decode($activity->map->polyline);
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
                    'segment_efforts' => $activity->segment_efforts
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
        $strava_token = $user_me->token_strava_access;
        $token = $strava_token;
        //dd($token2);



        //$activity = $client->getActivity($activity_id, $include_all_efforts = null);
        $activity = Strava::activity($token, $activity_id);
        //dd($activity);
        $activity_array_pt = \Polyline::decode($activity->map->polyline);

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
