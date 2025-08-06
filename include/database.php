<?php
$servidor="localhost";
$database="contactos";
$usuario="root";
$pass="samuel";
try {
    $conexion = new PDO("mysql:host=$servidor;dbname=$database", $usuario, $pass);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Conexión exitosa"; 
} catch(PDOException $e) {
    echo "Conexión fallida: " . $e->getMessage();
}
include "funciones.php";