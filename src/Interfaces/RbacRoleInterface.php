<?php  namespace Lewiatan\Rbac\Interfaces; 

interface RbacRoleInterface {
    public function can($permission);
    public function hasPermission($permission_id);
    public function getPermissionsArray();
}