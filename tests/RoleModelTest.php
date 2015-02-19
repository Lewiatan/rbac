<?php

class RoleModelTest extends PHPUnit_Framework_TestCase {

    private $permission;
    private $user;
    private $rolesCollection;
    private $role;

    public function setUp() {
        $this->permission = Mockery::mock('Lewiatan\Rbac\Models\Permission');
        $this->user = Mockery::mock('Lewiatan\Rbac\Models\User');
        $this->rolesCollection = Mockery::mock('Lewiatan\Rbac\Collections\RolesCollection');

        $this->role = Mockery::mock('Lewiatan\Rbac\Models\Role[permissions]', [$this->permission]);
        $this->role->shouldReceive('permissions')->andReturn($this->permission);
        $this->role->users = $this->user;
        $this->role->permissions = $this->permission;
    }

    public function tearDown() {
        Mockery::close();
    }

    /**
     * @test
     */
    public function creates_permissions_array_if_permissions_not_loaded() {
        $this->preparePermissionsArray();

        $permissions = $this->role->getPermissionsArray();

        $this->assertEquals([
            1 => 'addUser',
            2 => 'editUser',
            3 => 'deleteUser'
        ], $permissions);

        $this->assertAttributeEquals(true, 'permissionsLoaded', $this->role);
    }

    /**
     * @test
     */
    public function returns_permissions_array_if_permissions_already_loaded() {
        $this->preparePermissionsArray();

        $this->role->getPermissionsArray();
        $this->role->getPermissionsArray();
        $this->role->getPermissionsArray();

    }

    /**
     * @test
     */
    public function returns_true_if_role_can_do_action() {
        $this->preparePermissionsArray();

        $this->assertTrue($this->role->can('editUser'));
    }

    /**
     * @test
     */
    public function returns_false_if_role_can_not_do_action() {
        $this->preparePermissionsArray();

        $this->assertFalse($this->role->can('viewUser'));
    }

    /** @test */
    public function returns_true_if_role_has_permission() {
        $this->preparePermissionsArray();

        $this->assertTrue($this->role->hasPermission(1));
    }

    /** @test */
    public function returns_false_if_role_has_not_permission() {
        $this->preparePermissionsArray();

        $this->assertFalse($this->role->hasPermission(5));
    }

    /** @test */
    public function adds_permission_by_name() {
        $this->preparePermissionsArray();

        $perm = Mockery::mock('stdClass');
        $perm->id = 1;

        // if tryies to resolve permission id by given name
        $this->permission->shouldReceive('where')->once()->with('name', '=', 'viewUser')->andReturnSelf();
        $this->permission->shouldReceive('first')->once()->andReturn($perm);

        // than attaches its id
        $this->permission->shouldReceive('attach')->once()->with(1);

        $this->role->allow('viewUser');

        // after all it should reload permissions array
        $this->assertAttributeEquals(true, 'permissionsLoaded', $this->role);
    }

    /** @test */
    public function removes_permission_by_name() {
        $this->preparePermissionsArray();

        $perm = Mockery::mock('stdClass');
        $perm->id = 1;

        // if tryies to resolve permission id by given name
        $this->permission->shouldReceive('where')->once()->with('name', '=', 'viewUser')->andReturnSelf();
        $this->permission->shouldReceive('first')->once()->andReturn($perm);

        // than attaches its id
        $this->permission->shouldReceive('dettach')->once()->with(1);

        $this->role->disallow('viewUser');

        // after all it should reload permissions array
        $this->assertAttributeEquals(true, 'permissionsLoaded', $this->role);
    }

    /** @test */
    public function does_not_connect_to_database_if_allow_method_provided_with_integer() {
        $this->preparePermissionsArray();

        // it should return integer and shouldn't connect to database
        $this->permission->shouldReceive('where')->never();
        $this->permission->shouldReceive('first')->never();

        // than attaches its id
        $this->permission->shouldReceive('dettach')->once()->with(1);

        $this->role->disallow(1);

        // after all it should reload permissions array
        $this->assertAttributeEquals(true, 'permissionsLoaded', $this->role);
    }

    /** @test */
    public function attaches_permission() {
        $this->preparePermissionsArray();

        $this->permission->shouldReceive('attach')->once()->with(1);

        $this->role->addPermission(1);
    }

    /** @test */
    public function dettaches_permission() {
        $this->preparePermissionsArray();

        $this->permission->shouldReceive('dettach')->once()->with(1);

        $this->role->removePermission(1);
    }

    private function preparePermissionsArray() {
        $this->perm1 = Mockery::mock('Lewiatan\Rbac\Models\Permission');
        $this->perm2 = Mockery::mock('Lewiatan\Rbac\Models\Permission');
        $this->perm3 = Mockery::mock('Lewiatan\Rbac\Models\Permission');

        $this->perm1->shouldReceive('getAttribute')->once()->with('id')->andReturn(1);
        $this->perm1->shouldReceive('getAttribute')->once()->with('name')->andReturn('addUser');

        $this->perm2->shouldReceive('getAttribute')->once()->with('id')->andReturn(2);
        $this->perm2->shouldReceive('getAttribute')->once()->with('name')->andReturn('editUser');

        $this->perm3->shouldReceive('getAttribute')->once()->with('id')->andReturn(3);
        $this->perm3->shouldReceive('getAttribute')->once()->with('name')->andReturn('deleteUser');

        $this->role->permissions = [
            $this->perm1, $this->perm2, $this->perm3
        ];
    }


}