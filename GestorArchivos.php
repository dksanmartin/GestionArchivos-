<?php

date_default_timezone_set('America/Guayaquil');

class GestorArchivos 
{
    private $directorio = "archivos/";
    private $archivoDatos = "datos_archivos.json";

    private $tiposPermitidos = [
        "application/pdf",
        "image/jpeg",
        "image/png"
    ];

    private $extensionesPermitidas = [
        "pdf",
        "jpg",
        "jpeg",
        "png"
    ];

    private $tamMaximo = 2 * 1024 * 1024;

    public function __construct() 
    {
        if (!file_exists($this->directorio)) {
            mkdir($this->directorio, 0755, true);
        }

        if (!file_exists($this->archivoDatos)) {
            file_put_contents($this->archivoDatos, json_encode([]));
        }

        $this->crearHtaccess(); 
    }

    private function crearHtaccess()
    {
        $rutaHtaccess = $this->directorio . ".htaccess"; 

        if (!file_exists($rutaHtaccess)) {

            $contenido = "Options -Indexes\n\n";

            $contenido .= "<FilesMatch \"\\.(php|php3|php4|php5|php7|php8|phtml|phar|cgi|pl|py|sh|exe|bat|cmd)$\">\n";
            $contenido .= "    Require all denied\n";
            $contenido .= "</FilesMatch>\n\n";

            $contenido .= "AddType text/plain .php .php3 .php4 .php5 .php7 .php8 .phtml .phar .cgi .pl .py .sh .exe .bat .cmd\n";

            file_put_contents($rutaHtaccess, $contenido);
        }
    }

    public function subir($archivo, $correoArchivo, $passwordArchivo) 
    {
        if (!isset($archivo) || empty($archivo["name"])) {
            return "No se seleccionó ningún archivo.";
        }

        $correoArchivo = strtolower(trim($correoArchivo));

        if (empty($correoArchivo)) {
            return "Debe ingresar un correo electrónico.";
        }

        if (!filter_var($correoArchivo, FILTER_VALIDATE_EMAIL)) {
            return "El correo electrónico no es válido."; 
        }

        if (empty($passwordArchivo)) {
            return "Debe ingresar una contraseña para el archivo.";
        }

        if (strlen($passwordArchivo) < 7) {
            return "La contraseña debe tener mínimo 7 caracteres.";
        }

        if ($archivo["error"] !== UPLOAD_ERR_OK) {
            return "Error al subir el archivo.";
        }

        if ($archivo["size"] <= 0) {
            return "El archivo está vacío.";
        }

        if ($archivo["size"] > $this->tamMaximo) {
            return "El archivo supera el tamaño máximo permitido de 2 MB.";
        }

        $extension = strtolower(pathinfo($archivo["name"], PATHINFO_EXTENSION));

        if (!in_array($extension, $this->extensionesPermitidas)) {
            return "Solo se permiten archivos PDF, JPG, JPEG y PNG.";
        }

        if (!is_uploaded_file($archivo["tmp_name"])) {
            return "El archivo no fue subido correctamente.";
        }

        $mime = mime_content_type($archivo["tmp_name"]);

        if (!in_array($mime, $this->tiposPermitidos)) {
            return "Tipo MIME no permitido.";
        }

        if (!$this->extensionCoincideConMime($extension, $mime)) {
            return "La extensión del archivo no coincide con su contenido real.";
        }

        $nombreSeguro = $this->generarNombreSeguro($extension); 
        $rutaDestino = $this->directorio . $nombreSeguro;

        if (move_uploaded_file($archivo["tmp_name"], $rutaDestino)) {

            chmod($rutaDestino, 0644);

            $datos = $this->leerDatos();

            $datos[] = [
                "titulo" => pathinfo($archivo["name"], PATHINFO_FILENAME),
                "descripcion" => "",
                "nombre" => $nombreSeguro,
                "nombre_original" => basename($archivo["name"]),
                "correo" => $correoArchivo,
                "tamano" => round(filesize($rutaDestino) / 1024, 2),
                "tipo" => strtoupper($extension === "jpeg" ? "jpg" : $extension),
                "fecha" => date("d/m/Y H:i:s"),
                "password_hash" => password_hash($passwordArchivo, PASSWORD_DEFAULT)
            ];

            $this->guardarDatos($datos); 

            return true;
        }

        return "No se pudo guardar el archivo.";
    }

