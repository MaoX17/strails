<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
//use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Repositories\Frontend\Access\User\UserRepository;
//use App\Helpers\Frontend\Auth\Socialite as SocialiteHelper;
use App\Helpers\SocialiteHelper;
use Illuminate\Support\Facades\Auth;

/**
 * Class SocialLoginController.
 */
class SocialLoginController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $user;

    /**
     * @var SocialiteHelper
     */
    //protected $helper;

    /**
     * SocialLoginController constructor.
     *
     * @param UserRepository  $user
     * @param SocialiteHelper $helper
     */
    public function __construct(UserRepository $user) //, SocialiteHelper $helper)
    {
        $this->user = $user;
        //$this->helper = $helper;
    }

    /**
     * @param Request $request
     * @param $provider
     *
     * @throws GeneralException
     *
     * @return \Illuminate\Http\RedirectResponse|mixed
     */
    public function login(Request $request, $provider)
    {
        // There's a high probability something will go wrong
        $user = null;

        // If the provider is not an acceptable third party than kick back
        //if (! in_array($provider, $this->helper->getAcceptedProviders())) {
            //dd($provider);
        if (! in_array($provider, getAcceptedProviders())) {
            return redirect()->route(homeRoute())->withFlashDanger(trans('auth.socialite.unacceptable', ['provider' => $provider]));
        }

        /*
         * The first time this is hit, request is empty
         * It's redirected to the provider and then back here, where request is populated
         * So it then continues creating the user
         */
        if (! $request->all()) {
            return $this->getAuthorizationFirst($provider);
        }

        // Create the user if this is a new social account or find the one that is already there.
        try {
            //dd($this->user);
            $user = $this->user->findOrCreateSocial($this->getSocialUser($provider), $provider);
        } catch (GeneralException $e) {
            dd("ERR");
            return redirect()->route(homeRoute())->withFlashDanger($e->getMessage());
        }


        if (is_null($user) || ! isset($user)) {
            return redirect()->route(homeRoute())->withFlashDanger(trans('exceptions.frontend.auth.unknown'));
        }


        // Check to see if they are active.
        if (! $user->isActive()) {
            throw new GeneralException(trans('exceptions.frontend.auth.deactivated'));
        }



        // Account approval is on
        if ($user->isPending()) {
            throw new GeneralException(trans('exceptions.frontend.auth.confirmation.pending'));
        }

        // User has been successfully created or already exists
        //access()->login($user, true);
        //auth()->login($user, true);
        Auth::login($user);
        //dd($user);

        // Set session variable so we know which provider user is logged in as, if ever needed
        session([config('access.socialite_session_name') => $provider]);

        // Return to the intended url or default to the class property
        return redirect()->intended(route(homeRoute()));
    }

    /**
     * @param  $provider
     *
     * @return mixed
     */
    private function getAuthorizationFirst($provider)
    {
        $socialite = Socialite::driver($provider);
        //dd(config("services.{$provider}.with"));

        if (is_null(config("services.{$provider}.scopes"))) {
            $scopes = false;
        }
        else {
            $scopes = count(config("services.{$provider}.scopes")) ? config("services.{$provider}.scopes") : false;
        }

        if (is_null(config("services.{$provider}.with"))) {
            $with = false;
        }
        else {
            $with = count(config("services.{$provider}.with")) ? config("services.{$provider}.with") : false;
        }



        //2020_05_11 - Modifica perchè altrimenti php7.2 da errore in caso di config("services.{$provider}.fields") = NULL
        if (is_null(config("services.{$provider}.fields"))) {
            $fields = false;
        }
        else {
            $fields = count(config("services.{$provider}.fields")) ? config("services.{$provider}.fields") : false;
        }


        if ($scopes) {
            $socialite->scopes($scopes);
        }

        if ($with) {
            $socialite->with($with);
        }

        if ($fields) {
            $socialite->fields($fields);
        }

        return $socialite->redirect();
    }

    /**
     * @param $provider
     *
     * @return mixed
     */
    private function getSocialUser($provider)
    {
        //dd($provider);
        //$user1 = Socialite::driver('google')->stateless()->user();
        //dd($user1);
        return Socialite::driver($provider)->stateless()->user();
    }
}
