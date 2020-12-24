<?php

namespace App\Models\Map;

use Illuminate\Database\Eloquent\Model;

class Position extends Node
{
    //
    public $timestamps = false;
    //protected $table = null;

    protected $lat;
    protected $lon;


    public function __construct($lat, $lon)
    {
        $this->lat = $lat;
        $this->lon = $lon;
    }


}
