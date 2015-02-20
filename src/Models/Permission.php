<?php namespace Lewiatan\Rbac\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\Config;

class Permission extends Eloquent {

    protected $fillable = [];
    
    public $timestamps = false;

    public function roles()
    {
        return $this->belongsToMany(config('rbac.models.role'));
    }

}
