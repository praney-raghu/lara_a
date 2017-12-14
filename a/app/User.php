<?php

namespace Autovilla;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Autovilla\Role;
use Auth;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public $timestamps = true;

    public function roles()
    {
        return $this->belongsToMany(Role::class,'user_has_role','user_id','role_id');
    }

    public function assignRole(Role $role)
    {
        return $this->roles()->save($role);
    }

    public function isAdmin()
    {
        $role_id = DB::table('user_has_role')->where('user_id',Auth::user()->id)->pluck('role_id')->first();
        if($role_id != 1)
        {
            return false;
        }
        else
        {
            return true;
        }
    }
}
