<?php
// Script para borrar todas las horas extras de la base de datos
$db = require __DIR__ . '/../config/database.php';
$db->exec('DELETE FROM horas_extras');
echo "Todas las horas extras han sido eliminadas.";
