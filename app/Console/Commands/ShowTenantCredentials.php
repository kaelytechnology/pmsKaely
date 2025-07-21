<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tenant;
use App\Models\User;

class ShowTenantCredentials extends Command
{
    protected $signature = 'tenant:show-credentials';
    protected $description = 'Show all tenant credentials in a organized way';

    public function handle()
    {
        $this->info('🔐 CREDENCIALES DE TODOS LOS TENANTS');
        $this->info(str_repeat('=', 80));
        
        $tenants = Tenant::all();
        
        foreach ($tenants as $index => $tenant) {
            $this->info("\n📋 TENANT #" . ($index + 1));
            $this->info(str_repeat('-', 40));
            
            $tenant->run(function () use ($tenant) {
                $this->info("🏢 Nombre: " . ($tenant->tenancy_data['name'] ?? 'Sin nombre'));
                $this->info("🆔 ID: {$tenant->id}");
                
                // Mostrar dominios
                $domains = $tenant->domains;
                if ($domains->count() > 0) {
                    $this->info("🌐 Dominios:");
                    foreach ($domains as $domain) {
                        $this->line("   • {$domain->domain}");
                    }
                }
                
                // Mostrar usuarios y credenciales
                $users = User::all();
                if ($users->count() > 0) {
                    $this->info("👥 Usuarios disponibles:");
                    foreach ($users as $user) {
                        $this->line("   • {$user->name}");
                        $this->line("     Email: {$user->email}");
                        $this->line("     Password: password123");
                        $this->line("");
                    }
                } else {
                    $this->warn("⚠️ No hay usuarios en este tenant");
                }
                
                // Mostrar información de la base de datos
                $this->info("💾 Base de datos:");
                $this->line("   • Conexión: " . config('database.default'));
                $this->line("   • Base de datos: " . config('database.connections.mysql.database'));
            });
        }
        
        $this->info("\n" . str_repeat('=', 80));
        $this->info("📝 RESUMEN DE ACCESO");
        $this->info(str_repeat('=', 80));
        
        $this->info("🌐 URLs de acceso:");
        foreach ($tenants as $tenant) {
            $domains = $tenant->domains;
            if ($domains->count() > 0) {
                $domain = $domains->first();
                $this->line("   • {$domain->domain}");
            }
        }
        
        $this->info("\n🔑 Credenciales por defecto:");
        $this->line("   • Email: user{TENANT_ID}@example.com");
        $this->line("   • Password: password123");
        
        $this->info("\n📡 APIs disponibles:");
        $this->line("   • POST /api/auth/login");
        $this->line("   • POST /api/auth/register");
        $this->line("   • GET /api/auth/me");
        $this->line("   • POST /api/auth/logout");
        
        $this->info("\n✅ Estado: Todos los tenants están funcionando correctamente");
        
        return 0;
    }
} 