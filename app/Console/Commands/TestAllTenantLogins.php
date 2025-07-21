<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TestAllTenantLogins extends Command
{
    protected $signature = 'test:all-tenant-logins';
    protected $description = 'Test login in all existing tenants with detailed information';

    public function handle()
    {
        $this->info('=== PRUEBA COMPLETA DE LOGIN EN TODOS LOS TENANTS ===');
        
        $tenants = Tenant::all();
        $this->info("Total de tenants encontrados: " . $tenants->count());
        
        $successCount = 0;
        $totalCount = 0;
        
        foreach ($tenants as $tenant) {
            $totalCount++;
            $this->info("\n" . str_repeat('=', 60));
            $this->info("TENANT #{$totalCount}: {$tenant->id}");
            $this->info(str_repeat('=', 60));
            
            $tenant->run(function () use ($tenant, &$successCount) {
                $this->info("🏢 Nombre del tenant: " . ($tenant->tenancy_data['name'] ?? 'Sin nombre'));
                
                // Mostrar dominios
                $domains = $tenant->domains;
                if ($domains->count() > 0) {
                    $this->info("🌐 Dominios asociados:");
                    foreach ($domains as $domain) {
                        $this->line("   - {$domain->domain}");
                    }
                } else {
                    $this->warn("⚠️ No hay dominios asociados");
                }
                
                // Verificar usuarios existentes
                $users = User::all();
                $this->info("👥 Usuarios en este tenant: " . $users->count());
                
                if ($users->count() > 0) {
                    foreach ($users as $user) {
                        $this->line("   - {$user->name} ({$user->email})");
                    }
                    
                    // Probar login con el primer usuario
                    $testUser = $users->first();
                    $this->info("\n🔐 Probando login con: {$testUser->email}");
                    
                    $credentials = [
                        'email' => $testUser->email,
                        'password' => 'password123'
                    ];
                    
                    if (auth()->attempt($credentials)) {
                        $this->info("✅ Login exitoso!");
                        $this->info("   Usuario autenticado: " . auth()->user()->name);
                        $this->info("   ID del usuario: " . auth()->user()->id);
                        $this->info("   Email: " . auth()->user()->email);
                        
                        // Generar token de API
                        try {
                            $token = $testUser->createToken('test-token')->plainTextToken;
                            $this->info("🔑 Token API generado: " . substr($token, 0, 50) . "...");
                        } catch (\Exception $e) {
                            $this->error("❌ Error generando token: " . $e->getMessage());
                        }
                        
                        $successCount++;
                        
                    } else {
                        $this->error("❌ Login fallido");
                        $this->error("   Verificar credenciales para: {$testUser->email}");
                    }
                    
                } else {
                    $this->warn("⚠️ No hay usuarios en este tenant");
                    
                    // Crear un usuario de prueba
                    $this->info("📝 Creando usuario de prueba...");
                    $newUser = User::create([
                        'name' => "Usuario {$tenant->id}",
                        'email' => "user{$tenant->id}@example.com",
                        'password' => Hash::make('password123'),
                    ]);
                    
                    $this->info("✅ Usuario creado: {$newUser->name} ({$newUser->email})");
                    
                    // Probar login con el nuevo usuario
                    $credentials = [
                        'email' => $newUser->email,
                        'password' => 'password123'
                    ];
                    
                    if (auth()->attempt($credentials)) {
                        $this->info("✅ Login exitoso con nuevo usuario!");
                        $successCount++;
                    } else {
                        $this->error("❌ Login fallido con nuevo usuario");
                    }
                }
                
                // Mostrar información de la base de datos
                $this->info("\n💾 Información de la base de datos:");
                $this->line("   Conexión: " . config('database.default'));
                $this->line("   Base de datos: " . config('database.connections.mysql.database'));
                
            });
        }
        
        $this->info("\n" . str_repeat('=', 60));
        $this->info("RESUMEN FINAL");
        $this->info(str_repeat('=', 60));
        $this->info("Total de tenants probados: {$totalCount}");
        $this->info("Logins exitosos: {$successCount}");
        $this->info("Logins fallidos: " . ($totalCount - $successCount));
        
        if ($successCount === $totalCount) {
            $this->info("🎉 ¡TODOS LOS TENANTS FUNCIONAN CORRECTAMENTE!");
        } else {
            $this->warn("⚠️ Algunos tenants tienen problemas");
        }
        
        return 0;
    }
} 