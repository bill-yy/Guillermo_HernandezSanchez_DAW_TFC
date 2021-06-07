<?php
require_once "/var/www/Proyectos/prometeo/imp/db.php";
require_once "../permisos.php";
require_once "../sesiones.php";

sesionNoIniciada();

if($_SERVER["REQUEST_METHOD"] === "POST") {
    if(isset($_POST["modulo"]) && $_POST["modulo"] === "crearUser") {
        validarDatos();
    }else {
        redirigir(); 
    }
}else {
    redirigir();
}

function validarDatos() {
    if(!empty($_POST["nombreUsuario"]) && !empty($_POST["email"]) && !empty($_POST["password"]) && !empty($_POST["rol"])) {
        $telefono = !empty($_POST["telefono"]) ? $_POST["telefono"] : "NULL";
        $nombre = !empty($_POST["nombre"]) ? $_POST["nombre"] : "NULL";
        $apellido1 = !empty($_POST["apellido1"]) ? $_POST["apellido1"] : "NULL";
        $apellido2 = !empty($_POST["apellido2"]) ? $_POST["apellido2"] : "NULL";

        if(crearUsuario($_POST["nombreUsuario"], $_POST["email"], $_POST["password"], $_POST["rol"], $telefono, $nombre, $apellido1, $apellido2) === TRUE) {
            echo "USUARIO REGISTRADO CORRECTAMENTE";
        }else {
            echo "ERROR AL REGISTRAR EL USUARIO";
        }
    }else {
        echo "ERROR, DATROS INTRODUCIDOS NO VALIDOS";
    }
}