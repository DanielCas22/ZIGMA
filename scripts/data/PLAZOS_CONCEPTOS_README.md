# Funcionalidad de Plazos - Conceptos Adicionales

## Descripción General

Se ha implementado una funcionalidad avanzada de **plazos** para los conceptos adicionales en los módulos de **Total Devengado** y **Total Deducido**. Esto permite al administrador distribuir el pago o descuento de un concepto en múltiples períodos (quincenas o meses).

## ¿Qué es un Plazo?

Un plazo permite dividir un concepto adicional en períodos iguales. Por ejemplo:
- Un bono de **$200,000** puede pagarse en **2 quincenas** de **$100,000** cada una
- Un descuento de **$600,000** puede distribuirse en **3 meses** de **$200,000** cada uno

## Características

### Configuración del Plazo

Al agregar un concepto adicional (en Devengado o Deducido), ahora hay una sección dedicada a plazos:

1. **¿Distribuir en plazos?** - Checkbox para activar/desactivar la funcionalidad
2. **Tipo de período** - Seleccionar entre:
   - Quincena (15 días)
   - Mes (30 días)
3. **Cantidad de plazos** - Número de períodos en que se dividirá el valor (2-12)
4. **Vista previa de distribución** - Muestra automáticamente cómo se dividirá el monto

### Base de Datos

Se crearon dos tablas nuevas:

#### `conceptos_adicionales_plazos`
Almacena el detalle de cada plazo:
```sql
CREATE TABLE conceptos_adicionales_plazos (
    id INT PRIMARY KEY,
    concepto_id INT,           -- Referencia al concepto
    periodo_numero INT,        -- Número secuencial (1, 2, 3...)
    valor_periodo DECIMAL,     -- Monto a pagar en este período
    tipo_periodo ENUM,         -- 'quincena' o 'mes'
    estado ENUM,              -- 'pendiente', 'pagado', 'cancelado'
    ...
)
```

#### Columnas Agregadas a `conceptos_adicionales_prestaciones`
```sql
ALTER TABLE conceptos_adicionales_prestaciones ADD COLUMN:
- total_plazos INT           -- Cantidad de plazos (1 = sin plazo)
- tipo_plazo ENUM            -- 'quincena' o 'mes'
- tiene_plazo BOOLEAN        -- Flag si está distribuido en plazos
```

## Métodos del Modelo

El `ConceptosAdicionalesModel` incluye nuevos métodos:

### `crearPlazos($concepto_id, $valor_total, $total_plazos, $tipo_plazo)`
Crea automáticamente los registros de plazo distribuidos equitativamente.

```php
$modelo->crearPlazos(5, 200000, 2, 'quincena');
// Crea 2 plazos de $100,000 cada uno
```

### `obtenerPlazos($concepto_id)`
Retorna todos los plazos de un concepto.

```php
$plazos = $modelo->obtenerPlazos(5);
// Retorna array con todos los períodos
```

### `obtenerConceptoConPlazos($concepto_id)`
Obtiene un concepto con toda la información de sus plazos.

```php
$concepto = $modelo->obtenerConceptoConPlazos(5);
// $concepto['plazos'] contiene todos los plazos
```

### `obtenerProximoPlazo($concepto_id)`
Obtiene el próximo plazo pendiente de pagar.

```php
$proximo = $modelo->obtenerProximoPlazo(5);
```

### `marcarPlazoPagado($plazo_id)`
Marca un plazo como pagado.

```php
$modelo->marcarPlazoPagado(15);
```

## Interfaz de Usuario

### En Total Devengado
- Color de tema: Azul (btn-info)
- Sección de plazos con borde azul
- Badge de plazos en color info

### En Total Deducido
- Color de tema: Rojo (btn-danger)
- Sección de plazos con borde rojo
- Badge de plazos en color danger

## Ejemplo de Uso

### Scenario: Adicionar Bono con Plazo

1. Admin accede a "Total Devengado"
2. Hace clic en celda "Otros" de un empleado
3. En el modal, ingresa:
   - Concepto: "Bonificación Desempeño"
   - Valor: $300,000
   - Descripción: "Bono por cumplimiento de metas Q4"
4. Activa "¿Distribuir en plazos?"
5. Selecciona:
   - Tipo: "Quincena"
   - Cantidad: 3
6. El sistema muestra: "3 quincenas de $100,000 cada una"
7. Click en "Agregar Concepto"
8. Sistema crea:
   - 1 registro en `conceptos_adicionales_prestaciones` con `tiene_plazo=TRUE`
   - 3 registros en `conceptos_adicionales_plazos` (períodos 1, 2, 3)

### Scenario: Adicionar Descuento con Plazo

Proceso similar en "Total Deducido":
1. Admin hace clic en "Otros Descuentos"
2. Ingresa un descuento por préstamo
3. Distribuye en 2 meses de $150,000 cada uno
4. El sistema crea los registros de plazo

## Cálculos Relacionados

Los plazos se integran automáticamente con:

- **Nómina**: Cada período genera un registro en la nómina con su porción del concepto
- **Total Devengado**: Suma el valor del período actual
- **Total Deducido**: Descuenta el valor del período actual
- **Desprendibles**: Muestran los conceptos con plazo en el comprobante

## Notas Técnicas

- Todos los plazos se crean con estado `'pendiente'`
- La distribución es equitativa (valor total / número de plazos)
- Si un valor no es divisible exactamente, los decimales se distribuyen en el primer plazo
- Los plazos no se pueden modificar directamente, solo eliminar el concepto completo
- El sistema mantiene historial de plazos pagados

## Archivos Modificados

- `/app/models/ConceptosAdicionalesModel.php` - Métodos de plazos
- `/app/views/devengado/index.php` - UI y JS para Total Devengado
- `/app/views/total_deducido/index.php` - UI y JS para Total Deducido
- `/scripts/data/agregar_plazos_conceptos.sql` - Script de creación de tablas
- `/bin/crear_tabla_plazos.php` - Script de ejecución de SQL

## SQL de Creación

```bash
php /ZIGMA/bin/crear_tabla_plazos.php
```

Este script fue ejecutado automáticamente durante la implementación.
