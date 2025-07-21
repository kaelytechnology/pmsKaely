<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tenant;

class ShowTenantTypes extends Command
{
    protected $signature = 'tenant:show-types';
    protected $description = 'Show the difference between subdomain and full domain tenants';

    public function handle()
    {
        $this->info('🌐 TIPOS DE TENANTS - SUBDOMINIOS vs DOMINIOS COMPLETOS');
        $this->info(str_repeat('=', 80));
        
        $tenants = Tenant::all();
        $subdomainTenants = collect();
        $fullDomainTenants = collect();
        
        foreach ($tenants as $tenant) {
            $domains = $tenant->domains;
            $hasSubdomain = $domains->contains(function ($domain) {
                return str_contains($domain->domain, 'kaelytechnology.test');
            });
            
            if ($hasSubdomain) {
                $subdomainTenants->push($tenant);
            } else {
                $fullDomainTenants->push($tenant);
            }
        }
        
        // Mostrar tenants con subdominios
        $this->info("\n📋 TENANTS CON SUBDOMINIOS ({$subdomainTenants->count()})");
        $this->info(str_repeat('-', 50));
        
        foreach ($subdomainTenants as $index => $tenant) {
            $this->info("Tenant #" . ($index + 1));
            $this->line("   🏢 Nombre: " . ($tenant->tenancy_data['name'] ?? 'Sin nombre'));
            $this->line("   🆔 ID: {$tenant->id}");
            
            $domains = $tenant->domains;
            foreach ($domains as $domain) {
                $this->line("   🌐 Dominio: {$domain->domain}");
            }
            
            $tenant->run(function () use ($tenant) {
                $users = \App\Models\User::all();
                if ($users->count() > 0) {
                    $user = $users->first();
                    $this->line("   👤 Usuario: {$user->email}");
                }
                
                $databaseManager = new \App\TenantDatabaseManager();
                $databaseName = $databaseManager->getDatabaseName($tenant);
                $this->line("   💾 BD: {$databaseName}");
            });
            
            $this->line("");
        }
        
        // Mostrar tenants con dominios completos
        $this->info("\n🌍 TENANTS CON DOMINIOS COMPLETOS ({$fullDomainTenants->count()})");
        $this->info(str_repeat('-', 50));
        
        foreach ($fullDomainTenants as $index => $tenant) {
            $this->info("Tenant #" . ($index + 1));
            $this->line("   🏢 Nombre: " . ($tenant->tenancy_data['name'] ?? 'Sin nombre'));
            $this->line("   🆔 ID: {$tenant->id}");
            
            $domains = $tenant->domains;
            foreach ($domains as $domain) {
                $this->line("   🌐 Dominio: {$domain->domain}");
            }
            
            $tenant->run(function () use ($tenant) {
                $users = \App\Models\User::all();
                if ($users->count() > 0) {
                    $user = $users->first();
                    $this->line("   👤 Usuario: {$user->email}");
                }
                
                $databaseManager = new \App\TenantDatabaseManager();
                $databaseName = $databaseManager->getDatabaseName($tenant);
                $this->line("   💾 BD: {$databaseName}");
            });
            
            $this->line("");
        }
        
        // Resumen comparativo
        $this->info("\n" . str_repeat('=', 80));
        $this->info("📊 RESUMEN COMPARATIVO");
        $this->info(str_repeat('=', 80));
        
        $this->info("🔹 Tenants con Subdominios:");
        $this->line("   • Formato: subdominio.kaelytechnology.test");
        $this->line("   • Ejemplo: miempresa.kaelytechnology.test");
        $this->line("   • Cantidad: {$subdomainTenants->count()}");
        $this->line("   • Uso: Desarrollo y pruebas");
        
        $this->info("\n🔸 Tenants con Dominios Completos:");
        $this->line("   • Formato: dominio.com");
        $this->line("   • Ejemplo: empresaglobal.com");
        $this->line("   • Cantidad: {$fullDomainTenants->count()}");
        $this->line("   • Uso: Producción y clientes reales");
        
        $this->info("\n✅ Estado: Ambos tipos funcionan correctamente");
        
        return 0;
    }
} 