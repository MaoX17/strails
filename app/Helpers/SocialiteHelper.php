<?php


if (! function_exists('getSocialLinks')) {
    /**
     * Access the htmlLang helper.
     */
    function getSocialLinks()
    {

        $socialite_enable = [];
        $socialite_links = '';


/*
        if (config('services.bitbucket.client_id')) {
            $socialite_enable[] = link_to_route('auth.social.login', trans('labels.frontend.auth.login_with', ['provider' => 'Bit Bucket']), 'bitbucket');
        }
*/


        if (config('services.facebook.client_id')) {
            //$socialite_enable[] = link_to_route('auth.social.login', trans('labels.frontend.auth.login_with', ['provider' => 'Facebook']), 'facebook');
            $socialite_enable[] = '<a href="'.route('auth.social.login' , ['provider' => 'facebook']).'" class="btn btn-primary"> <i class="fab fa-facebook-square" aria-hidden="true"></i> Login tramite Facebook </a>';
            //link_to_route('auth.social.login', trans('labels.frontend.auth.login_with', ['provider' => 'Facebook']), 'facebook');
        }



        if (config('services.google.client_id')) {
            //$socialite_enable[] = link_to_route('auth.social.login', trans('labels.frontend.auth.login_with', ['provider' => 'Google']), 'google');
            $socialite_enable[] = '<a href="'.route('auth.social.login',['provider' => 'google']).'" class="btn btn-danger"> <i class="fab fa-google-plus-square" aria-hidden="true"></i> Login tramite Google </a>';
        }
/*
        if (config('services.github.client_id')) {
            $socialite_enable[] = link_to_route('auth.social.login', trans('labels.frontend.auth.login_with', ['provider' => 'Github']), 'github');
        }

        if (config('services.linkedin.client_id')) {
            $socialite_enable[] = link_to_route('auth.social.login', trans('labels.frontend.auth.login_with', ['provider' => 'Linked In']), 'linkedin');
        }

        if (config('services.twitter.client_id')) {
            $socialite_enable[] = link_to_route('auth.social.login', trans('labels.frontend.auth.login_with', ['provider' => 'Twitter']), 'twitter');
        }
*/
        if (config('services.strava.client_id')) {

            //$socialite_enable[] = link_to_route('auth.social.login', trans('labels.frontend.auth.login_with', ['provider' => 'Strava']), 'strava');
            $socialite_enable[] = '<a href="'.route('auth.social.login',['provider' => 'strava']).'" class="btn btn-default"> <i class="fa fa-bicycle" aria-hidden="true"></i> Login tramite Strava </a>';
        }



        $socialite_links ='<p class="card-text">';
        for ($i = 0; $i < count($socialite_enable); $i++) {
            //$socialite_links .= ($socialite_links != '' ? '&nbsp;|&nbsp;' : '').$socialite_enable[$i];
            //$socialite_links .= ($socialite_links != '<div class="row text-center">' ? '</div><div class="row text-center">' : '').$socialite_enable[$i];
            $socialite_links .= ($socialite_links != '' ? '</p><br><p class="card-text">' : '').$socialite_enable[$i];
        }
        $socialite_links .='</p><br>';

        return $socialite_links;
    }
}


if (! function_exists('getAcceptedProviders')) {
    function getAcceptedProviders()
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
