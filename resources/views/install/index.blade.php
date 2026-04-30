<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NexusCMS &mdash; Installation Wizard</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            font-size: 14px;
            line-height: 1.5;
            background: #edf0f5;
            color: #1a2130;
            min-height: 100vh;
        }

        /* ---- Layout ---- */
        .installer {
            display: flex;
            min-height: 100vh;
        }

        /* ---- Sidebar ---- */
        .sidebar {
            width: 264px;
            flex-shrink: 0;
            background: #1b2232;
            display: flex;
            flex-direction: column;
        }

        .sidebar-brand {
            padding: 28px 24px 24px;
            border-bottom: 1px solid rgba(255,255,255,0.07);
        }

        .sidebar-brand-name {
            font-size: 18px;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: 0.02em;
        }

        .sidebar-brand-sub {
            font-size: 11px;
            color: #7a8caa;
            margin-top: 3px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .sidebar-steps {
            padding: 20px 16px;
            flex: 1;
        }

        .sidebar-step {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 10px 8px;
            border-radius: 6px;
            cursor: default;
            user-select: none;
            margin-bottom: 2px;
        }

        .sidebar-step.is-current {
            background: rgba(55, 118, 255, 0.14);
        }

        .step-num {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
            flex-shrink: 0;
            border: 1.5px solid #3a4a63;
            color: #7a8caa;
            background: transparent;
            margin-top: 1px;
        }

        .sidebar-step.is-done .step-num {
            background: #1f6b46;
            border-color: #27915e;
            color: #a7f3cf;
        }

        .sidebar-step.is-current .step-num {
            background: #1a4dcc;
            border-color: #3776ff;
            color: #d0e4ff;
        }

        .step-check { display: none; }
        .sidebar-step.is-done .step-check { display: block; }
        .sidebar-step.is-done .step-num-digit { display: none; }

        .step-info { line-height: 1.3; }

        .step-label {
            font-size: 13px;
            font-weight: 600;
            color: #d0d8e8;
        }

        .sidebar-step.is-current .step-label { color: #ffffff; }
        .sidebar-step.is-done .step-label { color: #6d8ba8; }

        .step-sub {
            font-size: 11px;
            color: #4f617c;
            margin-top: 2px;
        }

        .sidebar-step.is-current .step-sub { color: #7b9cc4; }

        .sidebar-footer {
            padding: 16px 24px;
            border-top: 1px solid rgba(255,255,255,0.06);
            font-size: 11px;
            color: #3f5168;
        }

        /* ---- Content area ---- */
        .content {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .content-topbar {
            background: #ffffff;
            border-bottom: 1px solid #dce2ec;
            padding: 0 32px;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-shrink: 0;
        }

        .topbar-breadcrumb {
            font-size: 12px;
            color: #7a8caa;
        }

        .topbar-breadcrumb strong { color: #1a2130; }

        .topbar-progress {
            display: flex;
            gap: 4px;
            align-items: center;
        }

        .progress-pip {
            width: 28px;
            height: 4px;
            border-radius: 2px;
            background: #dde3ed;
        }

        .progress-pip.done   { background: #27915e; }
        .progress-pip.active { background: #3776ff; }

        .content-main {
            flex: 1;
            padding: 32px;
            overflow-y: auto;
        }

        .pane-header {
            margin-bottom: 24px;
            padding-bottom: 18px;
            border-bottom: 1px solid #dce2ec;
        }

        .pane-header h1 {
            font-size: 20px;
            font-weight: 700;
            color: #1a2130;
        }

        .pane-header p {
            font-size: 13px;
            color: #6b7a93;
            margin-top: 5px;
        }

        /* ---- Error box ---- */
        .alert-error {
            background: #fff2f2;
            border: 1px solid #fca5a5;
            border-left: 4px solid #dc2626;
            border-radius: 6px;
            padding: 12px 16px;
            margin-bottom: 20px;
            font-size: 13px;
            color: #7f1d1d;
        }

        .alert-error ul { margin: 6px 0 0 16px; }
        .alert-error li { margin-top: 3px; }

        /* ---- Step panes ---- */
        .step-pane { display: none; }
        .step-pane.current { display: block; }

        /* ---- Form sections ---- */
        .form-section {
            background: #ffffff;
            border: 1px solid #dce2ec;
            border-radius: 8px;
            margin-bottom: 16px;
        }

        .form-section-head {
            padding: 14px 20px;
            border-bottom: 1px solid #edf0f5;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-section-head h2 {
            font-size: 11px;
            font-weight: 700;
            color: #6b7a93;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin: 0;
        }

        .section-badge {
            font-size: 11px;
            font-weight: 600;
            padding: 2px 7px;
            border-radius: 99px;
            background: #edf2ff;
            color: #3358cc;
            border: 1px solid #c7d7ff;
        }

        .section-badge.optional {
            background: #f5f5f5;
            color: #6b7a93;
            border-color: #dce2ec;
        }

        .form-section-body { padding: 20px; }

        /* ---- Field grid ---- */
        .field-row {
            display: grid;
            grid-template-columns: repeat(12, minmax(0, 1fr));
            gap: 16px 14px;
            margin-bottom: 16px;
        }

        .field-row:last-child { margin-bottom: 0; }

        .f-full  { grid-column: span 12; }
        .f-half  { grid-column: span 6; }
        .f-third { grid-column: span 4; }
        .f-two   { grid-column: span 8; }
        .f-one   { grid-column: span 3; }

        .field label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: #3d4f68;
            margin-bottom: 6px;
            letter-spacing: 0.01em;
        }

        .field .field-hint {
            font-size: 11px;
            color: #8a9bb4;
            margin-top: 4px;
        }

        input[type="text"],
        input[type="url"],
        input[type="email"],
        input[type="number"],
        input[type="password"],
        select {
            width: 100%;
            height: 36px;
            padding: 0 10px;
            border: 1px solid #c8d0de;
            border-radius: 5px;
            background: #ffffff;
            color: #1a2130;
            font-size: 13px;
            font-family: inherit;
            appearance: none;
            -webkit-appearance: none;
            transition: border-color 0.15s, box-shadow 0.15s;
            box-sizing: border-box;
        }

        select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%236b7a93' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
            padding-right: 28px;
        }

        input:focus, select:focus {
            outline: none;
            border-color: #3776ff;
            box-shadow: 0 0 0 3px rgba(55, 118, 255, 0.12);
        }

        /* ---- Checkbox ---- */
        .checkbox-field {
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .checkbox-field input[type="checkbox"] {
            width: 16px;
            height: 16px;
            flex-shrink: 0;
            margin-top: 2px;
            cursor: pointer;
            accent-color: #3776ff;
        }

        .checkbox-field label {
            font-size: 13px;
            font-weight: 500;
            color: #2c3e58;
            cursor: pointer;
            line-height: 1.4;
            margin: 0;
        }

        /* ---- License box ---- */
        .license-text {
            background: #f8fafc;
            border: 1px solid #dce2ec;
            border-radius: 6px;
            padding: 16px 18px;
            max-height: 240px;
            overflow-y: auto;
            font-size: 13px;
            line-height: 1.65;
            color: #3d4f68;
        }

        .license-text p { margin-bottom: 10px; }
        .license-text p:last-child { margin-bottom: 0; }

        /* ---- Requirements table ---- */
        .req-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        .req-table th {
            text-align: left;
            font-size: 11px;
            font-weight: 700;
            color: #6b7a93;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            padding: 0 12px 10px;
            border-bottom: 1px solid #dce2ec;
        }

        .req-table td {
            padding: 9px 12px;
            border-bottom: 1px solid #edf0f5;
            color: #2c3e58;
        }

        .req-table tr:last-child td { border-bottom: 0; }

        .req-status { font-weight: 700; font-size: 12px; }
        .req-status.ok  { color: #16a34a; }
        .req-status.err { color: #dc2626; }

        .req-value {
            color: #6b7a93;
            font-family: "Cascadia Code", "Fira Mono", "Consolas", monospace;
            font-size: 12px;
        }

        /* ---- DB test ---- */
        .db-test-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid #edf0f5;
        }

        .db-feedback { font-size: 13px; font-weight: 600; }
        .db-feedback.pending { color: #6b7a93; }
        .db-feedback.ok  { color: #16a34a; }
        .db-feedback.err { color: #dc2626; }

        /* ---- Footer bar ---- */
        .content-footer {
            background: #ffffff;
            border-top: 1px solid #dce2ec;
            padding: 14px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-shrink: 0;
        }

        .footer-left, .footer-right {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* ---- Buttons ---- */
        .btn {
            height: 36px;
            padding: 0 16px;
            font-size: 13px;
            font-weight: 600;
            border-radius: 5px;
            border: 1px solid transparent;
            cursor: pointer;
            font-family: inherit;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            transition: background 0.1s;
        }

        .btn-ghost {
            background: transparent;
            border-color: #c8d0de;
            color: #3d4f68;
        }

        .btn-ghost:hover { background: #f0f3f8; }

        .btn-primary {
            background: #2563eb;
            border-color: #1d4ed8;
            color: #ffffff;
        }

        .btn-primary:hover { background: #1d4ed8; }

        .btn-success {
            background: #15803d;
            border-color: #166534;
            color: #ffffff;
        }

        .btn-success:hover { background: #166534; }
        .btn:disabled { opacity: 0.55; cursor: not-allowed; }

        /* ---- Confirmation checklist ---- */
        .checklist {
            list-style: none;
            font-size: 13px;
            color: #3d4f68;
        }

        .checklist li {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            padding: 7px 0;
            border-bottom: 1px solid #edf0f5;
        }

        .checklist li:last-child { border-bottom: 0; }

        .checklist li::before {
            content: "→";
            color: #6b7a93;
            flex-shrink: 0;
            margin-top: 1px;
        }

        code {
            background: #edf0f5;
            border-radius: 3px;
            padding: 1px 5px;
            font-size: 12px;
            font-family: "Cascadia Code", "Fira Mono", "Consolas", monospace;
        }

        /* ---- Responsive ---- */
        @media (max-width: 860px) {
            .sidebar { width: 220px; }
            .content-main, .content-topbar, .content-footer { padding-left: 20px; padding-right: 20px; }
        }

        @media (max-width: 640px) {
            .installer { flex-direction: column; }
            .sidebar { width: 100%; }
            .sidebar-steps { display: flex; flex-wrap: nowrap; overflow-x: auto; padding: 10px 16px; gap: 4px; }
            .sidebar-step { flex-direction: column; gap: 4px; min-width: 80px; text-align: center; padding: 8px; }
            .f-half, .f-third, .f-one, .f-two { grid-column: span 12; }
        }

    </style>
</head>
<body>
<div class="installer">

    {{-- Sidebar --}}
    <aside class="sidebar">
        <div class="sidebar-brand">
            <div class="sidebar-brand-name">NexusCMS</div>
            <div class="sidebar-brand-sub">Installation Wizard</div>
        </div>

        <nav class="sidebar-steps" id="stepList">
            <div class="sidebar-step" data-step="0">
                <div class="step-num">
                    <span class="step-num-digit">1</span>
                    <svg class="step-check" width="13" height="13" viewBox="0 0 13 13" fill="none"><path d="M2.5 6.5l3 3 5-5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
                <div class="step-info">
                    <div class="step-label">Licencia</div>
                    <div class="step-sub">EULA y requisitos</div>
                </div>
            </div>
            <div class="sidebar-step" data-step="1">
                <div class="step-num">
                    <span class="step-num-digit">2</span>
                    <svg class="step-check" width="13" height="13" viewBox="0 0 13 13" fill="none"><path d="M2.5 6.5l3 3 5-5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
                <div class="step-info">
                    <div class="step-label">Requisitos</div>
                    <div class="step-sub">Verificación del entorno</div>
                </div>
            </div>
            <div class="sidebar-step" data-step="2">
                <div class="step-num">
                    <span class="step-num-digit">3</span>
                    <svg class="step-check" width="13" height="13" viewBox="0 0 13 13" fill="none"><path d="M2.5 6.5l3 3 5-5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
                <div class="step-info">
                    <div class="step-label">Aplicación</div>
                    <div class="step-sub">Nombre, URL e idioma</div>
                </div>
            </div>
            <div class="sidebar-step" data-step="3">
                <div class="step-num">
                    <span class="step-num-digit">4</span>
                    <svg class="step-check" width="13" height="13" viewBox="0 0 13 13" fill="none"><path d="M2.5 6.5l3 3 5-5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
                <div class="step-info">
                    <div class="step-label">Base de datos</div>
                    <div class="step-sub">Conexión principal</div>
                </div>
            </div>
            <div class="sidebar-step" data-step="4">
                <div class="step-num">
                    <span class="step-num-digit">5</span>
                    <svg class="step-check" width="13" height="13" viewBox="0 0 13 13" fill="none"><path d="M2.5 6.5l3 3 5-5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
                <div class="step-info">
                    <div class="step-label">Administrador</div>
                    <div class="step-sub">Cuenta inicial</div>
                </div>
            </div>
            <div class="sidebar-step" data-step="5">
                <div class="step-num">
                    <span class="step-num-digit">6</span>
                    <svg class="step-check" width="13" height="13" viewBox="0 0 13 13" fill="none"><path d="M2.5 6.5l3 3 5-5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
                <div class="step-info">
                    <div class="step-label">Primer reino</div>
                    <div class="step-sub">Realm y consola</div>
                </div>
            </div>
            <div class="sidebar-step" data-step="6">
                <div class="step-num">
                    <span class="step-num-digit">7</span>
                    <svg class="step-check" width="13" height="13" viewBox="0 0 13 13" fill="none"><path d="M2.5 6.5l3 3 5-5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </div>
                <div class="step-info">
                    <div class="step-label">Confirmación</div>
                    <div class="step-sub">Resumen final</div>
                </div>
            </div>
        </nav>

        <div class="sidebar-footer">NexusCMS &mdash; Setup</div>
    </aside>

    {{-- Content --}}
    <div class="content">
        <div class="content-topbar">
            <span class="topbar-breadcrumb" id="topbarBreadcrumb">Paso <strong>1</strong> de 6</span>
            <div class="topbar-progress">
                <div class="progress-pip" data-pip="0"></div>
                <div class="progress-pip" data-pip="1"></div>
                <div class="progress-pip" data-pip="2"></div>
                <div class="progress-pip" data-pip="3"></div>
                <div class="progress-pip" data-pip="4"></div>
                <div class="progress-pip" data-pip="5"></div>
                <div class="progress-pip" data-pip="6"></div>
            </div>
        </div>

        <div class="content-main">
            <div class="pane-header">
                <h1 id="paneTitle">Licencia</h1>
                <p id="paneDesc">Lee y acepta los términos de la GNU General Public License v3.0.</p>
            </div>

            @if($errors->any())
                <div class="alert-error">
                    <strong>No se pudo completar la instalación:</strong>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('install.run') }}" method="POST" id="installerForm" novalidate>
                @csrf

                {{-- Step 0: License & Requirements --}}
                <div class="step-pane" data-pane="0">
                    <div class="form-section">
                        <div class="form-section-head">
                            <h2>Acuerdo de licencia (EULA)</h2>
                        </div>
                        <div class="form-section-body">
                            <div class="license-text">
                                <p><strong>NexusCMS</strong> es un proyecto de código abierto publicado bajo la <strong>GNU General Public License v3.0</strong> (GPL-3.0). Al continuar con la instalación confirmas que has leído y aceptas los términos de esta licencia.</p>

                                <p>El software se distribuye de forma gratuita y con plena libertad de uso. Puedes ejecutarlo para cualquier propósito, estudiar cómo funciona, adaptarlo a tus necesidades y redistribuir tanto la versión original como tus modificaciones. La única condición es que cualquier versión derivada que distribuyas públicamente debe conservar este mismo aviso de licencia y publicarse también bajo GPL-3.0, garantizando que los demás usuarios reciban las mismas libertades que tú tienes.</p>

                                <p>El uso comercial está permitido sin coste adicional. Sin embargo, si decides distribuir una versión modificada de NexusCMS —ya sea de forma gratuita o de pago— estás obligado a hacer disponible el código fuente de tus cambios bajo la misma licencia. Esta condición es la base del modelo copyleft que protege el ecosistema de software libre.</p>

                                <p>NexusCMS se entrega <em>tal cual</em>, sin ninguna garantía expresa ni implícita. Los autores y contribuidores no asumen responsabilidad por daños derivados del uso del software, interrupciones de servicio, pérdida de datos ni ninguna otra consecuencia directa o indirecta. La instalación y operación del sistema son responsabilidad exclusiva del administrador que realiza el despliegue, quien debe garantizar la seguridad del servidor, la protección de los datos de los usuarios y el cumplimiento de la normativa aplicable, incluyendo el Reglamento General de Protección de Datos (GDPR) u otras leyes de privacidad vigentes en su jurisdicción.</p>

                                <p>Las contribuciones enviadas al repositorio oficial del proyecto —a través de pull requests, issues u otros medios— se considerarán licenciadas bajo GPL-3.0 salvo acuerdo escrito distinto. La comunidad de NexusCMS valora y agradece cada aportación, sea código, documentación, traducciones o informes de error.</p>

                                <p>El texto íntegro de la GNU General Public License v3.0 está disponible en <em>https://www.gnu.org/licenses/gpl-3.0.html</em> y en el archivo <code>LICENSE</code> incluido en el repositorio del proyecto.</p>
                            </div>
                            <div class="checkbox-field" style="margin-top:14px;">
                                <input type="checkbox" id="agree_eula" name="agree_eula" value="1" {{ old('agree_eula') ? 'checked' : '' }} required>
                                <label for="agree_eula">He leído y acepto el acuerdo de licencia (EULA) para continuar con la instalación.</label>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Step 1: Requirements --}}
                <div class="step-pane" data-pane="1">
                    <div class="form-section">
                        <div class="form-section-head">
                            <h2>Requisitos del sistema</h2>
                            @if($allRequirementsPassed)
                                <span class="section-badge">Todo en orden</span>
                            @else
                                <span class="section-badge" style="background:#fff2f2;color:#991b1b;border-color:#fca5a5;">Hay fallos</span>
                            @endif
                        </div>
                        <div class="form-section-body" style="padding:0;">
                            <table class="req-table">
                                <thead>
                                    <tr>
                                        <th>Requisito</th>
                                        <th>Estado</th>
                                        <th>Valor actual</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($requirements as $req)
                                        <tr>
                                            <td>{{ $req['label'] }}</td>
                                            <td><span class="req-status {{ $req['ok'] ? 'ok' : 'err' }}">{!! $req['ok'] ? '&#10003; OK' : '&#10007; Fallo' !!}</span></td>
                                            <td class="req-value">{{ $req['current'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Step 2: App config --}}
                <div class="step-pane" data-pane="1">
                    <div class="form-section">
                        <div class="form-section-head">
                            <h2>Configuración general</h2>
                        </div>
                        <div class="form-section-body">
                            <div class="field-row">
                                <div class="field f-half">
                                    <label for="app_name">Nombre de la aplicación</label>
                                    <input type="text" id="app_name" name="app_name" value="{{ old('app_name', 'NexusCMS') }}" required>
                                </div>
                                <div class="field f-half">
                                    <label for="app_url">URL base</label>
                                    <input type="url" id="app_url" name="app_url" value="{{ old('app_url', url('/')) }}" required>
                                    <span class="field-hint">Ej: https://misite.com &mdash; sin barra al final.</span>
                                </div>
                            </div>
                            <div class="field-row">
                                <div class="field f-third">
                                    <label for="locale">Idioma predeterminado</label>
                                    <select id="locale" name="locale">
                                        <option value="es" {{ old('locale', 'es') === 'es' ? 'selected' : '' }}>Español</option>
                                        <option value="en" {{ old('locale') === 'en' ? 'selected' : '' }}>English</option>
                                        <option value="fr" {{ old('locale') === 'fr' ? 'selected' : '' }}>Français</option>
                                        <option value="de" {{ old('locale') === 'de' ? 'selected' : '' }}>Deutsch</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Step 2: Database --}}
                <div class="step-pane" data-pane="2">
                    <div class="form-section">
                        <div class="form-section-head">
                            <h2>Base de datos de NexusCMS</h2>
                        </div>
                        <div class="form-section-body">
                            <div class="field-row">
                                <div class="field f-two">
                                    <label for="db_host">Host</label>
                                    <input type="text" id="db_host" name="db_host" value="{{ old('db_host', '127.0.0.1') }}" required>
                                </div>
                                <div class="field f-one">
                                    <label for="db_port">Puerto</label>
                                    <input type="number" id="db_port" name="db_port" min="1" max="65535" value="{{ old('db_port', 3306) }}" required>
                                </div>
                            </div>
                            <div class="field-row">
                                <div class="field f-third">
                                    <label for="db_name">Nombre de la base de datos</label>
                                    <input type="text" id="db_name" name="db_name" value="{{ old('db_name') }}" required>
                                </div>
                                <div class="field f-third">
                                    <label for="db_username">Usuario</label>
                                    <input type="text" id="db_username" name="db_username" value="{{ old('db_username') }}" required>
                                </div>
                                <div class="field f-third">
                                    <label for="db_password">Contraseña</label>
                                    <input type="password" id="db_password" name="db_password" value="{{ old('db_password') }}">
                                </div>
                            </div>
                            <div class="db-test-row">
                                <button type="button" class="btn btn-ghost" id="testConnection">Probar conexión</button>
                                <span id="dbFeedback" class="db-feedback pending">Sin verificar</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Step 3: Admin account --}}
                <div class="step-pane" data-pane="3">
                    <div class="form-section">
                        <div class="form-section-head">
                            <h2>Cuenta administradora</h2>
                        </div>
                        <div class="form-section-body">
                            <div class="field-row">
                                <div class="field f-half">
                                    <label for="admin_name">Nombre</label>
                                    <input type="text" id="admin_name" name="admin_name" value="{{ old('admin_name') }}" required>
                                </div>
                                <div class="field f-half">
                                    <label for="admin_email">Email</label>
                                    <input type="email" id="admin_email" name="admin_email" value="{{ old('admin_email') }}" required>
                                </div>
                            </div>
                            <div class="field-row">
                                <div class="field f-half">
                                    <label for="admin_password">Contraseña</label>
                                    <input type="password" id="admin_password" name="admin_password" required>
                                    <span class="field-hint">Mínimo 8 caracteres.</span>
                                </div>
                                <div class="field f-half">
                                    <label for="admin_password_confirmation">Confirmar contraseña</label>
                                    <input type="password" id="admin_password_confirmation" name="admin_password_confirmation" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Step 4: First realm --}}
                <div class="step-pane" data-pane="4">
                    <div class="form-section">
                        <div class="form-section-head">
                            <h2>Configuración del realm</h2>
                        </div>
                        <div class="form-section-body">
                            <div class="field-row">
                                <div class="field f-third">
                                    <label for="realm_name">Nombre del reino</label>
                                    <input type="text" id="realm_name" name="realm_name" value="{{ old('realm_name', 'Realm #1') }}" required>
                                </div>
                                <div class="field f-third">
                                    <label for="realm_hostname">Hostname</label>
                                    <input type="text" id="realm_hostname" name="realm_hostname" value="{{ old('realm_hostname', '127.0.0.1') }}" required>
                                </div>
                                <div class="field f-one">
                                    <label for="realm_port">Puerto</label>
                                    <input type="number" id="realm_port" name="realm_port" min="1" max="65535" value="{{ old('realm_port', 8085) }}" required>
                                </div>
                            </div>
                            <div class="field-row">
                                <div class="field f-third">
                                    <label for="realm_expansion">Expansión</label>
                                    <select id="realm_expansion" name="realm_expansion" required>
                                        @foreach($expansions as $expansionId => $expansionName)
                                            <option value="{{ $expansionId }}" {{ (int) old('realm_expansion', $defaultExpansion) === (int) $expansionId ? 'selected' : '' }}>{{ $expansionName }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="field f-third">
                                    <label for="realm_emulator">Emulador</label>
                                    <select id="realm_emulator" name="realm_emulator" required>
                                        @foreach($emulators as $emulatorValue => $emulatorLabel)
                                            <option value="{{ $emulatorValue }}" {{ old('realm_emulator', $defaultEmulator) === $emulatorValue ? 'selected' : '' }}>{{ $emulatorLabel }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="field f-third">
                                    <label for="realm_console_urn">URN de consola</label>
                                    <select id="realm_console_urn" name="realm_console_urn" required>
                                        @foreach($emulatorUrns as $emulatorValue => $urnValue)
                                            <option value="{{ $urnValue }}" {{ old('realm_console_urn') === $urnValue ? 'selected' : '' }}>{{ $emulators[$emulatorValue] ?? $emulatorValue }} ({{ $urnValue }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="checkbox-field" style="margin-top:8px;">
                                <input type="checkbox" id="realm_bnet" name="realm_bnet" value="1" {{ old('realm_bnet') ? 'checked' : '' }}>
                                <label for="realm_bnet">Habilitar autenticación Battle.net para este reino</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <div class="form-section-head">
                            <h2>Consola remota</h2>
                            <span class="section-badge optional">SOAP / RA</span>
                        </div>
                        <div class="form-section-body">
                            <div class="field-row">
                                <div class="field f-two">
                                    <label for="realm_console_hostname">Host</label>
                                    <input type="text" id="realm_console_hostname" name="realm_console_hostname" value="{{ old('realm_console_hostname', '127.0.0.1') }}" required>
                                </div>
                                <div class="field f-one">
                                    <label for="realm_console_port">Puerto</label>
                                    <input type="number" id="realm_console_port" name="realm_console_port" min="1" max="65535" value="{{ old('realm_console_port', 3443) }}">
                                </div>
                            </div>
                            <div class="field-row">
                                <div class="field f-half">
                                    <label for="realm_console_username">Usuario</label>
                                    <input type="text" id="realm_console_username" name="realm_console_username" value="{{ old('realm_console_username') }}" required>
                                </div>
                                <div class="field f-half">
                                    <label for="realm_console_password">Contraseña</label>
                                    <input type="password" id="realm_console_password" name="realm_console_password" value="{{ old('realm_console_password') }}" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    @php
                        $dbSections = [
                            'realm_auth'       => 'Auth DB',
                            'realm_characters' => 'Characters DB',
                            'realm_world'      => 'World DB',
                        ];
                    @endphp

                    @foreach($dbSections as $key => $sectionLabel)
                        <div class="form-section">
                            <div class="form-section-head">
                                <h2>{{ $sectionLabel }}</h2>
                            </div>
                            <div class="form-section-body">
                                <div class="field-row">
                                    <div class="field f-two">
                                        <label for="{{ $key }}_host">Host</label>
                                        <input type="text" id="{{ $key }}_host" name="{{ $key }}[host]" value="{{ old($key . '.host', '127.0.0.1') }}" required>
                                    </div>
                                    <div class="field f-one">
                                        <label for="{{ $key }}_port">Puerto</label>
                                        <input type="number" id="{{ $key }}_port" name="{{ $key }}[port]" min="1" max="65535" value="{{ old($key . '.port', 3306) }}">
                                    </div>
                                </div>
                                <div class="field-row">
                                    <div class="field f-third">
                                        <label for="{{ $key }}_database">Base de datos</label>
                                        <input type="text" id="{{ $key }}_database" name="{{ $key }}[database]" value="{{ old($key . '.database') }}" required>
                                    </div>
                                    <div class="field f-third">
                                        <label for="{{ $key }}_username">Usuario</label>
                                        <input type="text" id="{{ $key }}_username" name="{{ $key }}[username]" value="{{ old($key . '.username') }}" required>
                                    </div>
                                    <div class="field f-third">
                                        <label for="{{ $key }}_password">Contraseña</label>
                                        <input type="password" id="{{ $key }}_password" name="{{ $key }}[password]" value="{{ old($key . '.password') }}" required>
                                    </div>
                                    <div class="field f-half">
                                        <label for="{{ $key }}_charset">Charset</label>
                                        <input type="text" id="{{ $key }}_charset" name="{{ $key }}[charset]" value="{{ old($key . '.charset', 'utf8mb4') }}">
                                    </div>
                                    <div class="field f-half">
                                        <label for="{{ $key }}_collation">Collation</label>
                                        <input type="text" id="{{ $key }}_collation" name="{{ $key }}[collation]" value="{{ old($key . '.collation', 'utf8mb4_unicode_ci') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Step 5: Confirm --}}
                <div class="step-pane" data-pane="5">
                    <div class="form-section">
                        <div class="form-section-head">
                            <h2>Resumen de la instalación</h2>
                        </div>
                        <div class="form-section-body">
                            <ul class="checklist">
                                <li>Se escribirá el archivo <code>.env</code> con la configuración de entorno.</li>
                                <li>Se ejecutarán las migraciones de base de datos y los seeders iniciales.</li>
                                <li>Se creará la cuenta administradora con el rol Admin.</li>
                                <li>Se registrará el primer reino con sus bases de datos.</li>
                                <li>Se generará la clave de aplicación y se optimizarán las cachés.</li>
                                <li>Se creará el archivo <code>storage/installed.lock</code> para bloquear el asistente.</li>
                            </ul>
                            <div class="checkbox-field" style="margin-top:18px; padding-top:16px; border-top:1px solid #edf0f5;">
                                <input type="checkbox" id="confirm_apply" required>
                                <label for="confirm_apply">Confirmo que los datos introducidos son correctos y quiero iniciar la instalación.</label>
                            </div>
                        </div>
                    </div>
                </div>

            </form>
        </div>

        <div class="content-footer">
            <div class="footer-left">
                <button type="button" class="btn btn-ghost" id="prevBtn">&#8592; Anterior</button>
            </div>
            <div class="footer-right">
                <button type="button" class="btn btn-primary" id="nextBtn">Siguiente &#8594;</button>
                <button type="submit" form="installerForm" class="btn btn-success" id="installBtn">Instalar NexusCMS</button>
            </div>
        </div>
    </div>

</div>

<script>
    const requirementsPassed = @json($allRequirementsPassed);

    const stepMeta = [
        { title: 'Licencia',                        desc: 'Lee y acepta los términos de la GNU General Public License v3.0.' },
        { title: 'Requisitos del sistema',          desc: 'Verifica que el entorno cumpla los requisitos mínimos para instalar NexusCMS.' },
        { title: 'Configuración de la aplicación', desc: 'Nombre público, URL base e idioma predeterminado del CMS.' },
        { title: 'Base de datos principal',         desc: 'Conexión que usará Laravel para las migraciones y datos del CMS.' },
        { title: 'Cuenta administradora',           desc: 'Credenciales del primer usuario con permisos de administración.' },
        { title: 'Primer reino',                    desc: 'Datos del realm, consola remota y conexiones Auth / Characters / World.' },
        { title: 'Confirmación final',              desc: 'Revisa el resumen y ejecuta la instalación.' },
    ];

    const sidebarSteps = Array.from(document.querySelectorAll('.sidebar-step'));
    const panes        = Array.from(document.querySelectorAll('.step-pane'));
    const pips         = Array.from(document.querySelectorAll('.progress-pip'));
    const paneTitle    = document.getElementById('paneTitle');
    const paneDesc     = document.getElementById('paneDesc');
    const breadcrumb   = document.getElementById('topbarBreadcrumb');
    const nextBtn      = document.getElementById('nextBtn');
    const prevBtn      = document.getElementById('prevBtn');
    const installBtn   = document.getElementById('installBtn');
    const form         = document.getElementById('installerForm');
    const dbFeedback   = document.getElementById('dbFeedback');
    const testConnBtn  = document.getElementById('testConnection');
    const emulatorSel  = document.getElementById('realm_emulator');
    const urnSel       = document.getElementById('realm_console_urn');

    let currentStep = 0;

    function updateWizard() {
        sidebarSteps.forEach((item, i) => {
            item.classList.toggle('is-current', i === currentStep);
            item.classList.toggle('is-done', i < currentStep);
        });

        panes.forEach((pane, i) => {
            pane.classList.toggle('current', i === currentStep);
        });

        pips.forEach((pip, i) => {
            pip.classList.remove('done', 'active');
            if (i < currentStep)   pip.classList.add('done');
            if (i === currentStep) pip.classList.add('active');
        });

        const meta = stepMeta[currentStep];
        paneTitle.textContent = meta.title;
        paneDesc.textContent  = meta.desc;
        breadcrumb.innerHTML  = 'Paso <strong>' + (currentStep + 1) + '</strong> de ' + panes.length;

        prevBtn.style.visibility = currentStep === 0 ? 'hidden' : 'visible';
        const isLast = currentStep === panes.length - 1;
        nextBtn.style.display    = isLast ? 'none'        : 'inline-flex';
        installBtn.style.display = isLast ? 'inline-flex' : 'none';
    }

    function validateCurrentStep() {
        const pane = panes[currentStep];
        if (!pane) return true;

        if (currentStep === 1 && !requirementsPassed) {
            alert('No puedes continuar hasta resolver los requisitos del sistema marcados como Fallo.');
            return false;
        }

        const required = pane.querySelectorAll('input[required], select[required]');
        for (const field of required) {
            if (field.type === 'checkbox') {
                if (!field.checked) {
                    field.focus();
                    alert('Debes marcar el checkbox obligatorio de este paso para continuar.');
                    return false;
                }
                continue;
            }
            if (!field.value.trim()) {
                field.focus();
                alert('Completa todos los campos obligatorios antes de continuar.');
                return false;
            }
        }

        if (currentStep === 4) {
            const pass = document.getElementById('admin_password').value;
            const conf = document.getElementById('admin_password_confirmation').value;
            if (pass.length < 8) {
                alert('La contraseña debe tener al menos 8 caracteres.');
                return false;
            }
            if (pass !== conf) {
                alert('Las contraseñas no coinciden.');
                return false;
            }
        }

        if (currentStep === panes.length - 1) {
            const chk = document.getElementById('confirm_apply');
            if (chk && !chk.checked) {
                alert('Debes marcar la casilla de confirmación para ejecutar la instalación.');
                return false;
            }
        }

        return true;
    }

    nextBtn.addEventListener('click', () => {
        if (!validateCurrentStep()) return;
        if (currentStep < panes.length - 1) {
            currentStep++;
            updateWizard();
            window.scrollTo(0, 0);
        }
    });

    prevBtn.addEventListener('click', () => {
        if (currentStep > 0) {
            currentStep--;
            updateWizard();
            window.scrollTo(0, 0);
        }
    });

    testConnBtn.addEventListener('click', async () => {
        dbFeedback.textContent = 'Probando conexión\u2026';
        dbFeedback.className   = 'db-feedback pending';
        try {
            const body = new FormData(form);
            const res  = await fetch('{{ route("install.testDb") }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                body,
            });
            const data = await res.json();
            dbFeedback.textContent = data.message || (data.success ? 'Conexión exitosa' : 'Error de conexión');
            dbFeedback.className   = 'db-feedback ' + (data.success ? 'ok' : 'err');
        } catch {
            dbFeedback.textContent = 'No se pudo contactar con el servidor.';
            dbFeedback.className   = 'db-feedback err';
        }
    });

    emulatorSel.addEventListener('change', () => {
        const val   = emulatorSel.value;
        const match = Array.from(urnSel.options).find(o => o.value === val);
        if (match) urnSel.value = val;
    });

    form.addEventListener('submit', (e) => {
        currentStep = panes.length - 1;
        if (!validateCurrentStep()) {
            e.preventDefault();
            updateWizard();
            return;
        }
        installBtn.disabled    = true;
        installBtn.textContent = 'Instalando\u2026';
    });

    updateWizard();
</script>
</body>
</html>