    public function listar()
    {
        $datos = $this->leerDatos();
        $lista = [];

        foreach ($datos as $item) {

            if (!isset($item["nombre"])) {
                continue;
            }

            $nombreArchivo = basename($item["nombre"]);
            $ruta = $this->directorio . $nombreArchivo;

            if (file_exists($ruta) && is_file($ruta)) {

                $item["nombre"] = $nombreArchivo;
                $item["tamano"] = round(filesize($ruta) / 1024, 2);

                $extension = strtolower(pathinfo($ruta, PATHINFO_EXTENSION));
                $item["tipo"] = strtoupper($extension === "jpeg" ? "jpg" : $extension);

                if (!isset($item["titulo"]) || empty($item["titulo"])) {
                    $item["titulo"] = pathinfo($nombreArchivo, PATHINFO_FILENAME);
                }

                if (!isset($item["descripcion"])) {
                    $item["descripcion"] = "";
                }

                if (!isset($item["fecha"])) {
                    $item["fecha"] = date("d/m/Y H:i:s", filemtime($ruta));
                }

                if (!isset($item["correo"])) {
                    $item["correo"] = "";
                }

                if (!isset($item["password_hash"])) {
                    $item["password_hash"] = "";
                }

                $lista[] = $item;
            }
        }

        usort($lista, function ($a, $b) { 

            $fechaA = DateTime::createFromFormat("d/m/Y H:i:s", $a["fecha"]);
            $fechaB = DateTime::createFromFormat("d/m/Y H:i:s", $b["fecha"]);

            if (!$fechaA || !$fechaB) {
                return 0;
            }

            return $fechaB->getTimestamp() - $fechaA->getTimestamp();
        });

        return $lista;
    }

    public function actualizar($archivoActual, $titulo, $tipoDocumento, $descripcion, $nuevoNombre = "")
    {
        $archivoActual = basename($archivoActual);
        $titulo = $this->limpiarTexto($titulo);
        $descripcion = $this->limpiarTexto($descripcion);
        $tipoDocumento = strtolower(trim($tipoDocumento));
        $nuevoNombre = trim($nuevoNombre);

        if (empty($archivoActual)) {
            return "Debe seleccionar un archivo.";
        }

        if (empty($titulo)) {
            $titulo = pathinfo($archivoActual, PATHINFO_FILENAME);
        }

        if (empty($tipoDocumento)) {
            $tipoDocumento = strtolower(pathinfo($archivoActual, PATHINFO_EXTENSION));

            if ($tipoDocumento === "jpeg") {
                $tipoDocumento = "jpg";
            }
        }

        if (!in_array($tipoDocumento, ["pdf", "jpg", "png"])) {
            return "El tipo de documento no es válido.";
        }

        $rutaActual = $this->directorio . $archivoActual;

        if (!file_exists($rutaActual) || !is_file($rutaActual)) {
            return "El archivo seleccionado no existe.";
        }

        $extensionNueva = $tipoDocumento;

        if (!empty($nuevoNombre)) {
            $baseNombre = $this->limpiarNombreArchivo($nuevoNombre);
        } else {
            $baseNombre = $this->limpiarNombreArchivo($titulo);
        }

        $nuevoArchivo = $baseNombre . "." . $extensionNueva;
        $rutaNueva = $this->directorio . $nuevoArchivo;

        if ($nuevoArchivo !== $archivoActual && file_exists($rutaNueva)) {
            return "Ya existe un archivo con ese nombre.";
        }

        if ($nuevoArchivo !== $archivoActual) {
            if (!rename($rutaActual, $rutaNueva)) {
                return "No se pudo cambiar el nombre del archivo.";
            }
        } else {
            $rutaNueva = $rutaActual;
        }

        $datos = $this->leerDatos();
        $encontrado = false;

        foreach ($datos as &$item) {

            if (isset($item["nombre"]) && basename($item["nombre"]) === $archivoActual) {

                $item["titulo"] = $titulo;
                $item["descripcion"] = $descripcion;
                $item["nombre"] = $nuevoArchivo;
                $item["tipo"] = strtoupper($extensionNueva);
                $item["tamano"] = round(filesize($rutaNueva) / 1024, 2);
                $item["fecha"] = date("d/m/Y H:i:s");

                $encontrado = true;
                break;
            }
        }

        unset($item);

        if (!$encontrado) {
            return "No se encontró la información del archivo.";
        }

        $this->guardarDatos($datos);

        return true;
    }

    public function verificarPassword($nombre, $passwordArchivo) 
    {
        $nombre = basename($nombre);

        if (empty($nombre) || empty($passwordArchivo)) {
            return false;
        }

        $datos = $this->leerDatos();

        foreach ($datos as $item) {

            if (isset($item["nombre"]) && basename($item["nombre"]) === $nombre) {

                if (!isset($item["password_hash"]) || empty($item["password_hash"])) {
                    return false;
                }

                return password_verify($passwordArchivo, $item["password_hash"]); 
            }
        }

        return false;
    }

    public function verificarCorreo($nombre, $correoArchivo)
    {
        $nombre = basename($nombre);
        $correoArchivo = strtolower(trim($correoArchivo));

        if (empty($nombre) || empty($correoArchivo)) {
            return false;
        }

        $datos = $this->leerDatos();

        foreach ($datos as $item) {

            if (isset($item["nombre"]) && basename($item["nombre"]) === $nombre) {

                if (!isset($item["correo"]) || empty($item["correo"])) {
                    return false;
                }

                return strtolower($item["correo"]) === $correoArchivo;
            }
        }

        return false;
    }

