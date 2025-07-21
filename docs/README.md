# 📚 Documentación Completa - CMS Multitenant Laravel

## 🎯 Descripción General

Este es un sistema CMS multitenant desarrollado en Laravel que utiliza el paquete `stancl/tenancy` para la gestión de múltiples inquilinos y el paquete `kaelytechnology/auth-package` para la autenticación. El sistema soporta tanto subdominios como dominios completos con bases de datos completamente aisladas.

## 📋 Índice de Documentación

### 🔧 Documentación Técnica
- **[API_DOCUMENTATION.md](API_DOCUMENTATION.md)** - Documentación técnica completa para desarrolladores e IAs
- **[GUIA_POSTMAN.md](GUIA_POSTMAN.md)** - Guía paso a paso para probar APIs en Postman

### 🚀 Guías de Uso
- **[COMANDOS_ARTISAN.md](COMANDOS_ARTISAN.md)** - Lista completa de comandos Artisan disponibles
- **[CONFIGURACION.md](CONFIGURACION.md)** - Guía de configuración del sistema

---

## 🏗️ Arquitectura del Sistema

### Tipos de Tenants
- **🔹 Subdominios**: `{subdominio}.kaelytechnology.test`
- **🔸 Dominios Completos**: `{dominio}.com/net/etc`

### Base de Datos
- **Formato**: `tenant_{nombre}_{dominio}`
- **Ejemplo**: `tenant_empresa_global_empresaglobal`
- **Aislamiento**: Cada tenant tiene su propia base de datos

### Autenticación
- **Laravel Sanctum** para tokens API
- **Modelo User** con soft deletes
- **Sistema de roles y permisos** del paquete auth

---

## 🎮 Tenants Disponibles

### 🔹 Tenants con Subdominios (3)
| # | Nombre | Dominio | Usuario | Contraseña |
|---|--------|---------|---------|------------|
| 1 | Kaely | kaelytechnology.kaelytechnology.test | user687db17b8c62e@example.com | password123 |
| 2 | Tenant 1 | tenant1.kaelytechnology.test | usertenant1@example.com | password123 |
| 3 | Mi Empresa | miempresa.kaelytechnology.test | user687dc24f975d7@example.com | password123 |

### 🔸 Tenants con Dominios Completos (2)
| # | Nombre | Dominio | Usuario | Contraseña |
|---|--------|---------|---------|------------|
| 1 | Empresa Global | empresaglobal.com | admin@empresaglobal.com | password123 |
| 2 | Tech Solutions | techsolutions.net | admin@techsolutions.net | password123 |

---

## 🔌 Endpoints de la API

### Autenticación
- `POST /api/auth/login` - Iniciar sesión
- `POST /api/auth/register` - Registrar usuario
- `GET /api/auth/me` - Obtener usuario actual
- `POST /api/auth/logout` - Cerrar sesión
- `POST /api/auth/refresh` - Renovar token

### Respuestas Estándar
```json
{
    "status": "success|error",
    "message": "Mensaje descriptivo",
    "data": { /* Datos de respuesta */ }
}
```

---

## 🛠️ Comandos Principales

### Gestión de Tenants
```bash
# Crear tenant con subdominio
php artisan tenant:create-named "Nombre" "subdominio"

# Crear tenant con dominio completo
php artisan tenant:create-domain "Nombre" "dominio.com"

# Mostrar credenciales de todos los tenants
php artisan tenant:show-credentials

# Mostrar tipos de tenants
php artisan tenant:show-types
```

### Pruebas y Validación
```bash
# Probar login en todos los tenants
php artisan test:all-tenant-logins

# Probar rutas del paquete auth
php artisan test:auth-routes

# Probar tenants con dominios completos
php artisan test:domain-tenants
```

### Gestión de Base de Datos
```bash
# Listar bases de datos de tenants
php artisan tenant:list-databases

# Migrar nombres de bases de datos
php artisan tenant:migrate-database-names
```

---

## 🧪 Cómo Probar el Sistema

### 1. Configuración Inicial
```bash
# Instalar dependencias
composer install

# Configurar base de datos
php artisan migrate

# Crear tenants de prueba
php artisan tenant:create-named "Test" "test"
```

