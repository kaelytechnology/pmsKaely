<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tenant;

class MigrateTenantsSoftDeletes extends Command
{
    protected $signature = 'tenants:migrate-soft-deletes';
    protected $description = 'Migrate SoftDeletes to all tenants';

    public function handle()
    {
        $this->info("=== MIGRANDO SOFT DELETES A TODOS LOS TENANTS ===");
        
        $tenants = Tenant::all();
        
        if ($tenants->isEmpty()) {
            $this->error("❌ No se encontraron tenants");
            return 1;
        }
        
        $this->info("📋 Encontrados {$tenants->count()} tenants");
        
        foreach ($tenants as $tenant) {
            $this->info("\n============================================================");
            $this->info("🏢 Tenant: {$tenant->id}");
            $this->info("🌐 Dominios: " . $tenant->domains->pluck('domain')->implode(', '));
            
            try {
                $tenant->run(function () use ($tenant) {
                    $this->info("💾 Base de datos: " . config('database.connections.tenant.database'));
                    
                    // Verificar si la columna deleted_at ya existe
                    $hasColumn = \Schema::hasColumn('users', 'deleted_at');
                    
                    if ($hasColumn) {
                        $this->warn("⚠️  La columna deleted_at ya existe en la tabla users");
                        return;
                    }
                    
                    // Ejecutar la migración
                    $this->info("🔄 Ejecutando migración...");
                    \Artisan::call('migrate', [
                        '--path' => 'database/migrations/tenant/2024_01_01_000009_add_soft_deletes_to_users_table.php',
                        '--force' => true
                    ]);
                    
                    $this->info("✅ Migración completada exitosamente");
                });
                
            } catch (\Exception $e) {
                $this->error("❌ Error en tenant {$tenant->id}: " . $e->getMessage());
            }
        }
        
        $this->info("\n=== MIGRACIÓN COMPLETADA ===");
        return 0;
    }
} 