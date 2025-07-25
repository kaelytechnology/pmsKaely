<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Module;
use App\Models\RoleCategory;

class AuthPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear módulos básicos
        $modules = [
            [
                'name' => 'Authentication',
                'slug' => 'auth',
                'order' => 1,
                'description' => 'Authentication and authorization module',
                'icon' => 'fas fa-shield-alt',
                'route' => '/auth',
                'is_active' => true,
            ],
            [
                'name' => 'Users',
                'slug' => 'users',
                'order' => 2,
                'description' => 'User management module',
                'icon' => 'fas fa-users',
                'route' => '/users',
                'is_active' => true,
            ],
            [
                'name' => 'Roles',
                'slug' => 'roles',
                'order' => 3,
                'description' => 'Role management module',
                'icon' => 'fas fa-user-tag',
                'route' => '/roles',
                'is_active' => true,
            ],
            [
                'name' => 'Permissions',
                'slug' => 'permissions',
                'order' => 4,
                'description' => 'Permission management module',
                'icon' => 'fas fa-key',
                'route' => '/permissions',
                'is_active' => true,
            ],
        ];

        foreach ($modules as $moduleData) {
            Module::firstOrCreate(
                ['slug' => $moduleData['slug']],
                $moduleData
            );
        }   

        // Crear categorías de roles
        $roleCategories = [
            [
                'name' => 'System',
                'slug' => 'system',
                'description' => 'System level roles',
            ],
            [
                'name' => 'Administrative',
                'slug' => 'administrative',
                'description' => 'Administrative roles',
            ],
            [
                'name' => 'User',
                'slug' => 'user',
                'description' => 'Regular user roles',
            ],
        ];

        foreach ($roleCategories as $categoryData) {
            RoleCategory::firstOrCreate(
                ['slug' => $categoryData['slug']],
                $categoryData
            );
        }

        // Crear roles
        $roles = [
            [
                'name' => 'Super Admin',
                'slug' => 'super-admin',
                'description' => 'Super administrator with all permissions',
                'role_category_id' => RoleCategory::where('slug', 'system')->first()->id,
                'status' => true,
            ],
            [
                'name' => 'Admin',
                'slug' => 'admin',
                'description' => 'Administrator with most permissions',
                'role_category_id' => RoleCategory::where('slug', 'administrative')->first()->id,
                'status' => true,
            ],
            [
                'name' => 'User',
                'slug' => 'user',
                'description' => 'Regular user',
                'role_category_id' => RoleCategory::where('slug', 'user')->first()->id,
                'status' => true,
            ],
        ];

        foreach ($roles as $roleData) {
            Role::firstOrCreate(
                ['slug' => $roleData['slug']],
                $roleData
            );
        }

        // Crear permisos básicos
        $permissions = [
            // Permisos de autenticación
            [
                'module_id' => Module::where('slug', 'auth')->first()->id,
                'name' => 'Login',
                'slug' => 'auth.login',
                'description' => 'Can login to the system',
                'status' => true,
            ],
            [
                'module_id' => Module::where('slug', 'auth')->first()->id,
                'name' => 'Logout',
                'slug' => 'auth.logout',
                'description' => 'Can logout from the system',
                'status' => true,
            ],
            [
                'module_id' => Module::where('slug', 'auth')->first()->id,
                'name' => 'View Profile',
                'slug' => 'auth.profile',
                'description' => 'Can view own profile',
                'status' => true,
            ],
            // Permisos de usuarios
            [
                'module_id' => Module::where('slug', 'users')->first()->id,
                'name' => 'View Users',
                'slug' => 'users.view',
                'description' => 'Can view users list',
                'status' => true,
            ],
            [
                'module_id' => Module::where('slug', 'users')->first()->id,
                'name' => 'Create Users',
                'slug' => 'users.create',
                'description' => 'Can create new users',
                'status' => true,
            ],
            [
                'module_id' => Module::where('slug', 'users')->first()->id,
                'name' => 'Edit Users',
                'slug' => 'users.edit',
                'description' => 'Can edit existing users',
                'status' => true,
            ],
            [
                'module_id' => Module::where('slug', 'users')->first()->id,
                'name' => 'Delete Users',
                'slug' => 'users.delete',
                'description' => 'Can delete users',
                'status' => true,
            ],
            // Permisos de roles
            [
                'module_id' => Module::where('slug', 'roles')->first()->id,
                'name' => 'View Roles',
                'slug' => 'roles.view',
                'description' => 'Can view roles list',
                'status' => true,
            ],
            [
                'module_id' => Module::where('slug', 'roles')->first()->id,
                'name' => 'Create Roles',
                'slug' => 'roles.create',
                'description' => 'Can create new roles',
                'status' => true,
            ],
            [
                'module_id' => Module::where('slug', 'roles')->first()->id,
                'name' => 'Edit Roles',
                'slug' => 'roles.edit',
                'description' => 'Can edit existing roles',
                'status' => true,
            ],
            [
                'module_id' => Module::where('slug', 'roles')->first()->id,
                'name' => 'Delete Roles',
                'slug' => 'roles.delete',
                'description' => 'Can delete roles',
                'status' => true,
            ],
            // Permisos de permisos
            [
                'module_id' => Module::where('slug', 'permissions')->first()->id,
                'name' => 'View Permissions',
                'slug' => 'permissions.view',
                'description' => 'Can view permissions list',
                'status' => true,
            ],
            [
                'module_id' => Module::where('slug', 'permissions')->first()->id,
                'name' => 'Create Permissions',
                'slug' => 'permissions.create',
                'description' => 'Can create new permissions',
                'status' => true,
            ],
            [
                'module_id' => Module::where('slug', 'permissions')->first()->id,
                'name' => 'Edit Permissions',
                'slug' => 'permissions.edit',
                'description' => 'Can edit existing permissions',
                'status' => true,
            ],
            [
                'module_id' => Module::where('slug', 'permissions')->first()->id,
                'name' => 'Delete Permissions',
                'slug' => 'permissions.delete',
                'description' => 'Can delete permissions',
                'status' => true,
            ],
        ];

        foreach ($permissions as $permissionData) {
            Permission::firstOrCreate(
                ['slug' => $permissionData['slug']],
                $permissionData
            );
        }

        // Asignar todos los permisos al rol Super Admin
        $superAdminRole = Role::where('slug', 'super-admin')->first();
        $allPermissions = Permission::all();
        $superAdminRole->permissions()->syncWithoutDetaching($allPermissions->pluck('id'));

        // Asignar permisos básicos al rol Admin
        $adminRole = Role::where('slug', 'admin')->first();
        $adminPermissions = Permission::whereIn('slug', [
            'auth.login',
            'auth.logout',
            'auth.profile',
            'users.view',
            'users.create',
            'users.edit',
            'roles.view',
            'roles.create',
            'roles.edit',
            'permissions.view',
        ])->get();
        $adminRole->permissions()->syncWithoutDetaching($adminPermissions->pluck('id'));

        // Asignar permisos básicos al rol User
        $userRole = Role::where('slug', 'user')->first();
        $userPermissions = Permission::whereIn('slug', [
            'auth.login',
            'auth.logout',
            'auth.profile',
        ])->get();
        $userRole->permissions()->syncWithoutDetaching($userPermissions->pluck('id'));

        // Crear usuario administrador por defecto
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        );

        // Asignar rol Super Admin al usuario administrador
        if ($superAdminRole) {
            $adminUser->roles()->syncWithoutDetaching([$superAdminRole->id]);
        } else {
            throw new \Exception('No se encontró el rol Super Admin para asignar al usuario administrador.');
        }
    }
}