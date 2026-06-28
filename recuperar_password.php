<?php

require_once "clases/GestorArchivos.php"; 

$gestor = new GestorArchivos(); 

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: index.php"); 
    exit();
}

$archivo = trim($_POST["archivo"] ?? "");
$correoArchivo = trim($_POST["correo_archivo"] ?? "");
$nuevaPassword = trim($_POST["nueva_password"] ?? "");
$confirmarPassword = trim($_POST["confirmar_password"] ?? "");

if ($archivo == "") {
    header("Location: index.php?error=" . urlencode("Debe seleccionar un archivo."));
    exit();
}

if ($correoArchivo == "") {
    header("Location: index.php?error=" . urlencode("Debe ingresar el correo registrado."));
    exit();
}

if ($nuevaPassword == "" || $confirmarPassword == "") { 
    header("Location: index.php?error=" . urlencode("Debe ingresar y confirmar la nueva contraseña.")); 
    exit();
}

if ($nuevaPassword !== $confirmarPassword) {
    header("Location: index.php?error=" . urlencode("Las contraseñas no coinciden."));
    exit();
}

$resultado = $gestor->recuperarPassword($archivo, $correoArchivo, $nuevaPassword);  

if ($resultado === true) {
    header("Location: index.php?recuperado=1#recuperar");  
    exit();
}

header("Location: index.php?error=" . urlencode($resultado)); 
exit();

?> 