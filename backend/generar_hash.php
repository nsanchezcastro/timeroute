<?php
$pass = "123456"; 

// Generar el hash
$hash = password_hash($pass, PASSWORD_BCRYPT);

echo "Tu contraseña cifrada es: " . $hash;