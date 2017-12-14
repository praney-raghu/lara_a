<?php

namespace Autovilla;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['name'];

    public function users()
    {
    	return $this->hasOne('Autovilla\User');
    }
}
