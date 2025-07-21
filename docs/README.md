# CMS Multitenant - Documentación Completa

## 📋 Descripción

Sistema de gestión de contenido (CMS) multitenant desarrollado en Laravel, utilizando el paquete `stancl/tenancy` para la gestión de múltiples inquilinos y el paquete `kaelytechnology/auth-package` para la autenticación. El sistema soporta tanto subdominios como dominios completos con bases de datos completamente aisladas.

## 🚀 Estado del Proyecto

### ✅ Funcionalidades Implementadas

- **Sistema Multitenant**: Soporte completo para múltiples inquilinos
- **Autenticación API**: Login, registro y gestión de tokens con Sanctum
- **Dominios Completos**: Soporte para dominios personalizados (ej: techsolutions.net)
- **Subdominios**: Soporte para subdominios (ej: tenant.kaelytechnology.test)
- **Bases de Datos Aisladas**: Cada tenant tiene su propia base de datos
- **Gestión de Usuarios**: Sistema completo de usuarios con roles y permisos
- **Comandos Artisan**: Herramientas para gestión y testing de tenants

### 🔧 Problemas Resueltos

- ✅ **Login API**: Solucionado problema de autenticación en contexto multitenant
- ✅ **Configuración de Base de Datos**: Migrado de SQLite a MySQL
- ✅ **Rutas del Tenant**: Implementadas todas las rutas del paquete de autenticación
- ✅ **Modelos Personalizados**: Configurados para usar conexiones correctas
- ✅ **SoftDeletes**: Implementado correctamente en todos los tenants

## 📚 Documentación

### 📖 Guías Principales

- [**Configuración del Sistema**](CONFIGURACION.md) - Configuración completa del entorno
- [**Documentación API**](API_DOCUMENTATION.md) - Especificaciones técnicas de la API
- [**Guía Postman**](GUIA_POSTMAN.md) - Cómo probar la API con Postman
- [**Comandos Artisan**](COMANDOS_ARTISAN.md) - Todos los comandos disponibles
- [**Solución Login API**](SOLUCION_LOGIN_API.md) - Documentación de la solución del problema de autenticación

### 🔗 Enlaces Rápidos

- [**Postman Collection**](CMS_Multitenant_API.postman_collection.json) - Colección para importar en Postman
- [**Configuración del Paquete**](config/auth-package.php) - Configuración del paquete de autenticación

## 🛠️ Instalación y Configuración

### Requisitos Previos

- PHP 8.1+
- MySQL 8.0+
- Composer
- Node.js (para Vite)

### Instalación

1. **Clonar el repositorio**
```bash
git clone <repository-url>
cd cms-multitenat
```

2. **Instalar dependencias**
```bash
composer install
npm install
```

3. **Configurar entorno**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configurar base de datos**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cms_multitenant
DB_USERNAME=root
DB_PASSWORD=
```

5. **Ejecutar migraciones**
```bash
php artisan migrate
```

6. **Crear tenants de prueba**
```bash
php artisan tenant:create-domain-tenant "Tech Solutions" "techsolutions.net"
php artisan tenant:create-domain-tenant "Empresa Global" "empresaglobal.com"
```

## 🧪 Testing

### Probar Login API

```bash
# Probar login específico
php artisan test:api-login techsolutions.net admin@techsolutions.net password123

# Probar todos los tenants con dominios completos
php artisan test:domain-tenants

# Mostrar credenciales de todos los tenants
php artisan tenant:show-credentials
```

### Probar con Postman

1. **Configurar archivo hosts**:
```
127.0.0.1 techsolutions.net
127.0.0.1 empresaglobal.com
127.0.0.1 kaelytechnology.test
```

2. **Importar colección**: [CMS_Multitenant_API.postman_collection.json](CMS_Multitenant_API.postman_collection.json)

3. **Ejecutar servidor**:
```bash
php artisan serve --host=0.0.0.0 --port=8000
```

4. **Probar login**:
```
POST http://techsolutions.net:8000/api/auth/login
{
    "email": "admin@techsolutions.net",
    "password": "password123"
}
```

## 🏗️ Arquitectura

### Estructura de Base de Datos

```
cms_multitenant (Base de datos central)
├── tenants (Tabla de inquilinos)
├── domains (Tabla de dominios)
└── migrations (Migraciones centrales)

tenant_{tenant_id} (Base de datos por tenant)
├── users (Usuarios del tenant)
├── roles (Roles del tenant)
├── permissions (Permisos del tenant)
└── ... (Otras tablas específicas del tenant)
```

### Flujo de Autenticación

1. **Resolución del Tenant**: El middleware `InitializeTenancyByDomain` identifica el tenant por dominio
2. **Conexión de Base de Datos**: Se cambia automáticamente a la base de datos del tenant
3. **Autenticación**: Se valida el usuario en el contexto del tenant
4. **Generación de Token**: Se crea un token Sanctum para el usuario
5. **Respuesta**: Se devuelve el token y datos del usuario

## 🔧 Comandos Útiles

### Gestión de Tenants

```bash
# Crear tenant con dominio completo
php artisan tenant:create-domain-tenant "Nombre" "dominio.com"

# Crear tenant con subdominio
php artisan tenant:create-subdomain-tenant "Nombre" "subdominio"

# Listar todos los tenants
php artisan tenant:list

# Mostrar credenciales
php artisan tenant:show-credentials
```

### Testing y Debugging

```bash
# Probar login API
php artisan test:api-login dominio.com email@dominio.com password

# Probar todos los tenants
php artisan test:all-tenant-logins

# Probar rutas de autenticación
php artisan test:auth-routes

# Mostrar tipos de tenants
php artisan tenant:show-types
```

### Mantenimiento

```bash
# Limpiar cache
php artisan config:clear
php artisan route:clear
php artisan cache:clear

# Migrar SoftDeletes
php artisan tenants:migrate-soft-deletes

# Arreglar tenant específico
php artisan fix:tech-solutions-soft-deletes
```

## 🚀 Despliegue

### Variables de Entorno de Producción

```env
APP_ENV=production
APP_DEBUG=false
DB_CONNECTION=mysql
DB_HOST=your-db-host
DB_PORT=3306
DB_DATABASE=cms_multitenant
DB_USERNAME=your-username
DB_PASSWORD=your-password

SANCTUM_STATEFUL_DOMAINS=your-domain.com,another-domain.com
SESSION_DOMAIN=.your-domain.com
```

### Optimizaciones

```bash
# Optimizar para producción
php artisan config:cache
php artisan route:cache
php artisan view:cache
composer install --optimize-autoloader --no-dev
```

## 🤝 Contribución

1. Fork el proyecto
2. Crear una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abrir un Pull Request

## 📄 Licencia

Este proyecto está bajo la Licencia MIT. Ver el archivo `LICENSE` para más detalles.

## 🆘 Soporte

Para soporte técnico o preguntas:

- 📧 Email: soporte@kaelytechnology.com
- 📖 Documentación: [docs/](docs/)
- 🐛 Issues: [GitHub Issues](https://github.com/kaelytechnology/cms-multitenant/issues)

## 🔄 Changelog

### v1.0.0 (2024-01-XX)
- ✅ Sistema multitenant funcional
- ✅ Autenticación API con Sanctum
- ✅ Soporte para dominios completos y subdominios
- ✅ Gestión de usuarios, roles y permisos
- ✅ Comandos Artisan para gestión y testing
- ✅ Documentación completa
- ✅ Solución del problema de login API 