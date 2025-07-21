<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Tenant;

class TestApiLogin extends Command
{
    protected $signature = 'test:api-login {domain} {email} {password}';
    protected $description = 'Test API login for a specific domain';

    public function handle()
    {
        $domain = $this->argument('domain');
        $email = $this->argument('email');
        $password = $this->argument('password');
        
        $this->info("=== PROBANDO LOGIN API ===");
        $this->info("Dominio: {$domain}");
        $this->info("Email: {$email}");
        $this->info("Password: {$password}");
        
        // Buscar el tenant por dominio
        $tenant = Tenant::whereHas('domains', function ($query) use ($domain) {
            $query->where('domain', $domain);
        })->first();
        
        if (!$tenant) {
            $this->error("❌ No se encontró tenant para el dominio: {$domain}");
            return 1;
        }
        
        $this->info("✅ Tenant encontrado: {$tenant->id}");
        
        // Ejecutar en el contexto del tenant
        $tenant->run(function () use ($email, $password, $domain) {
            $this->info("🔍 Verificando usuario en tenant...");
            
            // Buscar el usuario
            $user = \App\Models\User::where('email', $email)->first();
            
            if (!$user) {
                $this->error("❌ Usuario no encontrado: {$email}");
                return;
            }
            
            $this->info("✅ Usuario encontrado: {$user->name}");
            
            // Probar autenticación
            $credentials = [
                'email' => $email,
                'password' => $password
            ];
            
            if (auth()->attempt($credentials)) {
                $this->info("✅ Autenticación exitosa");
                
                // Generar token
                $token = $user->createToken('test-token')->plainTextToken;
                $this->info("🔑 Token generado: " . substr($token, 0, 50) . "...");
                
            } else {
                $this->error("❌ Autenticación fallida");
            }
        });
        
        // Ahora probar la API HTTP
        $this->info("\n🌐 Probando API HTTP...");
        
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Host' => $domain
            ])->post('http://localhost:8000/api/auth/login', [
                'email' => $email,
                'password' => $password
            ]);
            
            $this->info("📊 Status Code: " . $response->status());
            $this->info("📄 Response: " . $response->body());
            
            if ($response->successful()) {
                $this->info("✅ API HTTP exitosa");
            } else {
                $this->error("❌ API HTTP fallida");
            }
            
        } catch (\Exception $e) {
            $this->error("❌ Error en API HTTP: " . $e->getMessage());
        }
        
        return 0;
    }
} 