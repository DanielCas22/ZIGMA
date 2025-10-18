# Sistema de Aprobación de Horas Extras - ZIGMA

## Implementación Completada

### 1. Sistema de Aprobación
- **Estado de horas extras**: Todas las nuevas horas extras se crean con estado 'pendiente'
- **Roles autorizados**: Solo admin y RRHH pueden aprobar/rechazar horas extras
- **Empleados generales**: Solo pueden crear sus propias horas extras, que quedan pendientes

### 2. Campos de Auditoría
Se agregaron los siguientes campos a la tabla `horas_extras`:
- `estado`: ENUM('pendiente', 'aprobada', 'rechazada') 
- `fecha_aprobacion`: DATETIME - Hora exacta de aprobación/rechazo
- `aprobado_por`: INT - ID del usuario que aprobó/rechazó (FK a user.id_doc)
- `comentario_aprobacion`: TEXT - Comentario del aprobador

### 3. Notificaciones en Tiempo Real
- **Dashboard**: Muestra alerta con cantidad de horas extras pendientes para admin/RRHH
- **Navbar**: Botón con badge de notificación para acceso rápido a pendientes
- **Sidebar**: Enlace "Pendientes" con contador en el menú lateral

### 4. Vistas Implementadas

#### Vista de Pendientes (`/HorasExtras/pendientes`)
- Lista de todas las horas extras pendientes de aprobación
- Información completa del empleado solicitante
- Botones para aprobar/rechazar con modales de confirmación
- Campos obligatorios para comentarios de rechazo

#### Vista de Historial (`/HorasExtras/historial`)
- Historial completo con información de aprobación
- Muestra fecha y hora exacta de aprobación
- Muestra quién aprobó/rechazó cada registro
- Tooltips con comentarios de aprobación
- Estadísticas resumen (aprobadas, rechazadas, pendientes)

### 5. Controles de Acceso por Rol

#### Administrador
- Puede aprobar/rechazar todas las horas extras
- Ve todas las notificaciones pendientes
- Acceso completo al historial

#### RRHH
- Puede aprobar/rechazar todas las horas extras
- Ve todas las notificaciones pendientes  
- Acceso completo al historial

#### Empleado
- Solo puede crear sus propias horas extras (quedan pendientes)
- Ve únicamente su propio historial
- No tiene acceso a funciones de aprobación

### 6. Características del Sistema

#### Registro de Aprobación
- **Fecha y hora exacta**: Se registra automáticamente al aprobar/rechazar
- **Usuario responsable**: Se guarda el ID del usuario que realiza la acción
- **Comentarios**: Campo obligatorio para rechazos, opcional para aprobaciones

#### Notificaciones Inteligentes
- **Contador en tiempo real**: Se actualiza automáticamente
- **Acceso directo**: Botones y enlaces para acceso rápido
- **Solo para autorizados**: Las notificaciones solo aparecen para admin/RRHH

#### Trazabilidad Completa
- **Historial detallado**: Cada acción queda registrada
- **Auditoría**: Se puede rastrear quién y cuándo aprobó cada registro
- **Estados claros**: Visual diferenciado para pendiente/aprobada/rechazada

### 7. Scripts de Base de Datos
Ejecutar el script `agregar_aprobacion_horas_extras.sql` para:
- Agregar los campos necesarios
- Crear índices para optimizar consultas
- Configurar claves foráneas

### 8. Flujo de Trabajo

1. **Empleado crea horas extras** → Estado 'pendiente'
2. **Admin/RRHH recibe notificación** → Ve contador en dashboard/navbar
3. **Admin/RRHH revisa pendientes** → Accede a lista de solicitudes
4. **Aprobación/Rechazo** → Se registra fecha, usuario y comentario
5. **Historial actualizado** → Empleado ve el estado de su solicitud

### 9. Seguridad
- Validación de permisos en cada acción
- Restricciones de acceso por rol
- Prevención de manipulación de datos ajenos
- Trazabilidad completa de acciones

## Resultado Final
El sistema garantiza que:
- ✅ Solo empleados autorizados pueden aprobar horas extras
- ✅ Se registra exactamente quién y cuándo aprobó cada solicitud
- ✅ Las notificaciones aparecen automáticamente para los responsables
- ✅ Hay trazabilidad completa de todas las acciones
- ✅ Los empleados solo pueden gestionar sus propias horas extras
