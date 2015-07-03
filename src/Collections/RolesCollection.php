<?php  namespace Lewiatan\Rbac\Collections; 

use Illuminate\Database\Eloquent\Collection;

class RolesCollection extends Collection {
    private $permissions = [];
    private $roles = [];

    public function __construct($items) {
        parent::__construct($items);

        foreach ($items as $role) {
            $this->roles[$role->id] = $role->name;
            $this->permissions = array_merge($this->permissions, $role->getPermissionsArray());
        }
    }

    public function can($permission) {
        return in_array($permission, $this->permissions);
    }

    public function hasPermission($permission_id) {
        return array_has($this->permissions, $permission_id);
    }

    public function has($role) {
        return in_array($role, $this->roles);
    }
}