# API Documentation - Laravel Multitenant CMS with Auth Package

## System Overview

This is a Laravel multitenant application using the `stancl/tenancy` package with the `kaelytechnology/auth-package` for authentication. The system supports both subdomain and full domain tenants with isolated databases.

## Architecture

### Tenant Types
- **Subdomain Tenants**: `{subdomain}.kaelytechnology.test`
- **Full Domain Tenants**: `{domain}.com/net/etc`

### Database Naming Convention
- Format: `tenant_{name}_{domain}`
- Example: `tenant_empresa_global_empresaglobal`

### Authentication System
- Laravel Sanctum for API tokens
- User model with soft deletes
- Role and permission system from auth package

## Database Schema

### Core Tables (Per Tenant)
- `users` - User accounts with soft deletes
- `personal_access_tokens` - Sanctum tokens
- `modules` - System modules
- `roles` - User roles
- `permissions` - System permissions
- `role_user` - Role assignments
- `permission_role` - Permission assignments

### Central Tables
- `tenants` - Tenant information
- `domains` - Domain mappings

## API Endpoints

### Base URL Structure
- Subdomain: `https://{subdomain}.kaelytechnology.test/api`
- Full Domain: `https://{domain}.com/api`

### Authentication Endpoints

#### POST /api/auth/login
**Description**: Authenticate user and generate access token

**Request Body**:
```json
{
    "email": "user@example.com",
    "password": "password123"
}
```

**Response**:
```json
{
    "status": "success",
    "message": "Login successful",
    "data": {
        "user": {
            "id": 1,
            "name": "User Name",
            "email": "user@example.com",
            "created_at": "2024-01-01T00:00:00.000000Z",
            "updated_at": "2024-01-01T00:00:00.000000Z"
        },
        "token": "1|token_string_here"
    }
}
```

#### POST /api/auth/register
**Description**: Register new user account

**Request Body**:
```json
{
    "name": "New User",
    "email": "newuser@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

**Response**:
```json
{
    "status": "success",
    "message": "User registered successfully",
    "data": {
        "user": {
            "id": 2,
            "name": "New User",
            "email": "newuser@example.com",
            "created_at": "2024-01-01T00:00:00.000000Z",
            "updated_at": "2024-01-01T00:00:00.000000Z"
        },
        "token": "2|token_string_here"
    }
}
```

#### GET /api/auth/me
**Description**: Get current authenticated user information

**Headers**:
```
Authorization: Bearer {token}
```

**Response**:
```json
{
    "status": "success",
    "data": {
        "user": {
            "id": 1,
            "name": "User Name",
            "email": "user@example.com",
            "created_at": "2024-01-01T00:00:00.000000Z",
            "updated_at": "2024-01-01T00:00:00.000000Z"
        }
    }
}
```

#### POST /api/auth/logout
**Description**: Logout user and invalidate token

**Headers**:
```
Authorization: Bearer {token}
```

**Response**:
```json
{
    "status": "success",
    "message": "Logged out successfully"
}
```

#### POST /api/auth/refresh
**Description**: Refresh access token

**Headers**:
```
Authorization: Bearer {token}
```

**Response**:
```json
{
    "status": "success",
    "data": {
        "token": "new_token_string_here"
    }
}
```

## Error Responses

### Validation Errors (422)
```json
{
    "status": "error",
    "message": "Validation failed",
    "errors": {
        "email": ["The email field is required."],
        "password": ["The password field is required."]
    }
}
```

### Authentication Errors (401)
```json
{
    "status": "error",
    "message": "Unauthorized"
}
```

### Not Found Errors (404)
```json
{
    "status": "error",
    "message": "Resource not found"
}
```

### Server Errors (500)
```json
{
    "status": "error",
    "message": "Internal server error"
}
```

## Tenant Management

### Available Tenants

#### Subdomain Tenants
1. **Kaely**
   - Domain: `kaelytechnology.kaelytechnology.test`
   - User: `user687db17b8c62e@example.com`
   - Password: `password123`

2. **Tenant 1**
   - Domain: `tenant1.kaelytechnology.test`
   - User: `usertenant1@example.com`
   - Password: `password123`

3. **Mi Empresa**
   - Domain: `miempresa.kaelytechnology.test`
   - User: `user687dc24f975d7@example.com`
   - Password: `password123`

#### Full Domain Tenants
1. **Empresa Global**
   - Domain: `empresaglobal.com`
   - User: `admin@empresaglobal.com`
   - Password: `password123`

2. **Tech Solutions**
   - Domain: `techsolutions.net`
   - User: `admin@techsolutions.net`
   - Password: `password123`

## Testing Commands

### Artisan Commands
```bash
# Test all tenant logins
php artisan test:all-tenant-logins

# Test auth package routes
php artisan test:auth-routes

# Test domain tenants specifically
php artisan test:domain-tenants

# Show tenant credentials
php artisan tenant:show-credentials

# Show tenant types
php artisan tenant:show-types

# Create new subdomain tenant
php artisan tenant:create-named {name} {subdomain}

# Create new full domain tenant
php artisan tenant:create-domain {name} {domain}
```

## Security Considerations

1. **Token Expiration**: Tokens expire after 60 minutes by default
2. **CSRF Protection**: Disabled for API routes
3. **Rate Limiting**: Implemented on auth endpoints
4. **Password Requirements**: Minimum 8 characters
5. **Email Validation**: Unique per tenant

## Database Isolation

Each tenant has:
- Separate database connection
- Isolated user tables
- Independent token storage
- Unique role/permission sets

## Environment Configuration

### Required Environment Variables
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

### Tenant Configuration
```php
// config/tenancy.php
'database' => [
    'central_connection' => env('DB_CONNECTION', 'mysql'),
    'template_tenant_connection' => null,
    'prefix' => 'tenant',
    'suffix' => '',
],
```

## Integration Examples

### JavaScript/Fetch
```javascript
const login = async (email, password) => {
    const response = await fetch('https://tenant.kaelytechnology.test/api/auth/login', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ email, password })
    });
    return response.json();
};
```

### cURL
```bash
curl -X POST https://tenant.kaelytechnology.test/api/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"email":"user@example.com","password":"password123"}'
```

### PHP/Guzzle
```php
use GuzzleHttp\Client;

$client = new Client();
$response = $client->post('https://tenant.kaelytechnology.test/api/auth/login', [
    'json' => [
        'email' => 'user@example.com',
        'password' => 'password123'
    ]
]);
```

## Monitoring and Logging

### Log Files
- `storage/logs/laravel.log` - General application logs
- `storage/logs/tenant-{id}.log` - Per-tenant logs

### Health Check Endpoints
- `GET /api/health` - System health status
- `GET /api/tenant/status` - Current tenant status

## Performance Considerations

1. **Database Connections**: Each tenant uses separate connection
2. **Caching**: Tenant-scoped cache keys
3. **Queue Jobs**: Tenant-aware job processing
4. **File Storage**: Tenant-isolated file systems

## Troubleshooting

### Common Issues
1. **Token Not Found**: Check if token exists in tenant database
2. **User Not Found**: Verify user exists in correct tenant
3. **Database Connection**: Ensure tenant database exists
4. **Domain Resolution**: Check DNS/hosts file configuration

### Debug Commands
```bash
# Check tenant status
php artisan tenants:list

# Verify database connections
php artisan tenants:run "db:show"

# Test tenant isolation
php artisan tenants:run "tinker --execute='echo User::count();'"
``` 