### 2. Probar con Postman
1. Importa la colección de Postman desde `docs/GUIA_POSTMAN.md`
2. Configura los entornos para cada tipo de tenant
3. Ejecuta las pruebas de autenticación

### 3. Probar con Comandos
```bash
# Verificar que todo funciona
php artisan test:all-tenant-logins

# Mostrar información de tenants
php artisan tenant:show-credentials
```

---

## 🔧 Configuración del Entorno

### Variables de Entorno Requeridas
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cms_multitenant
DB_USERNAME=root
DB_PASSWORD=

SANCTUM_STATEFUL_DOMAINS=kaelytechnology.test,empresaglobal.com,techsolutions.net
SESSION_DOMAIN=.kaelytechnology.test
```

### Configuración de Hosts (Opcional)
```
127.0.0.1 kaelytechnology.kaelytechnology.test
127.0.0.1 tenant1.kaelytechnology.test
127.0.0.1 miempresa.kaelytechnology.test
127.0.0.1 empresaglobal.com
127.0.0.1 techsolutions.net
```

---

## 📊 Estado del Sistema

### ✅ Funcionalidades Implementadas
- [x] Sistema multitenant con aislamiento completo
- [x] Soporte para subdominios y dominios completos
- [x] Autenticación con Laravel Sanctum
- [x] Sistema de roles y permisos
- [x] Comandos Artisan para gestión
- [x] Pruebas automatizadas
- [x] Documentación completa

### 📈 Métricas
- **Total de Tenants**: 5
- **Subdominios**: 3
- **Dominios Completos**: 2
- **Logins Exitosos**: 100%
- **APIs Funcionando**: 100%

---

## 🚨 Solución de Problemas

### Errores Comunes

#### Error: "Connection refused"
```bash
# Solución: Iniciar servidor
php artisan serve
```

#### Error: "Database connection failed"
```bash
# Solución: Ejecutar migraciones
php artisan tenants:run "migrate"
```

#### Error: "Unauthorized (401)"
```bash
# Solución: Verificar credenciales
php artisan tenant:show-credentials
```

### Comandos de Diagnóstico
```bash
# Verificar estado de tenants
php artisan tenants:list

# Probar conexiones de base de datos
php artisan tenants:run "db:show"

# Ver logs de errores
tail -f storage/logs/laravel.log
```

---

## 🔄 Flujo de Desarrollo

### 1. Crear Nuevo Tenant
```bash
# Para subdominio
php artisan tenant:create-named "Mi Cliente" "micliente"

# Para dominio completo
php artisan tenant:create-domain "Mi Cliente" "micliente.com"
```

### 2. Probar Funcionalidad
```bash
# Verificar que funciona
php artisan test:all-tenant-logins

# Probar APIs específicas
php artisan test:auth-routes
```

### 3. Documentar Cambios
- Actualizar documentación técnica
- Agregar casos de prueba
- Documentar nuevas funcionalidades

---

## 📞 Soporte y Contacto

### Recursos Disponibles
- **Documentación Técnica**: `docs/API_DOCUMENTATION.md`
- **Guía Postman**: `docs/GUIA_POSTMAN.md`
- **Logs del Sistema**: `storage/logs/laravel.log`

### Comandos de Ayuda
```bash
# Ver todos los comandos disponibles
php artisan list

# Ver ayuda de un comando específico
php artisan tenant:create-domain --help
```

---

## 📝 Notas de Versión

### v1.0.0 - Versión Actual
- ✅ Sistema multitenant funcional
- ✅ Autenticación con Sanctum
- ✅ Soporte para subdominios y dominios completos
- ✅ Comandos de gestión completos
- ✅ Documentación técnica y de usuario
- ✅ Pruebas automatizadas

### Próximas Funcionalidades
- [ ] Panel de administración web
- [ ] Gestión de archivos por tenant
- [ ] Sistema de notificaciones
- [ ] API para gestión de tenants
- [ ] Métricas y analytics

---

## 📄 Licencia

Este proyecto está bajo la licencia MIT. Ver el archivo `LICENSE` para más detalles.

---

**Última actualización**: Julio 2024  
**Versión**: 1.0.0  
**Autor**: Sistema CMS Multitenant Laravel 