<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Installation Successful - NexusCMS</title>
    <style>
        :root {
            --bg-1: #071427;
            --bg-2: #0e2b45;
            --ok-1: #047857;
            --ok-2: #22c55e;
            --card: #f8fbff;
            --ink: #0f1f3b;
            --muted: #4f6280;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            font-family: "Segoe UI", "Trebuchet MS", sans-serif;
            background:
                radial-gradient(circle at 18% 22%, rgba(14, 165, 233, 0.28), transparent 35%),
                radial-gradient(circle at 78% 20%, rgba(34, 197, 94, 0.22), transparent 35%),
                linear-gradient(145deg, var(--bg-1), var(--bg-2));
            padding: 18px;
        }

        .card {
            width: min(720px, 100%);
            background: var(--card);
            color: var(--ink);
            border-radius: 22px;
            padding: 28px;
            box-shadow: 0 26px 60px rgba(1, 10, 24, 0.45);
            border: 1px solid rgba(208, 229, 249, 0.9);
        }

        .icon {
            width: 78px;
            height: 78px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 14px;
            background: linear-gradient(145deg, var(--ok-1), var(--ok-2));
            color: #fff;
        }

        h1 {
            margin: 0;
            font-size: clamp(1.5rem, 3vw, 2rem);
        }

        p {
            color: var(--muted);
            margin-top: 10px;
            line-height: 1.5;
        }

        .actions {
            margin-top: 20px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn {
            border-radius: 10px;
            text-decoration: none;
            font-weight: 700;
            padding: 11px 16px;
            font-size: 0.88rem;
            border: 1px solid transparent;
        }

        .btn-primary {
            color: #fff;
            background: linear-gradient(135deg, #0f766e, #0ea5e9);
        }

        .btn-secondary {
            color: #175188;
            background: #dceeff;
            border-color: #b5d9fb;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon">
            <svg width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
        </div>
        <h1>Instalación completada</h1>
        <p>{{ session('install_success_message', 'NexusCMS está listo para usarse. Ya puedes iniciar sesión con la cuenta administradora y continuar con la configuración desde el panel.') }}</p>
        <div class="actions">
            <a href="{{ url('/') }}" class="btn btn-primary">Ir al inicio</a>
            <a href="{{ route('login') }}" class="btn btn-secondary">Iniciar sesión</a>
        </div>
    </div>
</body>
</html>
