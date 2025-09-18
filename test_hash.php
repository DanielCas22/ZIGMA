<?php
// Generar hashes válidos para los usuarios
$usuarios = [
    'admin123',
    'rrhh123',
    'empleado123'
];
foreach ($usuarios as $user) {
    echo "$user: ", password_hash($user, PASSWORD_DEFAULT), "<br>";
}
