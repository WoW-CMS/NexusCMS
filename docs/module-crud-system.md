# Module CRUD System — Diseño e Implementación

## Contexto

Los módulos de NexusCMS ya tienen un sistema de registro y carga sólido. Lo que falta es un mecanismo estándar para que cada módulo exponga su gestión en el ACP (Admin Control Panel) sin necesidad de código repetitivo y sin acoplarse al módulo Admin.

Además, se detecta un problema existente en `ModuleManagerController`: el toggle y el update escriben en **dos sitios** (DB + `module.json`), y no existe un `uninstall` limpio separado del `destroy`.

---

## Parte 1 — Correcciones en ModuleManagerController

### 1.1 Añadir `uninstall` (solo limpia la BD)

Actualmente `destroy` borra la carpeta del módulo y su registro en BD. Se necesita una acción separada `uninstall` que **solo elimina el registro en `managed_modules`** sin tocar el filesystem. Útil cuando se quiere "desvincular" un módulo sin perder su código.

**Ruta nueva:**
```
DELETE /acp/modules/{module}/uninstall  →  admin.modules.uninstall
```

**Comportamiento:**
- Llama a `ModuleRegistryService::deleteState($module)`
- Limpia caché
- **No** borra la carpeta ni corre rollback de migrations
- Redirige a `admin.modules.index` con mensaje de éxito

**Distinción visual en el index:**
- `Uninstall` → desvincula de BD, deja el código intacto
- `Delete` → borra carpeta + BD (comportamiento actual)

### 1.2 Limpiar código muerto

- Eliminar el método `buildModuleData()` (nunca se llama)
- El `edit` / `update` solo debe permitir editar `name` y `module_type`. Los campos `namespace`, `routes`, `migrations`, `views`, `translations` son responsabilidad del desarrollador en `module.json`, no del admin.

---

## Parte 2 — Sistema de CRUD para módulos

### Visión general

Cada módulo puede declarar una sección de administración en el ACP mediante **uno** de dos modos mutuamente excluyentes:

| Modo | Descripción | Activación |
|------|-------------|------------|
| **A — JSON CRUD** | El módulo define su CRUD mediante un JSON. El framework renderiza la UI automáticamente. | `"admin_crud": "json"` en `module.json` |
| **B — Custom Views** | El módulo provee sus propias vistas Blade y controladores bajo `Admin/`. | `"admin_crud": "custom"` en `module.json` |

> Si `admin_crud` no está definido, el módulo no expone sección de administración.  
> Los modos A y B son **mutuamente excluyentes**: definir `custom` desactiva la lógica JSON.

---

## Modo A — JSON CRUD (Genérico)

### Archivo de configuración: `admin-crud.json`

Ubicación: `app/Modules/{ModuleName}/admin-crud.json`

Este archivo describe el modelo que se gestiona, los campos del formulario y las columnas del listado.

```json
{
    "title": "Forum Categories",
    "model": "Modules\\Forum\\Domain\\Models\\ForumCategory",
    "route_prefix": "admin.forum.categories",
    "permissions": {
        "index":   "manage.forum",
        "create":  "manage.forum",
        "edit":    "manage.forum",
        "delete":  "manage.forum"
    },
    "list": {
        "columns": [
            { "field": "name",        "label": "Name",        "sortable": true  },
            { "field": "slug",        "label": "Slug",        "sortable": false },
            { "field": "created_at",  "label": "Created",     "sortable": true,  "format": "date" }
        ],
        "per_page": 15,
        "searchable": ["name", "slug"]
    },
    "form": {
        "layout": "single",
        "sections": [
            {
                "title": "General",
                "fields": [
                    {
                        "name": "name",
                        "label": "Name",
                        "type": "text",
                        "required": true,
                        "placeholder": "Category name"
                    },
                    {
                        "name": "slug",
                        "label": "Slug",
                        "type": "slug",
                        "source": "name",
                        "required": false,
                        "hint": "Auto-generated from Name"
                    },
                    {
                        "name": "description",
                        "label": "Description",
                        "type": "textarea",
                        "required": false,
                        "rows": 4
                    },
                    {
                        "name": "is_visible",
                        "label": "Visible",
                        "type": "toggle",
                        "default": true
                    }
                ]
            }
        ]
    }
}
```

### Tipos de campo soportados (Modo A)

| Tipo | Descripción |
|------|-------------|
| `text` | Input de texto simple |
| `textarea` | Área de texto |
| `richtext` | CKEditor (como en News) |
| `slug` | Input con generación automática desde otro campo (`source`) |
| `select` | Dropdown. Requiere `options: [{"value":"","label":""}]` o `relation` |
| `toggle` | Checkbox booleano |
| `number` | Input numérico |
| `date` | Date picker |
| `datetime` | Datetime picker |
| `image` | Upload de imagen a `storage/` |
| `file` | Upload de archivo genérico |
| `relation` | Select cargado desde relación Eloquent. Requiere `model`, `value_field`, `label_field` |
| `hidden` | Campo oculto con valor fijo o generado |

