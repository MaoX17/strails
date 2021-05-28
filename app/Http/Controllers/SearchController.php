<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Map\Relation;
use App\Models\Map\RelationTag;
use App\Models\Map\Way;
use App\Models\Map\WayTag;
use DB;

class SearchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function search()
    {
        return view('search');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function autocomplete(Request $request)
    {
        /*
         * SELECT name, 'Things' as source FROM Things WHERE name LIKE 'A%'
            UNION ALL
            SELECT name, 'People' as source FROM People WHERE name LIKE 'A%'
            UNION ALL
            SELECT name, 'Places' as source FROM Places WHERE name LIKE 'A%'

         * $users = DB::table('users')
                     ->select(DB::raw('count(*) as user_count, status'))
                     ->where('status', '<>', 1)
                     ->groupBy('status')
                     ->get();
         */

        $data = DB::table('relationtags')
            ->select(DB::raw('v as name, id'))
            ->where('k', 'LIKE', "%name%")
            ->where('v', 'LIKE', "%{$request->input('query')}%")
            ->get();


/*
        $data = RelationTag::select("v as name")
            ->where("k","LIKE","%name%")
            ->where("v","LIKE","%{$request->input('query')}%")->get();
  */
        //$data = Item::select("title as name")->where("title","LIKE","%{$request->input('query')}%")->get();
        //$data = Item::select("title as name")->where("title","LIKE","%{$request->input('query')}%")->get();
        return response()->json($data);
    }



    public function execSearch(Request $request)
    {
        //dd($request->input('search'));
        if ($request->input('search') == "")
        {
            return redirect()->back()->withFlashInfo("Nessun sentiero trovato con il nome specificato");
        }

        $relTag = RelationTag::select("id")
            ->where("v","=","{$request->input('search')}")->first();
            
        if (is_null($relTag))
        {
            return redirect()->back()->withFlashInfo("Nessun sentiero trovato con il nome specificato");
        }

        $id = $relTag->id;

        $relation = Relation::find($id);
        $first_node = $relation->getCoordinate();

        //dd($first_node);

//        return view('frontend.view_map_rel')
  //          ->with('rel',$relation)
    //        ->with('first_node',$first_node);

    //return redirect()->route('viewRelationMap', ['rel' => $relation, 'first_node' => $first_node]);
    return redirect()->route('viewRelationMap', ['rel' => $relation]);



        //return redirect()->back()->with('errors', trans('texts.message.sent_failed'));
    }

}
