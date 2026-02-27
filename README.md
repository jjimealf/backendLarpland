# Larpland API

Backend API en Laravel 11 para gestion de usuarios, productos, pedidos y eventos de roleplay.

## Base URL

- Local: `http://127.0.0.1:8000/api`

## Versionado

- Nueva version: `/api/v1/...`
- Legacy: `/api/...` (deprecado)
- Sunset legacy: `Wed, 30 Apr 2026 23:59:59 GMT`

## Stack

- PHP 8.2+
- Laravel 11
- Laravel Sanctum (Bearer Token)
- MySQL/MariaDB

## Setup rapido

```bash
composer install
npm install
copy .env.example .env
php artisan key:generate
php artisan migrate
php artisan storage:link
php artisan serve
```

## Auth v1

### `POST /v1/auth/register`

```json
{
  "name": "Juan",
  "email": "juan@example.com",
  "password": "password123"
}
```

### `POST /v1/auth/login`

```json
{
  "email": "juan@example.com",
  "password": "password123"
}
```

### `GET /v1/auth/me`
### `POST /v1/auth/logout`

Header para rutas protegidas:

```http
Authorization: Bearer TU_TOKEN
Accept: application/json
```

## Endpoints v1

Todos los endpoints siguientes requieren token.

| Recurso | Metodo | Endpoint |
|---|---|---|
| Users | GET/POST | `/v1/users` |
| Users | GET/PUT/PATCH/DELETE | `/v1/users/{id}` |
| Products | GET/POST | `/v1/products` |
| Products | GET/PUT/PATCH/DELETE | `/v1/products/{id}` |
| Reviews por producto | GET | `/v1/products/{product}/reviews` |
| Reviews | GET/POST | `/v1/reviews` |
| Reviews | GET/PUT/PATCH/DELETE | `/v1/reviews/{id}` |
| Orders | GET/POST | `/v1/orders` |
| Orders | GET/PUT/PATCH/DELETE | `/v1/orders/{id}` |
| Order details | GET/POST | `/v1/order-details` |
| Order details | GET/PUT/PATCH/DELETE | `/v1/order-details/{id}` |
| Events | GET/POST | `/v1/events` |
| Events | GET/PUT/PATCH/DELETE | `/v1/events/{id}` |
| Event registrations | GET/POST | `/v1/event-registrations` |
| Event registrations | GET/PUT/PATCH/DELETE | `/v1/event-registrations/{id}` |

## Reglas clave v1

- Respuesta exitosa estandar:

```json
{
  "message": "Request successful",
  "data": {}
}
```

- Respuesta de error estandar:

```json
{
  "error": {
    "code": "validation_error",
    "message": "Validation failed.",
    "details": {}
  }
}
```

- RBAC:
  - `rol = 1`: admin (CRUD completo)
  - `rol = 0`: cliente (ownership y solo lectura en products/events)
- Paginacion: `?page=1&per_page=15`
- Filtros principales:
  - products: `search`, `categoria`, `min_price`, `max_price`
  - orders: `estado`, `user_id` (solo admin)
  - events: `search`, `from`, `to`

## Legacy

Las rutas legacy siguen disponibles temporalmente y devuelven:

- `Deprecation: true`
- `Sunset: Wed, 30 Apr 2026 23:59:59 GMT`
- `Link: </api/v1>; rel="successor-version"`

## Pruebas

```bash
php artisan test
```

## Notas

- Imagenes en `storage/app/public/img`.
- Ejecuta `php artisan storage:link` para acceso publico.
