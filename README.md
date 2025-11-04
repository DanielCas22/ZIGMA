# ZIGMA
sistema de gestion de nomina y horas extras

## Migraciones SQL (local)

Hay un pequeño runner para ejecutar los archivos `.sql` en `scripts/data`.

Ejecutar desde PowerShell con PHP de XAMPP:

```powershell
& 'C:\xampp\php\php.exe' 'C:\xampp\htdocs\ZIGMA\bin\migrate.php'
```

Los resultados y errores parciales se registran en `logs/migrations.log`.
