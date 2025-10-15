# Guía de Actualización de Vistas - ZIGMA Theme

## Archivos Actualizados

### ✅ Completados:
1. `app/views/login/index.php` - Login con tema ZIGMA
2. `app/views/dashboard/index.php` - Dashboard con tema ZIGMA
3. `app/views/empleado/index.php` - Parcialmente actualizado
4. `public/css/zigma-theme.css` - Tema CSS global creado
5. `app/views/components/navbar.php` - Componente navbar creado

## Instrucciones para Actualizar Cada Vista

### Paso 1: Agregar el CSS del Tema
En el `<head>` de cada vista, después de Bootstrap, agregar:
```html
<link href="/ZIGMA/public/css/zigma-theme.css" rel="stylesheet">
```

### Paso 2: Agregar Navbar (opcional)
Al inicio del `<body>`, incluir:
```php
<?php 
$pageTitle = "Nombre de la Página"; // Cambiar según la vista
include __DIR__ . '/../components/navbar.php'; 
?>
```

### Paso 3: Reemplazar Clases CSS

#### Botones:
- `btn-primary` → `btn-zigma-primary`
- `btn-secondary` → `btn-zigma-secondary`
- `btn-outline-primary` → `btn-zigma-outline`

#### Cards:
- `card` → `card-zigma`
- `card-header` con background → `card-header-zigma`

#### Tablas:
- `table` → `table table-zigma`
- `table-primary` en thead → Eliminar (ya incluido en table-zigma)

#### Alerts:
- `alert-success` → `alert-zigma-success`
- `alert-warning` → `alert-zigma-warning`
- `alert-danger` → `alert-zigma-danger`

#### Textos de Color:
- `text-primary` → `text-zigma-primary` (rosa)
- `text-success` → `text-zigma-secondary` (cyan)
- Agregar `text-zigma-navy` para azul oscuro

#### Forms:
- Labels: agregar clase `form-label-zigma`
- Inputs: agregar clase `form-control-zigma`

#### Badges:
- `badge bg-primary` → `badge-zigma-primary`
- `badge bg-info` → `badge-zigma-info`
- `badge bg-secondary` → `badge-zigma-secondary`

### Paso 4: Agregar Animaciones
Agregar clase `fade-in-up` a contenedores principales para animación de entrada.

### Paso 5: Page Headers
Reemplazar títulos grandes con:
```html
<div class="page-header-zigma mb-4">
    <h2><i class="fas fa-icon me-2"></i>Título de la Página</h2>
</div>
```

## Vistas Pendientes de Actualizar

### Alta Prioridad:
1. ❌ `app/views/horas_extras/index.php`
2. ❌ `app/views/horas_extras/create.php`
3. ❌ `app/views/horas_extras/edit.php`
4. ❌ `app/views/empleado/create.php`
5. ❌ `app/views/empleado/edit.php`
6. ❌ `app/views/nomina/index.php`
7. ❌ `app/views/desprendible/index.php`
8. ❌ `app/views/prestaciones_sociales/index.php`
9. ❌ `app/views/seguridad_social/index.php`
10. ❌ `app/views/devengado/index.php`
11. ❌ `app/views/total_deducido/index.php`
12. ❌ `app/views/parafiscales/index.php`

### Media Prioridad:
- Vistas de detalle
- Vistas de reportes
- Vistas de configuración

## Paleta de Colores ZIGMA

### Variables CSS:
```css
--zigma-navy: #1e3a8a;        /* Azul oscuro */
--zigma-blue: #2563eb;         /* Azul medio */
--zigma-pink: #ec4899;         /* Rosa/Magenta */
--zigma-magenta: #d946ef;      /* Magenta */
--zigma-cyan: #06b6d4;         /* Cyan */
--zigma-cyan-light: #22d3ee;   /* Cyan claro */
--zigma-dark: #1f2937;         /* Gris oscuro */
```

### Uso Recomendado:
- **Navbar/Headers**: Gradiente Navy → Blue
- **Botones principales**: Gradiente Pink → Magenta
- **Botones secundarios**: Gradiente Cyan → Cyan Light
- **Sidebar**: Gradiente Dark → Dark Secondary
- **Acentos**: Pink, Cyan

## Ejemplos de Código

### Card con Tema:
```html
<div class="card-zigma shadow">
    <div class="card-header-zigma">
        <h5 class="mb-0"><i class="fas fa-icon me-2"></i>Título</h5>
    </div>
    <div class="card-body">
        <!-- Contenido -->
    </div>
</div>
```

### Tabla con Tema:
```html
<div class="table-responsive">
    <table class="table table-zigma">
        <thead>
            <tr>
                <th>Columna 1</th>
                <th>Columna 2</th>
            </tr>
        </thead>
        <tbody>
            <!-- Filas -->
        </tbody>
    </table>
</div>
```

### Botones con Tema:
```html
<button class="btn btn-zigma-primary">
    <i class="fas fa-save me-2"></i>Guardar
</button>
<button class="btn btn-zigma-secondary">
    <i class="fas fa-download me-2"></i>Descargar
</button>
<button class="btn btn-zigma-outline">
    <i class="fas fa-times me-2"></i>Cancelar
</button>
```

## Notas Importantes

1. El archivo `zigma-theme.css` debe cargarse DESPUÉS de Bootstrap
2. Las clases de Bootstrap siguen funcionando, solo se agregan estilos adicionales
3. El logo debe estar en `/ZIGMA/public/img/logo_zigma.jpg`
4. Usar iconos de Font Awesome para mejor apariencia
5. Mantener consistencia en toda la aplicación

## Estado Actual

- ✅ Login: 100% completado
- ✅ Dashboard: 100% completado
- 🟡 Empleados: 60% completado
- ❌ Resto de vistas: Pendiente

## Próximos Pasos

1. Terminar de actualizar vista de Empleados
2. Actualizar vistas de Horas Extras
3. Actualizar vista de Nómina
4. Actualizar vistas de Prestaciones y Seguridad Social
5. Revisar y ajustar todos los componentes
