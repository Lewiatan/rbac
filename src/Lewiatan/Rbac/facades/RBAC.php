<?php namespace Lewiatan\Rbac\Facades;

use Illuminate\Support\Facades\Facade;

class RBAC extends Facade {
    protected static function getFacadeAccessor() {
        return 'rbac';
    }
}