<?php

class UserModelTest extends PHPUnit_Framework_TestCase {

    private $role;
    private $user;

    public function setUp() {
        $this->role = Mockery::mock('Lewiatan\Rbac\Models\Role');

        $this->user = new Lewiatan\Rbac\Models\User;
        $this->user->roles = $this->role;
    }

    public function tearDown() {
        Mockery::close();
    }

    /**
     * @test
     */
    public function checks_if_user_can_access_something() {
        $this->role->shouldReceive('can')->once()->with('permission')->andReturn(true);

        $this->assertTrue($this->user->can('permission'));
    }

    /**
     * @test
     */
    public function checks_if_user_has_permission() {
        $this->role->shouldReceive('hasPermission')->once()->with(1)->andReturn(true);

        $this->assertTrue($this->user->hasPermission(1));
    }

    /**
     * @test
     */
    public function assigns_role() {
        $this->role->shouldReceive('attach')->once()->with('role');

        $this->assertInstanceOf('Lewiatan\Rbac\Models\User', $this->user->assignRole('role'));
    }

    /**
     * @test
     */
    public function removes_role() {
        $this->role->shouldReceive('dettach')->once()->with('role');

        $this->assertInstanceOf('Lewiatan\Rbac\Models\User', $this->user->removeRole('role'));
    }

}