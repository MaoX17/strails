<?php

namespace App\Http\Controllers;

use App\Helpers\Auth\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use App\Models\Map\Way;
use App\Models\Map\Relation;
use App\Models\Map\StravaSegment;

use App\Models\Map\Evaluation;
use App\Models\Map\Evaluable;

use App\Models\Access\User\User;
use App\Notifications\NewEvaluate;
use App\Notifications\NewEvaluateWebpush;


use Strava\API\OAuth;
use Strava\API\Exception;
use Strava\API\Client;

use Strava\API\Service\REST;

use Illuminate\Support\Facades\Session;
use App\Models\Access\User\SocialLogin;

use Intervention\Image\Facades\Image;





use Notification;

class EvaluationController extends Controller
{
    //


    public function getEvaluableObj($evaluable_type, $evaluable_id)
    {
        
        switch ($evaluable_type) {
        case 'Way':
            $evaluable = Way::find($evaluable_id);
            break;

        case 'Node':
            $evaluable = Node::find($evaluable_id);
            break;

        case 'Relation':
            $evaluable = Relation::find($evaluable_id);
            break;
        case 'App\Models\Map\Relation':
            $evaluable = Relation::find($evaluable_id);
            break;
        
        case 'App\Models\Map\StravaSegment':
            $evaluable = StravaSegment::firstOrCreate(['id' => $evaluable_id]);
            $evaluable->updateTmpSegment();
            break;
        case 'StravaSegment':        
            $evaluable = StravaSegment::firstOrCreate(['id' => $evaluable_id]);
            $evaluable->updateTmpSegment();
            break;

        default:
            $evaluable = null;
        }
        return $evaluable;
    }


    public function getAjaxEvaluations($evaluable_type, $evaluable_id) {

        $evaluable = $this->getEvaluableObj($evaluable_type, $evaluable_id);

        $result = "";
        $result = '<table class="table table-condensed">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Descrizione</th>
                        <th>Utente</th>
                    </tr>
                </thead>
                <tbody>';
        foreach ($evaluable->evaluations as $eval) {
            # code...
            $result .= '
            <tr class="'.$eval->rating.'">
                    <td> '. \Carbon\Carbon::parse($eval->updated_at)->format('d/m/Y') .'</td>
                    <td>'. $eval->rating_desc.'</td>
                    <td>'. $eval->author->fullname.'</td>
                </tr>';

        };

        $result .= '</tbody>
            </table>';
    

        return response()->json($result);

    }


    public function new($evaluable_id, $evaluable_type)
    {
        $evaluable = $this->getEvaluableObj($evaluable_type, $evaluable_id);
        //dd($evaluable);
        
        
        return view('frontend.evaluate_new')
            ->with('evaluable',$evaluable);

    }




    //evaluable è l'oggetto evaluable o Way o... altro che voglio valutare
    //evaluation_id è l'id di Evaluation se esistente

    public function edit($evaluation_id)
    {
        $evaluation = Evaluation::find($evaluation_id);
        
        //dd($eval);


        return view('frontend.evaluate_edit')
            //->with(compact('users'));
            ->with(compact('evaluation'));

    }

