<?php

namespace App\Models\Access\User;

use Illuminate\Notifications\Notifiable;
use App\Models\Access\User\Traits\UserAccess;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Access\User\Traits\Scope\UserScope;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Access\User\Traits\UserSendPasswordReset;
use App\Models\Access\User\Traits\Attribute\UserAttribute;
use App\Models\Access\User\Traits\Relationship\UserRelationship;

use App\Models\Map\Relation;
use App\Models\Map\StravaSegment;

use NotificationChannels\WebPush\HasPushSubscriptions;


/**
 * Class User.
 */
class User extends Authenticatable
{
    use UserScope,
        UserAccess,
        SoftDeletes,
        UserAttribute,
        UserRelationship,
        UserSendPasswordReset;

        use Notifiable, 
            HasPushSubscriptions;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['first_name', 'last_name', 'email', 'password', 'status', 'confirmation_code', 'confirmed', 'token_strava_access', 'token_strava_refresh','notify_email','notify_fcm','notify_other','token_fcm'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token', 'token_strava_access', 'token_strava_refresh'];

    /**
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The dynamic attributes from mutators that should be returned with the user object.
     * @var array
     */
    protected $appends = ['full_name', 'name'];

    /**
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('access.users_table');
    }



    public function evaluations()
    {
        //return $this->hasMany('App\Models\Trails\Evaluation');
        return $this->hasMany('App\Models\Map\Evaluation');
    }

    public function favourites()
    {
        return $this->belongsToMany(Relation::class, 'favourites', 'user_id', 'relation_id')->withTimeStamps();
    }

    public function stravafavourites()
    {
        return $this->belongsToMany(StravaSegment::class, 'favourites', 'user_id', 'relation_id')->withTimeStamps();
    }
    

    public function stravafavouritesFromStrava()
    {

        

        return $this->belongsToMany(StravaSegment::class, 'favourites', 'user_id', 'relation_id')->withTimeStamps();
    }
    
    
    public function isTop()
    {
        $top_users = \DB::select('select users.id, users.first_name, users.last_name, COUNT(evaluations.id) as count_eval from users
                            INNER JOIN evaluations
                            on evaluations.user_id=users.id
                            GROUP BY users.id, users.first_name,users.last_name
                            ORDER BY COUNT(evaluations.id) DESC
                            limit 1');
        
        //print_r($top_users);
        
        if ($this->id == $top_users[0]->id){
            return true;
        }
        else {
            return false;
        }
        
    }

}
