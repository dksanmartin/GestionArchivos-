<?php

require_once "clases/GestorArchivos.php"; 

$gestor = new GestorArchivos(); 
$archivos = $gestor->listar(); 

$mensaje = "";
$tipoMensaje = "";

if (isset($_GET['subido'])) { 
    $mensaje = "Archivo subido correctamente.";
    $tipoMensaje = "success";
}

if (isset($_GET['actualizado'])) {
    $mensaje = "Documento actualizado correctamente.";
    $tipoMensaje = "success";
}

if (isset($_GET['eliminado'])) {
    $mensaje = "Documento eliminado correctamente.";
    $tipoMensaje = "danger";
}

if (isset($_GET['recuperado'])) {
    $mensaje = "Contraseña actualizada correctamente.";
    $tipoMensaje = "success";
}

if (isset($_GET['error'])) {
    $mensaje = $_GET['error'];
    $tipoMensaje = "danger";
}

?>

<!DOCTYPE html> 
<html lang="es"> 

<head>
    <meta charset="UTF-8">     
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestión Segura de Archivos</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/estilos.css">
</head>

<body>

<header class="hero-header"> 
    <div class="container">
        <div class="hero-content">
            <div>
                <span class="etiqueta-superior">Proyecto Desarrollo Web - POO</span>

                <h1>
                    <i class="bi bi-shield-lock-fill"></i>
                    Sistema de Gestión Segura de Archivos
                </h1>

                <p>
                    Plataforma web para subir, editar, visualizar, descargar y eliminar archivos de forma segura.
                </p>
            </div>

            <div class="hero-icon">
                <i class="bi bi-folder-check"></i>
            </div>
        </div>
    </div>
</header>

<nav class="navbar navbar-expand-lg navbar-dark menu-moderno"> 
    <div class="container">
        <a class="navbar-brand" href="index.php"> 
            <i class="bi bi-house-door-fill"></i>
            Inicio
        </a>

        <a class="navbar-brand" href="#subir"> 
            <i class="bi bi-cloud-upload-fill"></i>
            Subir
        </a>

        <a class="navbar-brand" href="#editar"> 
            <i class="bi bi-pencil-square"></i>
            Editar
        </a>

        <a class="navbar-brand" href="#recuperar"> 
            <i class="bi bi-key-fill"></i>
            Recuperar
        </a>

        <a class="navbar-brand" href="#listado"> 
            <i class="bi bi-folder2-open"></i>
            Archivos
        </a>
    </div>
</nav>

