<?php
    require_once "/var/www/Proyectos/prometeo/imp/db.php";
    require_once "../sesiones.php";
    require_once "../permisos.php";

    sesionNoIniciada();

    if($_SERVER["REQUEST_METHOD"] === "POST") {
        if($_POST["modulo"] === "fPerfil" && isset($_FILES['fotoPerfil'])) {
            subidaFotoPerfil();
        }else if($_POST["modulo"] === "modPerfil") {
            if(isset($_POST["nombreUsuario"]) && !empty($_POST["nombreUsuario"]) && isset($_POST["email"]) && !empty($_POST["email"]) && isset($_POST["telefono"]) && isset($_POST["nombre"]) && isset($_POST["apellido1"]) && isset($_POST["apellido2"])) {
                $telefono = !empty($_POST["telefono"]) ? $_POST["telefono"] : "NULL";
                $nombre = !empty($_POST["nombre"]) ? $_POST["nombre"] : "NULL";
                $apellido1 = !empty($_POST["apellido1"]) ? $_POST["apellido1"] : "NULL";
                $apellido2 = !empty($_POST["apellido2"]) ? $_POST["apellido2"] : "NULL";

                if(actualizarDatosUsuario($_POST["nombreUsuario"], $_POST["email"], $telefono, $nombre, $apellido1, $apellido2)) {
                    echo "Datos actualizados correctamente";
                }else {
                    echo "ERROR AL ACTUALIZAR LOS DATOS DE USUARIO";
                }
            }else {
                echo "ERROR CON LOS DATOS INTRODUCIDOS";
            }
        }else {
            redirigir();
        }
    }else {
        redirigir();
    }

    function subidaFotoPerfil() {
        $file_name = $_FILES['fotoPerfil']['name'];
        $file_size = $_FILES['fotoPerfil']['size'];
        $file_tmp = $_FILES['fotoPerfil']['tmp_name'];
        $file_type = $_FILES['fotoPerfil']['type'];
        $file_ext = explode('.', $file_name);
        $file_ext = strtolower(end($file_ext));
        if(strpos($file_type, "jpeg") || strpos($file_type, "jpg") || strpos($file_type, "png")) {
            if($file_size > 2097152) {
                echo "ERROR EL ARCHIVO ES DEMASIADO GRANDE";
            }else {
                $nuevoNombre = uniqid('', true).'.'.$file_ext;
                $destino = "./userspics/".$nuevoNombre;
                echo $destino;
                if(move_uploaded_file($file_tmp,$destino)) {
                    if(actualizarFotoPerfil($nuevoNombre, $_POST["user"])) {
                        echo "Archivo guardado correctamente";
                    }else {
                        echo "ERROR AL GUARDAR EL ARCHIVO";
                    }
                }else {
                    echo "ERROR AL SUBIR EL ARCHIVO";
                }
            }
        }else {
            echo "ERROR AL CON EL ARCHIVO INTRODUCIDO";
        }
    }

    