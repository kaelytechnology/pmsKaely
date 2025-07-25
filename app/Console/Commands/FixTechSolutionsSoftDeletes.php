<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tenant;
use Illuminate\Support\Facades\Schema;

class FixTechSolutionsSoftDeletes extends Command
{
    protected $signature = 'fix:tech-solutions-soft-deletes';
    protected $description = 'Fix SoftDeletes column for Tech Solutions tenant';

    public function handle()
    {
        $this->info("=== ARREGLANDO SOFT DELETES PARA TECH SOLUTIONS ===");
        
        $tenant = Tenant::find('687dc3ccc4f22');
        
        if (!$tenant) {
            $this->error("❌ No se encontró el tenant Tech Solutions");
            return 1;
        }
        
        $this->info("🏢 Tenant: {$tenant->id}");
        $this->info("🌐 Dominios: " . $tenant->domains->pluck('domain')->implode(', '));
        
        try {
            $tenant->run(function () {
                $this->info("💾 Base de datos: " . config('database.connections.tenant.database'));
                
                // Verificar si la columna deleted_at existe
                $hasColumn = Schema::hasColumn('users', 'deleted_at');
                
                if ($hasColumn) {
                    $this->warn("⚠️  La columna deleted_at ya existe en la tabla users");
                    return;
                }
                
                // Agregar la columna
                $this->info("🔄 Agregando columna deleted_at si no existe...");
                if (!Schema::hasColumn('users', 'deleted_at')) {
                    Schema::table('users', function ($table) {
                        $table->softDeletes();
                    });
                    $this->info("Columna deleted_at agregada.");
                } else {
                    $this->info("La columna deleted_at ya existe en la tabla users.");
                }
                
                // Verificar que se agregó correctamente
                $hasColumnAfter = Schema::hasColumn('users', 'deleted_at');
                if ($hasColumnAfter) {
                    $this->info("✅ Verificación exitosa: la columna deleted_at existe");
                } else {
                    $this->error("❌ Error: la columna deleted_at no se agregó correctamente");
                }
            });
            
        } catch (\Exception $e) {
            $this->error("❌ Error: " . $e->getMessage());
            return 1;
        }
        
        $this->info("\n=== REPARACIÓN COMPLETADA ===");
        return 0;
    }
} 