    public function recuperarPassword($nombre, $correoArchivo, $nuevaPassword)
    {
        $nombre = basename($nombre);
        $correoArchivo = strtolower(trim($correoArchivo));

        if (empty($nombre)) {
            return "Debe seleccionar un archivo.";
        }

        if (empty($correoArchivo)) {
            return "Debe ingresar el correo registrado.";
        }

        if (!filter_var($correoArchivo, FILTER_VALIDATE_EMAIL)) {
            return "El correo electrónico no es válido.";
        }

        if (empty($nuevaPassword)) {
            return "Debe ingresar una nueva contraseña.";
        }

        if (strlen($nuevaPassword) < 7) {
            return "La nueva contraseña debe tener mínimo 7 caracteres.";
        }

        $datos = $this->leerDatos();
        $encontrado = false;

        foreach ($datos as &$item) {

            if (isset($item["nombre"]) && basename($item["nombre"]) === $nombre) {

                if (!isset($item["correo"]) || strtolower($item["correo"]) !== $correoArchivo) {
                    return "El correo electrónico no corresponde a este archivo.";
                }

                $item["password_hash"] = password_hash($nuevaPassword, PASSWORD_DEFAULT);
                $item["fecha"] = date("d/m/Y H:i:s");

                $encontrado = true;
                break;
            }
        }

        unset($item);

        if (!$encontrado) {
            return "No se encontró el archivo seleccionado.";
        }

        $this->guardarDatos($datos);

        return true;
    }

    public function obtenerRutaSegura($nombre) 
    {
        $nombre = basename($nombre);

        if (empty($nombre)) {
            return false;
        }

        $ruta = $this->directorio . $nombre;

        $rutaReal = realpath($ruta);
        $directorioReal = realpath($this->directorio);

        if ($rutaReal === false || $directorioReal === false) {
            return false;
        }

        if (strpos($rutaReal, $directorioReal) !== 0) {
            return false;
        }

        if (!file_exists($rutaReal) || !is_file($rutaReal)) {
            return false;
        }

        return $rutaReal;
    }

    public function eliminar($nombre, $passwordArchivo = "")  
    {
        $nombre = basename($nombre);

        if (empty($nombre)) {
            return false;
        }

        if (!$this->verificarPassword($nombre, $passwordArchivo)) { 
            return "Contraseña incorrecta.";
        }

        $rutaReal = $this->obtenerRutaSegura($nombre); 

        if ($rutaReal === false) {
            return false;
        }

        $eliminado = unlink($rutaReal);

        if ($eliminado) {

            $datos = $this->leerDatos();

            $datos = array_filter($datos, function ($item) use ($nombre) {
                return isset($item["nombre"]) && basename($item["nombre"]) !== $nombre;
            });

            $this->guardarDatos(array_values($datos));
        }

        return $eliminado;
    }

    private function leerDatos() 
    {
        if (!file_exists($this->archivoDatos)) {
            return [];
        }

        $contenido = file_get_contents($this->archivoDatos);
        $datos = json_decode($contenido, true);

        if (!is_array($datos)) {
            return [];
        }

        return $datos;
    }

    private function guardarDatos($datos)
    {
        file_put_contents(
            $this->archivoDatos,
            json_encode($datos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );
    }

    private function generarNombreSeguro($extension)
    {
        return "archivo_" . date("Ymd_His") . "_" . bin2hex(random_bytes(6)) . "." . $extension;
    }

    private function limpiarNombreArchivo($nombre) 
    {
        $nombre = strtolower(trim($nombre));

        $buscar = ["á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú", "ñ", "Ñ"];
        $reemplazar = ["a", "e", "i", "o", "u", "a", "e", "i", "o", "u", "n", "n"];

        $nombre = str_replace($buscar, $reemplazar, $nombre);
        $nombre = preg_replace("/[^a-z0-9_-]/", "_", $nombre);
        $nombre = preg_replace("/+/", "", $nombre);
        $nombre = trim($nombre, "_");

        if (empty($nombre)) {
            $nombre = "archivo_" . date("Ymd_His");
        }

        return $nombre;
    }

    private function limpiarTexto($texto)   
    {
        return strip_tags(trim($texto));
    }

    private function extensionCoincideConMime($extension, $mime) 
    {
        $relacion = [
            "pdf"  => ["application/pdf"],
            "jpg"  => ["image/jpeg"],
            "jpeg" => ["image/jpeg"],
            "png"  => ["image/png"]
        ];

        return isset($relacion[$extension]) && in_array($mime, $relacion[$extension]);
    }
}

?> 