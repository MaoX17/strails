<?php

namespace App\Models\Map;

use Illuminate\Database\Eloquent\Model;

use Strava;

use Illuminate\Support\Facades\Session;
use App\Models\Access\User\SocialLogin;
use App\Models\Access\User\User;


class StravaSegment extends Model
{
    //
    protected $table = 'stravasegments';
    protected $primaryKey = 'id';

    protected $fillable = ['id', 'name', 'description'];



    public function evaluations()
    {
        return $this->morphToMany('App\Models\Map\Evaluation', 'evaluable', 'evaluables','evaluable_id','evaluation_id')
            ->orderBy('updated_at', 'DESC');
    }


    public function getName()
    {

        return $this->name;

    }


    public function echoNomeCorretto($name)
    {
        return $name;
    }

    public function getCoordinate()
    {

        $result = [$this->lng, $this->lat];
        return $result;


    }


    public function favoriteFollowingUsers()
    {
        return $this->belongsToMany('App\Models\Access\User\User', 'favourites', 'relation_id', 'user_id')->withTimeStamps();

    }

    public function getEvaluationType()
    {
        return class_basename(get_class($this));
    }

    public function lastStateClass()
    {
        $lastEval = $this->evaluations()->first();
        if (\is_object($lastEval))
        {
            $return = $lastEval->rating;
        }
        else {
            $return = "info";
        }

        //dd($return);
        return $return;
        
    }

    public function updateTmpSegment()
    {
        
        //quando arrivo qui sono autenticato
        //controllo se è necessario chiedere autorizzazione o già chiesta
        //dd(\Auth::user());
        $user_me = User::find(\Auth::user()->id);
        $strava_token = $user_me->token_strava_access;
        $token = $strava_token;
        //dd($strava_token);

        //se non ho mai richiesto autorizzazione
        /*
        if ($strava_token == "") {
            $clientId = env('STRAVA_CLIENT_ID', false);
            $clientSecret = env('STRAVA_CLIENT_SECRET', false);
            $redirect = route('strava.getauthorization');


            $options = [
                'clientId'     => $clientId,
                'clientSecret' => $clientSecret,
                'redirectUri'  => $redirect
            ];
            $oauth = new OAuth($options);

            $url_strava = $oauth->getAuthorizationUrl([
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
            $token2 =  $token->getToken();
            Session::put('stravaToken', $token2);
            $user_me = User::find(\Auth::user()->id);
            $user_me->token_strava_access = $token2;
            $user_me->save();


            }
    
            
        }
        */

        
            
            //$segment = $client->getSegment($this->id);
            $segment = Strava::segment($token, $this->id);

            //dd($segment);

            //return $segment;

            $this->name = $segment->name;
            $this->description = $segment->name;


            $this->save();

            $this->lat = $segment->start_latlng[0];
            $this->lng = $segment->start_latlng[1];
            $this->polyline = $segment->map->polyline;

            $this->tmp = $segment;



    }
    



}
