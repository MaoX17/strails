<?php

/**
 * Global Routes
 * Routes that are used between both frontend and backend.
 */

// Switch between the included languages
//Route::get('lang/{lang}', 'LanguageController@swap');

/* ----------------------------------------------------------------------- */


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





/*
 * Frontend Routes
 * Namespaces indicate folder structure
 */
Route::group(['namespace' => 'Frontend', 'as' => 'frontend.'], function () {
    includeRouteFiles(__DIR__.'/Frontend/');
});

/* ----------------------------------------------------------------------- */

/*
 * Backend Routes
 * Namespaces indicate folder structure
 */
//Route::group(['namespace' => 'Backend', 'prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'admin'], function () {
    /*
     * These routes need view-backend permission
     * (good if you want to allow more than one group in the backend,
     * then limit the backend features by different roles or permissions)
     *
     * Note: Administrator has all permissions so you do not have to specify the administrator role everywhere.
     */
//    includeRouteFiles(__DIR__.'/Backend/');
//});