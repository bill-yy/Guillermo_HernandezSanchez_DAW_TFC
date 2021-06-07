<?php
    require_once "/var/www/Proyectos/prometeo/imp/db.php";
    require_once "/var/www/Proyectos/prometeo/imp/permisos.php";
    
     if($_SERVER["REQUEST_METHOD"] == "GET") {
        if(isset($_GET["modulo"]) && $_GET["modulo"] == "categorias") {
            $subcategorias = listarSubcategorias();
            if($subcategorias != FALSE) {
                foreach($subcategorias as $row) {
                    echo $row["nombre_subcategoria"]."||";
                }
            }else {
                echo "ERROR AL CARGAR LAS CATEGORÍAS";
            }
        }else if(isset($_GET["modulo"]) && $_GET["modulo"] == "tipos") {
            $unidades = listarUnidades();
            if($unidades != FALSE) {
                foreach($unidades as $row) {
                    echo $row["tipo"]."||";
                }
            }else {
                echo "ERROR AL CARGAR LAS UNIDADES";
            }
        }else if(isset($_GET["producto"]) && !empty($_GET["producto"])) {
           $producto = comprobarProducto($_GET["producto"]);

           if($producto != FALSE) {
                foreach($producto as $row) {
                    echo $row["nombre_producto"]."||".$row["nombre_subcategoria"]."||".$row["marca"];
                }
            }else {
                echo "FALSE";
            }
        }
     }else {
        if(isset($_POST["modulo"]) && $_POST["modulo"] == "crearProducto" && isset($_POST["nombreProducto"]) && !empty($_POST["nombreProducto"]) && isset($_POST["bc"]) && !empty($_POST["bc"]) && isset($_POST["codApp"]) && !empty($_POST["codApp"]) && comprobarCodigoApp($_POST["codApp"]) != FALSE && isset($_POST["cantidad"]) && !empty($_POST["cantidad"]) && isset($_POST["unidad"]) && !empty($_POST["unidad"]) && isset($_POST["subcategoria"]) && !empty($_POST["subcategoria"]) && isset($_POST["marca"]) && !empty($_POST["marca"]) && isset($_POST["caducidad"]) && !empty($_POST["caducidad"])) {
            $marca = !empty($_POST["marca"]) ? $_POST["marca"] : "NULL";
            $caducidad = $_POST["caducidad"] != "Caducidad" ? $_POST["caducidad"] : "NULL";

            if(crearProductoApp($_POST["nombreProducto"], $_POST["cantidad"], $_POST["unidad"], $_POST["subcategoria"], $caducidad, $marca, $_POST["bc"], $_POST["codApp"]) === FALSE) {
                echo "FALSE";
            }else {
                echo "TRUE";
            }
        }else {
            echo "FALSE";
        }
     }
?>