<?php

//Función que leera el xml con los datos de para acceder a la BD
function leerConfiguracionBd($nombre,$esquema) {
    $config = new DOMDocument();
    $config -> load($nombre);
    $res = $config-> schemaValidate($esquema);
    if($res === FALSE){
        throw new InvalidArgumentException("Revise el fichero de configuración");
    }
    $datos = simplexml_load_file($nombre);
    $ip = $datos ->xpath("//ipServidorBD");
    $nombre = $datos ->xpath("//nombreBD");
    $usuario = $datos ->xpath("//usuarioBD");
    $clave = $datos ->xpath("//claveBD");
    $cad = sprintf("mysql:dbname=%s;host=%s",$nombre[0],$ip[0]);
    $resul = [];
    $resul [] = $cad;
    $resul [] = $usuario[0];
    $resul [] = $clave[0] ;

    return $resul;
};

function conexionBd() {
    $res = leerConfiguracionBd("/var/www/Proyectos/prometeo/xml/configuracion.xml","/var/www/Proyectos/prometeo/xml/configuracion.xsd");
    $db = new PDO($res[0],$res[1],$res[2]);
    return $db;
};

//Función que comprobará los datos introducidos en el inicio  de sesión
function comprobarUsuario($user, $password) {
    $db = conexionBd();
    $query = "SELECT * FROM usuarios WHERE correo = '$user' AND password = MD5('$password')";
    $resul = $db->query($query);
    if($resul->rowCount() === 1) {
        return $resul->fetch();
    }else {
        return FALSE;
    }
};

//Función que comprobará que el cod_app que recibe el conector es válido
function comprobarCodigoApp($codigo) {
    $db = conexionBd();
    $resul = $db -> prepare("SELECT id_usuario FROM usuarios WHERE cod_app = ?");
    $resul -> execute(array($codigo));

    if($resul->rowCount() === 1) {
        return $resul -> fetch();
    }else {
        return FALSE;
    }
};


//Función para crear un cod_app único 
function generadorCodApp() {
    $diccionario = "AaBbCcDdEeFfGgHhIiJjKkLlMmNnÑñOoPpQqRrSsTtUuVvWwXxYyZz0123456789";
    $arrDicc = str_split($diccionario);
    $cod_app = "";
    $db = conexionBd();
    $unica = FALSE;

    while($unica === FALSE) {
        for($i = 0; $i < 12; $i++) {
            $cod_app = $cod_app.$arrDicc[random_int(0, 63)];
        }

        $resul = $db->prepare("SELECT id_usuario FROM usuarios WHERE cod_app = ?");
        $resul -> execute(array($cod_app));

        if($resul->rowCount() === 0) {
            $unica = TRUE;
        }
    }

    return $cod_app;
};

//Función para obtener el cod_app de un usuario
function obtenerCodApp($usuario) {
    $db = conexionBd();
    $codApp = $db -> prepare("SELECT cod_app FROM usuarios WHERE correo = ?");
    $codApp -> execute(array($usuario));
    
    return $codApp -> fetch();;
};

//Función para obtener el rol de un usuario
function obtenerRol($usuario) {
    $db = conexionBd();
    $rol = $db -> prepare("SELECT rol FROM usuarios WHERE correo = ?");
    $rol -> execute(array($usuario));

    return $rol -> fetch();
};

function idUsuarioCorreo($correo) {
    $db = conexionBd();
    $idUsuario = $db -> prepare("SELECT * FROM usuarios WHERE correo = ?");
    $idUsuario -> execute(array($correo));

    if($idUsuario -> rowCount() === 1) {
        return $idUsuario -> fetch();
    }else {
        return FALSE;
    }
};

function subcategoriaPorNombre($nombre) {
    $db = conexionBd();
    $idSubCat = $db -> prepare("SELECT id_subcat FROM subcategorias WHERE nombre_subcategoria = ?");
    $idSubCat -> execute(array($nombre));

    if($idSubCat -> rowCount() === 1) {
        return $idSubCat -> fetch();
    }else {
        return FALSE;
    }
};

function productoPorNombre($nombre) {
    $db = conexionBd();
    $idProducto = $db -> prepare("SELECT id_producto FROM productos WHERE nombre_producto = ?");
    $idProducto -> execute(array($nombre));

    if($idProducto -> rowCount() === 1) {
        return $idProducto -> fetch();
    }else {
        return FALSE;
    }
};

function tipoPorNombre($nombre) {
    $db = conexionBd();
    $idTipo = $db -> prepare("SELECT id_unidad FROM tipos_unidades WHERE tipo = ?");
    $idTipo -> execute(array($nombre));

    if($idTipo -> rowCount() === 1) {
        return $idTipo -> fetch();
    }else {
        return FALSE;
    }
};

function tipoClientePorNombre($nombre) {
    $db = conexionBd();
    $idTipo = $db -> prepare("SELECT id_tipo FROM tipos_clientes WHERE tipo = ?");
    $idTipo -> execute(array($nombre));

    if($idTipo -> rowCount() === 1) {
        return $idTipo -> fetch();
    }else {
        return FALSE;
    }
};

