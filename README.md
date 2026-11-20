# Laravel REST API Demo (Sanctum + FormRequests + Policies)

Repositorio dedicado en **PHP 8.3 + Laravel 13** que implementa una arquitectura limpia de API REST v1 con autenticación por tokens (**Laravel Sanctum**), validación estricta, transformación de respuestas, políticas de autorización, paginación y pruebas automatizadas.

## 1. Problema que resuelve
Sirve como referencia directa de buenas prácticas para backend APIs en Laravel: separación clara entre transporte HTTP (`FormRequest`, `JsonResource`), autorización por recurso (`Policy`), filtrado/paginación seguros, limitación de tasa (`throttle`) y pruebas de integración reproducibles.

## 2. Tecnologías utilizadas
- **Backend:** PHP `^8.3`, Laravel `^13`, Laravel Sanctum `^4`, SQLite.
- **Pruebas:** PHPUnit `^12.5` sobre SQLite en memoria.

## 3. Arquitectura general
- `app/Http/Controllers/Api/V1/AuthController.php`: Registro, login, perfil (`/me`) y revocación de tokens (`/logout`).
- `app/Http/Controllers/Api/V1/TaskController.php`: CRUD paginado con búsqueda, filtros y ordenamiento seguro por lista blanca.
- `app/Http/Requests/`: `RegisterRequest`, `LoginRequest`, `StoreTaskRequest`, `UpdateTaskRequest`.
- `app/Http/Resources/TaskResource.php`: Contrato JSON consistente.
- `app/Policies/TaskPolicy.php`: Aislamiento por propietario (`403 Forbidden`).

## 4. Instrucciones para ejecutarlo

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

## 5. Pruebas disponibles
```bash
php artisan test
```
Verifica registro/login/logout con Sanctum, rechazo `401`, CRUD completo, autorización `403` entre usuarios, validaciones `422` y paginación con filtros.

## 6. Contribución real al desarrollo
Arquitectura API y suite de casos diseñada por Carlos Ariel Villegas Flores como repositorio de referencia backend, con asistencia de IA para scaffolding Laravel y verificación completa con `php artisan test`.
