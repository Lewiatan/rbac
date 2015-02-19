<?php

use Lewiatan\Rbac\Collections\RolesCollection;

class RolesCollectionTest extends PHPUnit_Framework_TestCase {

   public function setUp() {
        $this->adminRole = Mockery::mock('Lewiatan\Rbac\Models\Role');

        $this->writerRole = Mockery::mock('Lewiatan\Rbac\Models\Role');

        $adminPermissions = [
            1 => 'addUser',
            2 => 'editUser',
            3 => 'deleteUser'
        ];

        $writerPermissions = [
            4 => 'writeArticle',
            5 => 'editArticle',
            6 => 'publishArticle'
        ];

        $this->adminRole->shouldReceive('getPermissionsArray')->andReturn($adminPermissions);
        $this->writerRole->shouldReceive('getPermissionsArray')->andReturn($writerPermissions);

       $this->collection = new RolesCollection([
           $this->adminRole,
           $this->writerRole
       ]);
    }

    public function tearDown() {
        Mockery::close();
    }


    /** @test */
    public function returns_true_if_can_perform_acction() {
        $this->assertTrue($this->collection->can('addUser'));
        $this->assertTrue($this->collection->can('writeArticle'));
    }

    /** @test */
    public function return_false_if_can_not_perform_action() {
        $this->assertFalse($this->collection->can('viewUser'));
        $this->assertFalse($this->collection->can('viewArticle'));
    }

    /** @test */
    public function returns_true_if_has_permission() {
        $this->assertTrue($this->collection->hasPermission(2));
        $this->assertTrue($this->collection->hasPermission(5));
    }

    /** @test */
    public function returns_false_if_has_not_permission() {
        $this->assertFalse($this->collection->hasPermission(7));
        $this->assertFalse($this->collection->hasPermission(8));
    }


    
}