<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BasicRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear módulos básicos
        $authModule = DB::table('modules')->where('slug', 'auth')->first();
        if (!$authModule) {
            $authModuleId = DB::table('modules')->insertGetId([
                'name' => 'Authentication',
                'slug' => 'auth',
                'order' => 1,
                'description' => 'Authentication and authorization module',
                'icon' => 'fas fa-shield-alt',
                'route' => '/auth',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $authModuleId = $authModule->id;
        }

        // Crear categoría de roles
        $systemCategory = DB::table('role_categories')->where('slug', 'system')->first();
        if (!$systemCategory) {
            $systemCategoryId = DB::table('role_categories')->insertGetId([
                'name' => 'System',
                'slug' => 'system',
                'description' => 'System level roles',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $systemCategoryId = $systemCategory->id;
        }

        // Crear rol básico
        $role = DB::table('roles')->where('slug', 'super-admin')->first();
        if (!$role) {
            $roleId = DB::table('roles')->insertGetId([
                'name' => 'Super Admin',
                'slug' => 'super-admin',
                'description' => 'Super administrator with all permissions',
                'role_category_id' => $systemCategoryId,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $roleId = $role->id;
        }

        // Crear permiso básico
        $permission = DB::table('permissions')->where('slug', 'auth.login')->first();
        if (!$permission) {
            $permissionId = DB::table('permissions')->insertGetId([
                'module_id' => $authModuleId,
                'name' => 'Login',
                'slug' => 'auth.login',
                'description' => 'Can login to the system',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $permissionId = $permission->id;
        }

        // Crear usuario administrador
        $user = DB::table('users')->where('email', 'admin@brisashux.com')->first();
        if (!$user) {
            $userId = DB::table('users')->insertGetId([
                'name' => 'Admin User',
                'email' => 'admin@brisashux.com',
                'password' => bcrypt('password'),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $userId = $user->id;
        }

        // Asignar rol al usuario (verificar si ya existe)
        $userRole = DB::table('user_role')->where('user_id', $userId)->where('role_id', $roleId)->first();
        if (!$userRole) {
            DB::table('user_role')->insert([
                'user_id' => $userId,
                'role_id' => $roleId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Asignar permiso al rol (verificar si ya existe)
        $rolePermission = DB::table('role_permission')->where('role_id', $roleId)->where('permission_id', $permissionId)->first();
        if (!$rolePermission) {
            DB::table('role_permission')->insert([
                'role_id' => $roleId,
                'permission_id' => $permissionId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}