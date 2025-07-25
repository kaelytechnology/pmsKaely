<?php

namespace App\Models;

use Kaely\AuthPackage\Models\RoleCategory as AuthPackageRoleCategory;

class RoleCategory extends AuthPackageRoleCategory
{
    /**
     * The database connection that should be used by the model.
     *
     * @var string
     */
    protected $connection = 'tenant';
}