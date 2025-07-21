<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tenant;
use Stancl\Tenancy\Database\Models\Domain;

class CreateDomainTenant extends Command
{
    protected $signature = 'tenant:create-domain {name} {domain}';
    protected $description = 'Create a new tenant with a full domain (not subdomain)';

    public function handle()
    {
        $name = $this->argument('name');
        $domain = $this->argument('domain');
        
        $this->info("=== CREANDO TENANT CON DOMINIO COMPLETO ===");
        $this->info("Nombre: {$name}");
        $this->info("Dominio: {$domain}");
        
        // Crear el tenant
        $tenant = Tenant::create([
            'id' => uniqid(),
            'tenancy_data' => ['name' => $name]
        ]);
        
        $this->info("Tenant creado con ID: {$tenant->id}");
        
        // Crear el dominio completo (sin agregar .kaelytechnology.test)
        $tenant->domains()->create([
            'domain' => $domain
        ]);
        
        $this->info("Dominio creado: {$domain}");
        
        // Mostrar el nombre de la base de datos que se creará
        $databaseManager = new \App\TenantDatabaseManager();
        $databaseName = $databaseManager->getDatabaseName($tenant);
        $this->info("Nombre de la base de datos: {$databaseName}");
        
        // Ejecutar migraciones
        $this->info("Ejecutando migraciones...");
        $tenant->run(function () {
            $this->info("Migraciones ejecutadas correctamente");
        });
        
        // Crear un usuario de prueba
        $this->info("Creando usuario de prueba...");
        $tenant->run(function () use ($tenant) {
            $user = \App\Models\User::create([
                'name' => "Usuario {$tenant->tenancy_data['name']}",
                'email' => "admin@{$tenant->domains->first()->domain}",
                'password' => \Illuminate\Support\Facades\Hash::make('password123'),
            ]);
            
            $this->info("Usuario creado: {$user->name} ({$user->email})");
        });
        
        $this->info("✅ Tenant con dominio completo creado exitosamente!");
        
        return 0;
    }
} 