# 📋 FUNCIONALIDAD CONCEPTOS ADICIONALES - PRESTACIONES SOCIALES

## 🎯 **DESCRIPCIÓN**

La nueva funcionalidad de **Conceptos Adicionales** permite agregar valores monetarios extras a empleados específicos en el módulo de prestaciones sociales. Estos conceptos aparecen en la columna "Otros" con tooltips informativos.

---

## ✨ **CARACTERÍSTICAS PRINCIPALES**

### 🟢 **Columna "Otros" Funcional**
- **Antes:** Columna estática con $0
- **Ahora:** Columna interactiva que muestra totales de conceptos adicionales
- **Tooltips Informativos:** Al posicionar el cursor sobre el valor, se muestra el detalle de conceptos

### 💰 **Conceptos Adicionales Permitidos**
- Bonificaciones especiales
- Auxilios educativos
- Compensaciones extra
- Incentivos por desempeño
- Cualquier concepto monetario adicional

### 🔧 **Interfaz de Usuario**
- **Botón (+):** En cada fila de empleado para agregar/editar conceptos
- **Modal Interactivo:** Formulario para gestionar conceptos
- **Lista de Conceptos:** Visualización de conceptos actuales del empleado
- **Eliminación:** Opción para remover conceptos existentes

---

## 🛠️ **CÓMO USAR**

### **1. Agregar Concepto Adicional**
1. Ir a la vista de **Prestaciones Sociales**
2. Localizar al empleado deseado
3. Hacer clic en el botón **"+"** en la columna "Otros"
4. Llenar el formulario en el modal:
   - **Concepto:** Nombre del concepto (ej: "Bonificación Especial")
   - **Valor:** Cantidad monetaria
   - **Descripción:** Detalle explicativo (opcional)
5. Hacer clic en **"Agregar Concepto"**

### **2. Ver Detalles de Conceptos**
1. Posicionar el cursor sobre el valor en la columna "Otros"
2. Aparecerá un **tooltip** con:
   - Lista de conceptos
   - Valores individuales
   - Descripciones
   - Total acumulado

### **3. Eliminar Conceptos**
1. Abrir el modal del empleado
2. En la sección **"Conceptos Actuales"**
3. Hacer clic en el botón **"🗑️"** del concepto a eliminar
4. Confirmar la eliminación

---

## 📊 **IMPACTO EN CÁLCULOS**

### **Total por Empleado**
- **Antes:** Solo prestaciones legales (cesantías + intereses + prima + vacaciones)
- **Ahora:** Prestaciones legales + conceptos adicionales

### **Totales de Empresa**
- Se incluyen automáticamente los conceptos adicionales en:
  - Total general por empleado
  - Suma total de empresa
  - Resúmenes y reportes

### **Ejemplo de Cálculo**
```
Empleado: Juan Pérez
Cesantías:           $2,500,000
Intereses:           $  300,000  
Prima:               $2,500,000
Vacaciones:          $1,250,000
Conceptos Adicionales: $500,000  ← NUEVO
─────────────────────────────────
TOTAL:               $7,050,000
```

---

## 💾 **ESTRUCTURA DE DATOS**

### **Tabla: conceptos_adicionales_prestaciones**
```sql
- id (INT AUTO_INCREMENT PRIMARY KEY)
- empleado_id (INT NOT NULL)
- concepto (VARCHAR(255) NOT NULL)
- descripcion (TEXT)
- valor (DECIMAL(15,2) NOT NULL)
- fecha_creacion (TIMESTAMP)
- fecha_actualizacion (TIMESTAMP)
- activo (BOOLEAN DEFAULT TRUE)
- creado_por (VARCHAR(100))
```

---

## 🔍 **FUNCIONES TÉCNICAS**

### **Modelo: ConceptosAdicionalesModel.php**
- `obtenerConceptosPorEmpleado()` - Lista conceptos de un empleado
- `obtenerTotalConceptosPorEmpleado()` - Suma total de conceptos
- `agregarConcepto()` - Crear nuevo concepto
- `actualizarConcepto()` - Modificar concepto existente
- `eliminarConcepto()` - Desactivar concepto
- `obtenerResumenConceptos()` - Datos para tooltips

### **Controlador: PrestacionesSocialesController.php**
- `agregarConcepto()` - Endpoint AJAX para agregar
- `obtenerConceptos()` - Endpoint AJAX para consultar
- `eliminarConcepto()` - Endpoint AJAX para eliminar

### **Vista: prestaciones_sociales/index.php**
- Nueva columna "Otros" con tooltips
- Modal para gestión de conceptos
- JavaScript para interacción AJAX
- Actualización automática de totales

---

## 🎨 **ELEMENTOS VISUALES**

### **Tooltips Informativos**
- **Activación:** Hover sobre valores en columna "Otros"
- **Contenido:** Lista detallada de conceptos con valores
- **Estilo:** Bootstrap tooltips con HTML habilitado

### **Modal Responsivo**
- **Diseño:** Bootstrap modal large
- **Formulario:** Campos validados
- **Lista:** Tabla de conceptos existentes
- **Acciones:** Botones para agregar/eliminar

### **Indicadores Visuales**
- **$0:** Texto gris cuando no hay conceptos
- **$X,XXX:** Texto verde bold cuando hay conceptos
- **Botón (+):** Siempre visible para agregar conceptos

---

## 🚀 **BENEFICIOS**

### **Para Usuarios**
✅ **Flexibilidad:** Agregar conceptos no previstos en la nómina  
✅ **Transparencia:** Tooltips muestran desglose detallado  
✅ **Facilidad:** Interfaz intuitiva sin complejidad técnica  
✅ **Control:** Agregar/eliminar conceptos en tiempo real  

### **Para la Empresa**
✅ **Cálculos Completos:** Incluye todos los valores en totales  
✅ **Trazabilidad:** Registra quién y cuándo agregó cada concepto  
✅ **Reportes Precisos:** Los conceptos se incluyen en reportes  
✅ **Cumplimiento:** Flexibilidad para manejar casos especiales  

---

## 🛡️ **SEGURIDAD Y VALIDACIONES**

### **Validaciones de Frontend**
- Campos obligatorios marcados
- Validación de números positivos
- Prevención de envíos duplicados

### **Validaciones de Backend**
- Verificación de sesión de usuario
- Validación de datos requeridos
- Sanitización de entradas
- Control de errores con try-catch

### **Trazabilidad**
- Registro de usuario que crea conceptos
- Timestamps de creación y modificación
- Soft delete (activo/inactivo) en lugar de eliminación física

---

## 📈 **CASOS DE USO COMUNES**

1. **Bonificaciones por Desempeño:** Agregar incentivos variables por resultados
2. **Auxilios Especiales:** Apoyo educativo, médico, o familiar
3. **Compensaciones Extra:** Horas adicionales no registradas en sistema
4. **Incentivos por Proyectos:** Bonos por completar proyectos específicos
5. **Ajustes Retroactivos:** Correcciones de períodos anteriores

---

**🎯 FUNCIONALIDAD IMPLEMENTADA Y LISTA PARA USAR**

*Documentación actualizada: <?php echo date('Y-m-d H:i:s'); ?>*