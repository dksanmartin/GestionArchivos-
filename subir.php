<?php

require_once "clases/GestorArchivos.php"; 

$gestor = new GestorArchivos();  

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: index.php");
    exit();
}

if (!isset($_FILES["archivo"])) {
    header("Location: index.php?error=" . urlencode("Debe seleccionar un archivo."));
    exit();
}

$correoArchivo = trim($_POST["correo_archivo"] ?? "");  
$passwordArchivo = trim($_POST["password_archivo"] ?? ""); 

if ($correoArchivo == "") {
    header("Location: index.php?error=" . urlencode("Debe ingresar un correo electrónico.")); 
    exit();
}

if (!filter_var($correoArchivo, FILTER_VALIDATE_EMAIL)) {
    header("Location: index.php?error=" . urlencode("El correo electrónico no es válido."));
    exit();
}

if ($passwordArchivo == "") {
    header("Location: index.php?error=" . urlencode("Debe ingresar una contraseña para el archivo.")); 
    exit();
}

if (strlen($passwordArchivo) < 7) {
    header("Location: index.php?error=" . urlencode("La contraseña debe tener mínimo 7 caracteres."));
    exit();
}

$resultado = $gestor->subir($_FILES["archivo"], $correoArchivo, $passwordArchivo); 

if ($resultado === true) {
    header("Location: index.php?subido=1#listado");
    exit();
}

header("Location: index.php?error=" . urlencode($resultado)); 
exit();

?> 