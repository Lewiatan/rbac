<?php

use Lewiatan\Rbac\Rbac;

class RbacClassTest extends PHPUnit_Framework_TestCase {

    private $rbac;

    private $user;

    private $role;

    /**
     *
     */
    public function setUp() {
        $this->user = Mockery::mock('Lewiatan\Rbac\Interfaces\RbacUserInterface');
        $this->role = Mockery::mock('Lewiatan\Rbac\Interfaces\RbacRoleInterface');
        $this->rbac = new Rbac($this->user, $this->role);
    }

    public function tearDown() {
        Mockery::close();
    }

    /**
     * @test
     */
    public function it_is_setting_user_by_id() {
        $this->user->shouldReceive('findOrFail')->with(1)->once()->andReturnSelf();

        $this->rbac->user(1);

        $this->assertAttributeInstanceOf('Lewiatan\Rbac\Interfaces\RbacUserInterface', 'user', $this->rbac);
    }


    /**
     * @test
     */
    public function it_is_setting_user_by_model() {
        $this->rbac->user($this->user);

        $this->assertAttributeInstanceOf('Lewiatan\Rbac\Interfaces\RbacUserInterface', 'user', $this->rbac);
    }

    /**
     * @test
     */
    public function is_is_throwing_exception_when_wrong_user_parameter_provided() {
        $this->setExpectedException('Lewiatan\Rbac\RbacWrongUserException');

        $this->rbac->user('wrong id');
    }

    /**
     * @test
     */
    public function is_setting_role_by_model() {
        $this->rbac->role($this->role);

        $this->assertAttributeInstanceOf('Lewiatan\Rbac\Interfaces\RbacRoleInterface', 'role', $this->rbac);
    }

    /**
     * @test
     */
    public function it_is_setting_role_by_id() {
        $this->role->shouldReceive('findOrFail')->with(1)->once()->andReturnSelf();

        $this->rbac->role(1);

        $this->assertAttributeInstanceOf('Lewiatan\Rbac\Interfaces\RbacRoleInterface', 'role', $this->rbac);
    }

    /**
     * @test
     */
    public function is_is_throwing_exception_when_wrong_role_parameter_provided() {
        $this->setExpectedException('Lewiatan\Rbac\RbacWrongRoleException');

        $this->rbac->role('wrong id');
    }

    /**
     * @test
     */
    public function is_checking_permission_by_name() {
        $this->user->shouldReceive('can')->with('permissionName');

        $this->rbac->user($this->user)->can('permissionName');
    }

    /**
     * @test
     */
    public function is_checking_permission_by_id() {
        $this->user->shouldReceive('hasPermission')->with(1);

        $this->rbac->user($this->user)->hasPermission(1);
    }

    /**
     * @test
     */
    public function is_attaching_role() {
        $this->user->shouldReceive('attach')->once()->with('roleName');

        $this->rbac->user($this->user)->assignRole('roleName');
    }

    /**
     * @test
     */
    public function is_dettaching_role() {
        $this->user->shouldReceive('dettach')->once()->with('roleName');

        $this->rbac->user($this->user)->removeRole('roleName');
    }

    /**
     * @test
     */
    public function is_allowing_permission() {
        $this->role->shouldReceive('allow')->with('permissionName');

        $this->rbac->role($this->role)->allow('permissionName');
    }

    /**
     * @test
     */
    public function is_disallowing_permission() {
        $this->role->shouldReceive('disallow')->with('permissionName');

        $this->rbac->role($this->role)->disallow('permissionName');
    }



}