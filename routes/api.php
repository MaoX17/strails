<?php

use Illuminate\Http\Request;
use App\Helpers\Auth\Auth;
use App\Models\Access\User\User;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['prefix' => 'notify', 'as' => 'notify.', 'middleware' => ['web','auth']], function () {

//Route::post('/save-subscription/{id}',function($id, Request $request){
Route::post('/save-subscription',function(Request $request){
  //$user = User::findOrFail($id);
  $user = \Auth::user();  


  \Log::info('utente loggato $user:'.$user);
  $user->updatePushSubscription($request->input('endpoint'), $request->input('keys.p256dh'), $request->input('keys.auth'));
  $user->notify(new \App\Notifications\GenericNotification("Welcome To WebPush", "You will now get all of our push notifications"));
  return response()->json([
    'success' => true
  ]);
});

//Route::post('/send-notification/{id}', function($id, Request $request){
Route::post('/send-notification', function(Request $request){
  //$user = User::findOrFail($id);
  $user = \Auth::user();
  $user->notify(new \App\Notifications\GenericNotification($request->title, $request->body));
  return response()->json([
    'success' => true
  ]);
});

});