function categoriaPorNombre($nombre) {
    $db = conexionBd();
    $idCategoria = $db -> prepare("SELECT id_categoria FROM categorias WHERE nombre_categoria = ?");
    $idCategoria -> execute(array($nombre));

    if($idCategoria -> rowCount() === 1) {
        return $idCategoria -> fetch();
    }else {
        return FALSE;
    }
};

function recuperarDatosDeUsuario($idUsuario) {
    $db = conexionBd();
    $datos = $db -> prepare("SELECT * FROM usuarios INNER JOIN roles ON usuarios.rol = roles.id_rol AND id_usuario = ?");
    $datos -> execute(array($idUsuario));

    if($datos -> rowCount() === 1) {
        return $datos -> fetch();
    }else {
        return FALSE;
    }
};

function recuperarImagenUsuario($correo) {
    $db = conexionBd();
    $dato = $db -> prepare("SELECT foto_perfil FROM usuarios WHERE correo = ?");
    $dato -> execute(array($correo));

    if($dato -> rowCount() === 1) {
        return $dato -> fetch();
    }else {
        return FALSE;
    }
};

function actualizarFotoPerfil($nombre, $correo) {
    $db = conexionBd();
    $resul = $db -> prepare("UPDATE `usuarios` SET `foto_perfil`=:ruta WHERE `correo`=:correo");

    $resul ->bindParam(':ruta', $nombre, PDO::PARAM_STR, 100);
    $resul ->bindParam(':correo', $correo, PDO::PARAM_STR, 50);
    $resul -> execute();

    if($resul -> rowCount() > 0) {
        return TRUE;
    }else {
        return FALSE;
    }
}

function actualizarDatosUsuario($nombreUsuario, $email, $telefono, $nombre, $apellido1, $apellido2) {
    $db = conexionBd();
    $resul = $db -> prepare("UPDATE `usuarios` SET `correo`=:correo, `nombre`=:nombre, `apellido1`=:apellido1, `apellido2`=:apellido2, `telefono`=:telefono WHERE `usuario`=:usuario");

    $resul ->bindParam(':correo', $email, PDO::PARAM_STR, 50);
    $resul ->bindParam(':nombre', $nombre, PDO::PARAM_STR, 20);
    $resul ->bindParam(':apellido2', $apellido2, PDO::PARAM_STR, 20);
    $resul ->bindParam(':apellido1', $apellido1, PDO::PARAM_STR, 20);
    $resul ->bindParam(':telefono', $telefono, PDO::PARAM_STR, 13);
    $resul ->bindParam(':usuario', $nombreUsuario, PDO::PARAM_STR, 50);
    $resul -> execute();

    if($resul -> rowCount() > 0) {
        return TRUE;
    }else {
        return FALSE;
    }
}

function listarSubcategorias() {
    $db = conexionBd();
    $resul = $db -> query("SELECT * FROM subcategorias");

    if($resul -> rowCount() > 0) {
        return $resul;
    }else {
        return FALSE;
    }
}

function listarUnidades() {
    $db = conexionBd();
    $resul = $db -> query("SELECT * FROM tipos_unidades");

    if($resul -> rowCount() > 0) {
        return $resul;
    }else {
        return FALSE;
    }
}

function comprobarProducto($bc) {
    $db = conexionBd();
    $resul = $db -> prepare("SELECT * FROM productos INNER JOIN subcategorias ON productos.id_subcat = subcategorias.id_subcat AND productos.codigo_barras = ?");
    $resul -> execute(array($bc));

    if($resul -> rowCount() > 0) {
        return $resul;
    }else {
        return FALSE;
    }
}

function crearProductoApp($nombreProducto, $cantidad, $unidad, $subcategoria, $caducidad, $marca, $bc, $codApp) {
    if(productoPorNombre($nombreProducto) === FALSE) {
        echo "Producto no existe";
        $db = conexionBd();
        $creador = comprobarCodigoApp($codApp)[0];
        $subcat = subcategoriaPorNombre($subcategoria)[0];
        $resul = $db -> query("INSERT INTO productos (nombre_producto, marca, id_subcat, codigo_barras, creador) VALUES ('$nombreProducto', '$marca', $subcat, '$bc', $creador)");

        if(!$resul) {
            return FALSE;
        }else {
            $idProducto = productoPorNombre($nombreProducto);
            $tipoUnidad = tipoPorNombre($unidad)[0];

            if($caducidad === "NULL") {
                $resulStock = $db -> query("INSERT INTO stock (id_unidad, stock, id_producto) VALUES($tipoUnidad, $cantidad, $idProducto)");
            }else {
                $fechCaducidad = explode("-",$caducidad)[2]."-".explode("-",$caducidad)[1]."-".explode("-",$caducidad)[0];
                $resulStock = $db -> query("INSERT INTO stock (id_unidad, stock, id_producto, caducidad) VALUES($tipoUnidad, $cantidad, $idProducto, '$fechCaducidad')");
            }

           
            if(!$resulStock) {
                return FALSE;
            }else {
                return TRUE;
            }
        }
    }else {
        $db = conexionBd();

        $idProducto = productoPorNombre($nombreProducto)[0];
        $tipoUnidad = tipoPorNombre($unidad)[0];
        if($caducidad === "NULL") {
            $resulStock = $db -> query("INSERT INTO stock (id_unidad, stock, id_producto) VALUES($tipoUnidad, $cantidad, $idProducto)");
        }else {
            $fechCaducidad = explode("-",$caducidad)[2]."-".explode("-",$caducidad)[1]."-".explode("-",$caducidad)[0];
            $resulStock = $db -> query("INSERT INTO stock (id_unidad, stock, id_producto, caducidad) VALUES($tipoUnidad, $cantidad, $idProducto, '$fechCaducidad')");
        }

        if(!$resulStock) {
            return FALSE;
        }else {
            return TRUE;
        }
    }
}

