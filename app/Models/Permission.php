<?php

namespace App\Models;

use Kaely\AuthPackage\Models\Permission as AuthPackagePermission;

class Permission extends AuthPackagePermission
{
    /**
     * The database connection that should be used by the model.
     *
     * @var string
     */
    protected $connection = 'tenant';
}