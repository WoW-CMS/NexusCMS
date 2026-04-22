# Admin Panel – Roadmap de desarrollo

> Trabajo en solitario. Las versiones están ordenadas por prioridad y carga de trabajo realista.

---

## v0.1.6 – Completar la base funcional del Admin

Objetivo: que todo lo que ya existe en el ACP funcione de verdad con datos reales y sin stubs.

### Dashboard (`/acp`)
- [ ] Conectar las stat cards con datos reales:
  - Total de cuentas → `User::count()`
  - Jugadores online → query al realm via `RealmHelper`
  - Revenue del mes → agregado de donaciones (si existe tabla)
  - Server uptime → dato del realm o valor configurable
- [ ] Sección "Recent Activity" con datos reales (últimos logins, cambios de ajustes, etc.)
- [ ] Sección "Realms Status" conectada a los reinos configurados
- [ ] Sección "Recent Donations" conectada a la tabla de donaciones (o ocultar si el módulo Donate no está activo)
- [ ] Eliminar todos los valores hardcodeados del blade (`15,430`, `1,250`, `$8,420`, `99.8%`)

### Settings – Tabs incompletos
- [ ] **Appearance** – Subir logo/favicon (upload de imagen a `storage/`), previsualización en tiempo real
- [ ] **Maintenance** – Toggle real de modo mantenimiento vía `php artisan down/up` o `App::isDownForMaintenance()`; el formulario solo guarda el mensaje pero no activa el modo
- [ ] **Security** – Las reglas de contraseña guardadas en settings deben conectarse al flujo de validación de registro/cambio de contraseña
- [ ] **Localization** – Conectar `locale` guardado en settings con `App::setLocale()` en el middleware

### Analytics (`/acp/analytics`)
- [ ] Añadir gráfica de visitas (Chart.js o similar) con datos de `analytics_page_views` agrupados por día
- [ ] Filtro por período funcional (actualmente los botones existen en la vista pero la lógica backend sí está, falta la gráfica visual)

### Backups (`/acp/backups`)
- [ ] Incluir en el ZIP los archivos de `storage/app/` (imágenes subidas, etc.), actualmente solo se incluyen DB dump, `.env` y config
- [ ] Añadir opción de excluir archivos grandes del backup
- [ ] Indicador de progreso o feedback al generar (la generación puede tardar)

### Usuarios (`/acp/users`)
- [ ] Vista de detalle de usuario (actualmente solo hay index/create/edit)
- [ ] Filtro/búsqueda en el listado de usuarios
- [ ] Mostrar fecha del último login del usuario en el listado/edición

### Módulos (`/acp/modules`)
- [ ] Al hacer toggle enable/disable mostrar advertencia si el módulo tiene dependencias
- [ ] Validar que el módulo tiene `module.json` válido antes de intentar migrate

---

## v0.1.7 – Funcionalidades avanzadas del Admin

Objetivo: herramientas de administración más potentes, gestión avanzada de contenido y permisos.

### Gestión de News/Contenido
- [ ] CRUD de noticias desde el ACP (`/acp/news`) con editor de texto enriquecido (TipTap o similar)
- [ ] CRUD de categorías de noticias
- [ ] Moderar comentarios (listar, aprobar, eliminar) desde el ACP

### Gestión de usuarios – mejoras
- [ ] Acción de ban/unban de usuarios con motivo y duración
- [ ] Impersonate (actuar como usuario) para depuración
- [ ] Historial de acciones del usuario (log de actividad)

### Roles y permisos – mejoras
- [ ] Asignación de permisos individuales a usuarios (actualmente solo por rol)
- [ ] Vista de qué usuarios tienen un rol concreto directamente desde la página del rol

### Media Manager
- [ ] Gestor básico de archivos/imágenes subidos (`/acp/media`)
- [ ] Listar, eliminar y previsualizar archivos en `storage/images/`

### Audit Log
- [ ] Registro de acciones administrativas (quién cambió qué ajuste, quién baneó a quién, etc.)
- [ ] Vista en el ACP para consultar el log de auditoría con filtros

### Analytics – mejoras
- [ ] Gráfica de páginas más visitadas con histórico
- [ ] Estadística de nuevos registros por día
- [ ] Exportar datos de analytics a CSV

### Settings – completar API tab
- [ ] Generación y revocación de API tokens para acceso externo
- [ ] Documentación inline de los endpoints disponibles

---

## Notas

- **Store** queda fuera de este roadmap (estimado para v0.1.7 o v0.1.8 dependiendo del volumen de trabajo).
- Las tareas de v0.1.7 asumen que v0.1.6 está completamente terminado y estable.
- El orden dentro de cada versión no es estricto; prioriza lo que más uses/necesites en ese momento.
