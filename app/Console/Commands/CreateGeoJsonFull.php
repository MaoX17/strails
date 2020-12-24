<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Models\Map\Relation;
use App\Models\Map\Way;

use Illuminate\Support\Facades\Storage;


class CreateGeoJsonFull extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'CreateGeoJsonFull';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'CreateGeoJsonFull description';


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //


        //ATTENZIONE??????????????
        //mettere tutto in una featurescollection non è possibioe perchè non ne entra + di 2000
        $rels = Relation::all();
        //$rels = Relation::all()->take(2000);//->get();
        //$rels = Relation::all()->take(1);//->get();
        //$tt = ['196950', '194476','4437','196949','7173288'];
        //$rels = Relation::whereIn('id', $tt)->get();
        //dd($rels);
        //scrivo la testa del file
        $feature = array();
        $feature_pt = array();
        $i=0;
        $j=0;
        foreach ($rels as $rel) {
            //$array_tmp[$i] = $rel->toGeoJsonFull2();
            //$array_tmp[$i] = $rel->toGeoJsonFull();
            $array_tmp_pt[$j] = $rel->ptToGeoJsonFull();
            $j++;

            
  
            //$feature = array_merge($feature, $array_tmp[$i]);
            //$feature_pt = array_merge($feature_pt, $array_tmp_pt);

             //  if($array_tmp[$i] <> null) {
               $i++;
                //}
         
            
        }


        //dd($array_tmp);
        $feature_pt = $array_tmp_pt;

       //$feature = $array_tmp;
//       $feature2 = $array_tmp2;
/*
        $geojson = array(
            'type'      => 'FeatureCollection',
            'features'  => $feature
        );
        */
        $geojson_pt = array(
            'type'      => 'FeatureCollection',
            'features'  => $feature_pt
        );

        if (version_compare(phpversion(), '7.1', '>=')) {
            ini_set( 'serialize_precision',  9);
        }

//        header('Content-type: application/json');
  //      $result = json_encode($geojson, JSON_NUMERIC_CHECK);
        //scrivo la coda del file
        //echo $result;
    //    Storage::disk('public')->put('full_0_100.json', $result);
      

        $result_pt = json_encode($geojson_pt, JSON_NUMERIC_CHECK);
        Storage::disk('public')->put('full_0_100_pt.json', $result_pt);
        
    }






public function handle_old_ok()
    {
        //

        //$rels = Relation::all();
        //$rels = Relation::all()->take(1);//->get();
        //$rels = Relation::all()->take(1);//->get();
        $tt = ['196950', '194476','4437','196949','7173288'];
        $rels = Relation::whereIn('id', $tt)->get();
        //dd($rels);
        //scrivo la testa del file
        $feature = array();
        $feature_pt = array();
        $i=0;
        $j=0;
        foreach ($rels as $rel) {
            //$array_tmp[$i] = $rel->toGeoJsonFull2();
            $array_tmp[$i] = $rel->toGeoJsonFull();
            $array_tmp_pt[$j] = $rel->ptToGeoJsonFull();
            $j++;

            
  
            $feature = array_merge($feature, $array_tmp[$i]);
            $feature_pt = array_merge($feature_pt, $array_tmp_pt);

             //  if($array_tmp[$i] <> null) {
               $i++;
                //}
         
            
        }


        //dd($array_tmp);

       //$feature = $array_tmp;
//       $feature2 = $array_tmp2;

        $geojson = array(
            'type'      => 'FeatureCollection',
            'features'  => $feature
        );
        $geojson_pt = array(
            'type'      => 'FeatureCollection',
            'features'  => $feature_pt
        );

        if (version_compare(phpversion(), '7.1', '>=')) {
            ini_set( 'serialize_precision',  9);
        }

        header('Content-type: application/json');
        $result = json_encode($geojson, JSON_NUMERIC_CHECK);
        //scrivo la coda del file
        //echo $result;
        Storage::disk('public')->put('full_0_100.json', $result);
        //Storage::disk('public')->put('file.txt', 'Contents');

        $result_pt = json_encode($geojson_pt, JSON_NUMERIC_CHECK);
        Storage::disk('public')->put('full_0_100_pt.json', $result_pt);
        
    }
}
