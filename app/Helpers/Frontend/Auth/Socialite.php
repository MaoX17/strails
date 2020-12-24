<?php

namespace App\Helpers\Frontend\Auth;

/**
 * Class Socialite.
 */
class Socialite
{
    /**
     * Generates social login links based on what is enabled.
     *
     * @return string
     */
    public function getSocialLinks()
    {
        $socialite_enable = [];
        $socialite_links = '';

        if (config('services.bitbucket.client_id')) {
            $socialite_enable[] = link_to_route('frontend.auth.social.login', trans('labels.frontend.auth.login_with', ['social_media' => 'Bit Bucket']), 'bitbucket');
        }

        if (config('services.facebook.client_id')) {
            //$socialite_enable[] = link_to_route('frontend.auth.social.login', trans('labels.frontend.auth.login_with', ['social_media' => 'Facebook']), 'facebook');
            $socialite_enable[] = '<a href="'.route('frontend.auth.social.login',['social_media' => 'facebook']).'" class="btn btn-primary"> <i class="fa fa-facebook-square" aria-hidden="true"></i> '.trans('labels.frontend.auth.login_with', ['social_media' => 'Facebook']).' </a>';
            //link_to_route('frontend.auth.social.login', trans('labels.frontend.auth.login_with', ['social_media' => 'Facebook']), 'facebook');
        }

        if (config('services.google.client_id')) {
            //$socialite_enable[] = link_to_route('frontend.auth.social.login', trans('labels.frontend.auth.login_with', ['social_media' => 'Google']), 'google');
            $socialite_enable[] = '<a href="'.route('frontend.auth.social.login',['social_media' => 'google']).'" class="btn btn-danger"> <i class="fa fa-google-plus-square" aria-hidden="true"></i> '.trans('labels.frontend.auth.login_with', ['social_media' => 'Google']).' </a>';
        }

        if (config('services.github.client_id')) {
            $socialite_enable[] = link_to_route('frontend.auth.social.login', trans('labels.frontend.auth.login_with', ['social_media' => 'Github']), 'github');
        }

        if (config('services.linkedin.client_id')) {
            $socialite_enable[] = link_to_route('frontend.auth.social.login', trans('labels.frontend.auth.login_with', ['social_media' => 'Linked In']), 'linkedin');
        }

        if (config('services.twitter.client_id')) {
            $socialite_enable[] = link_to_route('frontend.auth.social.login', trans('labels.frontend.auth.login_with', ['social_media' => 'Twitter']), 'twitter');
        }
        if (config('services.strava.client_id')) {
           
            //$socialite_enable[] = link_to_route('frontend.auth.social.login', trans('labels.frontend.auth.login_with', ['social_media' => 'Strava']), 'strava');
            $socialite_enable[] = '<a href="'.route('frontend.auth.social.login',['social_media' => 'strava']).'" class="btn btn-default"> <i class="fa fa-bicycle" aria-hidden="true"></i> '.trans('labels.frontend.auth.login_with', ['social_media' => 'Strava']).' </a>';
        }

        $socialite_links ='<div class="row">';
        for ($i = 0; $i < count($socialite_enable); $i++) {
            //$socialite_links .= ($socialite_links != '' ? '&nbsp;|&nbsp;' : '').$socialite_enable[$i];
            //$socialite_links .= ($socialite_links != '<div class="row text-center">' ? '</div><div class="row text-center">' : '').$socialite_enable[$i];
            $socialite_links .= ($socialite_links != '' ? '</div><br><div class="row">' : '').$socialite_enable[$i];
        }
        $socialite_links .='</div><br>';

        return $socialite_links;
    }


    
    /**
     * List of the accepted third party provider types to login with.
     *
     * @return array
     */
    public function getAcceptedProviders()
    {
        return [
            'bitbucket',
            'facebook',
            'google',
            'github',
            'linkedin',
            'twitter',
            'strava',
        ];
    }
}
