<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Map\Evaluation;

// use Strava\API\OAuth;
// use Strava\API\Exception;
// use Strava\API\Client;

use Strava;
use Carbon\Carbon;



use Strava\API\Service\REST;

use Illuminate\Support\Facades\Session;
use App\Models\Access\User\SocialLogin;
use App\Models\Access\User\User;

/**
 * Class FrontendController.
 */
class FrontendController extends Controller
{

    public function all_trails_evaluation()
    {
        $evals = Evaluation::orderBy('id', 'desc')->get();

        return view('frontend.view_all_evaluations')
        ->with('evaluations',$evals);
    }


    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        //dd(auth()->user());
        //dd($logged_in_user);

        $best_users = \DB::select('select users.id, users.first_name, users.last_name, COUNT(evaluations.id) as count_eval from users
                            INNER JOIN evaluations
                            on evaluations.user_id=users.id
                            GROUP BY users.id, users.first_name,users.last_name
                            ORDER BY COUNT(evaluations.id) DESC
                            limit 5');
        //dd($best_users);
        //$utente->evaluations()->count()

       //dd($best_users[0]->id);

        $top_user = $best_users[0];

        $evals = Evaluation::orderBy('id', 'desc')->take(10)->get();



        $alert_nostrava = "";
        $alert_nologin = "";

        //attività strava
        $activities = array();
        $mine_evals = array();
        $starred = array();

        if(\Auth::user()) {

            $mine_evals = Evaluation::where('user_id', \Auth::user()->id)->orderBy('id', 'desc')->take(10)->get();

            //dd(\Auth::user());
            if ((\Auth::user()->token_strava_access != "") && (\Auth::user()->token_strava_refresh != "")) {
                $user_me = \Auth::user();

                //dd($user_me);

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



                $token = $user_me->token_strava_access;



                //$adapter = new \GuzzleHttp\Client(['base_uri' => 'https://www.strava.com/api/v3/']);
                //$service = new REST($token2, $adapter);  // Define your user token here.
                //$client = new Client($service);

                //$athlete = $client->getAthlete();
                //$me_id = $athlete['id'];

                //$activities = $client->getAthleteActivities();
                $activities = Strava::activities($token);
                //dd($activities);
                if (count($activities) >= 3){
                    $activities = array_slice($activities, 0, 3);
                }

                //preferiti da strava
                //$starred = $client->getAthleteStarredSegments();
                $starred = Strava::starredSegments($token);
                //dd($starred);

            }
            else {
                $alert_nostrava = "Attenzione! Per poter visualizzare e commentare i segmenti Strava occorre cliccare su \"Connect with Strava\" e concedere i permessi.";
                session()->flash('flash_danger', $alert_nostrava);
            }
        }
        else {
            $alert_nologin = "Attenzione. Per poter utilizzare al meglio l'applicazione è necessario il login. Altrimenti alcune funzioni non saranno attive.";
            session()->flash('flash_danger', $alert_nologin);
        }



        //if (session('pwa') == "true")
        //{
        //dd($alert_nostrava);




        return view('frontend.index')
        ->with('activities',$activities)
        ->with('mine_evaluations',$mine_evals)
        ->with('evaluations',$evals)
        ->with('starred',$starred)
        //->with('logged_in_user', \Auth::user())
        ->with('best_users',$best_users);
        //}
        //else
        //{
        //    return redirect()->route('frontend.guide');
        //}



    }

    /**
     * @return \Illuminate\View\View
     */
    public function macros()
    {
        return view('frontend.macros');
    }

    public function guide()
    {
        session([ 'pwa' => 'true' ]);
        return view('frontend.guide');


    }


    public function intro()
    {
        session([ 'pwa' => 'true' ]);

//        if (\Auth::user()) {
//            return redirect()->route('frontend.index');
//        }
//        else {
            return view('frontend.intro');
//        }

    }






}