    public function delete($evaluation_id)
    {
        //TODO : Correggere diversi errori
        //dd("Errori da correggere");
        $evaluation = Evaluation::find($evaluation_id);
        
        $evaluable_obj_db = Evaluable::find($evaluation_id);
        
        $evaluable_type = $evaluable_obj_db->evaluable_type;

        
        $evaluable_id = $evaluable_obj_db->evaluable_id;
        //dd($evaluable_type);
        
        $evaluable = $this->getEvaluableObj($evaluable_type, $evaluable_id);

        //dd($evaluable);
        $first_node = $evaluable->getCoordinate();

        
        

        //Controllo che stiano coancellando la propria!!!!
        if ($evaluation->author->id == $author = \Auth::user()->id)
        {
            //dd(\Auth::user()->id);

            //dd($evaluable);
            
            $evaluation->delete();
            $evaluable_obj_db->delete();
        }
       
        
        //dd($eval);

/*
        return view('frontend.view_map_rel')
            ->with('rel',$evaluable_rel)
            ->with('first_node',$first_node);
*/

//Il return dipende dal tipo di oggetto evaluable
if ($baseClass = class_basename($evaluable) == "Relation") {
/*
        return view('frontend.view_map_rel')
            ->with('rel',$evaluable)
            ->with('first_node',$first_node);
*/
            return view('frontend.view_map_rel_leaf')
            ->with('rel',$evaluable)
            ->with('first_node',$first_node);

    
}
//strava segment
else {
    return view('frontend.view_map_segment')
            ->with('segment',$evaluable)
            ->with('first_node',$first_node);
}


    }


    public function store(Request $request)
    {
        $evaluable_id = $request->get('evaluable_id');
        $evaluable_type = $request->get('evaluable_type');

        $evaluable = $this->getEvaluableObj($evaluable_type, $evaluable_id);
       
        $author = \Auth::user();
       
        $eval = new Evaluation();

        $eval->direction = $request->get('direction');;
        $eval->sport = $request->get('sport');
        $eval->rating = $request->get('rating');
        $eval->rating_desc = $request->get('desc');
        $eval->lat = session('lat');
        $eval->lon = session('lng');
        $eval->note = $request->get('note');
        $eval->user_id = $author->id;

        $eval = $evaluable->evaluations()->save($eval);

        $first_node = $evaluable->getCoordinate();

        //notifico a tutti i follower
        //dd($evaluable->favoriteFollowingUsers());
        
        //if ($evaluable->favoriteFollowingUsers()->isNotEmpty()){
        if (count($evaluable->favoriteFollowingUsers()->get()) > 0){
            $followers = ($evaluable->favoriteFollowingUsers()->get());
            foreach ($followers as $follower) {
                //Selto la notifica all'autore
                if ($follower->id == $author->id)
                {
                    continue;
                }
                if ($follower->notify_fcm) {
                    Notification::send($follower, new NewEvaluateWebpush($eval,$evaluable));
                }
                if ($follower->notify_email) {
                    Notification::send($follower, new NewEvaluate($eval,$evaluable));
                }
                
                
            }
        }
        
        //FUNZIONA!!!!
        //$author->notify(new \App\Notifications\GenericNotification("titolo", "body"));
        //Notification::send($author, new NewEvaluate($eval,$evaluable));
        

//Il return dipende dal tipo di oggetto evaluable
if ($evaluable->getEvaluationType() == 'Relation' ) {
//if ($baseClass = class_basename($evaluable) == "Relation") {

    return redirect()->route('viewRelationMap', ['relid' => $evaluable->id]);
    //return view('frontend.view_map_rel_leaf')
    //    ->with('rel',$evaluable)
    //    ->with('first_node',$first_node);

    
}
//strava segment
elseif ($evaluable->getEvaluationType() == 'StravaSegment' ) {
    return redirect()->route('viewSegmentMap', ['segment_id' => $evaluable->id]);
    //return view('frontend.view_map_segment')
    //    ->with('segment',$evaluable)
    //    ->with('first_node',$first_node);
}

}


