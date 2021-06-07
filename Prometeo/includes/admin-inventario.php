<?php
require_once "/var/www/Proyectos/prometeo/imp/db.php";
require_once "../permisos.php";
require_once "../sesiones.php";

sesionNoIniciada();

if ($_SERVER['REQUEST_METHOD'] === "POST" && $_POST["modulo"] != "") {
    if(isset($_POST["modulo"])) {
        if($_POST["modulo"] === "eliPro") {
            if(isset($_POST["idProducto"])) {
                eliminarProducto();
            }else {
                echo "ERROR 1";
            }
        }else if($_POST["modulo"] === "crearPro") {
            if(isset($_POST["nombreProducto"]) && isset($_POST["subcategoria"])) {
                crearProducto();
            }else {
                echo "ERROR 1";
            }
        }else if($_POST["modulo"] === "buscarPro") {
            buscarProducto();
        }else if($_POST["modulo"] === "modPro") {
            modificarProducto();
        }else if($_POST["modulo"] === "eliRem") {
            eliminarRemesa();
        }else if($_POST["modulo"] === "crearRem") {
            crearRemesa();
        }else if($_POST["modulo"] === "buscarRem") {
            buscarRemesa();
        }else if($_POST["modulo"] === "modRem") {
            modificarRemesa();
        }
    }else {
        echo "ERROR 1";
    }
}else {
    redirigir();
}

function eliminarProducto() {
    $conexionDB = conexionBd();

    $resul = $conexionDB -> prepare("DELETE FROM productos WHERE id_producto = ?");
    $resul -> execute(array($_POST["idProducto"]));

    if($resul -> rowCount() != 1) {
        echo "ERROR ESA REMESA NO EXISTE";
    }else {
        echo "Remesa borrada con exito";
    }
}

function crearProducto() {
    $conexionDB = conexionBd();
    $nombreProducto = $_POST["nombreProducto"];
    $subcategoria = subcategoriaPorNombre($_POST["subcategoria"])[0];
    $idCreador = $_POST["idUsuario"];

    if(isset($_POST["descripcionProducto"]) && $_POST["descripcionProducto"] != "") {
        $descripcionProducto = $_POST["descripcionProducto"];
    }else {
        $descripcionProducto = "NULL";
    }
    if(isset($_POST["codigoBarras"]) && $_POST["codigoBarras"] != "") {
        $codigoBarras = $_POST["codigoBarras"];
    }else {
        $codigoBarras = "NULL";
    }
    if(isset($_POST["marca"]) && $_POST["marca"] != "") {
        $marca = $_POST["marca"];
    }else {
        $marca = "NULL";
    }

    $resul = $conexionDB -> query("INSERT INTO productos (nombre_producto,marca,id_subcat,descripcion_producto,codigo_barras,creador) values('$nombreProducto', '$marca', $subcategoria, '$descripcionProducto', '$codigoBarras', $idCreador)");

    if(!$resul) {
        echo "ERROR AL INSERTAR EL PRODUCTO";
    }else {
        echo "Producto insertado correctamente";
    }
}

function buscarProducto() {
    if(isset($_POST["idProducto"]) && $_POST["idProducto"] != "") {
        $conexionDB = conexionBd();

        $resul = $conexionDB -> prepare("SELECT * FROM productos INNER JOIN subcategorias ON productos.id_subcat = subcategorias.id_subcat AND productos.id_producto = ?");
        $resul -> execute(array($_POST["idProducto"]));

        if($resul -> rowCount() === 0) {
            echo "ERROR, EL PRODUCTO NO EXISTE";
        }else {
            foreach($resul as $row) {
                echo $row["nombre_producto"]."|".$row["nombre_subcategoria"]."|".$row["descripcion_producto"]."|".$row["codigo_barras"]."|".$row["marca"];
            }
        }
    }else {
        echo "ERROR AL BUSCAR EL PRODUCTO";
    }
}

