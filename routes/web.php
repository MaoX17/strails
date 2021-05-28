<?php


use Illuminate\Support\Facades\Route;


/*
Route::get('/', function () {
    return view('welcome');
});

*/

//Auth::routes();


//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');




//** STRAVA */
Route::group(['prefix' => 'strava', 'as' => 'strava.', 'middleware' => 'user'], function () {
    Route::get('/', array('as' => 'index', 'uses'=>'StravaController@index'));
    Route::get('/authorization', array('as' => 'getauthorization', 'uses'=>'StravaController@getAuthorization'));

    Route::get('/activity/{activity_id}', 'StravaController@stravaToGeoJson')->name('stravaToGeoJson');
    Route::get('/nearTrails/{activity_id}', 'StravaController@stravaNearTrailsLeaf')->name('stravaNearTrails');


});


//TEST
Route::get('/test_notify', function()
    {
        return View::make('frontend.test_notify');
    });




/**
 * Nuove rotte maox
 */
Route::get('mappa',array('as'=>'mappa','uses'=>'MapController@mappashow'));

Route::get('search',array('as'=>'search','uses'=>'SearchController@search'));
Route::get('autocomplete',array('as'=>'autocomplete','uses'=>'SearchController@autocomplete'));

Route::post('search',array('as'=>'search','uses'=>'SearchController@execSearch'));

Route::get('nearTrails', 'MapController@nearTrails')->name('nearTrails');

//Route::get('/relationView/{relid}', 'MapController@viewRelationMap')->name('viewRelationMap');

Route::get('/stravasegmentView/{segment_id}', 'MapController@viewSegmentMapLeaf')->middleware('auth')->name('viewSegmentMap');
//Route::get('/stravasegmentView/{segment_id}', 'MapController@viewSegmentMapLeaf')->name('viewSegmentMap');

Route::get('/relationView/{relid}', 'MapController@viewRelationMapLeaf')->middleware('auth')->name('viewRelationMap');
//Route::get('/relationView/{relid}', 'MapController@viewRelationMapLeaf')->name('viewRelationMap');



Route::get('/offline', function () {
    return View::make('frontend.offline');
});


//Route::get('/privacy', 'MapController@viewRelationMap')->name('viewRelationMap');
//Route::view('/privacy', 'privacy');
Route::get('/privacy', function()
    {
        return View::make('frontend.privacy');
    });

Route::get('/privacy2', function()
    {
        return View::make('frontend.privacy2');
    });


Route::get('sitemap_generate', 'Controller@sitemap_generate')->name('sitemapGenerate');

// Manifest file (optional if VAPID is used)
// Manifest file (optional if VAPID is used)
/*
Route::get('manifest.json', function () {
    return [
        'name' => config('app.name'),
        'description'=> env('APP_DESCRIPTION', 'Laravel 5'),
        'author'=> env('APP_AUTHOR', 'Maurizio Proietti'),
        'short_name'=> env('APP_SHORTNAME', 'Strails'),

        '"icons": [
         {
            "src": "/img/logo/android-icon-192x192.png",
            "type": "image/png",
            "sizes": "192x192"
        },
        {
            "src": "/img/logo/logo1024.png",
            "type": "image/png",
            "sizes": "512x512"
        }
        ]',

        "theme_color"=> "#000000",
        "background_color"=> "#FFFFFF",
        "display"=> "standalone",
        "orientation"=> "portrait",
        'gcm_sender_id' => config('webpush.gcm.sender_id')
    ];
});
*/



//****** favoriti */

Route::get('favourites/{rel_id}/{rel_type}', 'FavouritesController@favouriteRel')->name('addFavourite');
Route::get('unFavourites/{rel_id}/{rel_type}', 'FavouritesController@unFavouriteRel')->name('removeFavourite');
Route::get('user_favourites', 'MapController@favourites')->middleware('auth')->name('showFavourites');

//Route::get('nearTrailsOverpass', 'MapController@nearTrailsOverpass')->name('nearTrailsOverpass');
//Route::get('/', 'HomeController@index')->name('index');
//Route::get('/start', 'HomeController@start')->name('start');


