# API v1 Refactor Design

Date: 2026-02-27
Project: backendLarpland

## Goals

- Deliver a strong API refactor with explicit versioning (`/api/v1`).
- Enforce RBAC with roles: `0 = cliente`, `1 = admin`.
- Standardize response and error formats.
- Add pagination, filtering, and ownership-based access control.
- Keep legacy endpoints temporarily with deprecation headers.

## Scope

- New versioned routes under `/api/v1`.
- `auth` endpoints in v1 (`login`, `register`, `me`, `logout`).
- REST resources in v1:
  - `users`
  - `products`
  - `orders`
  - `order-details`
  - `reviews`
  - `events`
  - `event-registrations`
- New nested route: `GET /api/v1/products/{product}/reviews`.

## RBAC Rules

- Admin (`rol = 1`): full CRUD on all resources.
- Cliente (`rol = 0`):
  - `products` and `events`: read only.
  - `orders`: manage only own orders.
  - `order-details`: manage only details that belong to own orders.
  - `reviews`: manage only own reviews.
  - `event-registrations`: manage only own registrations.
  - `users`: can only view/update own profile (cannot change role).

## API Contract

- Success shape:

```json
{
  "message": "Request successful",
  "data": {},
  "meta": {}
}
```

- Error shape:

```json
{
  "error": {
    "code": "validation_error",
    "message": "Validation failed",
    "details": {}
  }
}
```

## Validation and Data Rules

- Use FormRequest classes for input validation.
- Return `422` for validation errors.
- Keep proper semantics for status codes:
  - `200` OK
  - `201` Created
  - `204` No Content
  - `401` Unauthorized
  - `403` Forbidden
  - `404` Not Found
  - `422` Unprocessable Entity
- Domain constraints:
  - `reviews.rating`: `1..5`
  - `orders.estado`: allowed enum values
  - `events.fecha_fin >= events.fecha_inicio`
  - `event_registrations` and `product_reviews`: prevent duplicate pairs
    (`user_id + event_id`, `user_id + product_id`) at validation level.

## Migration and Compatibility

- Legacy routes remain active during transition period.
- Legacy responses include deprecation headers:
  - `Deprecation: true`
  - `Sunset: Wed, 30 Apr 2026 23:59:59 GMT`
  - `Link: </api/v1>; rel="successor-version"`

## Implementation Plan

1. Add API foundation:
   - response helper trait
   - API exception rendering
   - deprecation middleware
   - role middleware
2. Add policies and model relationships.
3. Add FormRequests and API Resources.
4. Implement v1 controllers and routes.
5. Add feature tests for auth, RBAC, ownership, validation, and pagination.
6. Keep legacy routes but mark deprecated.

## Risks and Mitigations

- Risk: breaking frontend clients.
  - Mitigation: keep legacy endpoints with sunset headers.
- Risk: inconsistent ownership checks.
  - Mitigation: enforce policies + query scoping in controllers.
- Risk: validation behavior changes.
  - Mitigation: standard 422 responses and explicit request classes.
