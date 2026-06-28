<?php

require_once "clases/GestorArchivos.php";  

$gestor = new GestorArchivos();  

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: index.php"); 
    exit();
}

$archivo = trim($_POST["archivo"] ?? ""); 

$passwordArchivo = trim($_POST["password_archivo"] ?? ""); 

if ($passwordArchivo == "") {
    $passwordArchivo = trim($_POST["passwordAccion"] ?? "");
}

if ($archivo == "") {
    header("Location: index.php?error=" . urlencode("Debe seleccionar un archivo para eliminar."));
    exit();
}

if ($passwordArchivo == "") {
    header("Location: index.php?error=" . urlencode("Debe ingresar la contraseña del archivo.")); 
    exit();
}

$resultado = $gestor->eliminar($archivo, $passwordArchivo);  

if ($resultado === true) {
    header("Location: index.php?eliminado=1#listado"); 
    exit();
}

if (is_string($resultado)) {
    header("Location: index.php?error=" . urlencode($resultado)); 
    exit();
}

header("Location: index.php?error=" . urlencode("No se pudo eliminar el archivo.")); 
exit();

?> 