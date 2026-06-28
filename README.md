# Sistema de Gestión Segura de Archivos 

## Descripción  

El Sistema de Gestión Segura de Archivos es una aplicación web desarrollada en PHP utilizando Programación Orientada a Objetos (POO). Permite subir, visualizar, descargar, editar y eliminar archivos de forma segura mediante el uso de validaciones, contraseñas y medidas de seguridad que protegen la información almacenada.

El sistema fue desarrollado como parte de la asignatura Desarrollo Web, aplicando buenas prácticas de programación, organización del código y seguridad informática.

---

# Funcionalidades del sistema 

El sistema permite realizar las siguientes operaciones:

- Subir archivos PDF, JPG, JPEG y PNG.
- Registrar un correo electrónico para recuperación de contraseña.
- Proteger cada archivo mediante una contraseña.
- Visualizar archivos protegidos.
- Descargar archivos protegidos.
- Eliminar archivos protegidos.
- Editar título, descripción y nombre del archivo.
- Recuperar la contraseña utilizando el correo registrado.
- Mostrar listado completo de archivos registrados.
- Mostrar tamaño, tipo y fecha de cada archivo.

---

# Requisitos 

Para ejecutar correctamente el proyecto se necesita:

- PHP 8 o superior.
- XAMPP.
- Apache.
- Navegador web moderno.
- Visual Studio Code (opcional).

---

# Estructura del proyecto


GestionArchivo/

│
├── archivos/
│      .htaccess
│
├── clases/
│      GestorArchivos.php
│
├── css/
│      estilos.css
│
├── datos_archivos.json
├── index.php
├── subir.php
├── actualizar.php
├── eliminar.php
├── ver.php
├── descargar.php
├── recuperar_password.php
└── README.md


---

# Explicación de la clase GestorArchivos

La clase *GestorArchivos* implementa toda la lógica del sistema utilizando Programación Orientada a Objetos.

Sus principales métodos son:

### subir()

Permite validar y guardar un archivo de manera segura.

### listar()

Obtiene todos los archivos registrados y los muestra ordenados por fecha.

### actualizar()

Actualiza el título, descripción y nombre del archivo.

### verificarPassword()

Comprueba que la contraseña ingresada sea correcta.

### verificarCorreo()

Valida el correo registrado para recuperar la contraseña.

### recuperarPassword()

Permite cambiar la contraseña utilizando el correo electrónico registrado.

### obtenerRutaSegura()

Protege contra ataques de Path Traversal.

### eliminar()

Elimina un archivo únicamente cuando la contraseña es correcta.

---

# Medidas de seguridad implementadas

El sistema incorpora diversas medidas de seguridad:

- Validación de tipo MIME.
- Validación de extensión.
- Límite máximo de 2 MB por archivo.
- Renombrado automático de archivos.
- Protección mediante contraseña individual.
- Recuperación mediante correo electrónico.
- Contraseñas cifradas utilizando password_hash().
- Verificación mediante password_verify().
- Protección contra Path Traversal utilizando basename() y realpath().
- Carpeta protegida mediante archivo .htaccess.
- Bloqueo de ejecución de archivos peligrosos.
- Validación de formularios tanto en cliente como en servidor.

---

# Tecnologías utilizadas

- PHP
- HTML5
- CSS3
- Bootstrap 5
- JavaScript
- Programación Orientada a Objetos (POO)

---

# Cómo utilizar el sistema

## 1. Subir un archivo

Seleccione un archivo PDF, JPG, JPEG o PNG.

Ingrese:

- Correo electrónico.
- Contraseña del archivo.

Presione *Subir Archivo*.

---

## 2. Editar un archivo

Seleccione un documento.

Puede modificar:

- Título.
- Descripción.
- Nombre del archivo.

Presione *Actualizar Documento*.

---

## 3. Visualizar

Seleccione *Ver*.

Ingrese la contraseña correspondiente.

---

## 4. Descargar

Seleccione *Descargar*.

Ingrese la contraseña correspondiente.

---

## 5. Eliminar

Seleccione *Eliminar*.

Ingrese la contraseña del archivo.

---

## 6. Recuperar contraseña

Seleccione el archivo.

Ingrese el correo registrado.

Escriba una nueva contraseña.

Confirme la contraseña.

Presione *Cambiar contraseña*.

---

# Autor

*Darwin Kenedy Sanmartín Riera*

Universidad Técnica Particular de Loja

Carrera de Tecnología de la Información

Asignatura: Desarrollo Web

2026

---

# Conclusión

El sistema desarrollado cumple con los requisitos establecidos para la actividad, implementando Programación Orientada a Objetos, manejo seguro de archivos y múltiples mecanismos de seguridad informática. Además, ofrece una interfaz moderna e intuitiva que facilita la administración de documentos protegidos. 