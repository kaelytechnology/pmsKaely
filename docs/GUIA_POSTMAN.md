# 🚀 Guía Completa para Probar APIs en Postman

## 📋 Índice
1. [Configuración Inicial](#configuración-inicial)
2. [Colección de Postman](#colección-de-postman)
3. [Variables de Entorno](#variables-de-entorno)
4. [Pruebas por Tenant](#pruebas-por-tenant)
5. [Flujos de Autenticación](#flujos-de-autenticación)
6. [Solución de Problemas](#solución-de-problemas)

---

## ⚙️ Configuración Inicial

### 1. Descargar Postman
- Ve a [postman.com](https://www.postman.com/downloads/)
- Descarga e instala Postman para tu sistema operativo
- Crea una cuenta gratuita

### 2. Configurar el Entorno Local
Antes de probar, asegúrate de que:
- El servidor Laravel esté ejecutándose (`php artisan serve`)
- Las bases de datos estén configuradas
- Los tenants estén creados

---

## 📁 Colección de Postman

### Importar la Colección
1. Abre Postman
2. Haz clic en "Import"
3. Copia y pega el siguiente JSON:

```json
{
  "info": {
    "name": "CMS Multitenant API",
    "description": "Colección completa para probar el CMS multitenant con autenticación",
    "schema": "https://schema.getpostman.com/json/collection/v2.1.0/collection.json"
  },
  "variable": [
    {
      "key": "base_url",
      "value": "http://localhost:8000",
      "type": "string"
    },
    {
      "key": "token",
      "value": "",
      "type": "string"
    }
  ],
  "item": [
    {
      "name": "🔐 Autenticación",
      "item": [
        {
          "name": "Login",
          "request": {
            "method": "POST",
            "header": [
              {
                "key": "Content-Type",
                "value": "application/json",
                "type": "text"
              },
              {
                "key": "Accept",
                "value": "application/json",
                "type": "text"
              }
            ],
            "body": {
              "mode": "raw",
              "raw": "{\n    \"email\": \"{{email}}\",\n    \"password\": \"{{password}}\"\n}"
            },
            "url": {
              "raw": "{{base_url}}/api/auth/login",
              "host": ["{{base_url}}"],
              "path": ["api", "auth", "login"]
            }
          },
          "event": [
            {
              "listen": "test",
              "script": {
                "exec": [
                  "if (pm.response.code === 200) {",
                  "    const response = pm.response.json();",
                  "    if (response.data && response.data.token) {",
                  "        pm.environment.set('token', response.data.token);",
                  "        console.log('Token guardado:', response.data.token);",
                  "    }",
                  "}"
                ]
              }
            }
          ]
        },
        {
          "name": "Registro",
          "request": {
            "method": "POST",
            "header": [
              {
                "key": "Content-Type",
                "value": "application/json",
                "type": "text"
              },
              {
                "key": "Accept",
                "value": "application/json",
                "type": "text"
              }
            ],
            "body": {
              "mode": "raw",
              "raw": "{\n    \"name\": \"{{name}}\",\n    \"email\": \"{{email}}\",\n    \"password\": \"{{password}}\",\n    \"password_confirmation\": \"{{password}}\"\n}"
            },
            "url": {
              "raw": "{{base_url}}/api/auth/register",
              "host": ["{{base_url}}"],
              "path": ["api", "auth", "register"]
            }
          }
        },
        {
          "name": "Obtener Usuario Actual",
          "request": {
            "method": "GET",
            "header": [
              {
                "key": "Authorization",
                "value": "Bearer {{token}}",
                "type": "text"
              },
              {
                "key": "Accept",
                "value": "application/json",
                "type": "text"
              }
            ],
            "url": {
              "raw": "{{base_url}}/api/auth/me",
              "host": ["{{base_url}}"],
              "path": ["api", "auth", "me"]
            }
          }
        },
        {
          "name": "Logout",
          "request": {
            "method": "POST",
            "header": [
              {
                "key": "Authorization",
                "value": "Bearer {{token}}",
                "type": "text"
              },
              {
                "key": "Accept",
                "value": "application/json",
                "type": "text"
              }
            ],
            "url": {
              "raw": "{{base_url}}/api/auth/logout",
              "host": ["{{base_url}}"],
              "path": ["api", "auth", "logout"]
            }
          }
        },
        {
          "name": "Refresh Token",
          "request": {
            "method": "POST",
            "header": [
              {
                "key": "Authorization",
                "value": "Bearer {{token}}",
                "type": "text"
              },
              {
                "key": "Accept",
                "value": "application/json",
                "type": "text"
              }
            ],
            "url": {
              "raw": "{{base_url}}/api/auth/refresh",
              "host": ["{{base_url}}"],
              "path": ["api", "auth", "refresh"]
            }
          }
        }
      ]
    }
  ]
}
```

---

## 🌍 Variables de Entorno

### Crear Entornos por Tenant

#### 1. Entorno: "Subdominios"
```json
{
  "name": "Subdominios",
  "values": [
    {
      "key": "base_url",
      "value": "http://localhost:8000",
      "enabled": true
    },
    {
      "key": "token",
      "value": "",
      "enabled": true
    },
    {
      "key": "email",
      "value": "user687db17b8c62e@example.com",
      "enabled": true
    },
    {
      "key": "password",
      "value": "password123",
      "enabled": true
    },
    {
      "key": "name",
      "value": "Usuario Test",
      "enabled": true
    }
  ]
}
```

#### 2. Entorno: "Dominios Completos"
```json
{
  "name": "Dominios Completos",
  "values": [
    {
      "key": "base_url",
      "value": "http://localhost:8000",
      "enabled": true
    },
    {
      "key": "token",
      "value": "",
      "enabled": true
    },
    {
      "key": "email",
      "value": "admin@empresaglobal.com",
      "enabled": true
    },
    {
      "key": "password",
      "value": "password123",
      "enabled": true
    },
    {
      "key": "name",
      "value": "Admin Empresa Global",
      "enabled": true
    }
  ]
}
```

---

## 🧪 Pruebas por Tenant

### 🔹 Tenants con Subdominios

#### Tenant: Kaely
- **URL Base**: `http://kaelytechnology.kaelytechnology.test:8000`
- **Usuario**: `user687db17b8c62e@example.com`
- **Contraseña**: `password123`

#### Tenant: Tenant 1
- **URL Base**: `http://tenant1.kaelytechnology.test:8000`
- **Usuario**: `usertenant1@example.com`
- **Contraseña**: `password123`

#### Tenant: Mi Empresa
- **URL Base**: `http://miempresa.kaelytechnology.test:8000`
- **Usuario**: `user687dc24f975d7@example.com`
- **Contraseña**: `password123`

### 🔸 Tenants con Dominios Completos

#### Tenant: Empresa Global
- **URL Base**: `http://empresaglobal.com:8000`
- **Usuario**: `admin@empresaglobal.com`
- **Contraseña**: `password123`

#### Tenant: Tech Solutions
- **URL Base**: `http://techsolutions.net:8000`
- **Usuario**: `admin@techsolutions.net`
- **Contraseña**: `password123`

---

## 🔄 Flujos de Autenticación

### Flujo 1: Login Básico
1. **Selecciona el entorno** correspondiente al tenant
2. **Ejecuta "Login"** con las credenciales
3. **Verifica que el token se guarde** automáticamente
4. **Prueba "Obtener Usuario Actual"** para confirmar autenticación

### Flujo 2: Registro y Login
1. **Cambia las variables** `email` y `name` en el entorno
2. **Ejecuta "Registro"** para crear un nuevo usuario
3. **Ejecuta "Login"** con las nuevas credenciales
4. **Prueba las demás rutas** autenticadas

### Flujo 3: Refresh Token
1. **Haz login** para obtener un token
2. **Espera 1 hora** o modifica la expiración
3. **Ejecuta "Refresh Token"** para renovar
4. **Verifica que funcione** con "Obtener Usuario Actual"

---

## 📝 Ejemplos de Pruebas

### Ejemplo 1: Login en Kaely
```http
POST http://kaelytechnology.kaelytechnology.test:8000/api/auth/login
Content-Type: application/json
Accept: application/json

{
    "email": "user687db17b8c62e@example.com",
    "password": "password123"
}
```

### Ejemplo 2: Obtener Usuario con Token
```http
GET http://kaelytechnology.kaelytechnology.test:8000/api/auth/me
Authorization: Bearer 1|W8DIkrVmgKIG6N4YlVydxN2474G9RQQuMCzMMk9a1dbb4c4f...
Accept: application/json
```

### Ejemplo 3: Registro en Empresa Global
```http
POST http://empresaglobal.com:8000/api/auth/register
Content-Type: application/json
Accept: application/json

{
    "name": "Nuevo Usuario",
    "email": "nuevo@empresaglobal.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

---

## 🔧 Configuración de Hosts (Opcional)

Para usar dominios reales en lugar de localhost, agrega esto a tu archivo hosts:

### Windows: `C:\Windows\System32\drivers\etc\hosts`
```
127.0.0.1 kaelytechnology.kaelytechnology.test
127.0.0.1 tenant1.kaelytechnology.test
127.0.0.1 miempresa.kaelytechnology.test
127.0.0.1 empresaglobal.com
127.0.0.1 techsolutions.net
```

### Linux/Mac: `/etc/hosts`
```
127.0.0.1 kaelytechnology.kaelytechnology.test
127.0.0.1 tenant1.kaelytechnology.test
127.0.0.1 miempresa.kaelytechnology.test
127.0.0.1 empresaglobal.com
127.0.0.1 techsolutions.net
```

---

## ⚠️ Solución de Problemas

### Error: "Connection refused"
- **Causa**: Servidor no está ejecutándose
- **Solución**: Ejecuta `php artisan serve`

### Error: "Unauthorized (401)"
- **Causa**: Token inválido o expirado
- **Solución**: Haz login nuevamente

### Error: "Not Found (404)"
- **Causa**: URL incorrecta o tenant no existe
- **Solución**: Verifica la URL y que el tenant esté creado

### Error: "Validation failed (422)"
- **Causa**: Datos de entrada incorrectos
- **Solución**: Revisa el formato JSON y los campos requeridos

### Error: "Database connection failed"
- **Causa**: Base de datos del tenant no existe
- **Solución**: Ejecuta las migraciones del tenant

---

## 📊 Verificación de Respuestas

### Respuesta Exitosa de Login
```json
{
    "status": "success",
    "message": "Login successful",
    "data": {
        "user": {
            "id": 1,
            "name": "Usuario Kaely",
            "email": "user687db17b8c62e@example.com",
            "created_at": "2024-01-01T00:00:00.000000Z",
            "updated_at": "2024-01-01T00:00:00.000000Z"
        },
        "token": "1|W8DIkrVmgKIG6N4YlVydxN2474G9RQQuMCzMMk9a1dbb4c4f..."
    }
}
```

### Respuesta de Error
```json
{
    "status": "error",
    "message": "Invalid credentials"
}
```

---

## 🎯 Consejos de Uso

1. **Usa entornos separados** para cada tipo de tenant
2. **Guarda los tokens** automáticamente con scripts
3. **Prueba la aislación** entre tenants
4. **Verifica las respuestas** antes de continuar
5. **Documenta los casos de prueba** exitosos

---

## 📞 Soporte

Si encuentras problemas:
1. Revisa los logs de Laravel: `storage/logs/laravel.log`
2. Verifica la configuración de la base de datos
3. Ejecuta los comandos de prueba: `php artisan test:all-tenant-logins`
4. Consulta la documentación técnica en `docs/API_DOCUMENTATION.md` 