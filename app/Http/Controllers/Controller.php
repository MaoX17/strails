<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\Tags\Url;

use Carbon\Carbon;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    public function sitemap_generate()
    {
        $path = public_path('sitemap.xml');
        //$SiteMap = SitemapGenerator::create(config('app.url'))->writeToFile($path);
        $SiteMap = SitemapGenerator::create(config('app.url'))
            ->getSitemap()

            ->add(Url::create(url('/all_trails_evaluation'))
                ->setLastModificationDate(Carbon::yesterday())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(0.8))

            ->writeToFile($path);

        echo "ok";

    }
}
