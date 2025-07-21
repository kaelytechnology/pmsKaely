<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tenant;
use App\Models\User;

class TestDomainTenants extends Command
{
    protected $signature = 'test:domain-tenants';
    protected $description = 'Test tenants with full domains';

    public function handle()
    {
        $this->info('=== PROBANDO TENANTS CON DOMINIOS COMPLETOS ===');
        
        $tenants = Tenant::all();
        
        foreach ($tenants as $tenant) {
            $domains = $tenant->domains;
            
            // Solo probar tenants con dominios completos (que no contengan .kaelytechnology.test)
            $hasFullDomain = $domains->contains(function ($domain) {
                return !str_contains($domain->domain, 'kaelytechnology.test');
            });
            
            if (!$hasFullDomain) {
                continue;
            }
            
            $this->info("\n" . str_repeat('=', 60));
            $this->info("TENANT: {$tenant->id}");
            $this->info(str_repeat('=', 60));
            
            $tenant->run(function () use ($tenant) {
                $this->info("🏢 Nombre: " . ($tenant->tenancy_data['name'] ?? 'Sin nombre'));
                
                // Mostrar dominios
                $domains = $tenant->domains;
                $this->info("🌐 Dominios:");
                foreach ($domains as $domain) {
                    $this->line("   • {$domain->domain}");
                }
                
                // Buscar usuarios
                $users = User::all();
                $this->info("👥 Usuarios encontrados: " . $users->count());
                
                foreach ($users as $user) {
                    $this->line("   • {$user->name} ({$user->email})");
                    
                    // Probar login
                    $credentials = [
                        'email' => $user->email,
                        'password' => 'password123'
                    ];
                    
                    if (auth()->attempt($credentials)) {
                        $this->info("✅ Login exitoso para {$user->email}");
                        
                        // Generar token
                        try {
                            $token = $user->createToken('test-token')->plainTextToken;
                            $this->info("🔑 Token generado: " . substr($token, 0, 50) . "...");
                        } catch (\Exception $e) {
                            $this->error("❌ Error generando token: " . $e->getMessage());
                        }
                        
                    } else {
                        $this->error("❌ Login fallido para {$user->email}");
                    }
                }
                
                // Mostrar nombre de la base de datos
                $databaseManager = new \App\TenantDatabaseManager();
                $databaseName = $databaseManager->getDatabaseName($tenant);
                $this->info("💾 Base de datos: {$databaseName}");
            });
        }
        
        $this->info("\n=== PRUEBA COMPLETADA ===");
        
        return 0;
    }
} 