# KanbanAPI

A scalable, production-ready REST API backend for a Kanban Board SaaS platform. Built on Laravel 12 with a strict layered architecture designed for long-term maintainability, clean separation of concerns, and consistent API contracts.

---

## Table of Contents

- [Tech Stack](#tech-stack)
- [Architecture Overview](#architecture-overview)
- [Folder Structure](#folder-structure)
- [Layer Responsibilities](#layer-responsibilities)
- [API Versioning](#api-versioning)
- [API Response Structure](#api-response-structure)
- [Authentication](#authentication)
- [Validation](#validation)
- [Error Handling](#error-handling)
- [Pagination and Performance](#pagination-and-performance)
- [Security Practices](#security-practices)
- [Coding Standards](#coding-standards)
- [Scalability Principles](#scalability-principles)
- [Development Philosophy](#development-philosophy)
- [Future Roadmap](#future-roadmap)

---

## Tech Stack

| Layer          | Technology           |
| -------------- | -------------------- |
| Language       | PHP 8.x              |
| Framework      | Laravel 12           |
| Database       | MySQL 8.x            |
| Authentication | JWT (tymon/jwt-auth) |
| ORM            | Eloquent             |
| API Style      | REST                 |

---

## Architecture Overview

The project follows a **Controller → Service → Repository** layered pattern. Each layer has a single, well-defined responsibility. HTTP concerns never bleed into business logic, and business logic never touches the database directly.

```
HTTP Request
    |
Middleware          (auth, throttle, CORS)
    |
Controller          (delegates, returns response)
    |
Form Request        (validates and sanitizes input)
    |
Service             (business logic, domain rules)
    |
Repository          (database queries, Eloquent abstraction)
    |
Model               (schema, relationships, casts)
    |
MySQL
```

Controllers know nothing about the database. Repositories know nothing about business rules. Services own the logic.

---

## Folder Structure

```
app/
├── Actions/
├── DTOs/
├── Enums/
├── Exceptions/
├── Helpers/
├── Http/
│   ├── Controllers/
│   │   └── Api/
│   │       └── V1/
│   ├── Middleware/
│   ├── Requests/
│   └── Resources/
├── Interfaces/
├── Models/
├── Repositories/
├── Services/
├── Traits/
└── Utils/
```

---

## Layer Responsibilities

**Controllers**
Accept incoming requests and delegate immediately to a Service. Return structured responses via API Resources. Contain no business logic.

**Services**
Own all domain rules and orchestration logic. Call repositories, apply rules, and return results. Fully independent of HTTP — testable in isolation.

**Repositories**
Handle all database interactions. Services never call Eloquent directly. The underlying data store can be swapped without touching the Service layer.

**Form Requests**
Validate and sanitize all incoming data before it reaches the controller body. Each POST, PUT, and PATCH route has a dedicated Form Request class.

**DTOs**
Typed containers passed between layers in place of raw arrays. Make data contracts explicit and enforce type safety across boundaries.

**API Resources**
Transform Eloquent models into consistent, versioned JSON response shapes. Control exactly what data is exposed per endpoint.

**Interfaces**
All Services and Repositories implement typed contracts. This enables dependency injection, clean IoC bindings, and easy test mocking.

**Enums**
PHP 8.1+ backed enums replace magic strings throughout the codebase. Status fields, roles, and priority levels are all enum-driven.

**Actions**
Single-purpose classes for complex operations that do not cleanly fit inside a Service method. Useful for CQRS-adjacent patterns and dispatchable jobs.

---

## API Versioning

All routes are namespaced under `/api/v{n}/` so new versions can be introduced without modifying or breaking existing consumers.

```
/api/v1/auth/login
/api/v1/boards
/api/v1/boards/{id}/cards
```

Versioning is enforced at every layer:

| Layer         | Location                       |
| ------------- | ------------------------------ |
| Routes        | `routes/api/v1.php`            |
| Controllers   | `App\Http\Controllers\Api\V1\` |
| Form Requests | `App\Http\Requests\V1\`        |
| API Resources | `App\Http\Resources\V1\`       |

---

## API Response Structure

Every endpoint returns the same JSON envelope. No endpoint exposes raw model data.

**Success**

```json
{
    "success": true,
    "message": "Board retrieved successfully.",
    "data": {},
    "meta": null
}
```

**Paginated**

```json
{
    "success": true,
    "message": "Boards retrieved successfully.",
    "data": [],
    "meta": {
        "current_page": 1,
        "per_page": 15,
        "total": 84,
        "last_page": 6
    }
}
```

**Error**

```json
{
    "success": false,
    "message": "Validation failed.",
    "errors": {
        "title": ["The title field is required."]
    },
    "data": null
}
```

The `ApiResponseTrait` is shared across all controllers to enforce this structure without duplication.

---

## Authentication

Authentication is stateless and token-based using JWT. No session storage. The API can scale horizontally without shared state.

```
Client                      API
  |                          |
  |-- POST /auth/login ------>|
  |                          |-- Validate credentials
  |                          |-- Issue access + refresh tokens
  |<-- { token } ------------|
  |                          |
  |-- GET /boards ----------->| Authorization: Bearer {token}
  |                          |-- JwtAuthMiddleware validates token
  |<-- { data } -------------|
```

| Token         | Lifetime   | Purpose                        |
| ------------- | ---------- | ------------------------------ |
| Access Token  | 60 minutes | Authorizes API requests        |
| Refresh Token | 7 days     | Renews access without re-login |

Expired tokens return `401 Unauthorized`. Token blacklisting is applied on logout.

---

## Validation

All input passes through a Laravel Form Request before reaching the controller. No raw `$request->all()` is used in controllers or services.

Each Form Request defines:

- `authorize()` — ownership and role checks before validation runs
- `rules()` — field-level validation rules
- `messages()` — user-facing error messages
- `attributes()` — human-readable field labels in errors

Validation failures are automatically formatted into the standard error envelope.

---

## Error Handling

All exceptions are caught in `app/Exceptions/Handler.php` and returned as consistent API responses.

| Exception                 | HTTP Status |
| ------------------------- | ----------- |
| `ValidationException`     | 422         |
| `AuthenticationException` | 401         |
| `AuthorizationException`  | 403         |
| `ModelNotFoundException`  | 404         |
| `ApiException` (custom)   | Varies      |
| Unhandled `Throwable`     | 500         |

`ApiException` is a domain-level exception that Services can throw with a message, status code, and optional payload — without importing HTTP concerns into the business layer.

In production, all 500 errors return a generic message. Full exception detail is only shown in development and staging environments.

---

## Pagination and Performance

All collection endpoints are paginated. Unbounded queries are not permitted.

```
GET /api/v1/boards?page=2&per_page=15
```

`PaginationUtil` normalizes pagination parameters across endpoints and enforces min/max per-page constraints.

Performance standards applied at the repository level:

| Practice         | Detail                                                  |
| ---------------- | ------------------------------------------------------- |
| Eager loading    | `::with([])` on all queries that traverse relationships |
| Column selection | No `SELECT *` — only required columns are fetched       |
| Query scopes     | Eloquent scopes for repeated filter patterns            |
| Database indexes | Applied on foreign keys and frequently filtered columns |

---

## Security Practices

| Area             | Implementation                                     |
| ---------------- | -------------------------------------------------- |
| Input validation | All input sanitized via Form Requests before use   |
| Authentication   | Stateless JWT — no server-side session             |
| Mass assignment  | `$fillable` defined on every model                 |
| SQL injection    | Eloquent ORM with parameterized queries only       |
| Rate limiting    | Throttle middleware on auth and public endpoints   |
| CORS             | Configured via Laravel's built-in CORS middleware  |
| Secrets          | All credentials stored in `.env` — never hardcoded |

---

## Coding Standards

- PSR-12 code style
- `declare(strict_types=1)` on all files
- Full type hints on method signatures (parameters and return types)
- PHPDoc on public methods and non-obvious logic
- No business logic in Controllers
- No Eloquent calls in Services
- No raw arrays passed between layers — DTOs instead

### Naming Conventions

| Component    | Pattern                         | Example                    |
| ------------ | ------------------------------- | -------------------------- |
| Controller   | `{Resource}Controller`          | `BoardController`          |
| Service      | `{Resource}Service`             | `BoardService`             |
| Repository   | `{Resource}Repository`          | `BoardRepository`          |
| Interface    | `{Resource}RepositoryInterface` | `BoardRepositoryInterface` |
| Form Request | `{Action}{Resource}Request`     | `CreateBoardRequest`       |
| DTO          | `{Action}{Resource}DTO`         | `CreateBoardDTO`           |
| Resource     | `{Resource}Resource`            | `BoardResource`            |

---

## Scalability Principles

**Stateless by design.** JWT auth requires no shared session store. Multiple application servers can run behind a load balancer without coordination.

**Database abstraction.** The repository pattern isolates all data access. Switching databases, adding a read replica, or introducing a caching layer requires changes only at the repository level.

**Caching is an extension point.** A caching repository decorator can wrap any existing repository without modifying the Service layer. The interface contract stays the same.

**Queue-ready.** Long-running operations (emails, reports, webhooks) are designed to be dispatched to Laravel Queues. Actions are first-class candidates for async dispatch.

**Modular path.** The current structure can be migrated to Laravel Modules or a domain-driven layout without rewriting logic, because domain boundaries are already enforced by the layered architecture.

---

## Development Philosophy

The layered architecture costs slightly more setup time upfront but pays back significantly as the product grows. The goal is a codebase where:

- Adding a feature means following a clear, predictable pattern
- Changing the database does not affect business logic
- Business logic can be tested without an HTTP request
- A new engineer can understand where code belongs without reading everything
- API contracts do not break for existing consumers when new versions are released

Consistency matters as much as correctness. Every endpoint follows the same structure, every response follows the same envelope, and every layer follows the same rules. This reduces cognitive overhead and makes the system predictable at scale.

---

## Future Roadmap

**Near-term**

- Role-based access control with granular permissions
- Event-driven side effects using Laravel Events and Listeners
- Audit trail and activity log per board and card
- Per-user and per-plan API rate limiting

**Mid-term**

- Real-time board updates via WebSocket (Laravel Reverb)
- Multi-tenancy with strict tenant isolation
- Redis caching layer for high-frequency reads
- Background job queue for async operations

**Long-term**

- Domain extraction into Laravel Modules
- Auto-generated OpenAPI / Swagger documentation
- GraphQL endpoint as an opt-in alternative to REST
- Microservice extraction path for independently scalable domains
