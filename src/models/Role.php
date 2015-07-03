<?php namespace Lewiatan\Rbac\Models;

use Illuminate\Database\Eloquent\Model;
use Lewiatan\Rbac\Collections\RolesCollection;
use Lewiatan\Rbac\Interfaces\RbacRoleInterface;
use Lewiatan\Rbac\Interfaces\string;

class Role extends Model implements RbacRoleInterface {

    protected $guarded = ['id'];

    protected $with = ['permissions'];

    protected $permissionsLoaded = false;
    protected $permissionsArray = [];
    
    public $timestamps = false;

    private $permissionModel;

    public function __construct(array $attributes = [], Permission $permissionModel = null) {
        if ($permissionModel) {
            $this->permissionModel = $permissionModel;
        } else {
            $this->permissionModel = new Permission;
        }
    }

    public function newCollection(array $models = array()) {
        return new RolesCollection($models);
    }

    public function users()
    {
        return $this->belongsToMany(config('rbac.models.user'));
    }

    public function permissions() {
        return $this->belongsToMany(config('rbac.models.permission'));
    }

    public function getPermissionsArray() {
        if ( ! $this->permissionsLoaded) {
            $this->createPermissionsArray();
        }
        return $this->permissionsArray;
    }

    public function can($permission) {
        return in_array($permission, $this->getPermissionsArray());
    }

    public function hasPermission($permission_id) {
        return array_has($this->getPermissionsArray(), $permission_id);
    }

    public function allow($permission_name) {
        $permission_id = $this->getPermissionID($permission_name);

        return $this->addPermission($permission_id);
    }

    public function disallow($permission_name) {
        $permission_id = $this->getPermissionID($permission_name);

        return $this->removePermission($permission_id);
    }

    public function addPermission($permission_id) {
        $this->permissions()->attach($permission_id);

        $this->createPermissionsArray();

        return $this;
    }

    public function removePermission($permission_id) {
        $this->permissions()->dettach($permission_id);

        $this->createPermissionsArray();

        return $this;
    }

    private function createPermissionsArray() {
        foreach ($this->permissions as $permission) {
            $this->permissionsArray[$permission->id] = $permission->name;
        }

        $this->permissionsLoaded = true;
    }

    /**
     * @param $permission_name
     * @return int
     */
    private function getPermissionID($permission_name) {
        if ( ! is_int($permission_name)) {
            $permission = $this->permissionModel->where('name', '=', $permission_name)->first();

            return $permission->id;
        }

        return $permission_name;
    }

}
