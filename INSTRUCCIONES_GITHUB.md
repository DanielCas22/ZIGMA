# 📦 Instrucciones para GitHub y Distribución

> **Para compartir el proyecto de forma segura y completa**

---

## 🚀 ANTES DE SUBIR A GITHUB

### 1. Crear archivo `.gitignore` (si no existe)

```bash
# Crear archivo .gitignore en la raíz del proyecto
```

Contenido recomendado:

```
# PHP
vendor/
*.php~
*.phtml~
.env
.env.local
.env.*.local

# Base de datos
*.sql.bak
backups/
dumps/

# Logs
logs/
*.log

# Sesiones
sessions/
tmp/

# IDEs
.vscode/
.idea/
*.swp
*.swo
*~
.DS_Store

# OS
Thumbs.db
.htaccess

# Reportes temporales
reportes/temp/
uploads/temp/

# Debug
debug/
test_*.php
diagnostico*.php
```

### 2. Actualizar `.gitignore` para incluir archivos de configuración local

```
# No subir archivos de configuración local
.env
config/database_local.php
```

### 3. Archivos QUE SÍ debes subir

```
✅ config/database.php         (Sin credenciales reales)
✅ config/db_init.php          (Nuevo inicializador)
✅ config/session_config.php   (Modificado)
✅ INSTALACION_COMPLETA.sql    (BD centralizada)
✅ public/verificar_instalacion.php
✅ Toda la carpeta app/
✅ Toda la carpeta core/
✅ .env.example                (Ejemplo de variables)
✅ README*.md                  (Documentación)
✅ CHECKLIST_FINAL.md
✅ RESUMEN_CAMBIOS.md
✅ GUIA_INSTALACION_DEPLOYMENT.md
```

---

## 📝 Configuración de credentials para GitHub

### config/database.php
**Actualiza para ser flexible:**

```php
<?php
// Intenta usar variables de entorno primero
$db_host = getenv('DB_HOST') ?: 'localhost';
$db_name = getenv('DB_NAME') ?: 'zigmaog';
$db_user = getenv('DB_USER') ?: 'root';
$db_pass = getenv('DB_PASSWORD') ?: '';

return new PDO(
    "mysql:host={$db_host};dbname={$db_name};charset=utf8mb4",
    $db_user,
    $db_pass,
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);
```

---

## 📋 Checklist para GitHub

```
[ ] .gitignore está configurado
[ ] No hay archivos sensibles en el commit
[ ] INSTALACION_COMPLETA.sql está incluido
[ ] config/db_init.php está incluido
[ ] Todos los README están presentes
[ ] .env.example está presente
[ ] No hay claves API o contraseñas
[ ] composer.json está actualizado
[ ] README.md principal es claro
```

---

## 🔄 Pasos para subir a GitHub

### Opción 1: Desde GitHub Desktop

```
1. Abrir GitHub Desktop
2. Add Local Repository → Seleccionar carpeta ZIGMA
3. Changes → Escribir mensaje descriptivo
4. "Commit to main"
5. Push origin
```

### Opción 2: Desde línea de comandos

```bash
# Navegar a la carpeta
cd c:\xampp\htdocs\ZIGMA

# Inicializar git (si no existe)
git init

# Agregar todos los archivos
git add .

# Crear commit
git commit -m "ZIGMA v1.0 - Sistema 100% funcional para producción"

# Agregar remoto (reemplaza URL)
git remote add origin https://github.com/tuUsuario/ZIGMA.git

# Subir
git push -u origin main
```

---

## 📲 Para que otros descarguen el proyecto

### Método 1: GitHub Desktop
```
1. Abrir GitHub Desktop
2. File → Clone Repository
3. Seleccionar tu repo (ZIGMA)
4. Clonar en C:\xampp\htdocs\
```

### Método 2: Descargar ZIP
```
1. Ir a GitHub
2. Code → Download ZIP
3. Extraer en C:\xampp\htdocs\ZIGMA\
```

### Método 3: Git Bash
```bash
git clone https://github.com/tuUsuario/ZIGMA.git
```

---

## ✅ Validación después de clonar

Después de descargar el proyecto en otra máquina:

```
1. Abrir http://localhost/ZIGMA/public/
   → BD se crea automáticamente ✅

2. Abrir http://localhost/ZIGMA/public/verificar_instalacion.php
   → Todas las verificaciones deben pasar ✅

3. Login con admin/admin123
   → Acceso al sistema ✅

4. Ver lista de empleados
   → Datos iniciales presentes ✅
```

---

## 🏷️ Versionado Recomendado

```
Versiones:
v1.0 - Producción Ready
  ├── INSTALACION_COMPLETA.sql
  ├── Inicializador automático
  ├── Documentación completa
  └── Listo para jurados

v1.1 (futura) - Mejoras
  ├── Nuevas funcionalidades
  ├── Correcciones de bugs
  └── Optimizaciones
```

### Crear release en GitHub

```
1. GitHub → Releases
2. Create a new release
3. Tag: v1.0
4. Title: "Sistema 100% Funcional"
5. Description: (ver abajo)
6. Create release
```

