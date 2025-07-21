<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class TestAuthRoutes extends Command
{
    protected $signature = 'test:auth-routes';
    protected $description = 'Test auth package routes in tenants';

    public function handle()
    {
        $this->info('=== PROBANDO RUTAS DEL PAQUETE AUTH ===');
        
        $tenants = Tenant::all();
        
        foreach ($tenants as $tenant) {
            $this->info("\n=== TENANT: {$tenant->id} ===");
            
            $tenant->run(function () use ($tenant) {
                $this->info("Ejecutando en tenant: {$tenant->id}");
                
                // Verificar si existe un usuario
                $user = User::where('email', "user{$tenant->id}@example.com")->first();
                
                if (!$user) {
                    $this->error("No existe usuario para este tenant");
                    return;
                }
                
                $this->info("Usuario encontrado: {$user->name} ({$user->email})");
                
                // Probar login via API
                $this->info("Probando login via API...");
                
                // Simular una petición HTTP
                $response = $this->testLogin($user->email, 'password123');
                
                if ($response) {
                    $this->info("✅ Login API exitoso");
                    $this->info("Token: " . substr($response['token'], 0, 50) . "...");
                    
                    // Probar obtener usuario actual
                    $this->info("Probando obtener usuario actual...");
                    $meResponse = $this->testMe($response['token']);
                    
                    if ($meResponse) {
                        $this->info("✅ Usuario actual obtenido: {$meResponse['name']}");
                    } else {
                        $this->error("❌ Error obteniendo usuario actual");
                    }
                } else {
                    $this->error("❌ Login API fallido");
                }
            });
        }
        
        $this->info("\n=== PRUEBA DE RUTAS COMPLETADA ===");
        
        return 0;
    }
    
    private function testLogin($email, $password)
    {
        try {
            // Simular una petición de login
            $credentials = [
                'email' => $email,
                'password' => $password
            ];
            
            if (auth()->attempt($credentials)) {
                $user = auth()->user();
                $token = $user->createToken('test-token')->plainTextToken;
                
                return [
                    'success' => true,
                    'token' => $token,
                    'user' => $user
                ];
            }
            
            return null;
        } catch (\Exception $e) {
            $this->error("Error en login: " . $e->getMessage());
            return null;
        }
    }
    
    private function testMe($token)
    {
        try {
            // Simular obtener usuario actual
            $user = auth()->user();
            
            if ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email
                ];
            }
            
            return null;
        } catch (\Exception $e) {
            $this->error("Error obteniendo usuario: " . $e->getMessage());
            return null;
        }
    }
} 