    public function update(Request $request)
    {

        $author = \Auth::user();

        $eval = Evaluation::find($request->get('evaluation_id'));
        $evaluable_obj_db = Evaluable::find($request->get('evaluation_id'));
        //dd($evaluable_obj_db);

        //se valutazione su strava segment:
        if ($evaluable_obj_db->evaluable_type == "App\Models\Map\StravaSegment"){
            $evaluable = StravaSegment::find($evaluable_obj_db->evaluable_id);
        }
        //se valutazione su relation OSM
        elseif ($evaluable_obj_db->evaluable_type == "App\Models\Map\Relation"){
            $evaluable = Relation::find($evaluable_obj_db->evaluable_id);
            //if ((count($eval->relations)) > 0) {
                //valutazione riferita a una relation
              //  foreach ($eval->relations as $evaluable) {
                //    $evaluable_id = $evaluable->id;
                //}
           // }
            //elseif ((count($eval->ways))) {
                //valutazione riferita a una way
             //   foreach ($eval->ways as $evaluable) {
             //       $evaluable_id = $evaluable->id;
              //  }
           // }
        }
        elseif ($evaluable_obj_db->evaluable_type == "App\Models\Map\Way"){
            //valutazione riferita a una way
            $evaluable = Way::find($evaluable_obj_db->evaluable_id);
        }


        $eval->direction = $request->get('direction');;
        $eval->sport = $request->get('sport');
        $eval->rating = $request->get('rating');
        $eval->rating_desc = $request->get('desc');
        $eval->lat = session('lat');
        $eval->lon = session('lng');
        $eval->note = $request->get('note');
        $eval->user_id = $author->id;

        $eval->save();

        //$evaluable = $eval;
        //TODO: Occorre ottenere un evaluable dal suo evaluation
        //dd($evaluable);

        $first_node = $evaluable->getCoordinate();
        
        //Il return dipende dal tipo di oggetto evaluable
        if ($baseClass = class_basename($evaluable) == "Relation") {
/*
        return view('frontend.view_map_rel')
            ->with('rel',$evaluable)
            ->with('first_node',$first_node);
*/
            return view('frontend.view_map_rel_leaf')
            ->with('rel',$evaluable)
            ->with('first_node',$first_node);

    
        }
        //strava segment
        else {
            return view('frontend.view_map_segment')
                ->with('segment',$evaluable)
                ->with('first_node',$first_node);
        }

    }









