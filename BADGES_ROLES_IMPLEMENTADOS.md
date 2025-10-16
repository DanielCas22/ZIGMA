# Implementación de Badges de Roles con Colores Diferenciados

## Fecha de Implementación
15 de Octubre, 2025

## Descripción
Se implementaron badges de colores diferenciados para los roles/cargos en todas las tablas y vistas del sistema ZIGMA. Los badges permiten identificar visualmente el rol de cada empleado mediante colores distintivos.

## Paleta de Colores para Badges de Roles

### Clases CSS Creadas (en zigma-theme.css)

```css
/* Badges de roles con colores diferenciados */
.badge-role-admin {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    color: white;
    padding: 0.35rem 0.65rem;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    font-weight: 600;
    white-space: nowrap;
    display: inline-block;
}

.badge-role-empleado {
    background: linear-gradient(135deg, #ec4899 0%, #d946ef 100%);
    color: white;
    padding: 0.35rem 0.65rem;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    font-weight: 600;
    white-space: nowrap;
    display: inline-block;
}

.badge-role-rrhh {
    background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
    color: #1f2937;
    padding: 0.35rem 0.65rem;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    font-weight: 600;
    white-space: nowrap;
    display: inline-block;
}

.badge-role-default {
    background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
    color: white;
    padding: 0.35rem 0.65rem;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    font-weight: 600;
    white-space: nowrap;
    display: inline-block;
}
```

### Asignación de Colores por Rol

- **Admin**: Rojo (`.badge-role-admin`) - Gradiente de #dc3545 a #c82333
- **Empleado**: Magenta/Rosa (`.badge-role-empleado`) - Gradiente de #ec4899 a #d946ef
- **RRHH**: Amarillo/Naranja (`.badge-role-rrhh`) - Gradiente de #ffc107 a #ff9800
- **Default**: Gris (`.badge-role-default`) - Gradiente de #6c757d a #5a6268

## Vistas Actualizadas

### Vistas Principales (Index)

1. **app/views/empleado/index.php**
   - Tabla de empleados con columna "Rol/Cargo"
   - Badges de color aplicados en la columna de roles

2. **app/views/horas_extras/index.php**
   - Tabla de horas extras con columna "Empleado/Cargo"
   - Badges de color aplicados bajo el nombre del empleado

3. **app/views/desprendible/index.php**
   - Tabla de desprendibles con columna "Cargo"
   - Badges de color aplicados según el rol del empleado

4. **app/views/parafiscales/index.php**
   - Tabla de parafiscales con columna "Cargo"
   - Badges de color aplicados en lugar de texto simple

5. **app/views/devengado/index.php**
   - Tabla de devengados con información de cargo bajo el nombre
   - Badges de color aplicados en lugar de small text

6. **app/views/seguridad_social/index.php**
   - Tabla de seguridad social con columna de roles
   - Badges múltiples de color cuando hay varios roles

7. **app/views/empleado/dashboard.php**
   - Tabla de dashboard con columna "Rol/Cargo"
   - Badges de color aplicados en la tabla de resumen

### Vistas de Detalle

1. **app/views/empleado/detalle.php**
   - Badge de color en la información del empleado

2. **app/views/total_deducido/detalle.php**
   - Badge de color en el header con información del empleado
   - CSS de zigma-theme incluido

3. **app/views/parafiscales/detalle.php**
   - Badge de color en el header con información del empleado
   - CSS de zigma-theme incluido

4. **app/views/devengado/detalle.php**
   - Badge de color en el header principal
   - Badge de color en la sección de información del empleado
   - CSS de zigma-theme incluido

5. **app/views/prestaciones_sociales/detalle.php**
   - CSS de zigma-theme incluido (preparado para futuros badges si se requieren)

6. **app/views/seguridad_social/detalle.php**
   - Badges de color en la sección de roles del empleado
   - CSS de zigma-theme incluido

## Lógica de Asignación de Badges

En todas las vistas se implementó la siguiente lógica PHP para determinar el color del badge:

```php
<?php
$rol_nombre = strtolower($empleado['cargo'] ?? 'Sin rol');
$badge_class = 'badge-role-default';
if (strpos($rol_nombre, 'admin') !== false) {
    $badge_class = 'badge-role-admin';
} elseif (strpos($rol_nombre, 'rrhh') !== false || strpos($rol_nombre, 'recursos humanos') !== false) {
    $badge_class = 'badge-role-rrhh';
} elseif (strpos($rol_nombre, 'empleado') !== false) {
    $badge_class = 'badge-role-empleado';
}
?>
<span class="badge <?= $badge_class ?>"><?= htmlspecialchars($empleado['cargo']) ?></span>
```

## Características de los Badges

- **Responsive**: Se adaptan al tamaño de la pantalla
- **No Wrapping**: Propiedad `white-space: nowrap;` evita que el texto se parta en múltiples líneas
- **Gradientes**: Uso de gradientes lineales para un aspecto moderno
- **Legibilidad**: Colores contrastantes con texto blanco (excepto RRHH que usa texto oscuro)
- **Consistencia**: Mismo estilo y tamaño en todas las vistas

## Integración con CSS Global

Todos los badges utilizan las clases definidas en:
- **Archivo**: `/ZIGMA/public/css/zigma-theme.css`
- **Vistas**: Incluyen el link `<link href="/ZIGMA/public/css/zigma-theme.css" rel="stylesheet">`

## Compatibilidad

- ✅ Compatible con Bootstrap 5.3.2
- ✅ Compatible con Font Awesome 6.4.0
- ✅ Responsive en dispositivos móviles
- ✅ Compatible con todos los navegadores modernos

## Próximas Mejoras (Opcional)

1. Agregar iconos dentro de los badges (ej: 👤 para empleado, ⭐ para admin)
2. Agregar tooltips con información adicional sobre el rol
3. Implementar badges en reportes y vistas de configuración
4. Agregar animaciones hover más elaboradas

## Archivos Modificados

### Vistas Principales
- `app/views/empleado/index.php`
- `app/views/horas_extras/index.php`
- `app/views/desprendible/index.php`
- `app/views/parafiscales/index.php`
- `app/views/devengado/index.php`
- `app/views/seguridad_social/index.php`
- `app/views/empleado/dashboard.php`

### Vistas de Detalle
- `app/views/empleado/detalle.php`
- `app/views/total_deducido/detalle.php`
- `app/views/parafiscales/detalle.php`
- `app/views/devengado/detalle.php`
- `app/views/prestaciones_sociales/detalle.php`
- `app/views/seguridad_social/detalle.php`

### CSS
- `public/css/zigma-theme.css` (clases de badges ya existentes)

## Notas Técnicas

1. Los badges detectan el rol mediante búsqueda de cadenas (case-insensitive)
2. Si un empleado tiene múltiples roles, se muestran múltiples badges
3. El badge por defecto (gris) se aplica cuando no se identifica un rol específico
4. Todas las vistas de detalle ahora incluyen el CSS de zigma-theme para consistencia visual

## Testing

Se recomienda verificar visualmente:
- [x] Tabla de empleados
- [x] Tabla de horas extras
- [x] Tabla de desprendibles
- [x] Tabla de parafiscales
- [x] Tabla de devengados
- [x] Tabla de seguridad social
- [x] Dashboard de empleados
- [x] Todas las vistas de detalle

---

**Implementado por**: GitHub Copilot
**Fecha**: 15 de Octubre, 2025
**Estado**: ✅ Completado
