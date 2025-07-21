<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tenant;
use App\TenantDatabaseManager;
use Illuminate\Support\Facades\DB;

class ListTenantDatabases extends Command
{
    protected $signature = 'tenant:list-databases';
    protected $description = 'List all tenant databases in MySQL';

    public function handle()
    {
        $this->info('=== BASES DE DATOS DE TENANTS EN MYSQL ===');
        
        try {
            // Obtener todas las bases de datos
            $databases = DB::select("SHOW DATABASES");
            
            // Filtrar las que contienen 'tenant'
            $tenantDatabases = collect($databases)->filter(function ($db) {
                $dbName = $db->Database ?? $db->{'Database'} ?? $db->database ?? '';
                return strpos($dbName, 'tenant') !== false;
            });
            
            if ($tenantDatabases->isEmpty()) {
                $this->warn('No se encontraron bases de datos de tenants');
                return 0;
            }
            
            $this->info("Se encontraron " . $tenantDatabases->count() . " bases de datos de tenants:");
            $this->line('');
            
            foreach ($tenantDatabases as $db) {
                $databaseName = $db->Database ?? $db->{'Database'} ?? $db->database ?? 'unknown';
                $this->line("📊 {$databaseName}");
            }
            
        } catch (\Exception $e) {
            $this->error("Error consultando bases de datos: " . $e->getMessage());
            return 1;
        }
        
        $this->line('');
        $this->info('=== TENANTS CONFIGURADOS ===');
        
        $tenants = Tenant::all();
        $databaseManager = new TenantDatabaseManager();
        
        foreach ($tenants as $tenant) {
            $this->line('');
            $this->info("Tenant ID: {$tenant->id}");
            $this->line("Nombre: " . ($tenant->tenancy_data['name'] ?? 'Sin nombre'));
            
            $expectedDatabaseName = $databaseManager->getDatabaseName($tenant);
            $this->line("Base de datos esperada: {$expectedDatabaseName}");
            
            // Verificar si la base de datos existe
            $exists = $tenantDatabases->contains(function ($db) use ($expectedDatabaseName) {
                $dbName = $db->Database ?? $db->{'Database'} ?? $db->database ?? '';
                return $dbName === $expectedDatabaseName;
            });
            
            if ($exists) {
                $this->info("✅ Base de datos existe");
            } else {
                $this->error("❌ Base de datos no encontrada");
            }
            
            // Mostrar dominios
            $domains = $tenant->domains;
            if ($domains->count() > 0) {
                $this->line("Dominios:");
                foreach ($domains as $domain) {
                    $this->line("  - {$domain->domain}");
                }
            }
        }
        
        return 0;
    }
} 