<main class="container contenido-principal">  

    <?php if (!empty($mensaje)): ?> 
        <section class="mt-4"> 
            <div class="alert alert-<?php echo $tipoMensaje; ?> alert-dismissible fade show shadow-sm">
                <?php echo htmlspecialchars($mensaje); ?>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                </button>
            </div>
        </section>
    <?php endif; ?>

    <section class="tarjeta-principal mt-5" id="subir">

        <div class="titulo-tarjeta"> 
            <div>
                <h2>
                    <i class="bi bi-cloud-upload-fill"></i>
                    Subir Archivo Nuevo
                </h2>

                <p>
                    Seleccione un archivo PDF, JPG o PNG, registre un correo y agregue una contraseña de seguridad.
                </p>
            </div>

            <span class="badge-modern">
                PDF · JPG · PNG
            </span>
        </div>

        <form id="formSubir"
              action="subir.php"   
              method="POST"
              enctype="multipart/form-data"
              autocomplete="off"> 

            <div class="row g-4"> 

                <div class="col-md-12">
                    <label class="form-label">
                        Seleccionar archivo
                    </label>

                    <div class="upload-box">
                        <i class="bi bi-cloud-upload-fill"></i>

                        <input type="file"
                               name="archivo"
                               class="form-control campo-archivo"
                               accept=".pdf,.jpg,.jpeg,.png"
                               required>

                        <p>
                            Solo se permiten archivos PDF, JPG, JPEG y PNG con tamaño máximo de 2 MB.
                        </p>
                    </div>
                </div>

                <div class="col-md-12">
                    <label class="form-label">
                        Correo electrónico
                    </label>

                    <input type="email"
                           name="correo_archivo"
                           id="correoArchivo"
                           class="form-control campo-moderno"
                           placeholder="Ingrese su correo electrónico"
                           autocomplete="off"
                           required> 

                    <small class="text-muted">
                        Este correo servirá para recuperar la contraseña del archivo.
                    </small>
                </div>

                <div class="col-md-12">
                    <label class="form-label">
                        Contraseña del archivo
                    </label>

                    <div class="input-group">
                        <input type="password" 
                               name="password_archivo" 
                               id="passwordArchivo"
                               class="form-control campo-moderno"
                               minlength="7"
                               placeholder="Mínimo 7 caracteres: letras, números o signos"
                               autocomplete="new-password" 
                               required> 

                        <button class="btn btn-outline-secondary btnMostrarPassword"
                                type="button"
                                data-target="passwordArchivo">
                            <i class="bi bi-eye-fill"></i>
                        </button>
                    </div>

                    <small class="text-muted">
                        Esta contraseña será necesaria para ver, descargar o eliminar el archivo.
                    </small>
                </div>

                <div class="col-md-12">
                    <div class="seguridad-box">
                        <i class="bi bi-shield-check"></i>

                        <span>
                            El sistema valida extensión, tipo MIME, tamaño máximo, correo, contraseña y protege la carpeta de archivos.
                        </span>
                    </div>
                </div>

                <div class="col-md-12 text-center botones-formulario">

                    <button type="button"
                            class="btn btn-continuar"
                            id="btnConfirmarSubida">

                        <i class="bi bi-cloud-upload-fill"></i>
                        Subir Archivo

                    </button>

                    <button type="reset"
                            class="btn btn-cancelar">

                        <i class="bi bi-x-circle-fill"></i>
                        Cancelar

                    </button>

                </div>

            </div>

        </form>

    </section> 

    <section class="tarjeta-principal mt-5" id="editar">

        <div class="titulo-tarjeta">   
            <div>
                <h2>
                    <i class="bi bi-pencil-square"></i>
                    Editar Documento
                </h2>

                <p>
                    Seleccione un archivo ya registrado para modificar su información.
                </p>
            </div>

            <span class="badge-modern badge-editar">   
                Editar
            </span> 
        </div>

        <form action="actualizar.php" method="POST">   
 
            <div class="row g-4">

                <div class="col-md-12">
                    <label class="form-label">
                        Archivos
                    </label>

                    <select name="archivo"  
                            id="archivoSeleccionado"
                            class="form-select campo-moderno"> 

                        <option value="">
                            Seleccione un archivo
                        </option>

                        <?php foreach ($archivos as $archivo): ?>

                            <option
                                value="<?= htmlspecialchars($archivo['nombre']); ?>" 
                                data-titulo="<?= htmlspecialchars($archivo['titulo']); ?>"
                                data-descripcion="<?= htmlspecialchars($archivo['descripcion']); ?>"
                                data-tipo="<?= strtolower($archivo['tipo']) == 'jpeg' ? 'jpg' : strtolower($archivo['tipo']); ?>">

                                <?= htmlspecialchars($archivo['nombre']); ?>

                            </option>

                        <?php endforeach; ?>

                    </select>
                </div>

                <div class="col-md-6"> 
                    <label class="form-label">
                        Título del documento
                    </label>

                    <input type="text"
                           id="titulo"
                           name="titulo"
                           class="form-control campo-moderno"> 
                </div>

                <div class="col-md-6">
                    <label class="form-label"> 
                        Tipo del documento
                    </label>

                    <select id="tipo_documento"
                            name="tipo_documento"
                            class="form-select campo-moderno"> 

                        <option value="pdf">PDF</option>
                        <option value="jpg">JPG</option>
                        <option value="png">PNG</option>

                    </select>
                </div>

                <div class="col-md-12">
                    <label class="form-label">
                        Descripción
                    </label>

                    <textarea id="descripcion"
                              name="descripcion"
                              rows="4"
                              class="form-control campo-moderno"></textarea>
                </div>

                <div class="col-md-12">
                    <label class="form-label">
                        Cambiar nombre del archivo
                    </label>

                    <input type="text"
                           id="nuevo_nombre"
                           name="nuevo_nombre"
                           class="form-control campo-moderno">

                    <small class="text-muted">
                        Si lo deja vacío, conservará el nombre actual.
                    </small>
                </div>

                <div class="col-md-12 text-center">
                    <button type="submit"
                            class="btn btn-continuar">

                        <i class="bi bi-pencil-square"></i>
                        Actualizar Documento

                    </button>
                </div>

            </div>

        </form>

    </section> 
    
    <section class="tarjeta-principal mt-5" id="recuperar">

        <div class="titulo-tarjeta"> 
            <div>
                <h2>
                    <i class="bi bi-key-fill"></i>
                    Recuperar contraseña
                </h2>

                <p>
                    Si olvidó la contraseña de un archivo, puede restablecerla utilizando el correo electrónico registrado.
                </p>
            </div>

            <span class="badge-modern">
                Recuperación
            </span>
        </div>

        <form action="recuperar_password.php" method="POST"> 

            <div class="row g-4">

                <div class="col-md-12">

                    <label class="form-label">
                        Seleccione el archivo
                    </label>

                    <select name="archivo"
                            class="form-select campo-moderno"
                            required>

                        <option value="">
                            Seleccione un archivo
                        </option>

                        <?php foreach ($archivos as $archivo): ?>

                            <option value="<?= htmlspecialchars($archivo['nombre']); ?>">
                                <?= htmlspecialchars($archivo['nombre']); ?>
                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

                <div class="col-md-12">

                    <label class="form-label">
                        Correo electrónico registrado
                    </label>

                    <input type="email"
                           name="correo_archivo" 
                           class="form-control campo-moderno"
                           placeholder="Ingrese el correo utilizado al subir el archivo"
                           autocomplete="off" 
                           required>

                </div>

                <div class="col-md-6">

                    <label class="form-label">
                        Nueva contraseña
                    </label>

                    <div class="input-group">

                        <input type="password" 
                               name="nueva_password"
                               id="nuevaPassword"
                               class="form-control campo-moderno"
                               minlength="7"
                               placeholder="Mínimo 7 caracteres"
                               autocomplete="new-password" 
                               required>

                        <button type="button"
                                class="btn btn-outline-secondary btnMostrarPassword"
                                data-target="nuevaPassword">

                            <i class="bi bi-eye-fill"></i>

                        </button>

                    </div>

                </div>

                <div class="col-md-6">

                    <label class="form-label"> 
                        Confirmar contraseña
                    </label>

                    <div class="input-group">

                        <input type="password"
                               name="confirmar_password"
                               id="confirmarPassword"
                               class="form-control campo-moderno"
                               minlength="7"
                               placeholder="Repita la nueva contraseña"
                               required>

                        <button type="button"
                                class="btn btn-outline-secondary btnMostrarPassword"
                                data-target="confirmarPassword">

                            <i class="bi bi-eye-fill"></i>

                        </button>

                    </div>

                </div>

                <div class="col-md-12 text-center"> 

                    <button type="submit"
                            class="btn btn-continuar">

                        <i class="bi bi-key-fill"></i>
                        Cambiar contraseña

                    </button>

                </div>

            </div>

        </form>

    </section> 

    <section class="tarjeta-principal mt-5 mb-5" id="listado">

        <div class="titulo-tarjeta">

            <div>

                <h2>
                    <i class="bi bi-folder2-open"></i>
                    Archivos Registrados
                </h2>

                <p>
                    Visualice, descargue o elimine los documentos almacenados.
                </p>

            </div>

            <span class="badge-modern">
                <?= count($archivos); ?> Documento(s)
            </span>

        </div>

        <div class="table-responsive">

            <table class="table tabla-moderna align-middle">

                <thead>

                    <tr>

                        <th>#</th>
                        <th>Título</th>
                        <th>Descripción</th>
                        <th>Archivo</th>
                        <th>Tamaño</th>
                        <th>Tipo</th>
                        <th>Fecha</th>
                        <th>Acciones</th>

                    </tr>

                </thead>

                <tbody>

                <?php if(count($archivos)>0): ?>

                    <?php $contador=1; ?>

                    <?php foreach($archivos as $archivo): ?>

                    <tr>

                        <td class="text-center"> 
                            <?= $contador++; ?>
                        </td>

                        <td>

                            <strong>

                                <?= htmlspecialchars($archivo["titulo"] ?? $archivo["nombre"]); ?>

                            </strong>

                        </td>

                        <td>

                            <?= htmlspecialchars($archivo["descripcion"] ?? "Sin descripción"); ?>

                        </td>

                        <td>

                            <i class="bi bi-file-earmark-fill text-primary"></i>

                            <?= htmlspecialchars($archivo["nombre"]); ?> 

                        </td>

                        <td class="text-center">

                            <?= htmlspecialchars($archivo["tamano"]); ?> KB

                        </td>

                        <td class="text-center">

                            <?php

                            $tipo=strtoupper($archivo["tipo"]);

                            if($tipo=="PDF"){

                                echo '<span class="badge bg-danger">PDF</span>';

                            }elseif($tipo=="JPG" || $tipo=="JPEG"){

                                echo '<span class="badge bg-warning text-dark">JPG</span>';

                            }else{

                                echo '<span class="badge bg-success">PNG</span>';

                            }

                            ?>

                        </td>

                        <td class="text-center">

                            <?= htmlspecialchars($archivo["fecha"]); ?>

                        </td>

                        <td class="text-center">

                            <button
                                type="button"
                                class="btn btn-sm btn-ver btnAccionSegura"
                                data-archivo="<?= htmlspecialchars($archivo["nombre"]); ?>"
                                data-accion="ver.php"
                                data-titulo="Ver archivo">

                                <i class="bi bi-eye-fill"></i>
                                Ver

                            </button>

                            <button
                                type="button"
                                class="btn btn-sm btn-descargar btnAccionSegura"
                                data-archivo="<?= htmlspecialchars($archivo["nombre"]); ?>"
                                data-accion="descargar.php"
                                data-titulo="Descargar archivo">

                                <i class="bi bi-download"></i>
                                Descargar

                            </button>

                            <button
                                type="button"
                                class="btn btn-sm btn-eliminar btnAccionSegura"
                                data-archivo="<?= htmlspecialchars($archivo["nombre"]); ?>"
                                data-accion="eliminar.php"
                                data-titulo="Eliminar archivo">

                                <i class="bi bi-trash-fill"></i>
                                Eliminar

                            </button>

                        </td>

                    </tr>

                    <?php endforeach; ?>

                <?php else: ?>

                    <tr>

                        <td colspan="8" class="text-center p-5">

                            <i class="bi bi-folder-x display-4 text-secondary"></i>

                            <h5 class="mt-3">

                                No existen documentos registrados.

                            </h5>

                            <p>

                                Cargue un archivo para visualizarlo aquí.

                            </p>

                        </td>

                    </tr>

                <?php endif; ?>

                </tbody>

            </table>

        </div>

    </section>