### Layout del formulario

El campo `"layout"` en `form` puede ser:

- `"single"` — Una sola columna, secciones apiladas verticalmente
- `"sidebar"` — Columna principal (2/3) + sidebar (1/3). Las secciones se asignan a `"position": "main"` o `"position": "sidebar"`

Ejemplo con sidebar:
```json
"form": {
    "layout": "sidebar",
    "sections": [
        {
            "title": "Content",
            "position": "main",
            "fields": [...]
        },
        {
            "title": "Settings",
            "position": "sidebar",
            "fields": [...]
        }
    ]
}
```

### Componentes del sistema (Modo A)

#### `ModuleCrudService`
`app/Services/ModuleCrudService.php`

Responsabilidades:
- Leer y parsear `admin-crud.json` de un módulo
- Validar la estructura del JSON
- Generar reglas de validación Laravel desde la definición de campos
- Resolver el modelo Eloquent
- Ejecutar operaciones CRUD (index, store, update, delete) contra el modelo

#### `GenericCrudController`
`app/Modules/Admin/Http/Controllers/GenericCrudController.php`

Controlador único que gestiona todos los módulos en Modo A:
- Recibe `{module}` y `{action}` como parámetros de ruta
- Delega en `ModuleCrudService`
- Renderiza vistas genéricas reutilizables

#### Vistas genéricas (Modo A)
```
app/Modules/Admin/Resources/views/generic-crud/
├── index.blade.php   — Tabla de listado con paginación y búsqueda
├── create.blade.php  — Formulario de creación
└── edit.blade.php    — Formulario de edición
```

Estas vistas iteran sobre la definición de `admin-crud.json` y renderizan los campos dinámicamente mediante un componente Blade parcial por tipo de campo:

```
app/Modules/Admin/Resources/views/generic-crud/fields/
├── text.blade.php
├── textarea.blade.php
├── richtext.blade.php
├── slug.blade.php
├── select.blade.php
├── toggle.blade.php
├── image.blade.php
├── relation.blade.php
└── ...
```

#### Rutas (Modo A)

Se registran automáticamente para cada módulo con `admin_crud: json` activo:

```
GET    /acp/modules/{module}/manage          → index
GET    /acp/modules/{module}/manage/create   → create
POST   /acp/modules/{module}/manage          → store
GET    /acp/modules/{module}/manage/{id}     → edit
PUT    /acp/modules/{module}/manage/{id}     → update
DELETE /acp/modules/{module}/manage/{id}     → destroy
```

Rutas nombradas:
```
admin.module-crud.index
admin.module-crud.create
admin.module-crud.store
admin.module-crud.edit
admin.module-crud.update
admin.module-crud.destroy
```

El registro de estas rutas lo hace `ModuleServiceProvider` al detectar `admin_crud: json` en el manifest.

---

## Modo B — Custom Views (Vistas propias del módulo)

### Estructura dentro del módulo

```
app/Modules/{ModuleName}/
└── Admin/
    ├── Controllers/
    │   └── ForumAdminController.php
    ├── routes.php
    └── views/
        ├── index.blade.php
        ├── create.blade.php
        └── edit.blade.php
```

### Activación

En `module.json`:
```json
{
    "admin_crud": "custom"
}
```

Cuando `admin_crud` es `custom`:
- El framework **no** registra rutas genéricas para este módulo
- `BaseModuleServiceProvider` detecta la carpeta `Admin/` y:
  - Carga `Admin/routes.php` dentro del middleware `auth + permission:access.admin.panel`
  - Registra `Admin/views/` como namespace de vistas `{modulename}-admin::`
  
### Reglas del modo custom

1. Las rutas deben definirse en `Admin/routes.php`
2. Los controladores deben extender `App\Http\Controllers\Controller`
3. Las vistas deben extender `admin::layouts.app` para mantener el layout del ACP
4. El módulo es responsable de sus propias validaciones y lógica

### Ejemplo: `Admin/routes.php`

```php
<?php
use Illuminate\Support\Facades\Route;
use Modules\Forum\Admin\Controllers\ForumAdminController;

Route::middleware(['auth', 'permission:manage.forum'])
    ->prefix('acp/forum')
    ->name('admin.forum.')
    ->group(function () {
        Route::get('/', [ForumAdminController::class, 'index'])->name('index');
        Route::get('/create', [ForumAdminController::class, 'create'])->name('create');
        Route::post('/', [ForumAdminController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [ForumAdminController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ForumAdminController::class, 'update'])->name('update');
        Route::delete('/{id}', [ForumAdminController::class, 'destroy'])->name('destroy');
    });
```

---

## Integración en el módulo Admin — Sidebar / Menú

