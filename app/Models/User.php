<?php 
 
namespace App\Models;
 
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Traits\HasPermissions;
 
class User extends Authenticatable implements JWTSubject
{
    use Notifiable, HasPermissions;
 
    protected $fillable = [
        'name', 'email', 'password',
    ];
 
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
    
    public function role(){
        return $this->belongsToMany('App\Models\Role', 'role_user','user_id','role_id');
    }
}

