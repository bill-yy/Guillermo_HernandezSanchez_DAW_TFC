<?php
require_once "/var/www/Proyectos/prometeo/imp/db.php";
require_once "../permisos.php";
require_once "../sesiones.php";

sesionNoIniciada();

if($_SERVER["REQUEST_METHOD"] === "POST") {
    if(!empty($_POST["total"]) && !empty($_POST["fechaVenta"]) && !empty($_POST["cliente"]) && !empty($_POST["remesa"]) && !empty($_POST["cantidad"])) {
        if(crearVenta($_POST["total"], $_POST["fechaVenta"], $_POST["cliente"], $_POST["remesa"], $_POST["cantidad"]) === FALSE) {
            echo "ERROR AL INTRODUCIR LA VENTA";
        }else {
            echo "Venta introducida correctamente";
        }
    }else if($_POST["modulo"] === "eliVenta" && !empty($_POST["idVenta"]) ) {
        eliminarVenta($_POST["idVenta"]);
    }else {
        echo "FALTAN DATOS";
        redirigir();
    }
}else {
    redirigir();
}