Tanto en Modo A como en Modo B, el módulo puede declarar su entrada en el menú lateral del ACP añadiendo una clave `admin_menu` en `module.json`:

```json
{
    "admin_menu": {
        "label": "Forum",
        "icon": "fas fa-comments",
        "route": "admin.forum.index",
        "permission": "manage.forum",
        "order": 30
    }
}
```

El layout del ACP (`admin::layouts.app`) leerá estos registros de los módulos activos y construirá el menú dinámicamente.

---

## Cambios en `module.json` — Resumen de nuevas claves

```json
{
    "name": "Forum",
    "description": "Forum module",
    "version": "1.2.0",
    "author": "WoW-CMS",
    "enabled": true,
    "routes": true,
    "migrations": true,
    "views": true,
    "namespace": "Modules\\Forum",

    "admin_crud": "custom",

    "admin_menu": {
        "label": "Forum",
        "icon": "fas fa-comments",
        "route": "admin.forum.index",
        "permission": "manage.forum",
        "order": 30
    }
}
```

---

## Cambios en `BaseModuleServiceProvider`

```php
public function boot(): void
{
    parent::boot();

    $adminCrud = $this->config['admin_crud'] ?? null;

    if ($adminCrud === 'custom') {
        $this->loadAdminRoutes();
        $this->loadAdminViews();
    }
    // Si es 'json', las rutas genéricas las registra ModuleServiceProvider globalmente
}

private function loadAdminRoutes(): void
{
    $path = $this->modulePath . '/Admin/routes.php';
    if (is_file($path)) {
        Route::middleware(['web', 'auth'])
            ->group($path);
    }
}

private function loadAdminViews(): void
{
    $path = $this->modulePath . '/Admin/views';
    if (is_dir($path)) {
        $this->loadViewsFrom($path, strtolower($this->moduleName) . '-admin');
    }
}
```

---

## Fases de implementación

### Fase 1 — Correcciones ModuleManagerController
- Añadir método `uninstall()` (solo borra BD)
- Añadir ruta `DELETE /acp/modules/{module}/uninstall`
- Quitar campos estructurales del formulario `edit` (namespace, routes, etc.)
- Eliminar `buildModuleData()` (código muerto)
- Actualizar vista `index.blade.php`: botón "Uninstall" separado de "Delete"

### Fase 2 — Infraestructura base
- Añadir clave `admin_crud` al parsing del manifest en `ModuleRegistryService`
- Actualizar `BaseModuleServiceProvider` para detectar modo `custom`
- Actualizar `module.json` del módulo Example con la nueva estructura

### Fase 3 — Modo B (Custom Views)
- Implementar carga de `Admin/routes.php` y `Admin/views/` en `BaseModuleServiceProvider`
- Documentar con el módulo Example

### Fase 4 — Modo A (JSON CRUD)
- Implementar `ModuleCrudService` (parse + validación de `admin-crud.json`)
- Implementar `GenericCrudController`
- Crear vistas genéricas `generic-crud/index`, `create`, `edit`
- Crear partiales por tipo de campo en `generic-crud/fields/`
- Registrar rutas genéricas desde `ModuleServiceProvider` para módulos con `admin_crud: json`

### Fase 5 — Menú dinámico del ACP
- Implementar lectura de `admin_menu` desde el manifest
- Actualizar `admin::layouts.app` para construir el sidebar dinámicamente

---

## Archivos afectados / nuevos

| Archivo | Estado |
|---------|--------|
| `app/Modules/Admin/Http/Controllers/ModuleManagerController.php` | Modificar |
| `app/Modules/Admin/Resources/views/modules/index.blade.php` | Modificar |
| `app/Modules/Admin/Resources/views/modules/edit.blade.php` | Modificar |
| `app/Services/ModuleRegistryService.php` | Modificar |
| `app/Providers/BaseModuleServiceProvider.php` | Modificar |
| `app/Providers/ModuleServiceProvider.php` | Modificar (Fase 4) |
| `app/Services/ModuleCrudService.php` | Nuevo (Fase 4) |
| `app/Modules/Admin/Http/Controllers/GenericCrudController.php` | Nuevo (Fase 4) |
| `app/Modules/Admin/Resources/views/generic-crud/*.blade.php` | Nuevo (Fase 4) |
| `app/Modules/Admin/Resources/views/generic-crud/fields/*.blade.php` | Nuevo (Fase 4) |
| `app/Modules/{Name}/Admin/routes.php` | Nuevo por módulo (Fase 3) |
| `app/Modules/{Name}/Admin/Controllers/*.php` | Nuevo por módulo (Fase 3) |
| `app/Modules/{Name}/Admin/views/*.blade.php` | Nuevo por módulo (Fase 3) |
| `app/Modules/{Name}/admin-crud.json` | Nuevo por módulo (Fase 4) |
