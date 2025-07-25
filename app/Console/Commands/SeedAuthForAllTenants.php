<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tenant;

class SeedAuthForAllTenants extends Command
{
    protected $signature = 'tenants:seed-auth';
    protected $description = 'Ejecuta el seeder AuthPackageSeeder en todos los tenants';

    public function handle()
    {
        $this->info("=== Ejecutando AuthPackageSeeder en todos los tenants ===");
        $tenants = Tenant::all();
        if ($tenants->isEmpty()) {
            $this->error("❌ No se encontraron tenants");
            return 1;
        }
        foreach ($tenants as $tenant) {
            $this->info("\n============================================================");
            $this->info("🏢 Tenant: {$tenant->id}");
            $this->info("🌐 Dominios: " . $tenant->domains->pluck('domain')->implode(', '));
            try {
                $tenant->run(function () use ($tenant) {
                    $this->info("💾 Base de datos: " . config('database.connections.tenant.database'));
                    \Artisan::call('db:seed', ['--class' => 'AuthPackageSeeder']);
                    $this->info("✅ Seeder ejecutado correctamente");
                });
            } catch (\Exception $e) {
                $this->error("❌ Error en tenant {$tenant->id}: " . $e->getMessage());
            }
        }
        $this->info("\n=== Seeder ejecutado en todos los tenants ===");
        return 0;
    }
} 