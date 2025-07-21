# ⚙️ Guía de Configuración - CMS Multitenant Laravel

## 📋 Índice
1. [Requisitos del Sistema](#requisitos-del-sistema)
2. [Instalación Inicial](#instalación-inicial)
3. [Configuración de Base de Datos](#configuración-de-base-de-datos)
4. [Configuración de Dominios](#configuración-de-dominios)
5. [Configuración de Entorno](#configuración-de-entorno)
6. [Configuración de Seguridad](#configuración-de-seguridad)
7. [Configuración de Producción](#configuración-de-producción)

---

## 🖥️ Requisitos del Sistema

### Requisitos Mínimos
- **PHP**: 8.1 o superior
- **MySQL**: 5.7 o superior (o MariaDB 10.2+)
- **Composer**: 2.0 o superior
- **Node.js**: 16.0 o superior (para Vite)
- **RAM**: 2GB mínimo, 4GB recomendado
- **Espacio**: 1GB libre mínimo

### Extensiones PHP Requeridas
```bash
# Verificar extensiones instaladas
php -m | grep -E "(bcmath|ctype|fileinfo|json|mbstring|openssl|pdo|tokenizer|xml)"
```

**Extensiones necesarias:**
- `bcmath`
- `ctype`
- `fileinfo`
- `json`
- `mbstring`
- `openssl`
- `pdo`
- `tokenizer`
- `xml`

### Verificación de Requisitos
```bash
# Verificar versión de PHP
php -v

# Verificar Composer
composer --version

# Verificar MySQL
mysql --version

# Verificar Node.js
node --version
npm --version
```

---

## 🚀 Instalación Inicial

### 1. Clonar el Proyecto
```bash
# Clonar desde Git
git clone <repository-url>
cd cms-multitenat

# O descargar y extraer
# Luego navegar al directorio
```

### 2. Instalar Dependencias
```bash
# Instalar dependencias de PHP
composer install

# Instalar dependencias de Node.js (opcional)
npm install
```

### 3. Configurar Variables de Entorno
```bash
# Copiar archivo de configuración
cp .env.example .env

# Generar clave de aplicación
php artisan key:generate
```

### 4. Configurar Base de Datos
```bash
# Editar .env con tus credenciales
nano .env

# Ejecutar migraciones centrales
php artisan migrate

# Ejecutar seeders
php artisan db:seed
```

---

## 🗄️ Configuración de Base de Datos

### Configuración MySQL

#### 1. Crear Base de Datos Central
```sql
-- Conectar a MySQL
mysql -u root -p

-- Crear base de datos central
CREATE DATABASE cms_multitenant CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Crear usuario (opcional)
CREATE USER 'cms_user'@'localhost' IDENTIFIED BY 'tu_password';
GRANT ALL PRIVILEGES ON cms_multitenant.* TO 'cms_user'@'localhost';
FLUSH PRIVILEGES;
```

#### 2. Configurar Variables de Entorno
```env
# Configuración de base de datos central
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cms_multitenant
DB_USERNAME=root
DB_PASSWORD=tu_password

# Configuración de tenants (se genera automáticamente)
TENANT_DB_PREFIX=tenant
```

#### 3. Verificar Conexión
```bash
# Probar conexión
php artisan tinker
>>> DB::connection()->getPdo()
# Debería mostrar información de conexión

# Verificar tablas
php artisan migrate:status
```

### Configuración de Tenants

#### 1. Ejecutar Migraciones de Tenants
```bash
# Crear tenant de prueba
php artisan tenant:create-named "Test" "test"

# Verificar que se creó la base de datos
php artisan tenant:list-databases
```

#### 2. Verificar Aislamiento
```bash
# Probar aislamiento entre tenants
php artisan test:all-tenant-logins
```

---

## 🌐 Configuración de Dominios

### Configuración Local (Desarrollo)

#### 1. Configurar Hosts
**Windows** (`C:\Windows\System32\drivers\etc\hosts`):
```
127.0.0.1 kaelytechnology.test
127.0.0.1 kaelytechnology.kaelytechnology.test
127.0.0.1 tenant1.kaelytechnology.test
127.0.0.1 miempresa.kaelytechnology.test
127.0.0.1 empresaglobal.com
127.0.0.1 techsolutions.net
```

**Linux/Mac** (`/etc/hosts`):
```bash
# Editar archivo hosts
sudo nano /etc/hosts

# Agregar líneas
127.0.0.1 kaelytechnology.test
127.0.0.1 kaelytechnology.kaelytechnology.test
127.0.0.1 tenant1.kaelytechnology.test
127.0.0.1 miempresa.kaelytechnology.test
127.0.0.1 empresaglobal.com
127.0.0.1 techsolutions.net
```

#### 2. Configurar Variables de Entorno
```env
# Dominios permitidos para Sanctum
SANCTUM_STATEFUL_DOMAINS=kaelytechnology.test,empresaglobal.com,techsolutions.net

# Dominio de sesión
SESSION_DOMAIN=.kaelytechnology.test

# URL de la aplicación
APP_URL=http://kaelytechnology.test:8000
```

### Configuración de Producción

#### 1. Configurar DNS
```bash
# Para subdominios
*.tudominio.com → IP_DEL_SERVIDOR

# Para dominios completos
cliente1.com → IP_DEL_SERVIDOR
cliente2.com → IP_DEL_SERVIDOR
```

#### 2. Configurar SSL
```bash
# Instalar Certbot
sudo apt install certbot python3-certbot-nginx

# Generar certificados
sudo certbot --nginx -d tudominio.com -d *.tudominio.com
```

---

## 🔧 Configuración de Entorno

### Variables de Entorno Completas

#### Archivo `.env` Completo
```env
# Configuración de la aplicación
APP_NAME="CMS Multitenant"
APP_ENV=local
APP_KEY=base64:tu_clave_generada
APP_DEBUG=true
APP_URL=http://kaelytechnology.test:8000

# Configuración de base de datos central
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cms_multitenant
DB_USERNAME=root
DB_PASSWORD=tu_password

# Configuración de cache
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

# Configuración de Sanctum
SANCTUM_STATEFUL_DOMAINS=kaelytechnology.test,empresaglobal.com,techsolutions.net
SESSION_DOMAIN=.kaelytechnology.test

# Configuración de correo (opcional)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

# Configuración de logging
LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

# Configuración de tenancy
TENANT_DB_PREFIX=tenant
```

### Configuración por Entorno

#### Desarrollo (`APP_ENV=local`)
```env
APP_DEBUG=true
LOG_LEVEL=debug
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
```

#### Producción (`APP_ENV=production`)
```env
APP_DEBUG=false
LOG_LEVEL=error
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
```

---

## 🔒 Configuración de Seguridad

### Configuración de Sanctum

#### 1. Configurar Dominios Permitidos
```env
# En .env
SANCTUM_STATEFUL_DOMAINS=kaelytechnology.test,empresaglobal.com,techsolutions.net
```

#### 2. Configurar Middleware
```php
// config/sanctum.php
'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', sprintf(
    '%s%s',
    'localhost,localhost:3000,127.0.0.1,127.0.0.1:8000,::1',
    env('APP_URL') ? ','.parse_url(env('APP_URL'), PHP_URL_HOST) : ''
))),
```

### Configuración de CORS

#### 1. Configurar Dominios Permitidos
```php
// config/cors.php
'allowed_origins' => [
    'http://kaelytechnology.test:8000',
    'http://empresaglobal.com:8000',
    'http://techsolutions.net:8000',
],
```

#### 2. Configurar Headers Permitidos
```php
'allowed_headers' => [
    'Content-Type',
    'X-Requested-With',
    'Authorization',
    'Accept',
],
```

### Configuración de Rate Limiting

#### 1. Configurar Límites por Endpoint
```php
// app/Http/Kernel.php
protected $middlewareGroups = [
    'api' => [
        \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        'throttle:api',
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
    ],
];
```

#### 2. Configurar Límites Específicos
```php
// routes/api.php
Route::middleware(['throttle:6,1'])->group(function () {
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::post('/auth/register', [AuthController::class, 'register']);
});
```

---

## 🚀 Configuración de Producción

### Configuración del Servidor Web

#### 1. Configuración Nginx
```nginx
# /etc/nginx/sites-available/cms-multitenant
server {
    listen 80;
    server_name kaelytechnology.test *.kaelytechnology.test;
    root /var/www/cms-multitenat/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

#### 2. Configuración Apache (.htaccess)
```apache
# public/.htaccess
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

### Configuración de Optimización

#### 1. Cache de Configuración
```bash
# Cachear configuración
php artisan config:cache

# Cachear rutas
php artisan route:cache

# Cachear vistas
php artisan view:cache
```

#### 2. Optimización de Composer
```bash
# Optimizar autoloader
composer install --optimize-autoloader --no-dev

# Generar archivo de clases
php artisan optimize
```

### Configuración de Monitoreo

#### 1. Configurar Logs
```env
# En .env
LOG_CHANNEL=daily
LOG_LEVEL=error
```

#### 2. Configurar Supervisord (para colas)
```ini
# /etc/supervisor/conf.d/laravel-worker.conf
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/cms-multitenat/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=8
redirect_stderr=true
stdout_logfile=/var/www/cms-multitenat/storage/logs/worker.log
stopwaitsecs=3600
```

---

## 🔍 Verificación de Configuración

### Comandos de Verificación

#### 1. Verificar Configuración General
```bash
# Verificar configuración
php artisan config:show

# Verificar rutas
php artisan route:list

# Verificar cache
php artisan cache:table
```

#### 2. Verificar Tenants
```bash
# Listar tenants
php artisan tenants:list

# Verificar credenciales
php artisan tenant:show-credentials

# Probar logins
php artisan test:all-tenant-logins
```

#### 3. Verificar Base de Datos
```bash
# Verificar conexión central
php artisan db:show

# Verificar bases de datos de tenants
php artisan tenant:list-databases

# Probar migraciones
php artisan tenants:run "migrate:status"
```

### Pruebas de Funcionalidad

#### 1. Pruebas de API
```bash
# Probar rutas de autenticación
php artisan test:auth-routes

# Probar tenants con dominios completos
php artisan test:domain-tenants
```

#### 2. Pruebas de Aislamiento
```bash
# Verificar que los tenants están aislados
php artisan tenants:run "tinker --execute='echo User::count();'"
```

---

## 🚨 Solución de Problemas

### Problemas Comunes

#### Error: "Database connection failed"
```bash
# Verificar configuración de base de datos
php artisan config:show database

# Verificar conexión
php artisan tinker
>>> DB::connection()->getPdo()
```

#### Error: "Tenant not found"
```bash
# Verificar tenants existentes
php artisan tenants:list

# Verificar dominios
php artisan tenant:show-credentials
```

#### Error: "Token not found"
```bash
# Verificar tablas de Sanctum
php artisan tenants:run "migrate"

# Verificar configuración de Sanctum
php artisan config:show sanctum
```

### Logs de Error

#### Verificar Logs
```bash
# Ver logs en tiempo real
tail -f storage/logs/laravel.log

# Ver logs de errores específicos
grep "ERROR" storage/logs/laravel.log

# Ver logs de tenants
ls -la storage/logs/tenant-*
```

#### Limpiar Logs
```bash
# Limpiar logs antiguos
php artisan log:clear

# Rotar logs
logrotate /etc/logrotate.d/laravel
```

---

## 📞 Soporte

### Recursos de Ayuda
- **Documentación técnica**: `docs/API_DOCUMENTATION.md`
- **Guía Postman**: `docs/GUIA_POSTMAN.md`
- **Comandos Artisan**: `docs/COMANDOS_ARTISAN.md`
- **Logs del sistema**: `storage/logs/laravel.log`

### Comandos de Diagnóstico
```bash
# Estado general del sistema
php artisan about

# Verificar salud del sistema
php artisan route:list --compact

# Verificar configuración
php artisan config:show
```

---

**Última actualización**: Julio 2024  
**Versión**: 1.0.0 