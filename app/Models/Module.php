<?php

namespace App\Models;

use Kaely\AuthPackage\Models\Module as AuthPackageModule;

class Module extends AuthPackageModule
{
    /**
     * The database connection that should be used by the model.
     *
     * @var string
     */
    protected $connection = 'tenant';
}