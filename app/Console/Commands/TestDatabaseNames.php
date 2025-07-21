<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tenant;
use App\TenantDatabaseManager;

class TestDatabaseNames extends Command
{
    protected $signature = 'test:database-names';
    protected $description = 'Test the new database naming format for tenants';

    public function handle()
    {
        $this->info('=== PROBANDO NUEVO FORMATO DE NOMBRES DE BASE DE DATOS ===');
        
        $tenants = Tenant::all();
        $databaseManager = new TenantDatabaseManager();
        
        foreach ($tenants as $tenant) {
            $this->info("\n=== TENANT: {$tenant->id} ===");
            $this->info("Nombre del tenant: " . ($tenant->tenancy_data['name'] ?? 'Sin nombre'));
            
            // Obtener el nombre de la base de datos
            $databaseName = $databaseManager->getDatabaseName($tenant);
            $this->info("Nombre de la base de datos: {$databaseName}");
            
            // Mostrar dominios asociados
            $domains = $tenant->domains;
            if ($domains->count() > 0) {
                $this->info("Dominios asociados:");
                foreach ($domains as $domain) {
                    $this->line("  - {$domain->domain}");
                }
            } else {
                $this->warn("No hay dominios asociados");
            }
            
            // Verificar si la base de datos existe
            try {
                $tenant->run(function () {
                    $this->info("✅ Base de datos accesible");
                });
            } catch (\Exception $e) {
                $this->error("❌ Error accediendo a la base de datos: " . $e->getMessage());
            }
        }
        
        $this->info("\n=== PRUEBA COMPLETADA ===");
        
        return 0;
    }
} 