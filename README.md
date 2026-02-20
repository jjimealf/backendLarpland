# Larpland API

Backend API en Laravel 11 para gestión de usuarios, productos, pedidos y eventos de roleplay.

## Base URL

- Local: `http://127.0.0.1:8000/api`

## Stack

- PHP 8.2+
- Laravel 11
- Laravel Sanctum (Bearer Token)
- MySQL/MariaDB

## Setup rápido

```bash
composer install
npm install
copy .env.example .env
php artisan key:generate
php artisan migrate
php artisan storage:link
php artisan serve
```

## Autenticación

### `POST /register`

Request:

```json
{
  "name": "Juan",
  "email": "juan@example.com",
  "password": "password123",
  "rol": 0
}
```

Response (201):

```json
{
  "status": "1",
  "message": "Registro exitoso",
  "rol": "0",
  "userId": 1,
  "token": "1|...",
  "token_type": "Bearer"
}
```

### `POST /login`

Request:

```json
{
  "email": "juan@example.com",
  "password": "password123"
}
```

Response (200):

```json
{
  "status": "1",
  "message": "Login exitoso",
  "rol": "0",
  "userId": 1,
  "token": "1|...",
  "token_type": "Bearer"
}
```

## Header para rutas protegidas

```http
Authorization: Bearer TU_TOKEN
Accept: application/json
```

## Endpoints (Postman-friendly)

Todos los endpoints siguientes requieren token, excepto `/login` y `/register`.

| Recurso | Método | Endpoint |
|---|---|---|
| Usuario autenticado | GET | `/user` |
| Users | GET | `/users` |
| Users | POST | `/users` |
| Users | GET | `/users/{id}` |
| Users | PUT/PATCH | `/users/{id}` |
| Users | DELETE | `/users/{id}` |
| Products | GET | `/products` |
| Products | POST | `/products` |
| Products | GET | `/products/{id}` |
| Products | PUT/PATCH | `/products/{id}` |
| Products | DELETE | `/products/{id}` |
| Orders | GET | `/orders` |
| Orders | POST | `/orders` |
| Orders | GET | `/orders/{id}` |
| Orders | PUT/PATCH | `/orders/{id}` |
| Orders | DELETE | `/orders/{id}` |
| Order details | GET | `/detail` |
| Order details | POST | `/detail` |
| Order details | GET | `/detail/{id}` |
| Order details | PUT/PATCH | `/detail/{id}` |
| Order details | DELETE | `/detail/{id}` |
| Reviews | GET | `/reviews` |
| Reviews | POST | `/reviews` |
| Reviews | GET | `/reviews/{id}` |
| Reviews | PUT/PATCH | `/reviews/{id}` |
| Reviews | DELETE | `/reviews/{id}` |
| Events | GET | `/events` |
| Events | POST | `/events` |
| Events | GET | `/events/{id}` |
| Events | PUT/PATCH | `/events/{id}` |
| Events | DELETE | `/events/{id}` |
| Event registrations | GET | `/event/registrations` |
| Event registrations | POST | `/event/registrations` |
| Event registrations | GET | `/event/registrations/{id}` |
| Event registrations | PUT/PATCH | `/event/registrations/{id}` |
| Event registrations | DELETE | `/event/registrations/{id}` |

## Ejemplos por recurso

### Users

`POST /users`

```json
{
  "name": "Maria",
  "email": "maria@example.com",
  "password": "password123",
  "rol": 1
}
```

### Products

`POST /products` (`multipart/form-data`)

Campos:

- `nombre` (string)
- `descripcion` (string)
- `precio` (numeric)
- `cantidad` (numeric)
- `categoria` (string)
- `imagen` (file)

### Orders

`POST /orders`

```json
{
  "user_id": 1,
  "estado": "pendiente",
  "fecha_pedido": "2026-02-20 10:00:00",
  "direccion_envio": "Av. Principal 123"
}
```

### Order details

`POST /detail`

```json
{
  "order_id": 1,
  "product_id": 1,
  "cantidad": 2,
  "precio_unitario": 49.99
}
```

### Reviews

`POST /reviews`

```json
{
  "product_id": 1,
  "user_id": 1,
  "rating": 5,
  "comment": "Excelente producto"
}
```

Nota: `GET /reviews/{id}` devuelve reseñas por `product_id`.

### Events

`POST /events` (`multipart/form-data`)

Campos:

- `nombre` (string)
- `descripcion` (string)
- `fecha_inicio` (date/datetime)
- `fecha_fin` (date/datetime)
- `image` (file, opcional)

### Event registrations

`POST /event/registrations`

```json
{
  "user_id": 1,
  "event_id": 1
}
```

## Resumen de validaciones

- `rating`: 1 a 5
- `estado` en orders: `pendiente`, `procesando`, `completado`
- Imágenes:
  - Products: `imagen` requerida
  - Events: `image` opcional (max 5MB)

## Pruebas

```bash
php artisan test
```

## Notas

- Imágenes almacenadas en `storage/app/public/img`.
- Ejecuta `php artisan storage:link` para acceso público.
