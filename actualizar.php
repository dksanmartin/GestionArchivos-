<?php

require_once "clases/GestorArchivos.php"; 

$gestor = new GestorArchivos(); 

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: index.php");  
    exit();
}

$archivo = trim($_POST["archivo"] ?? ""); 
$titulo = trim($_POST["titulo"] ?? "");
$tipoDocumento = trim($_POST["tipo_documento"] ?? ""); 
$descripcion = trim($_POST["descripcion"] ?? "");
$nuevoNombre = trim($_POST["nuevo_nombre"] ?? "");

if ($archivo == "") {
    header("Location: index.php?error=" . urlencode("Seleccione un archivo para actualizar."));
    exit();
}

/* Si el usuario no escribe título, se usa el nombre del archivo */
if ($titulo == "") {
    $titulo = pathinfo($archivo, PATHINFO_FILENAME); 
}

/* Si no selecciona tipo, se usa la extensión actual */
if ($tipoDocumento == "") {
    $tipoDocumento = strtolower(pathinfo($archivo, PATHINFO_EXTENSION));

    if ($tipoDocumento == "jpeg") {
        $tipoDocumento = "jpg";
    }
}

$tiposPermitidos = ["pdf", "jpg", "png"];

if (!in_array(strtolower($tipoDocumento), $tiposPermitidos)) {
    header("Location: index.php?error=" . urlencode("Tipo de documento inválido."));
    exit();
}

$resultado = $gestor->actualizar(   
    $archivo,
    $titulo,
    $tipoDocumento,
    $descripcion,
    $nuevoNombre
);

if ($resultado === true) {
    header("Location: index.php?actualizado=1#editar");
    exit();
}

header("Location: index.php?error=" . urlencode($resultado));
exit(); 

?> 