# Scripts de Utilidades y Debug - Sistema ZIGMA

Este directorio contiene scripts organizados que no interfieren con el patrón MVC del sistema principal.

## Estructura de Directorios

```
scripts/
├── debug/          # Scripts de debugging y pruebas
├── utils/          # Scripts de utilidades y configuración
├── data/           # Scripts para insertar/manejar datos
└── README.md       # Este archivo
```

## Scripts de Debug (`/debug/`)

### `analizar_user.php`
- **Propósito**: Analiza la estructura de la tabla `user` y sus relaciones
- **Uso**: Ejecutar desde navegador para obtener información HTML
- **Dependencias**: Requiere `config/database.php`

### `debug_devengado.php`
- **Propósito**: Prueba el funcionamiento del modelo `DevengadoModel`
- **Uso**: Ejecutar desde línea de comandos
- **Dependencias**: `DevengadoModel`, `Empleado`

### `debug_empleados.php`
- **Propósito**: Lista todos los empleados y sus datos básicos
- **Uso**: Ejecutar desde línea de comandos para verificar datos
- **Dependencias**: `Empleado`

### `debug_seguridad_social.php`
- **Propósito**: Prueba el modelo de seguridad social con ARL
- **Uso**: Ejecutar desde línea de comandos
- **Dependencias**: `SeguridadSocialModel`, `ARLModel`

### `debug_temporal.php`
- **Propósito**: Versión temporal de pruebas sin ARL
- **Uso**: Ejecutar desde línea de comandos
- **Dependencias**: `SeguridadSocialModel`

### `probar_modelo.php`
- **Propósito**: Prueba básica del modelo `Empleado`
- **Uso**: Ejecutar desde navegador para obtener salida HTML
- **Dependencias**: `Empleado`

## Scripts de Utilidades (`/utils/`)

### `actualizar_salarios.php`
- **Propósito**: Actualiza salarios por rol en la tabla `salarios_por_rol`
- **Uso**: Ejecutar desde línea de comandos
- **Funcionalidad**: Configura salarios estándar para admin, rrhh, empleado
- **Dependencias**: `config/database.php`

### `calcular_valores_horas.php`
- **Propósito**: Calcula automáticamente valores de horas extras
- **Uso**: Ejecutar cuando hay registros sin valor calculado
- **Fórmula**: `(Salario ÷ 240 horas) × (1 + porcentaje/100) × cantidad_horas`
- **Dependencias**: `HorasExtras`, `TarifaHora`

### `configurar_empleados_individuales.php`
- **Propósito**: Configura salarios individuales por empleado
- **Uso**: Ejecutar para migrar de salarios por rol a individuales
- **Funcionalidades**:
  - Asigna salarios por defecto a empleados sin salario
  - Configura riesgos ARL
  - Ejecuta pruebas de cálculos
- **Dependencias**: `Empleado`, `SalarioPorRol`, `ARLModel`

## Scripts de Datos (`/data/`)

### `insertar_horas_extras.php`
- **Propósito**: Inserta datos de prueba para horas extras
- **Uso**: Ejecutar para poblar la tabla con datos de ejemplo
- **Datos**: Incluye diferentes tipos de horas extras para empleados específicos
- **Dependencias**: `config/database.php`

## Instrucciones de Uso

### Ejecución desde Línea de Comandos
```bash
cd c:\xampp\htdocs\ZIGMA\scripts

# Ejemplo: Ejecutar script de debug
php debug/debug_empleados.php

# Ejemplo: Actualizar salarios
php utils/actualizar_salarios.php

# Ejemplo: Insertar datos de prueba
php data/insertar_horas_extras.php
```

### Ejecución desde Navegador
Para scripts que generan salida HTML:
```
http://localhost/ZIGMA/scripts/debug/analizar_user.php
http://localhost/ZIGMA/scripts/debug/probar_modelo.php
```

## Características Importantes

### Rutas Actualizadas
- Todos los scripts usan rutas relativas con `__DIR__`
- Ejemplo: `require_once __DIR__ . '/../../config/database.php'`
- Esto mantiene la funcionalidad independientemente de donde se ejecuten

### Compatibilidad MVC
- Los scripts no interfieren con el patrón MVC
- Se mantienen separados del código principal de la aplicación
- Pueden ser ejecutados de forma independiente

### Logging y Output
- Todos los scripts proporcionan salida informativa
- Uso de emojis y formateo para mejor legibilidad
- Manejo de errores con try-catch

## Mantenimiento

### Agregar Nuevos Scripts
1. Crear el script en el directorio apropiado (`debug/`, `utils/`, `data/`)
2. Usar rutas relativas para includes: `__DIR__ . '/../../path/to/file.php'`
3. Documentar el propósito y uso en este README
4. Seguir el patrón de manejo de errores existente

### Consideraciones de Seguridad
- No exponer scripts sensibles en producción
- Usar variables de entorno para configuraciones críticas
- Restringir acceso a estos scripts en servidores de producción

---

**Fecha de Organización**: 16 de Octubre, 2025  
**Autor**: Sistema automatizado de organización MVC  
**Estado**: ✅ Todos los scripts reorganizados y funcionales