    public function storeModal(Request $request)
    {
        $evaluable_id = $request->get('evaluable_id');
        $evaluable_type = $request->get('evaluable_type');

        $evaluable = $this->getEvaluableObj($evaluable_type, $evaluable_id);
       
        $author = \Auth::user();
       
        $eval = new Evaluation();

        $eval->direction = $request->get('direction');;
        $eval->sport = $request->get('sport');
        $eval->rating = $request->get('rating');
        $eval->rating_desc = $request->get('desc');
        $eval->lat = session('lat');
        $eval->lon = session('lng');
        $eval->note = $request->get('note');
        $eval->user_id = $author->id;

        $eval = $evaluable->evaluations()->save($eval);

        $first_node = $evaluable->getCoordinate();

        //notifico a tutti i follower
        //dd($evaluable->favoriteFollowingUsers());
        
        //if ($evaluable->favoriteFollowingUsers()->isNotEmpty()){
        if (count($evaluable->favoriteFollowingUsers()->get()) > 0){
            $followers = ($evaluable->favoriteFollowingUsers()->get());
            foreach ($followers as $follower) {
                //Selto la notifica all'autore
                if ($follower->id == $author->id)
                {
                    continue;
                }
                if ($follower->notify_fcm) {
                    Notification::send($follower, new NewEvaluateWebpush($eval,$evaluable));
                }
                if ($follower->notify_email) {
                    Notification::send($follower, new NewEvaluate($eval,$evaluable));
                }
                
                
            }
        }
        
        //FUNZIONA!!!!
        //$author->notify(new \App\Notifications\GenericNotification("titolo", "body"));
        //Notification::send($author, new NewEvaluate($eval,$evaluable));
        
        $result = [
            'success' => 'success'
        ];

        return response()->json($result);


}

public function storeModalImg(Request $request)
    {

        $result = [
            'error' => 'error'
            //'img' => $imagepath
            ];


        $evaluable_id = $request->get('evaluable_id');
        $evaluable_type = $request->get('evaluable_type');
         \Log::info('type: '.$evaluable_type);

        $evaluable = $this->getEvaluableObj($evaluable_type, $evaluable_id);
       
        $author = \Auth::user();
       
        $eval = new Evaluation();
        
        $extension = $request->file('imgInputFile')->getClientOriginalExtension(); // getting image extension
        \Log::info('ext: '.$extension);
        \Log::info($request->file("imgInputFile")->getClientOriginalName());

        //$fileName = uniqid($evaluable_type."_".$evaluable_id."_") . '.' . $extension; // renameing image
        //$destinationPath = 'uploads/files'; // upload path
        //$path = $request->imgInputFile->storeAs('images', $fileName);
        //$request->file('imgInputFile')->move($destinationPath, $fileName); // uploading file to given path
        //$extension = $request->file('imgInputFile')->guessClientExtension(); // getting image extension
        //\Log::info('path: '.$path);

        if ($request->file('imgInputFile')->isValid()) {
            \Log::info('is valid!!!');
        }
        if (($request->hasFile('imgInputFile'))) {
            \Log::info('has file');
            $destinationPath = 'uploads/files'; // upload path
            $extension = $request->file('imgInputFile')->getClientOriginalExtension(); // getting image extension

            $tempName = $request->file("imgInputFile")->getClientOriginalName();
            //$fileName = uniqid("MW") . '.' . $extension; // renameing image
            $fileName = uniqid($evaluable_type."_".$evaluable_id."_") . '.' . $extension; // renameing image
            $request->file('imgInputFile')->move($destinationPath, $fileName); // uploading file to given path
            // sending back with message
            $imagepath = $destinationPath.'/'.$fileName;
            \Log::info('img: '.$imagepath);

            $data = Image::make($imagepath)->exif();
            \Log::info('data img: '.json_encode($data));

            //$data = Image::make(public_path('IMG.jpg'))->exif();
            if(isset($data['GPSLatitude'])) {
                $lat = eval('return ' . $data['GPSLatitude'][0] . ';')
                    + (eval('return ' . $data['GPSLatitude'][1] . ';') / 60)
                    + (eval('return ' . $data['GPSLatitude'][2] . ';') / 3600);
                $lng = eval('return ' . $data['GPSLongitude'][0] . ';')
                    + (eval('return ' . $data['GPSLongitude'][1] . ';') / 60)
                    + (eval('return ' . $data['GPSLongitude'][2] . ';') / 3600);
                echo "$lat, $lng";
            } else {
                echo "No GPS Info";
            }

            $result = [
            'success' => 'success'
            //'img' => $imagepath
            ];
        }

        
        //$eval->direction = $request->get('direction');;
        //$eval->sport = $request->get('sport');
        //$eval->rating = $request->get('rating');
        //$eval->rating_desc = $request->get('desc');
        //$eval->lat = session('lat');
        //$eval->lon = session('lng');
        //$eval->note = $request->get('note');
        //$eval->user_id = $author->id;

        //$eval = $evaluable->evaluations()->save($eval);

        //$first_node = $evaluable->getCoordinate();

        //notifico a tutti i follower
        /*
        if (count($evaluable->favoriteFollowingUsers()->get()) > 0){
            $followers = ($evaluable->favoriteFollowingUsers()->get());
            foreach ($followers as $follower) {
                //Selto la notifica all'autore
                if ($follower->id == $author->id)
                {
                    continue;
                }
                if ($follower->notify_fcm) {
                    Notification::send($follower, new NewEvaluateWebpush($eval,$evaluable));
                }
                if ($follower->notify_email) {
                    Notification::send($follower, new NewEvaluate($eval,$evaluable));
                }
                
                
            }
        }
        */

        //FUNZIONA!!!!
        //$author->notify(new \App\Notifications\GenericNotification("titolo", "body"));
        //Notification::send($author, new NewEvaluate($eval,$evaluable));
        
        

        return response()->json($result);


}



    
}