</main> 

<footer class="footer-moderno"> 

    <div class="container">

        <div class="row">

            <div class="col-md-4 mb-4">
                <h4>
                    <i class="bi bi-folder-check"></i>
                    Sistema de Gestión Segura
                </h4>

                <p>
                    Plataforma desarrollada para la administración segura de documentos.
                </p>
            </div>

            <div class="col-md-4 mb-4">
                <h4>
                    <i class="bi bi-shield-lock-fill"></i>
                    Seguridad Aplicada
                </h4>

                <ul class="lista-footer">
                    <li>✔️ Validación MIME</li>
                    <li>✔️ Validación de extensión</li>
                    <li>✔️ Tamaño máximo 2 MB</li>
                    <li>✔️ Contraseña por archivo</li>
                    <li>✔️ Correo de recuperación</li>
                    <li>✔️ Protección .htaccess</li>
                    <li>✔️ Prevención Path Traversal</li>
                </ul>
            </div>

            <div class="col-md-4 mb-4">
                <h4>
                    <i class="bi bi-person-circle"></i>
                    Desarrollador
                </h4>

                <p>
                    <strong>Darwin Kenedy Sanmartín Riera</strong>
                </p>

                <p>UTPL - Tecnología de la Información</p>
                <p>Desarrollo Web</p>
            </div>

        </div>

        <hr>

        <div class="text-center"> 
            © <?php echo date("Y"); ?> Sistema de Gestión Segura de Archivos
        </div>

    </div>

