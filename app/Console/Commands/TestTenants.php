<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TestTenants extends Command
{
    protected $signature = 'test:tenants';
    protected $description = 'Test tenants, create users and login';

    public function handle()
    {
        $this->info('=== TENANTS EXISTENTES ===');
        $tenants = Tenant::all();
        
        if ($tenants->isEmpty()) {
            $this->error('No hay tenants existentes. Creando tenants de prueba...');
            
            // Crear tenants de prueba
            $tenant1 = Tenant::create([
                'id' => 'tenant1',
                'tenancy_data' => ['name' => 'Tenant 1']
            ]);
            
            $tenant2 = Tenant::create([
                'id' => 'tenant2', 
                'tenancy_data' => ['name' => 'Tenant 2']
            ]);
            
            $tenants = collect([$tenant1, $tenant2]);
            $this->info('Tenants creados: tenant1, tenant2');
        }
        
        foreach ($tenants as $tenant) {
            $this->info("Tenant ID: {$tenant->id}");
            $this->info("Tenant Data: " . json_encode($tenant->tenancy_data));
            $this->line('---');
        }

        // Probar cada tenant
        foreach ($tenants as $tenant) {
            $this->info("\n=== PROBANDO TENANT: {$tenant->id} ===");
            
            // Inicializar el tenant
            $tenant->run(function () use ($tenant) {
                $this->info("Ejecutando en tenant: {$tenant->id}");
                
                // Verificar si ya existe un usuario
                $existingUser = User::where('email', "user{$tenant->id}@example.com")->first();
                
                if ($existingUser) {
                    $this->info("Usuario existente encontrado: {$existingUser->name} ({$existingUser->email})");
                    $user = $existingUser;
                } else {
                    // Crear un usuario de prueba
                    $user = User::create([
                        'name' => "Usuario {$tenant->id}",
                        'email' => "user{$tenant->id}@example.com",
                        'password' => Hash::make('password123'),
                    ]);
                    
                    $this->info("Usuario creado: {$user->name} ({$user->email})");
                }
                
                // Intentar hacer login
                $credentials = [
                    'email' => "user{$tenant->id}@example.com",
                    'password' => 'password123'
                ];
                
                if (auth()->attempt($credentials)) {
                    $this->info("✅ Login exitoso para {$user->email}");
                    $this->info("Usuario autenticado: " . auth()->user()->name);
                } else {
                    $this->error("❌ Login fallido para {$user->email}");
                }
                
                // Listar usuarios en este tenant
                $users = User::all();
                $this->info("Usuarios en este tenant: " . $users->count());
                foreach ($users as $u) {
                    $this->line("  - {$u->name} ({$u->email})");
                }
            });
            
            $this->line('---');
        }

        $this->info("\n=== PRUEBA COMPLETADA ===");
        
        return 0;
    }
} 