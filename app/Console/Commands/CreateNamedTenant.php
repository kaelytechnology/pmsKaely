<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tenant;
use Stancl\Tenancy\Database\Models\Domain;

class CreateNamedTenant extends Command
{
    protected $signature = 'tenant:create-named {name} {domain}';
    protected $description = 'Create a new tenant with the new database naming format';

    public function handle()
    {
        $name = $this->argument('name');
        $domain = $this->argument('domain');
        
        $this->info("=== CREANDO NUEVO TENANT ===");
        $this->info("Nombre: {$name}");
        $this->info("Dominio: {$domain}");
        
        // Crear el tenant
        $tenant = Tenant::create([
            'id' => uniqid(),
            'tenancy_data' => ['name' => $name]
        ]);
        
        $this->info("Tenant creado con ID: {$tenant->id}");
        
        // Crear el dominio
        $fullDomain = $domain . '.kaelytechnology.test';
        $tenant->domains()->create([
            'domain' => $fullDomain
        ]);
        
        $this->info("Dominio creado: {$fullDomain}");
        
        // Mostrar el nombre de la base de datos que se creará
        $databaseManager = new \App\TenantDatabaseManager();
        $databaseName = $databaseManager->getDatabaseName($tenant);
        $this->info("Nombre de la base de datos: {$databaseName}");
        
        // Ejecutar migraciones
        $this->info("Ejecutando migraciones...");
        $tenant->run(function () {
            $this->info("Migraciones ejecutadas correctamente");
        });
        
        $this->info("✅ Tenant creado exitosamente!");
        
        return 0;
    }
} 