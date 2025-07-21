# 🛠️ Comandos Artisan - CMS Multitenant Laravel

## 📋 Índice
1. [Comandos de Gestión de Tenants](#comandos-de-gestión-de-tenants)
2. [Comandos de Pruebas](#comandos-de-pruebas)
3. [Comandos de Base de Datos](#comandos-de-base-de-datos)
4. [Comandos de Información](#comandos-de-información)
5. [Comandos de Laravel Estándar](#comandos-de-laravel-estándar)

---

## 🏢 Comandos de Gestión de Tenants

### Crear Tenants

#### `tenant:create-named`
Crea un nuevo tenant con subdominio.

```bash
php artisan tenant:create-named "Nombre Empresa" "subdominio"
```

**Parámetros:**
- `name` (requerido): Nombre de la empresa
- `subdomain` (requerido): Subdominio (ej: "miempresa")

**Ejemplo:**
```bash
php artisan tenant:create-named "Mi Empresa" "miempresa"
# Resultado: miempresa.kaelytechnology.test
```

#### `tenant:create-domain`
Crea un nuevo tenant con dominio completo.

```bash
php artisan tenant:create-domain "Nombre Empresa" "dominio.com"
```

**Parámetros:**
- `name` (requerido): Nombre de la empresa
- `domain` (requerido): Dominio completo (ej: "empresa.com")

**Ejemplo:**
```bash
php artisan tenant:create-domain "Empresa Global" "empresaglobal.com"
# Resultado: empresaglobal.com
```

### Información de Tenants

#### `tenant:show-credentials`
Muestra las credenciales de todos los tenants.

```bash
php artisan tenant:show-credentials
```

**Salida:**
```
🔐 CREDENCIALES DE TODOS LOS TENANTS
================================================================================

📋 TENANT #1
----------------------------------------
🏢 Nombre: Kaely
🆔 ID: 687db17b8c62e
🌐 Dominios:
   • kaelytechnology.kaelytechnology.test
👥 Usuarios disponibles:
   • Usuario 687db17b8c62e
     Email: user687db17b8c62e@example.com
     Password: password123
```

#### `tenant:show-types`
Muestra la diferencia entre tenants con subdominios y dominios completos.

```bash
php artisan tenant:show-types
```

**Salida:**
```
🌐 TIPOS DE TENANTS - SUBDOMINIOS vs DOMINIOS COMPLETOS
================================================================================

📋 TENANTS CON SUBDOMINIOS (3)
--------------------------------------------------
Tenant #1
   🏢 Nombre: Kaely
   🆔 ID: 687db17b8c62e
   🌐 Dominio: kaelytechnology.kaelytechnology.test
   👤 Usuario: user687db17b8c62e@example.com
   💾 BD: tenant_kaely_kaelytechnology
```

---

## 🧪 Comandos de Pruebas

### Pruebas de Autenticación

#### `test:all-tenant-logins`
Prueba el login en todos los tenants existentes.

```bash
php artisan test:all-tenant-logins
```

**Características:**
- Prueba login en todos los tenants
- Genera tokens API
- Verifica autenticación
- Muestra información de base de datos

**Salida:**
```
=== PRUEBA COMPLETA DE LOGIN EN TODOS LOS TENANTS ===
Total de tenants encontrados: 5

============================================================
TENANT #1: 687db17b8c62e
============================================================
🏢 Nombre del tenant: Kaely
🌐 Dominios asociados:
   - kaelytechnology.kaelytechnology.test
👥 Usuarios en este tenant: 1
   - Usuario 687db17b8c62e (user687db17b8c62e@example.com)

🔐 Probando login con: user687db17b8c62e@example.com
✅ Login exitoso!
   Usuario autenticado: Usuario 687db17b8c62e
   ID del usuario: 1
   Email: user687db17b8c62e@example.com
🔑 Token API generado: 5|6d97k1fVNzKQStDRhVUzOvzOHVOzBu0oz7aJDdFa3c718c54...
```

#### `test:auth-routes`
Prueba las rutas del paquete de autenticación.

```bash
php artisan test:auth-routes
```

**Características:**
- Prueba login via API
- Verifica generación de tokens
- Prueba obtención de usuario actual
- Valida respuestas JSON

**Salida:**
```
=== PROBANDO RUTAS DEL PAQUETE AUTH ===

=== TENANT: 687db17b8c62e ===
Ejecutando en tenant: 687db17b8c62e
Usuario encontrado: Usuario 687db17b8c62e (user687db17b8c62e@example.com)
Probando login via API...
✅ Login API exitoso
Token: 6|6V0miFZ2Qz6XiedCN1i6FlIZsKchPhOIG4VbsnmS57f5f56e...
Probando obtener usuario actual...
✅ Usuario actual obtenido: Usuario 687db17b8c62e
```

#### `test:domain-tenants`
Prueba específicamente los tenants con dominios completos.

```bash
php artisan test:domain-tenants
```

**Características:**
- Solo prueba tenants con dominios completos
- Verifica login y generación de tokens
- Muestra información de base de datos

**Salida:**
```
=== PROBANDO TENANTS CON DOMINIOS COMPLETOS ===

============================================================
TENANT: 687dc3bf931cd
============================================================
🏢 Nombre: Empresa Global
🌐 Dominios:
   • empresaglobal.com
👥 Usuarios encontrados: 1
   • Usuario Empresa Global (admin@empresaglobal.com)
✅ Login exitoso para admin@empresaglobal.com
🔑 Token generado: 1|W8DIkrVmgKIG6N4YlVydxN2474G9RQQuMCzMMk9a1dbb4c4f...
💾 Base de datos: tenant_empresa_global_empresaglobal
```

---

## 💾 Comandos de Base de Datos

### Gestión de Bases de Datos

#### `tenant:list-databases`
Lista todas las bases de datos de tenants en MySQL.

```bash
php artisan tenant:list-databases
```

**Características:**
- Conecta a MySQL
- Lista bases de datos con prefijo "tenant"
- Verifica nombres esperados
- Muestra estadísticas

**Salida:**
```
🔍 BUSCANDO BASES DE DATOS DE TENANTS EN MYSQL
================================================================================

📊 BASES DE DATOS ENCONTRADAS:
   • tenant_kaely_kaelytechnology
   • tenant_tenant_1_tenant1
   • tenant_mi_empresa_miempresa
   • tenant_empresa_global_empresaglobal
   • tenant_tech_solutions_techsolutions

📈 ESTADÍSTICAS:
   • Total de bases de datos: 5
   • Bases de datos esperadas: 5
   • Coincidencias: 5/5 ✅
```

#### `tenant:migrate-database-names`
Migra los nombres de bases de datos existentes al nuevo formato.

```bash
php artisan tenant:migrate-database-names
```

**Características:**
- Detecta nombres antiguos
- Propone nuevos nombres
- Solicita confirmación
- Ejecuta renombrado seguro

**Salida:**
```
🔄 MIGRACIÓN DE NOMBRES DE BASES DE DATOS
================================================================================

📋 BASES DE DATOS A MIGRAR:
   1. tenant687db17b8c62e → tenant_kaely_kaelytechnology
   2. tenanttenant1 → tenant_tenant_1_tenant1
   3. tenant687dc24f975d7 → tenant_mi_empresa_miempresa

⚠️  ADVERTENCIA: Esta operación es irreversible.
¿Deseas continuar? (yes/no) [no]:
```

---

## 📊 Comandos de Información

### Información del Sistema

#### `tenant:database-names`
Prueba el nuevo formato de nombres de bases de datos.

```bash
php artisan tenant:database-names
```

**Características:**
- Prueba el TenantDatabaseManager
- Genera nombres de ejemplo
- Verifica formato correcto

**Salida:**
```
🧪 PROBANDO NUEVO FORMATO DE NOMBRES DE BASES DE DATOS
================================================================================

📋 EJEMPLOS DE NOMBRES GENERADOS:

🏢 Tenant: Kaely Technology
   🌐 Dominio: kaelytechnology.kaelytechnology.test
   💾 Base de datos: tenant_kaely_technology_kaelytechnology

🏢 Tenant: Mi Empresa
   🌐 Dominio: miempresa.kaelytechnology.test
   💾 Base de datos: tenant_mi_empresa_miempresa

🏢 Tenant: Empresa Global
   🌐 Dominio: empresaglobal.com
   💾 Base de datos: tenant_empresa_global_empresaglobal

✅ Formato de nombres funcionando correctamente
```

---

## 🔧 Comandos de Laravel Estándar

### Gestión de Tenants (stancl/tenancy)

#### `tenants:list`
Lista todos los tenants registrados.

```bash
php artisan tenants:list
```

#### `tenants:run`
Ejecuta un comando en todos los tenants.

```bash
php artisan tenants:run "migrate"
php artisan tenants:run "db:seed"
php artisan tenants:run "tinker"
```

#### `tenants:run --tenants=id1,id2`
Ejecuta un comando en tenants específicos.

```bash
php artisan tenants:run --tenants=687db17b8c62e,tenant1 "migrate"
```

### Migraciones

#### `migrate`
Ejecuta migraciones en la base de datos central.

```bash
php artisan migrate
```

#### `migrate:fresh`
Refresca la base de datos central.

```bash
php artisan migrate:fresh
```

#### `migrate:rollback`
Revierte la última migración.

```bash
php artisan migrate:rollback
```

### Seeders

#### `db:seed`
Ejecuta seeders en la base de datos central.

```bash
php artisan db:seed
```

#### `db:seed --class=AuthPackageSeeder`
Ejecuta un seeder específico.

```bash
php artisan db:seed --class=AuthPackageSeeder
```

### Cache y Configuración

#### `config:cache`
Cachea la configuración.

```bash
php artisan config:cache
```

#### `config:clear`
Limpia el cache de configuración.

```bash
php artisan config:clear
```

#### `cache:clear`
Limpia todo el cache.

```bash
php artisan cache:clear
```

#### `route:clear`
Limpia el cache de rutas.

```bash
php artisan route:clear
```

### Desarrollo

#### `serve`
Inicia el servidor de desarrollo.

```bash
php artisan serve
```

#### `serve --host=0.0.0.0 --port=8000`
Inicia el servidor en una IP específica.

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

#### `tinker`
Abre la consola interactiva de Laravel.

```bash
php artisan tinker
```

---

## 🎯 Ejemplos de Uso

### Flujo Completo de Creación de Tenant

```bash
# 1. Crear tenant con subdominio
php artisan tenant:create-named "Nueva Empresa" "nuevaempresa"

# 2. Verificar que se creó correctamente
php artisan tenant:show-credentials

# 3. Probar login
php artisan test:all-tenant-logins

# 4. Verificar base de datos
php artisan tenant:list-databases
```

### Flujo de Pruebas

```bash
# 1. Probar todos los tenants
php artisan test:all-tenant-logins

# 2. Probar rutas específicas
php artisan test:auth-routes

# 3. Probar tenants con dominios completos
php artisan test:domain-tenants

# 4. Mostrar información
php artisan tenant:show-types
```

### Gestión de Base de Datos

```bash
# 1. Listar bases de datos
php artisan tenant:list-databases

# 2. Ejecutar migraciones en todos los tenants
php artisan tenants:run "migrate"

# 3. Ejecutar seeders en todos los tenants
php artisan tenants:run "db:seed"

# 4. Verificar estado
php artisan tenants:list
```

---

## ⚠️ Comandos Peligrosos

### Comandos que Requieren Confirmación

#### `migrate:fresh`
⚠️ **PELIGROSO**: Elimina todas las tablas y las recrea.

```bash
php artisan migrate:fresh
```

#### `tenant:migrate-database-names`
⚠️ **PELIGROSO**: Renombra bases de datos existentes.

```bash
php artisan tenant:migrate-database-names
```

### Comandos de Limpieza

#### `cache:clear`
Limpia todo el cache del sistema.

```bash
php artisan cache:clear
```

#### `config:clear`
Limpia el cache de configuración.

```bash
php artisan config:clear
```

---

## 🔍 Comandos de Diagnóstico

### Verificar Estado del Sistema

```bash
# Ver todos los comandos disponibles
php artisan list

# Ver ayuda de un comando específico
php artisan tenant:create-domain --help

# Verificar estado de tenants
php artisan tenants:list

# Verificar conexiones de base de datos
php artisan tenants:run "db:show"
```

### Logs y Debugging

```bash
# Ver logs en tiempo real
tail -f storage/logs/laravel.log

# Limpiar logs
php artisan log:clear

# Verificar permisos de archivos
ls -la storage/logs/
```

---

## 📝 Notas Importantes

### Orden de Ejecución Recomendado

1. **Configuración inicial**: `migrate`, `db:seed`
2. **Crear tenants**: `tenant:create-named` o `tenant:create-domain`
3. **Verificar creación**: `tenant:show-credentials`
4. **Probar funcionalidad**: `test:all-tenant-logins`
5. **Mantenimiento**: `tenants:run "migrate"`

### Variables de Entorno Requeridas

Asegúrate de tener configuradas estas variables:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cms_multitenant
DB_USERNAME=root
DB_PASSWORD=
```

### Permisos de Archivos

Verifica que estos directorios tengan permisos de escritura:
- `storage/logs/`
- `storage/framework/cache/`
- `storage/framework/sessions/`
- `storage/framework/views/`

---

## 🆘 Comandos de Emergencia

### Reset Completo del Sistema

```bash
# ⚠️ PELIGROSO: Elimina todo
php artisan migrate:fresh --seed
php artisan tenants:run "migrate:fresh"
```

### Recuperación de Errores

```bash
# Limpiar cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Verificar estado
php artisan tenants:list
php artisan tenant:show-credentials
```

---

**Última actualización**: Julio 2024  
**Versión**: 1.0.0 