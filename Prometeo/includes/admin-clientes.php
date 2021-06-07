<?php
require_once "/var/www/Proyectos/prometeo/imp/db.php";
require_once "../permisos.php";
require_once "../sesiones.php";

sesionNoIniciada();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if(isset($_POST["modulo"]) && $_POST["modulo"] != "") {
        if($_POST["modulo"] === "eliCli") {
            eliminarCliente();
        }else if($_POST["modulo"] === "crearCli") {
            crearCliente();
        }else if($_POST["modulo"] === "modCli") {
            modificarCliente();
        }else if($_POST["modulo"] === "buscarCli") {
            buscarCliente();
        }else {
            redirigir();
        }
    }else {
        redirigir();
    }
}else {
    redirigir();
}

function eliminarCliente() {
    if(isset($_POST["idCliente"]) && $_POST["idCliente"] != "") {
        $conexionDB = conexionBd();

        $resul = $conexionDB -> prepare("DELETE FROM clientes WHERE id_cliente = ?");
        $resul -> execute(array($_POST["idCliente"]));

        if($resul -> rowCount() != 1) {
            echo "ERROR ESTE CLIENTE NO EXISTE";
        }else {
            echo "Cliente borrado exitosamente";
        }
    }else {
        echo "ERROR DATOS INTRODUCIDOS INCORRECTOS";
    }
}

function crearCliente() {
    if(isset($_POST["tipoCliente"]) && $_POST["tipoCliente"] != "" && isset($_POST["nombreCliente"]) && $_POST["nombreCliente"] != "" && isset($_POST["nombreEmpresa"]) && isset($_POST["correoCliente"]) && isset($_POST["telefonoCliente"])) {
        $conexionDB = conexionBd();

        $tipoCliente = tipoClientePorNombre($_POST["tipoCliente"])[0];
        $nombre = $_POST["nombreCliente"];
        $nombreEmpresa = $_POST["nombreEmpresa"] != "" ? $_POST["nombreEmpresa"] : "NULL";
        $correo = $_POST["correoCliente"] != "" ? $_POST["correoCliente"] : "NULL";
        $telefono = $_POST["telefonoCliente"] != "" ? $_POST["telefonoCliente"] : "NULL";

        $resul = $conexionDB -> query("INSERT INTO clientes (tipo_cliente,nombre_empresa ,nombre ,correo ,telefono) values($tipoCliente, '$nombreEmpresa', '$nombre', '$correo', '$telefono')");

        if(!$resul) {
            echo "ERROR AL INSERTAR EL CLIENTE";
        }else {
            echo "Cliente insertado correctamente";
        }
    }else {
        echo "ERROR 1";
    }
}

function modificarCliente() {
    if(isset($_POST["tipoCliente"]) && $_POST["tipoCliente"] != "" && isset($_POST["nombreCliente"]) && $_POST["nombreCliente"] != "" && isset($_POST["nombreEmpresa"]) && isset($_POST["correoCliente"]) && isset($_POST["telefonoCliente"])) {
        $tipoCliente = tipoClientePorNombre($_POST["tipoCliente"])[0];
        $nombre = $_POST["nombreCliente"];
        $nombreEmpresa = $_POST["nombreEmpresa"] != "" ? $_POST["nombreEmpresa"] : "NULL";
        $correo = $_POST["correoCliente"] != "" ? $_POST["correoCliente"] : "NULL";
        $telefono = $_POST["telefonoCliente"] != "" ? $_POST["telefonoCliente"] : "NULL";

        $conexionDB = conexionBd();

        $consulta = "UPDATE `clientes` 
        SET `tipo_cliente`= :tipocliente,`nombre_empresa`= :nombreempresa,`nombre`= :nombre,`correo`= :correo , `telefono`= :telefono
        WHERE `id_cliente`= :idcliente";
        
        $resul = $conexionDB -> prepare($consulta);
        $resul ->bindParam(':tipocliente', $tipoCliente, PDO::PARAM_INT);
        $resul ->bindParam(':nombreempresa', $nombreEmpresa, PDO::PARAM_STR, 25);
        $resul ->bindParam(':nombre', $nombre, PDO::PARAM_STR, 25);
        $resul ->bindParam(':correo', $correo, PDO::PARAM_STR, 30);
        $resul ->bindParam(':telefono', $telefono, PDO::PARAM_STR, 13);
        $resul ->bindParam(':idcliente', $_POST["idCliente"], PDO::PARAM_INT);
        $resul -> execute();

        if($resul -> rowCount() > 0) {
            echo "Cliente actualizado correctamente";
        }else {
            echo "ERROR AL ACTUALIZAR EL CLIENTE";
            print_r($resul -> errorInfo());
        }
    }else {
        echo "ERROR 1";
    }
}

function buscarCliente() {
    if(isset($_POST["idCliente"]) && $_POST["idCliente"] != "") {
        $conexionDB = conexionBd();

        $resul = $conexionDB -> prepare("SELECT * FROM clientes INNER JOIN tipos_clientes ON clientes.tipo_cliente = tipos_clientes.id_tipo AND clientes.id_cliente = ?");
        $resul -> execute(array($_POST["idCliente"]));

        if($resul -> rowCount() === 0) {
            echo "ERROR, EL CLIENTE NO EXISTE";
        }else {
            foreach($resul as $row) {
                echo $row["tipo"]."|".$row["nombre_empresa"]."|".$row["nombre"]."|".$row["correo"]."|".$row["telefono"];
            }
        }
    }else {
        echo "ERROR 1";
    }
}