**PASOS PARA SOLUCIONAR EL PROBLEMA DEL PORCENTAJE ARL:**

1. **Ejecuta el script SQL**: Copia y ejecuta el contenido de `fix_arl_simple.sql` en phpMyAdmin.

2. **Prueba el sistema**: Ejecuta en terminal:
```
php test_arl_completo.php
```

3. **Recarga la página** de gestión de seguridad social en el navegador.

El problema era que:
- La vista buscaba `$calculo['arl']['porcentaje_arl']` pero la estructura usaba `$calculo['arl']['nivel_riesgo']['porcentaje']`
- Las tablas de la base de datos tenían nombres de columnas incorrectos
- Ya corregí ambos problemas

Después de ejecutar el script SQL, los porcentajes deberían mostrarse correctamente:
- Clase I: 0.522%
- Clase II: 1.044% 
- Clase III: 2.436%
- Clase IV: 4.350%
- Clase V: 6.960%
