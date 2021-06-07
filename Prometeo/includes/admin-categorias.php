<?php
require_once "/var/www/Proyectos/prometeo/imp/db.php";
require_once "../permisos.php";
require_once "../sesiones.php";

sesionNoIniciada();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if(isset($_POST["modulo"]) && $_POST["modulo"] != "") {
        if($_POST["modulo"] === "eliCat") {
            eliminarCategoria();
        }else if($_POST["modulo"] === "crearCat") {
            crearCategoria();
        }else if($_POST["modulo"] === "modCat") {
            modificarCategoria();
        }else if($_POST["modulo"] === "buscarCat") {
            buscarCategoria();
        }else if($_POST["modulo"] === "eliSub") {
            eliminarSubcategoria();
        }else if($_POST["modulo"] === "crearSub") {
            crearSubcategoria();
        }else if($_POST["modulo"] === "modSub") {
            modificarSubcategoria();
        }else if($_POST["modulo"] === "buscarSub") {
            buscarSubcategoria();
        }else {
            redirigir();
        }
    }else {
        redirigir();
    }
}else {
    redirigir();
}

function eliminarCategoria() {
    if(isset($_POST["idCategoria"]) && $_POST["idCategoria"] != "") {
        $conexionDB = conexionBd();

        $resul = $conexionDB -> prepare("DELETE FROM categorias WHERE id_categoria = ?");
        $resul -> execute(array($_POST["idCategoria"]));

        if($resul -> rowCount() != 1) {
            echo "ERROR ESA CATEGORIA NO EXISTE";
        }else {
            echo "Categoría borrada exitosamente";
        }
    }else {
        echo "ERROR 1";
    }
}

function crearCategoria() {
    if(isset($_POST["nombreCategoria"]) && $_POST["nombreCategoria"] != "" && isset($_POST["descripcionCategoria"])) {
        $conexionDB = conexionBd();

        $nombre = $_POST["nombreCategoria"];
        $descripcion = $_POST["descripcionCategoria"] != "" ? $_POST["descripcionCategoria"] : "NULL";

        $resul = $conexionDB -> query("INSERT INTO categorias (nombre_categoria, descripcion) values('$nombre', '$descripcion')");

        if(!$resul) {
            echo "ERROR AL INSERTAR LA CATEGORÍA";
        }else {
            echo "Categoría insertada correctamente";
        }
    }else {
        echo "ERROR 1";
    }
}

function modificarCategoria() {
    if(isset($_POST["idCategoria"]) && $_POST["idCategoria"] != "" && isset($_POST["nombreCategoria"]) && $_POST["nombreCategoria"] != "" && isset($_POST["descripcionCategoria"])) {
        $nombre = $_POST["nombreCategoria"];
        $descripcion = $_POST["descripcionCategoria"] != "" ? $_POST["descripcionCategoria"] : "NULL";
        $idCategoria = $_POST["idCategoria"];
        
        $conexionDB = conexionBd();

        $consulta = "UPDATE `categorias` 
        SET `nombre_categoria`= :nombre,`descripcion`= :descripcion
        WHERE `id_categoria`= :idcategoria";
        
        $resul = $conexionDB -> prepare($consulta);
        $resul ->bindParam(':nombre', $nombre, PDO::PARAM_STR, 20);
        $resul ->bindParam(':descripcion', $descripcion, PDO::PARAM_STR, 200);
        $resul ->bindParam(':idcategoria', $idCategoria, PDO::PARAM_INT);
        $resul -> execute();

        if($resul -> rowCount() > 0) {
            echo "Categoría actualizada correctamente";
        }else {
            echo "ERROR AL ACTUALIZAR LA CATEGORÍA";
            print_r($resul -> errorInfo());
        }
    }else {
        echo "ERROR 1";
    }
}

function buscarCategoria() {
    if(isset($_POST["idCategoria"]) && $_POST["idCategoria"] != "") {
        $conexionDB = conexionBd();

        $resul = $conexionDB -> prepare("SELECT * FROM categorias WHERE id_categoria = ?");
        $resul -> execute(array($_POST["idCategoria"]));

        if($resul -> rowCount() === 0) {
            echo "ERROR, LA CATEGORÍA NO EXISTE";
        }else {
            foreach($resul as $row) {
                echo $row["nombre_categoria"]."|".$row["descripcion"];
            }
        }
    }else {
        echo "ERROR 1";
    }
}