function modificarProducto() {
    if(isset($_POST["idProducto"]) && $_POST["idProducto"] != "" && $_POST["idProducto"] != 0) {
        $nombreProducto = $_POST["nombreProducto"];
        $subcategoria = subcategoriaPorNombre($_POST["subcategoria"])[0];
        $idProducto = $_POST["idProducto"];

        if ($_POST["descripcionProducto"] === "") {
            $descripcionProducto = "NULL";
        }else {
            $descripcionProducto = $_POST["descripcionProducto"];
        }

        if($_POST["codigoBarras"] === "") {
            $codigoBarras = "NULL";
        }else {
            $codigoBarras = $_POST["codigoBarras"];
        }

        if($_POST["marca"] === "") {
            $marca = "NULL";
        }else {
            $marca = $_POST["marca"];
        }

        $conexionDB = conexionBd();

        $consulta = "UPDATE `productos` 
        SET `nombre_producto`= :nombreproducto,`marca`= :marca,`id_subcat`= :subcategoria,`descripcion_producto`= :descripcion,`codigo_barras`= :codigobarras 
        WHERE `id_producto`= :idproducto";
        
        $resul = $conexionDB -> prepare($consulta);
        $resul ->bindParam(':nombreproducto', $nombreProducto, PDO::PARAM_STR, 50);
        $resul ->bindParam(':marca', $marca, PDO::PARAM_STR, 30);
        $resul ->bindParam(':subcategoria', $subcategoria, PDO::PARAM_INT);
        $resul ->bindParam(':descripcion', $descripcionProducto, PDO::PARAM_STR, 200);
        $resul ->bindParam(':codigobarras', $codigoBarras, PDO::PARAM_STR, 20);
        $resul ->bindParam(':idproducto', $idProducto, PDO::PARAM_INT);
        $resul -> execute();

        if($resul -> rowCount() > 0) {
            echo "Producto actualizado correctamente";
        }else {
            echo "ERROR AL ACTUALIZAR EL PRODUCTO";
            print_r($resul -> errorInfo());
        }
    }else {
        echo "ERROR ID DEL PRODUCTO INCORRECTO";
    }
}

function eliminarRemesa() {
    $conexionDB = conexionBd();

    $resul = $conexionDB -> prepare("DELETE FROM stock WHERE id_stock = ?");
    $resul -> execute(array($_POST["idStock"]));

    if($resul -> rowCount() != 1) {
        echo "ERROR ESA REMESA NO EXISTE";
    }else {
        echo "Remesa borrada con exito";
    }
}

function crearRemesa() {
    if(isset($_POST["producto"]) && $_POST["producto"] != "" && isset($_POST["stock"]) && $_POST["stock"] != "" && isset($_POST["tipoUnidad"]) && $_POST["tipoUnidad"] != "" && isset($_POST["caducidad"])) {
        $conexionDB = conexionBd();
        $producto = productoPorNombre($_POST["producto"])[0];
        $stock = $_POST["stock"];
        $tipo = tipoPorNombre($_POST["tipoUnidad"])[0];
        $caducidad = $_POST["caducidad"] != "" ? $_POST["caducidad"] : "NULL";

        $resul = $conexionDB -> query("INSERT INTO stock (id_unidad, stock, id_producto, caducidad) VALUES ($tipo, $stock, $producto, '$caducidad')");

        if(!$resul) {
            echo "ERROR AL INSERTAR LA REMESA";
        }else {
            echo "Remesa insertada correctamente";
        }
    }else {
        echo "ERROR 1";
    }
}

function buscarRemesa() {
    if(isset($_POST["idStock"]) && $_POST["idStock"] != "") {
        $conexionDB = conexionBd();

        $resul = $conexionDB -> prepare("SELECT * FROM stock INNER JOIN tipos_unidades ON stock.id_unidad = tipos_unidades.id_unidad INNER JOIN productos ON stock.id_producto = productos.id_producto AND stock.id_stock = ?");
        $resul -> execute(array($_POST["idStock"]));

        if($resul -> rowCount() === 0) {
            echo "ERROR, LA REMESA NO EXISTE";
        }else {
            foreach($resul as $row) {
                echo $row["nombre_producto"]."|".$row["stock"]."|".$row["tipo"]."|".$row["caducidad"];
            }
        }
    }else {
        echo "ERROR AL BUSCAR EL PRODUCTO";
        return FALSE;
    }
}

function modificarRemesa() {
    if(isset($_POST["producto"]) && $_POST["producto"] != "" && isset($_POST["stock"]) && $_POST["stock"] != "" && isset($_POST["tipoUnidad"]) && $_POST["tipoUnidad"] != "" && isset($_POST["caducidad"])) {
        $caducidad = $_POST["caducidad"] != "" ? $_POST["caducidad"] : "NULL";
        $tipoUnidad = tipoPorNombre($_POST["tipoUnidad"])[0];
        $producto = productoPorNombre($_POST["producto"])[0];

        $conexionDB = conexionBd();

        $consulta = "UPDATE `stock` 
        SET `id_unidad`= ?,`stock`= ?,`id_producto`= ?,`caducidad`= ? 
        WHERE `id_stock`= ?";

        $resul = $conexionDB -> prepare($consulta);
        
        $resul -> execute(array($tipoUnidad, $_POST["stock"], $producto, $caducidad, $_POST["idStock"]));

        if($resul -> rowCount() > 0) {
            echo "Remesa actualizada correctamente";
        }else {
            echo "ERROR AL ACTUALIZAR LA REMESA";
            print_r($resul -> errorInfo());
        }
    }else {
        echo "ERROR 1";
    }
        
}