<?php  namespace Lewiatan\Rbac\Traits; 

trait RbacUserTrait {
    public function roles() {
        return $this->belongsToMany(config('rbac.models.role'));
    }

    public function can($permission) {
        return $this->roles->can($permission);
    }

    public function hasPermission($permission_id) {
        return $this->roles->hasPermission($permission_id);
    }

    public function assignRole($role) {
        $this->roles->attach($role);

        return $this;
    }

    public function removeRole($role) {
        $this->roles->dettach($role);

        return $this;
    }
}