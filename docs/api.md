# Gudang Hub REST API (v1)

Base URL:
- Local dev: `http://127.0.0.1:8000`
- API prefix: `/api`

Auth:
- Token-based auth via Laravel Sanctum.
- Send header: `Authorization: Bearer <token>`

RBAC:
- `staff-gudang` (read master data + create IN/OUT transactions)
- `admin` / `super-admin` (full access for categories/items + transactions update/delete + mutations read)

## Authentication

### `POST /api/login`
Login and return a Bearer token.

Request (JSON):
```json
{
  "email": "admin@example.com",
  "password": "secret",
  "device_name": "postman" 
}
```

Response (200):
```json
{
  "token": "<long-token>",
  "token_type": "Bearer",
  "user": {
    "id": "...",
    "name": "...",
    "email": "...",
    "role": {"id":"...","name":"...","slug":"admin"}
  }
}
```

Errors:
- `422` invalid credentials.

### `GET /api/me`
Get current user.

Requires: `auth:sanctum`

### `POST /api/logout`
Revoke the current token.

Requires: `auth:sanctum`

## Conventions

### Pagination
List endpoints return Laravel paginated resources:
- `data`: array
- `links`: pagination links
- `meta`: pagination meta

Use query param:
- `?page=2`

### Dates
- `transaction_date`: `YYYY-MM-DD`
- `from/to`: `YYYY-MM-DD`

### Uploads
Endpoints that accept file uploads must use `multipart/form-data`:
- `POST /api/items` (field `image`)
- `PUT /api/items/{id}` (field `image`)
- `POST /api/transactions/in` (field `proof`)
- `POST /api/transactions/out` (field `proof`)
- `PUT /api/transactions/{id}` (field `proof`)

Note:
- Jika client kamu kesulitan mengirim `multipart/form-data` dengan HTTP `PUT`, gunakan `POST` dengan field tambahan `_method=PUT` (Laravel method override).

## Categories

Access:
- Read: `staff-gudang, admin, super-admin`
- Write: `admin, super-admin`

- `GET /api/categories`
- `GET /api/categories/{id}`
- `POST /api/categories`
- `PUT /api/categories/{id}`
- `DELETE /api/categories/{id}`

## Items

Access:
- Read: `staff-gudang, admin, super-admin`
- Write: `admin, super-admin`

Filters:
- `GET /api/items?q=...`
- `GET /api/items?category_id=<uuid>`

Endpoints:
- `GET /api/items`
- `GET /api/items/{id}`
- `POST /api/items`
- `PUT /api/items/{id}`
- `DELETE /api/items/{id}`

## Transactions

Access:
- Read + create IN/OUT: `staff-gudang, admin, super-admin`
- Update + delete: `admin, super-admin`

Filters:
- `GET /api/transactions?type=in|out&from=YYYY-MM-DD&to=YYYY-MM-DD`

Endpoints:
- `GET /api/transactions`
- `GET /api/transactions/{id}`
- `POST /api/transactions/in`
- `POST /api/transactions/out`
- `PUT /api/transactions/{id}`
  - Body can update: `transaction_date` (required), `note` (optional), `proof` (optional), `quantity` (optional)
  - If `quantity` changes, stock is recalculated safely (locked) and an adjustment mutation is recorded.
- `DELETE /api/transactions/{id}`
  - Safe reversal: stock is adjusted back; request is rejected if it would cause negative stock.

## Mutations

Access:
- `admin, super-admin`

Filters:
- `GET /api/mutations?item_id=<uuid>&action=...&from=YYYY-MM-DD&to=YYYY-MM-DD`

Endpoints:
- `GET /api/mutations`
- `GET /api/mutations/{id}`
