<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tenant;
use App\TenantDatabaseManager;
use Illuminate\Support\Facades\DB;

class MigrateDatabaseNames extends Command
{
    protected $signature = 'tenant:migrate-database-names';
    protected $description = 'Migrate existing tenant databases to new naming format';

    public function handle()
    {
        $this->info('=== MIGRANDO NOMBRES DE BASES DE DATOS ===');
        
        $tenants = Tenant::all();
        $databaseManager = new TenantDatabaseManager();
        
        foreach ($tenants as $tenant) {
            $this->info("\n=== MIGRANDO TENANT: {$tenant->id} ===");
            $this->info("Nombre: " . ($tenant->tenancy_data['name'] ?? 'Sin nombre'));
            
            // Obtener el nombre actual de la base de datos (formato antiguo)
            $oldDatabaseName = "tenant{$tenant->id}";
            
            // Obtener el nuevo nombre de la base de datos
            $newDatabaseName = $databaseManager->getDatabaseName($tenant);
            
            $this->info("Base de datos actual: {$oldDatabaseName}");
            $this->info("Base de datos nueva: {$newDatabaseName}");
            
            // Verificar si la base de datos actual existe
            $currentExists = $this->databaseExists($oldDatabaseName);
            $newExists = $this->databaseExists($newDatabaseName);
            
            if (!$currentExists) {
                $this->warn("⚠️ La base de datos actual no existe, saltando...");
                continue;
            }
            
            if ($newExists) {
                $this->warn("⚠️ La nueva base de datos ya existe, saltando...");
                continue;
            }
            
            // Confirmar la migración
            if (!$this->confirm("¿Deseas migrar la base de datos de '{$oldDatabaseName}' a '{$newDatabaseName}'?")) {
                $this->info("Migración cancelada para este tenant");
                continue;
            }
            
            try {
                // Renombrar la base de datos
                DB::statement("RENAME DATABASE `{$oldDatabaseName}` TO `{$newDatabaseName}`");
                $this->info("✅ Base de datos renombrada exitosamente");
                
            } catch (\Exception $e) {
                $this->error("❌ Error renombrando la base de datos: " . $e->getMessage());
            }
        }
        
        $this->info("\n=== MIGRACIÓN COMPLETADA ===");
        
        return 0;
    }
    
    private function databaseExists($databaseName): bool
    {
        try {
            $databases = DB::select("SHOW DATABASES");
            return collect($databases)->contains(function ($db) use ($databaseName) {
                $dbName = $db->Database ?? $db->{'Database'} ?? $db->database ?? '';
                return $dbName === $databaseName;
            });
        } catch (\Exception $e) {
            return false;
        }
    }
} 