**Descripción recomendada para release:**

```markdown
# ZIGMA v1.0 - Producción Ready

## Novedades
- ✅ BD centralizada y automática
- ✅ Inicialización automática en primer acceso
- ✅ Verificador de instalación incluido
- ✅ Documentación completa
- ✅ Funciona en cualquier dispositivo

## Instalación
1. Descargar archivo ZIP
2. Extraer en C:\xampp\htdocs\ZIGMA\
3. Abrir http://localhost/ZIGMA/public/
4. ¡Sistema listo!

## Credenciales
- Usuario: admin
- Contraseña: admin123

## Documentación
- README_RAPIDO.md - Inicio rápido (2 min)
- GUIA_INSTALACION_DEPLOYMENT.md - Guía completa
- CHECKLIST_FINAL.md - Antes de usar
- RESUMEN_CAMBIOS.md - Qué cambió

## Requisitos
- XAMPP (Apache + MySQL + PHP)
- Navegador moderno
- Windows/Linux/Mac
```

---

## 📊 README.md Principal (raíz del proyecto)

```markdown
# ZIGMA - Sistema de Nómina y Prestaciones

## 🎯 ¿Qué es?
Sistema integral para gestión de nómina con cálculo automático de 
devengado/deducido, horas extras, parámetros legales y reportes.

## ⚡ Inicio Rápido
1. Descargar proyecto
2. Colocar en C:\xampp\htdocs\ZIGMA\
3. Abrir http://localhost/ZIGMA/public/
4. Login: admin / admin123
5. ¡Listo!

## 📚 Documentación
- [Guía Rápida](README_RAPIDO.md)
- [Guía Completa](GUIA_INSTALACION_DEPLOYMENT.md)
- [Checklist Final](CHECKLIST_FINAL.md)
- [Resumen de Cambios](RESUMEN_CAMBIOS.md)

## 🔧 Requisitos
- Apache
- MySQL 5.7+
- PHP 7.4+

## 💻 Stack Técnico
- PHP 7.4+
- MySQL
- HTML/CSS/JavaScript
- MVC Architecture

## 📄 Licencia
[Tu licencia aquí]

## 👥 Autor
[Tu nombre]

## 📞 Soporte
Ver GUIA_INSTALACION_DEPLOYMENT.md para troubleshooting

---
Última actualización: 14/12/2025
```

---

## 🔐 Variables Sensibles

**NUNCA subas a GitHub:**
```
❌ Contraseñas reales
❌ Claves API
❌ Configuración de servidor de producción
❌ .env con valores reales
❌ Credenciales de BD reales
```

**Usa .env.example para plantilla:**
```
✅ Ejemplo de variables
✅ Valores por defecto
✅ Sin información sensible
```

---

## 🔄 Flujo de Trabajo Recomendado

```
1. Desarrollar localmente
   ├── Hacer cambios en tu PC
   └── Probar en http://localhost/ZIGMA/

2. Commit a GitHub
   ├── git add .
   ├── git commit -m "descripción"
   └── git push

3. Validar en otro dispositivo
   ├── Clonar desde GitHub
   ├── Abrir en navegador
   └── Verificar /verificar_instalacion.php

4. Release para distribución
   ├── Crear tag (v1.0)
   ├── Crear release en GitHub
   └── Compartir con jurados
```

---

## 📦 Archivos para Entregar a Jurados

### Opción 1: ZIP del proyecto
```
ZIGMA.zip
├── Código completo
├── BD centralizada
├── Documentación
└── 100% funcional
```

### Opción 2: Link de GitHub
```
https://github.com/tuUsuario/ZIGMA/
- Clone o descarga
- Documentación completa
- Historial de versiones
```

### Opción 3: Release en GitHub
```
https://github.com/tuUsuario/ZIGMA/releases/tag/v1.0
- Archivo ZIP descargable
- Descripción clara
- Instrucciones de instalación
```

---

## ✅ Checklist Final para GitHub

```
[ ] .gitignore está bien configurado
[ ] INSTALACION_COMPLETA.sql incluido
[ ] config/db_init.php incluido
[ ] Documentación completa
[ ] .env.example sin valores sensibles
[ ] README.md principal es claro
[ ] No hay archivos de debug
[ ] Composer.json actualizado
[ ] Primera release creada
[ ] Link funciona desde otro dispositivo
```

---

## 🎓 Para Presentar a Jurados

### Qué mostrar:
```
✅ Link de GitHub con documentación
✅ Instrucciones claras para instalar
✅ Verificador de instalación
✅ Funcionamiento en múltiples PCs
✅ Documentación completa
✅ Arquitectura limpia
```

### Qué decir:
```
"El proyecto está en GitHub y es 100% funcional en cualquier dispositivo.
La BD se crea automáticamente sin necesidad de scripts manuales.
Incluye documentación completa y verificador de estado del sistema."
```

---

**¡Tu proyecto está listo para GitHub y para los jurados!** 🚀

*Última actualización: 14 de Diciembre de 2025*
