<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Kaely\AuthPackage\Models\User;
use Kaely\AuthPackage\Models\Role;
use Kaely\AuthPackage\Models\Permission;
use Kaely\AuthPackage\Models\Module;
use Kaely\AuthPackage\Models\RoleCategory;

class TenantAuthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Asegurar que usamos la conexión del tenant
        $connection = 'tenant';
        
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
            $existing = DB::connection($connection)->table('modules')->where('slug', $moduleData['slug'])->first();
            if (!$existing) {
                DB::connection($connection)->table('modules')->insert(array_merge($moduleData, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }
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
            $existing = DB::connection($connection)->table('role_categories')->where('slug', $categoryData['slug'])->first();
            if (!$existing) {
                DB::connection($connection)->table('role_categories')->insert(array_merge($categoryData, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }
        }

        // Obtener IDs de categorías
        $systemCategoryId = DB::connection($connection)->table('role_categories')->where('slug', 'system')->value('id');
        $adminCategoryId = DB::connection($connection)->table('role_categories')->where('slug', 'administrative')->value('id');
        $userCategoryId = DB::connection($connection)->table('role_categories')->where('slug', 'user')->value('id');

        // Crear roles
        $roles = [
            [
                'name' => 'Super Admin',
                'slug' => 'super-admin',
                'description' => 'Super administrator with all permissions',
                'role_category_id' => $systemCategoryId,
                'status' => true,
            ],
            [
                'name' => 'Admin',
                'slug' => 'admin',
                'description' => 'Administrator with most permissions',
                'role_category_id' => $adminCategoryId,
                'status' => true,
            ],
            [
                'name' => 'User',
                'slug' => 'user',
                'description' => 'Regular user',
                'role_category_id' => $userCategoryId,
                'status' => true,
            ],
        ];

        foreach ($roles as $roleData) {
            $existing = DB::connection($connection)->table('roles')->where('slug', $roleData['slug'])->first();
            if (!$existing) {
                DB::connection($connection)->table('roles')->insert(array_merge($roleData, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }
        }

        // Obtener IDs de módulos
        $authModuleId = DB::connection($connection)->table('modules')->where('slug', 'auth')->value('id');
        $usersModuleId = DB::connection($connection)->table('modules')->where('slug', 'users')->value('id');
        $rolesModuleId = DB::connection($connection)->table('modules')->where('slug', 'roles')->value('id');
        $permissionsModuleId = DB::connection($connection)->table('modules')->where('slug', 'permissions')->value('id');

        // Crear permisos básicos
        $permissions = [
            // Permisos de autenticación
            [
                'module_id' => $authModuleId,
                'name' => 'Login',
                'slug' => 'auth.login',
                'description' => 'Can login to the system',
                'status' => true,
            ],
            [
                'module_id' => $authModuleId,
                'name' => 'Logout',
                'slug' => 'auth.logout',
                'description' => 'Can logout from the system',
                'status' => true,
            ],
            [
                'module_id' => $authModuleId,
                'name' => 'View Profile',
                'slug' => 'auth.profile',
                'description' => 'Can view own profile',
                'status' => true,
            ],
            // Permisos de usuarios
            [
                'module_id' => $usersModuleId,
                'name' => 'View Users',
                'slug' => 'users.view',
                'description' => 'Can view users list',
                'status' => true,
            ],
            [
                'module_id' => $usersModuleId,
                'name' => 'Create Users',
                'slug' => 'users.create',
                'description' => 'Can create new users',
                'status' => true,
            ],
            [
                'module_id' => $usersModuleId,
                'name' => 'Edit Users',
                'slug' => 'users.edit',
                'description' => 'Can edit existing users',
                'status' => true,
            ],
            [
                'module_id' => $usersModuleId,
                'name' => 'Delete Users',
                'slug' => 'users.delete',
                'description' => 'Can delete users',
                'status' => true,
            ],
            // Permisos de roles
            [
                'module_id' => $rolesModuleId,
                'name' => 'View Roles',
                'slug' => 'roles.view',
                'description' => 'Can view roles list',
                'status' => true,
            ],
            [
                'module_id' => $rolesModuleId,
                'name' => 'Create Roles',
                'slug' => 'roles.create',
                'description' => 'Can create new roles',
                'status' => true,
            ],
            [
                'module_id' => $rolesModuleId,
                'name' => 'Edit Roles',
                'slug' => 'roles.edit',
                'description' => 'Can edit existing roles',
                'status' => true,
            ],
            [
                'module_id' => $rolesModuleId,
                'name' => 'Delete Roles',
                'slug' => 'roles.delete',
                'description' => 'Can delete roles',
                'status' => true,
            ],
            // Permisos de permisos
            [
                'module_id' => $permissionsModuleId,
                'name' => 'View Permissions',
                'slug' => 'permissions.view',
                'description' => 'Can view permissions list',
                'status' => true,
            ],
            [
                'module_id' => $permissionsModuleId,
                'name' => 'Create Permissions',
                'slug' => 'permissions.create',
                'description' => 'Can create new permissions',
                'status' => true,
            ],
            [
                'module_id' => $permissionsModuleId,
                'name' => 'Edit Permissions',
                'slug' => 'permissions.edit',
                'description' => 'Can edit existing permissions',
                'status' => true,
            ],
            [
                'module_id' => $permissionsModuleId,
                'name' => 'Delete Permissions',
                'slug' => 'permissions.delete',
                'description' => 'Can delete permissions',
                'status' => true,
            ],
        ];

        foreach ($permissions as $permissionData) {
            $existing = DB::connection($connection)->table('permissions')->where('slug', $permissionData['slug'])->first();
            if (!$existing) {
                DB::connection($connection)->table('permissions')->insert(array_merge($permissionData, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }
        }

        // Obtener IDs de roles
        $superAdminRoleId = DB::connection($connection)->table('roles')->where('slug', 'super-admin')->value('id');
        $adminRoleId = DB::connection($connection)->table('roles')->where('slug', 'admin')->value('id');
        $userRoleId = DB::connection($connection)->table('roles')->where('slug', 'user')->value('id');

        // Asignar todos los permisos al rol Super Admin
        $allPermissionIds = DB::connection($connection)->table('permissions')->pluck('id');
        foreach ($allPermissionIds as $permissionId) {
            $existing = DB::connection($connection)->table('role_permission')
                ->where('role_id', $superAdminRoleId)
                ->where('permission_id', $permissionId)
                ->first();
            if (!$existing) {
                DB::connection($connection)->table('role_permission')->insert([
                    'role_id' => $superAdminRoleId,
                    'permission_id' => $permissionId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Asignar permisos básicos al rol Admin
        $adminPermissionSlugs = [
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
        ];
        
        $adminPermissionIds = DB::connection($connection)->table('permissions')
            ->whereIn('slug', $adminPermissionSlugs)
            ->pluck('id');
            
        foreach ($adminPermissionIds as $permissionId) {
            $existing = DB::connection($connection)->table('role_permission')
                ->where('role_id', $adminRoleId)
                ->where('permission_id', $permissionId)
                ->first();
            if (!$existing) {
                DB::connection($connection)->table('role_permission')->insert([
                    'role_id' => $adminRoleId,
                    'permission_id' => $permissionId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Asignar permisos básicos al rol User
        $userPermissionSlugs = [
            'auth.login',
            'auth.logout',
            'auth.profile',
        ];
        
        $userPermissionIds = DB::connection($connection)->table('permissions')
            ->whereIn('slug', $userPermissionSlugs)
            ->pluck('id');
            
        foreach ($userPermissionIds as $permissionId) {
            $existing = DB::connection($connection)->table('role_permission')
                ->where('role_id', $userRoleId)
                ->where('permission_id', $permissionId)
                ->first();
            if (!$existing) {
                DB::connection($connection)->table('role_permission')->insert([
                    'role_id' => $userRoleId,
                    'permission_id' => $permissionId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Crear usuario administrador por defecto
        $existingUser = DB::connection($connection)->table('users')->where('email', 'admin@brisashux.com')->first();
        if (!$existingUser) {
            $userId = DB::connection($connection)->table('users')->insertGetId([
                'name' => 'Admin User',
                'email' => 'admin@brisashux.com',
                'password' => Hash::make('password'),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $userId = $existingUser->id;
        }

        // Asignar rol Super Admin al usuario administrador
        $existingUserRole = DB::connection($connection)->table('user_role')
            ->where('user_id', $userId)
            ->where('role_id', $superAdminRoleId)
            ->first();
            
        if (!$existingUserRole) {
            DB::connection($connection)->table('user_role')->insert([
                'user_id' => $userId,
                'role_id' => $superAdminRoleId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}