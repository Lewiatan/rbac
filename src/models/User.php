<?php namespace Lewiatan\Rbac\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

use Lewiatan\Rbac\Interfaces\RbacUserInterface;
use Lewiatan\Rbac\Traits\RbacUserTrait;


class User extends Model implements AuthenticatableContract, CanResetPasswordContract, RbacUserInterface {

    use Authenticatable, CanResetPassword, RbacUserTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = array('password', 'remember_token');

    protected $with = ['roles'];

}
