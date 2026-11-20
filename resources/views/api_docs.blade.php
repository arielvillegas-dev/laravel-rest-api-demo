<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel REST API Demo (v1)</title>
    <style>
        body { font-family: system-ui, sans-serif; background: #0f172a; color: #f8fafc; margin: 0; padding: 32px 16px; }
        .wrap { max-width: 860px; margin: 0 auto; }
        .card { background: #1e293b; border: 1px solid #334155; border-radius: 10px; padding: 20px; margin-bottom: 16px; }
        code { background: #0f172a; padding: 2px 6px; border-radius: 4px; color: #38bdf8; }
    </style>
</head>
<body>
<div class="wrap">
    <div class="card">
        <h1>Laravel REST API Demo (Sanctum + FormRequests + Policies)</h1>
        <p>Clean REST API reference architecture in Laravel 13 with token authentication, input validation, API Resources, ownership policies, pagination, filtering, rate limiting, and PHPUnit tests.</p>
    </div>
    <div class="card">
        <h2>Endpoints (<code>/api/v1</code>)</h2>
        <ul>
            <li><code>GET /api/v1/health</code> — Service health check</li>
            <li><code>POST /api/v1/auth/register</code> — Register user &amp; issue Sanctum token</li>
            <li><code>POST /api/v1/auth/login</code> — Authenticate &amp; issue Sanctum token</li>
            <li><code>GET /api/v1/auth/me</code> — Current authenticated user</li>
            <li><code>POST /api/v1/auth/logout</code> — Revoke active Bearer token</li>
            <li><code>GET|POST /api/v1/tasks</code> — Paginated list with filters &amp; creation</li>
            <li><code>GET|PATCH|DELETE /api/v1/tasks/{id}</code> — Policy-protected resource operations</li>
        </ul>
    </div>
</div>
</body>
</html>
