<?php

require_once "clases/GestorArchivos.php";  

$gestor = new GestorArchivos();  

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: index.php");  
    exit();
}

$archivo = trim($_POST["archivo"] ?? ""); 
$passwordArchivo = trim($_POST["password_archivo"] ?? ""); 

if ($archivo == "") {
    header("Location: index.php?error=" . urlencode("Debe seleccionar un archivo para descargar.")); 
    exit();
}

if ($passwordArchivo == "") {
    header("Location: index.php?error=" . urlencode("Debe ingresar la contraseña del archivo."));
    exit();
}

if (!$gestor->verificarPassword($archivo, $passwordArchivo)) {
    header("Location: index.php?error=" . urlencode("Contraseña incorrecta."));
    exit();
}

$ruta = $gestor->obtenerRutaSegura($archivo); 

if ($ruta === false) {
    header("Location: index.php?error=" . urlencode("Archivo no encontrado.")); 
    exit();
}

$nombreArchivo = basename($archivo);   

header("Content-Description: File Transfer");
header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"" . $nombreArchivo . "\"");
header("Content-Length: " . filesize($ruta));
header("Pragma: public");
header("Cache-Control: must-revalidate");

readfile($ruta);
exit();

?> 