<?php

namespace App\Models;

use Kaely\AuthPackage\Models\Role as AuthPackageRole;

class Role extends AuthPackageRole
{
    /**
     * The database connection that should be used by the model.
     *
     * @var string
     */
    protected $connection = 'tenant';
}