</footer>

<!-- MODAL CONFIRMAR SUBIDA -->
<div class="modal fade" id="modalSubirArchivo" tabindex="-1" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header bg-primary text-white">

                <h5 class="modal-title">
                    <i class="bi bi-cloud-upload-fill"></i>
                    Confirmar subida
                </h5>

                <button type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body text-center">

                <i class="bi bi-file-earmark-arrow-up-fill text-primary display-4"></i>

                <p class="mt-3">
                    ¿Está seguro de subir este archivo?
                </p>

                <strong id="nombreArchivoSeleccionado"> 
                    Ningún archivo seleccionado
                </strong>

                <p class="mt-3 text-muted">
                    El archivo quedará protegido con la contraseña ingresada y asociado al correo registrado.
                </p>

            </div>

            <div class="modal-footer">

                <button type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                    Cancelar
                </button>

                <button type="button"
                        class="btn btn-primary"
                        id="btnSubirDefinitivo">
                    Sí, subir
                </button>

            </div>

        </div>

    </div>

</div>

<!-- MODAL CONTRASEÑA PARA VER / DESCARGAR / ELIMINAR -->
<div class="modal fade" id="modalPasswordArchivo" tabindex="-1" aria-hidden="true"> 

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <form id="formPasswordArchivo" method="POST">

                <div class="modal-header bg-dark text-white">

                    <h5 class="modal-title" id="tituloModalPassword">
                        Acción protegida
                    </h5>

                    <button type="button" 
                            class="btn-close btn-close-white"
                            data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <div class="text-center mb-3">
                        <i class="bi bi-shield-lock-fill text-primary display-4"></i>
                    </div>

                    <p class="text-center">
                        Ingrese la contraseña del archivo:
                    </p>

                    <strong class="d-block text-center mb-3" id="nombreArchivoProtegido">
                        Archivo seleccionado
                    </strong>

                    <input type="hidden"
                           name="archivo"
                           id="archivoProtegido">

                    <label class="form-label">
                        Contraseña
                    </label>

                    <div class="input-group"> 

                        <input type="password"
                               name="password_archivo"
                               id="passwordAccion"
                               class="form-control campo-moderno"
                               minlength="7"
                               placeholder="Ingrese la contraseña del archivo"
                               required>

                        <button type="button"
                                class="btn btn-outline-secondary btnMostrarPassword"
                                data-target="passwordAccion">

                            <i class="bi bi-eye-fill"></i>

                        </button>

                    </div>

                    <small class="text-muted">
                        Mínimo 7 caracteres.
                    </small>

                    <div class="text-center mt-3">
                        <a href="#" id="linkRecuperarPassword">  
                            ¿Olvidaste la contraseña?
                        </a>
                    </div>

                </div>

                <div class="modal-footer"> 

                    <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">
                        Cancelar
                    </button>

                    <button type="submit"
                            class="btn btn-primary">
                        Continuar
                    </button>

                </div>

            </form>

        </div>

    </div>

