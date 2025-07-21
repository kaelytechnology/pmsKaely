<?php

namespace App;

use Stancl\Tenancy\TenantDatabaseManagers\MySQLDatabaseManager;
use Stancl\Tenancy\Contracts\TenantWithDatabase;

class TenantDatabaseManager extends MySQLDatabaseManager
{
    public function getDatabaseName(TenantWithDatabase $tenant): string
    {
        // Obtener el nombre del tenant desde tenancy_data
        $tenantName = $tenant->tenancy_data['name'] ?? 'unknown';
        
        // Limpiar el nombre para que sea válido como nombre de base de datos
        $cleanName = $this->cleanDatabaseName($tenantName);
        
        // Obtener el dominio principal si existe
        $domain = $tenant->domains->first();
        $domainName = 'nodomain';
        
        if ($domain) {
            // Extraer solo el subdominio o nombre del dominio
            $domainParts = explode('.', $domain->domain);
            if (count($domainParts) >= 2) {
                $domainName = $this->cleanDatabaseName($domainParts[0]);
            } else {
                $domainName = $this->cleanDatabaseName($domain->domain);
            }
        }
        
        // Formato: tenant_namedomain
        return "tenant_{$cleanName}_{$domainName}";
    }
    
    private function cleanDatabaseName($name): string
    {
        // Convertir a minúsculas
        $name = strtolower($name);
        
        // Reemplazar espacios y caracteres especiales con guiones bajos
        $name = preg_replace('/[^a-z0-9]/', '_', $name);
        
        // Eliminar múltiples guiones bajos consecutivos
        $name = preg_replace('/_+/', '_', $name);
        
        // Eliminar guiones bajos al inicio y final
        $name = trim($name, '_');
        
        // Limitar la longitud
        if (strlen($name) > 30) {
            $name = substr($name, 0, 30);
        }
        
        return $name;
    }
} 