function eliminarSubcategoria() {
    if(isset($_POST["idSubcategoria"]) && $_POST["idSubcategoria"] != "") {
        $conexionDB = conexionBd();

        $resul = $conexionDB -> prepare("DELETE FROM subcategorias WHERE id_subcat = ?");
        $resul -> execute(array($_POST["idSubcategoria"]));

        if($resul -> rowCount() != 1) {
            echo "ERROR ESA SUBCATEGORIA NO EXISTE";
        }else {
            echo "Subcategoría borrada exitosamente";
        }
    }else {
        echo "ERROR 1";
    }
}

function crearSubcategoria() {
    if(isset($_POST["nombreSubcategoria"]) && $_POST["nombreSubcategoria"] != "" && isset($_POST["categoria"]) && $_POST["categoria"] != "" && isset($_POST["descripcionSubcategoria"])) {
        $conexionDB = conexionBd();

        $nombre = $_POST["nombreSubcategoria"];
        $categoria = categoriaPorNombre($_POST["categoria"])[0];
        $descripcion = $_POST["descripcionSubcategoria"] != "" ? $_POST["descripcionSubcategoria"] : "NULL";

        $resul = $conexionDB -> query("INSERT INTO subcategorias (nombre_subcategoria, descripcion_subcategoria, id_categoria) values('$nombre', '$descripcion', $categoria)");

        if(!$resul) {
            echo "ERROR AL INSERTAR LA SUBCATEGORÍA";
        }else {
            echo "Subcategoría insertada correctamente";
        }
    }else {
        echo "ERROR 1";
    }
}

function modificarSubcategoria() {
    if(isset($_POST["idSubcategoria"]) && $_POST["idSubcategoria"] != "" && isset($_POST["nombreSubcategoria"]) && $_POST["nombreSubcategoria"] != "" && isset($_POST["categoria"]) && $_POST["categoria"] && isset($_POST["descripcionSubcategoria"])) {
        $nombre = $_POST["nombreSubcategoria"];
        $categoria = categoriaPorNombre($_POST["categoria"])[0];
        $descripcion = $_POST["descripcionSubcategoria"] != "" ? $_POST["descripcionSubcategoria"] : "NULL";
        $idSubcategoria = $_POST["idSubcategoria"];
        
        $conexionDB = conexionBd();

        $consulta = "UPDATE `subcategorias` 
        SET `nombre_subcategoria`= :nombre,`descripcion_subcategoria`= :descripcion,`id_categoria`= :categoria
        WHERE `id_subcat`= :idSubcategoria";
        
        $resul = $conexionDB -> prepare($consulta);
        $resul ->bindParam(':nombre', $nombre, PDO::PARAM_STR, 20);
        $resul ->bindParam(':descripcion', $descripcion, PDO::PARAM_STR, 200);
        $resul ->bindParam(':categoria', $categoria, PDO::PARAM_INT);
        $resul ->bindParam(':idSubcategoria', $idSubcategoria, PDO::PARAM_INT);
        $resul -> execute();

        if($resul -> rowCount() > 0) {
            echo "Subcategoría actualizada correctamente";
        }else {
            echo "ERROR AL ACTUALIZAR LA SUBCATEGORÍA";
            print_r($resul -> errorInfo());
        }
    }else {
        echo "ERROR 1";
    }
}

function buscarSubcategoria() {
    if(isset($_POST["idSubcategoria"]) && $_POST["idSubcategoria"] != "") {
        $conexionDB = conexionBd();

        $resul = $conexionDB -> prepare("SELECT * FROM subcategorias INNER JOIN categorias ON subcategorias.id_categoria = categorias.id_categoria AND subcategorias.id_subcat = ?");
        $resul -> execute(array($_POST["idSubcategoria"]));

        if($resul -> rowCount() === 0) {
            echo "ERROR, LA SUBCATEGORÍA NO EXISTE";
        }else {
            foreach($resul as $row) {
                echo $row["nombre_subcategoria"]."|".$row["nombre_categoria"]."|".$row["descripcion_subcategoria"];
            }
        }
    }else {
        echo "ERROR 1";
    }
}