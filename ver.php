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
    header("Location: index.php?error=" . urlencode("Debe seleccionar un archivo para visualizar.")); 
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

$extension = strtolower(pathinfo($archivo, PATHINFO_EXTENSION)); 

$tipos = [
    "pdf"  => "application/pdf",
    "jpg"  => "image/jpeg",
    "jpeg" => "image/jpeg",
    "png"  => "image/png"
];

if (!isset($tipos[$extension])) {
    header("Location: index.php?error=" . urlencode("Tipo de archivo no permitido."));  
    exit();
}

header("Content-Type: " . $tipos[$extension]);
header("Content-Disposition: inline; filename=\"" . basename($archivo) . "\"");
header("Content-Length: " . filesize($ruta));

readfile($ruta);  
exit(); 

?> 