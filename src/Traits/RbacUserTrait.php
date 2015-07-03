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

    public function is($role) {
        return $this->hasRole($role);
    }

    public function assignRole($role) {
        $this->roles()->attach($role);

        return $this;
    }

    public function removeRole($role) {
        $this->roles()->detach($role);

        return $this;
    }

    /**
     * @param $role
     */
    protected function hasRole($role) {
        return $this->roles->has($role);
    }
}