function rolPorNombre($nombre) {
    $db = conexionBd();
    $idRol = $db -> prepare("SELECT id_rol FROM roles WHERE nombre_rol = ?");
    $idRol -> execute(array($nombre));

    if($idRol -> rowCount() === 1) {
        return $idRol -> fetch();
    }else {
        return FALSE;
    }
}

function crearUsuario($nombreUsuario, $email, $password, $rol, $telefono, $nombre, $apellido1, $apellido2) {
    $db = conexionBd();

    $cod_app = generadorCodApp();
    $rolUsuario = rolPorNombre($rol)[0];

    $resul = $db -> query("INSERT INTO usuarios (usuario, correo, password, nombre, apellido1, apellido2, telefono, foto_perfil, rol, cod_app) VALUES('$nombreUsuario', '$email', MD5('$password'), '$nombre', '$apellido1', '$apellido2', '$telefono', 'default.png', $rolUsuario, '$cod_app')");
    
    if(!$resul) {
        return FALSE;
    }else {
        return TRUE;
    }
}

function idClientePorNombre($nombre) {
    $db = conexionBd();
    $idCliente = $db -> prepare("SELECT id_cliente FROM clientes WHERE nombre = ?");
    $idCliente -> execute(array($nombre));

    if($idCliente -> rowCount() === 1) {
        return $idCliente -> fetch();
    }else {
        return FALSE;
    }
}

function ultimaVenta() {
    $db = conexionBd();

    $resul = $db -> query("SELECT id_venta FROM ventas ORDER BY id_venta DESC LIMIT 1");

    if($resul -> rowCount() === 1) {
        return $resul -> fetch();
    }else {
        return FALSE;
    }
}

function obtenerStock($id) {
    $db = conexionBd();

    $resul = $db -> prepare("SELECT stock FROM stock WHERE id_stock = ?");
    $resul -> execute(array($id));

    if($resul -> rowCount() === 1) {
        return $resul -> fetch();
    }else {
        return FALSE;
    }
}

function crearVenta($total, $fechaVenta, $cliente, $remesa, $cantidad) {
    $db = conexionBd();
    $clienteVenta = idClientePorNombre($cliente)[0];
    $remesas = explode("-",$remesa);
    $cantidades = explode("-",$cantidad);

    $resulVenta = $db -> query("INSERT INTO ventas (fecha_venta, total_venta, id_cliente) VALUES('$fechaVenta', $total, $clienteVenta)");
    if(!$resulVenta) {
        echo "ERROR INSERT VENTA";
        return FALSE;
    }else {
        $idVenta = ultimaVenta()[0];
        for($indice = 0; $indice < count($remesas); $indice++) {
            if($remesas[$indice] != "" && $remesas[$indice] != " ") {
                $r = $remesas[$indice];
                $c = $cantidades[$indice];
                $resulVentaPro = $db -> query("INSERT INTO ProductosVenta (id_stock, id_venta, unidades) VALUES($r, $idVenta, $c)");

                $antiguoStock = obtenerStock($r)[0];
                $nuevoStock = $antiguoStock - $c;

                $resulCambio = $db -> prepare("UPDATE `stock` SET `stock`=? WHERE `id_stock`=?");
                $resulCambio -> execute(array($nuevoStock, $r));
                if(!$resulCambio) {
                    echo "ERROR UPDATE";
                    return FALSE;
                }
            }
        }
        eliminarStockVacio();
    }

}

function eliminarStockVacio() {
    $conexionDB = conexionBd();

    $resul = $conexionDB -> prepare("DELETE FROM stock WHERE stock = 0");
}

function eliminarVenta($idVenta) {
    $conexionDB = conexionBd();

    $resul = $conexionDB -> prepare("DELETE FROM ProductosVenta WHERE id_venta = ?");
    $resul -> execute(array($idVenta));

    $resulVenta = $conexionDB -> prepare("DELETE FROM ventas WHERE id_venta = ?");
    $resulVenta -> execute(array($idVenta));

    echo "Venta borrada correctamente";
}
?>