Route::post('/ajax/invia-log', 'AjaxController@inviaLog')->name('AjaxInviaLog');

Route::post('/ajax/save-subscription', 'AjaxController@saveSubscriptionNotify')->name('AjaxSaveSubscriptionNotify');
Route::post('/ajax/send-notification', 'AjaxController@sendNotificationNotify')->name('AjaxSendNotificationNotify');

Route::post('/ajax/store_type', 'AjaxController@storeSessionType')->name('AjaxStoreSessionType');

Route::post('/ajax/store_position', 'AjaxController@storeSessionPosition')->name('AjaxStoreSessionPosition');
Route::post('/ajax/get_near_way', 'AjaxController@getSessionPosition')->name('AjaxGetSessionPosition');
Route::post('/ajax/get_way_nodes', 'AjaxController@getWayNodes')->name('AjaxGetWayNodes');

Route::get('/ajax/strava_segments', 'AjaxController@stravaSegments')->name('AjaxStravaSegments');
Route::get('/ajax/strava_segments2/{lat1}/{lng1}/{lat2}/{lng2}', 'AjaxController@stravaSegments2')->name('AjaxStravaSegments2');
Route::get('/ajax/strava_segments_pt/{lat1}/{lng1}/{lat2}/{lng2}', 'AjaxController@stravaSegments_pt')->name('AjaxStravaSegments_pt');
Route::get('/ajax/strava_getsegment/{segment_id}', 'AjaxController@stravaGetSegment')->name('AjaxStravaGetSegment');

Route::get('/ajax/get_evaluations/{eval_type}/{eval_id}', 'EvaluationController@getAjaxEvaluations')->name('AjaxGetEvaluations');

Route::get('/way/source/{wayid}', 'MapController@sourceWayGeoJson')->name('WayGeoJson');
Route::get('/rel/source/{relid}', 'MapController@sourceRelGeoJson')->name('RelGeoJson');
Route::get('/rel/source3/{relid}', 'MapController@sourceRelGeoJson3')->name('RelGeoJson3');
Route::get('/rel/sourcefull', 'MapController@sourceRelGeoJsonFull')->name('RelGeoJsonFull');
Route::get('/rel/node/{relid}', 'MapController@sourceRelNodeGeoJson')->name('RelNodeGeoJson');
Route::get('/rel/node3/{relid}', 'MapController@sourceRelNodeGeoJson3')->name('RelNodeGeoJson3');

Route::get('/view_map', 'MapController@view')->name('view');


//TODO: aggiungere una versione senza mappa per velocizzare il tutto
//immagina di essere a piedi dove voglio inserire una segnalazione
//voglio che la cosa sia istantanea, senza bisogno di mappa
//al massimo posso aggiungere la geoReference

/*
* These routes need auth
*/
Route::group(['prefix' => 'eval', 'as' => 'eval.', 'middleware' => 'user'], function () {


    Route::get('/edit/{evaluation_id}', 'EvaluationController@edit')->name('edit');
    Route::get('/delete/{evaluation_id}', 'EvaluationController@delete')->name('delete');
    //Route::get('/new/{evaluable}', 'EvaluationController@new')->name('new');
    Route::get('/new/{evaluable_id}/{evaluable_type}', 'EvaluationController@new')->name('new');
    Route::post('/update', 'EvaluationController@update')->name('update');
    Route::post('/store', 'EvaluationController@store')->name('store');
    Route::post('/storemodal', 'EvaluationController@storeModal')->name('storeModal');
    Route::post('/storemodalimg', 'EvaluationController@storeModalImg')->name('storeModalImg');

});