</div> 

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>  

<script>
document.addEventListener("DOMContentLoaded", function () {   

    const selector = document.getElementById("archivoSeleccionado");
    const titulo = document.getElementById("titulo");
    const descripcion = document.getElementById("descripcion");
    const tipo = document.getElementById("tipo_documento");
    const nuevoNombre = document.getElementById("nuevo_nombre");

    const formSubir = document.getElementById("formSubir");
    const btnConfirmarSubida = document.getElementById("btnConfirmarSubida");
    const btnSubirDefinitivo = document.getElementById("btnSubirDefinitivo");
    const nombreArchivo = document.getElementById("nombreArchivoSeleccionado");
    const passwordArchivo = document.getElementById("passwordArchivo");
    const correoArchivo = document.getElementById("correoArchivo");

    if (formSubir && btnConfirmarSubida && btnSubirDefinitivo) {

        const inputArchivo = formSubir.querySelector('input[name="archivo"]'); 
        const modalSubir = new bootstrap.Modal(document.getElementById("modalSubirArchivo"));

        btnConfirmarSubida.addEventListener("click", function () {

            if (!inputArchivo.files || inputArchivo.files.length === 0) {
                alert("Debe seleccionar un archivo antes de subir.");
                return;
            }

            if (!correoArchivo.value) {
                alert("Debe ingresar un correo electrónico.");
                return;
            }

            if (!passwordArchivo.value || passwordArchivo.value.length < 7) {
                alert("La contraseña debe tener mínimo 7 caracteres.");
                return;
            }

            nombreArchivo.textContent = inputArchivo.files[0].name;
            modalSubir.show();

        });

        btnSubirDefinitivo.addEventListener("click", function () { 
            formSubir.submit();
        });

    }

    if (selector) { 

        selector.addEventListener("change", function () {

            const opcion = selector.options[selector.selectedIndex];

            titulo.value = opcion.getAttribute("data-titulo") || "";
            descripcion.value = opcion.getAttribute("data-descripcion") || "";
            tipo.value = opcion.getAttribute("data-tipo") || "";

            if (opcion.value) {
                nuevoNombre.value = opcion.value.replace(/\.[^/.]+$/, "");
            } else {
                nuevoNombre.value = "";
            }

        });

    }

    const botonesAccion = document.querySelectorAll(".btnAccionSegura");
    const modalPasswordElemento = document.getElementById("modalPasswordArchivo");
    const modalPassword = new bootstrap.Modal(modalPasswordElemento);
    const formPassword = document.getElementById("formPasswordArchivo");
    const archivoProtegido = document.getElementById("archivoProtegido");
    const nombreArchivoProtegido = document.getElementById("nombreArchivoProtegido");
    const tituloModalPassword = document.getElementById("tituloModalPassword");
    const passwordAccion = document.getElementById("passwordAccion");

    botonesAccion.forEach(function (boton) { 

        boton.addEventListener("click", function () {

            const archivo = boton.getAttribute("data-archivo");
            const accion = boton.getAttribute("data-accion");
            const tituloAccion = boton.getAttribute("data-titulo");

            formPassword.action = accion;
            archivoProtegido.value = archivo;
            nombreArchivoProtegido.textContent = archivo;
            tituloModalPassword.textContent = tituloAccion;
            passwordAccion.value = "";

            modalPassword.show();

        });

    });

    const botonesMostrarPassword = document.querySelectorAll(".btnMostrarPassword");  

    botonesMostrarPassword.forEach(function (boton) {

        boton.addEventListener("click", function () {

            const target = boton.getAttribute("data-target");
            const input = document.getElementById(target);
            const icono = boton.querySelector("i");

            if (input.type === "password") {
                input.type = "text";
                icono.classList.remove("bi-eye-fill");
                icono.classList.add("bi-eye-slash-fill");
            } else {
                input.type = "password";
                icono.classList.remove("bi-eye-slash-fill");
                icono.classList.add("bi-eye-fill");
            }

        });

    });

    const linkRecuperar = document.getElementById("linkRecuperarPassword"); 

if (linkRecuperar) {

    linkRecuperar.addEventListener("click", function(e) { 

        e.preventDefault();

        modalPassword.hide();

        setTimeout(function() {

            document.getElementById("recuperar").scrollIntoView({ 
                behavior: "smooth"
            });

        }, 300);

    });

} 

    if (window.location.search !== "") { 
        window.history.replaceState({}, document.title, "index.php");    
    }

});
</script>

</body>

</html> 