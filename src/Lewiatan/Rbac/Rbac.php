<?php namespace Lewiatan\Rbac;

use Lewiatan\Rbac\Interfaces\RbacRoleInterface;
use Lewiatan\Rbac\Interfaces\RbacUserInterface;

class Rbac {

    private $userModel = null;
    private $user = null;

    public function __construct(RbacUserInterface $userModel = null, RbacRoleInterface $roleModel = null) {
        if ($userModel) {
            $this->userModel = $userModel;
        } else {
            $this->userModel = config('rbac.models.user');
        }

        if ($roleModel) {
            $this->roleModel = $roleModel;
        } else {
            $this->roleModel = config('rbac.models.role');
        }
    }

    /**
     * @param $user
     * @return $this
     * @throws RbacWrongUserException
     */
    public function user($user) {
        if ($user instanceof RbacUserInterface) {
            $this->user = $user;
        } else if (is_int($user)) {
            $this->user = $this->userModel->findOrFail($user);
        } else {
            throw new RbacWrongUserException('User must be provided with ID of integer or Eloqent model with RbacUserInrerface implemented.');
        }

        return $this;
    }

    /**
     * @param $role
     * @return $this
     * @throws RbacWrongRoleException
     */
    public function role($role) {
        if ($role instanceof RbacRoleInterface) {
            $this->role = $role;
        } else if (is_int($role)) {
            $this->role = $this->roleModel->findOrFail($role);
        } else {
            throw new RbacWrongRoleException('Role must be provided with ID of integer or Eloquent model with RbacRoleInferface implemented.');
        }

        return $this;
    }

    public function can($permission) {
        return $this->user->can($permission);
    }

    public function hasPermission($permission_id) {
        return $this->user->hasPermission($permission_id);
    }

    public function assignRole($role) {
        $this->user->attach($role);

        return $this;
    }

    public function removeRole($role) {
        $this->user->dettach($role);

        return $this;
    }

    public function allow($permission_name) {
        $this->role->allow($permission_name);

        return $this;
    }

    public function disallow($permission_name) {
        $this->role->disallow($permission_name);

        return $this;
    }


}