//con queste rott non compare la mappa e quindi la segnalazione è + rapida
Route::group(['prefix' => 'fasteval', 'as' => 'fasteval.', 'middleware' => 'user'], function () {

    Route::get('nearTrails', 'MapController@fastNearTrails')->name('fastNearTrails');
    Route::get('/edit/{evaluation_id}', 'EvaluationController@edit')->name('edit');
    //Route::get('/new/{evaluable}', 'EvaluationController@new')->name('new');
    Route::get('/new/{evaluable_id}/{evaluable_type}', 'EvaluationController@new')->name('new');
    Route::post('/update', 'EvaluationController@update')->name('update');
    Route::post('/store', 'EvaluationController@store')->name('store');

});



Route::group(['prefix' => 'userauth', 'as' => 'userauth.', 'middleware' => 'user'], function () {

    Route::get('/getevalway/{wayid}', 'EvaluationController@getEvaulationWay')->name('EditEvalWay');
    Route::post('/storeevalway', 'EvaluationController@storeEvaulationWay')->name('StoreEvalWay');

    Route::get('/evalrel/{relid}', 'EvaluationController@EvaulationRel')->name('EvalRel');
    Route::post('/evalrel', 'EvaluationController@EvaulationRel')->name('EvalRel');

});



/* ----------------------------------------------------------------------- */


/**
 * Frontend Access Controllers
 * All route names are prefixed with 'frontend.auth'.
 */
Route::group(['namespace' => 'Auth', 'as' => 'auth.'], function () {

    /*
     * These routes require the user to be logged in
     */
    Route::group(['middleware' => 'auth'], function () {
        Route::get('logout', 'LoginController@logout')->name('logout');

        //For when admin is logged in as user from backend
        Route::get('logout-as', 'LoginController@logoutAs')->name('logout-as');

        // Change Password Routes
        //Route::patch('password/change', 'ChangePasswordController@changePassword')->name('password.change');
    });

    /*
     * These routes require no user to be logged in
     */
    Route::group(['middleware' => 'guest'], function () {
        // Authentication Routes
        Route::get('login', 'LoginController@showLoginForm')->name('login');
        Route::post('login', 'LoginController@login')->name('login.post');

        // Socialite Routes
        Route::get('login/{provider}', 'SocialLoginController@login')->name('social.login');

        // Registration Routes
        if (config('access.users.registration')) {
            Route::get('register', 'RegisterController@showRegistrationForm')->name('register');
            Route::post('register', 'RegisterController@register')->name('register.post');
        }

        // Confirm Account Routes
        Route::get('account/confirm/{token}', 'ConfirmAccountController@confirm')->name('account.confirm');
        Route::get('account/confirm/resend/{user}', 'ConfirmAccountController@sendConfirmationEmail')->name('account.confirm.resend');

        // Password Reset Routes
        Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.email');
        Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.email.post');

        Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset.form');
        Route::post('password/reset', 'ResetPasswordController@reset')->name('password.reset');
    });
});



/**
 * Frontend Controllers
 * All route names are prefixed with 'frontend.'.
 */

//Route::group(['prefix' => 'frontend', 'as' => 'frontend.'], function () {
    Route::get('/home', 'FrontendController@index')->name('index_home');
    Route::get('/', 'FrontendController@index')->name('index');
    //Route::get('macros', 'FrontendController@macros')->name('macros');
    Route::get('contact', 'ContactController@index')->name('contact');
    Route::post('contact/send', 'ContactController@send')->name('contact.send');

    Route::get('guide', 'FrontendController@guide')->name('guide');
    Route::get('intro', 'FrontendController@intro')->name('intro');
    Route::get('all_trails_evaluation', 'FrontendController@all_trails_evaluation')->name('all_trails_evaluation');
//});




/*
 * These frontend controllers require the user to be logged in
 * All route names are prefixed with 'frontend.'
 */
Route::group(['middleware' => 'auth'], function () {
    Route::group(['namespace' => 'User', 'as' => 'user.'], function () {
        /*
         * User Dashboard Specific
         */
        //Route::get('dashboard', 'DashboardController@index')->name('dashboard');

        /*
         * User Account Specific
         */
        //Route::get('account', 'AccountController@index')->name('account');

        /*
         * User Profile Specific
         */
        //Route::patch('profile/update', 'ProfileController@update')->name('profile.update');
    });
});




