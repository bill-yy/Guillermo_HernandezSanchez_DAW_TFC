<?php
    require_once "/var/www/Proyectos/prometeo/imp/db.php";

    $dataTableProductos;
    $dataTableRemesas;
    $subcategorias;
    $categorias;
    $tipos;
    $productos;

    function contruirTablas() {
        global $dataTableProductos, $dataTableRemesas;

        //Tabla productos
        $conexionBD = conexionBd();
        $dataTableProductos = $conexionBD -> query("SELECT * FROM productos INNER JOIN subcategorias ON productos.id_subcat = subcategorias.id_subcat INNER JOIN categorias ON subcategorias.id_categoria = categorias.id_categoria INNER JOIN usuarios ON productos.creador = usuarios.id_usuario ORDER BY productos.id_producto DESC LIMIT 10");

        //Tabla remesas
        $dataTableRemesas = $conexionBD -> query("SELECT * FROM stock INNER JOIN productos ON stock.id_producto = productos.id_producto INNER JOIN tipos_unidades ON stock.id_unidad = tipos_unidades.id_unidad INNER JOIN subcategorias ON productos.id_subcat = subcategorias.id_subcat INNER JOIN categorias ON subcategorias.id_categoria = categorias.id_categoria ORDER BY stock.id_stock DESC LIMIT 10");
    
    };

    function cargarSubcategorias() {
        global $subcategorias;

        $conexionBD = conexionBd();
        $subcategorias = $conexionBD -> query("SELECT id_subcat, nombre_subcategoria FROM subcategorias");
    };

    function cargarCategorias() {
        global $categorias;

        $conexionBD = conexionBd();
        $categorias = $conexionBD -> query("SELECT id_categoria, nombre_categoria FROM categorias");
    };

    function cargarTipos() {
        global $tipos;

        $conexionBD = conexionBd();
        $tipos = $conexionBD -> query("SELECT id_unidad, tipo FROM tipos_unidades");
    };

    function cargarProductos() {
        global $productos;

        $conexionBD = conexionBd();
        $productos = $conexionBD -> query("SELECT id_producto, nombre_producto FROM productos");
    };

    contruirTablas();
?>
<div class="d-sm-flex justify-content-between align-items-center mb-4">
    <h3 class="text-dark mb-0" id="titulo">Administrador de inventario</h3>
</div>
<div class="row">
    <div class="col">
        <h5 class="d-xl-flex justify-content-xl-end">Administrar productos</h5>
    </div>
</div>
<div class="row">
    <div class="col">
        <div class="row">
            <div class="col">
                <div class="card shadow mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="font-weight-bold m-0">Eliminar un Producto</h6>
                    </div>
                    <div class="card-body">
                        <form id="formEliPro">
                            <div class="form-row">
                                <div class="col"><input type="number" class="form-control" id="id-producto-elim" placeholder="ID del producto *" min="0" step="1" name="idProducto" /></div>
                                <div class="col"><button class="btn btn-primary" type="button" onclick="eliminarProducto()" style="background-color: rgb(223,87,78);color: rgb(255,255,255);">Eliminar</button></div>
                                <input type="hidden" name="modulo" value="eliPro">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="card shadow mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="font-weight-bold m-0">Crear un Producto</h6>
                    </div>
                    <div class="card-body">
                        <form id="formCrePro">
                            <div class="form-row">
                                <div class="col"><input type="text" class="form-control" id="nombre-producto" name="nombreProducto" maxlength="100" placeholder="Nombre del producto *" /></div>
                                <div class="col">
                                    <input list="lista-subcategorias" class="custom-select" id="subcategoria-producto" name="subcategoria" placeholder="Subcategoria *">
                                    <datalist id="lista-subcategorias">
                                        <?php
                                        cargarSubcategorias();
                                        if($subcategorias -> rowCount() > 0) {
                                            foreach($subcategorias as $row) {
                                                echo '<option value="'.$row["nombre_subcategoria"].'">';
                                            }
                                        }else {
                                            echo '<option value="NULL">No hay subcategorias</option>';
                                        }
                                        ?>
                                    </datalist>
                                </div>
                                <input type="hidden" name="modulo" value="crearPro">
                            </div>
                            <div class="form-row" style="margin: 5px -5px;">
                                <div class="col"><textarea class="form-control" id="descripcion-producto" style="resize:none;" name="descripcionProducto" maxlength="200"></textarea></div>
                            </div>
                            <div class="form-row" style="margin: 5px -5px;">
                                <div class="col"><input type="text" class="form-control" id="codigo-barras-1" placeholder="Codigo de barras" name="codigoBarras" /></div>
                                <div class="col"><input type="text" class="form-control" id="marca" placeholder="Marca" name="marca" /></div>
                                <div class="col"><button class="btn btn-primary" type="button" onclick="crearProducto()" style="background-color: #3F3F42;color: rgb(255,255,255);">Crear producto</button></div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card shadow mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="font-weight-bold m-0">Modificar un producto</h6>
            </div>
            <div class="card-body">
                <form id="formModPro">
                    <div class="form-row">
                        <div class="col"><input type="number" class="form-control" id="id-producto-mod" name="idProducto" min="0" placeholder="ID del producto *" /></div>
                        <div class="col"><button class="btn btn-primary" type="button" onclick="buscarProducto()" style="background-color: #3F3F42;">Buscar</button></div>
                    </div>
                    <div class="form-row d-none oculto-p" style="margin: 5px -5px;">
                        <div class="col"><input type="text" class="form-control" id="nombre-producto-mod" name="nombreProducto" maxlength="100" placeholder="Nombre del producto *" /></div>
                        <div class="col">
                            <input list="lista-subcategorias" class="custom-select" id="subcategoria-producto-mod" name="subcategoria" placeholder="Subcategoria *">
                            <datalist id="lista-subcategorias">
                                <?php
                                cargarSubcategorias();
                                if($subcategorias -> rowCount() > 0) {
                                    foreach($subcategorias as $row) {
                                        echo '<option value="'.$row["nombre_subcategoria"].'">';
                                    }
                                }else {
                                    echo '<option value="NULL">No hay subcategorias</option>';
                                }
                                ?>
                            </datalist>
                        </div>
                    </div>
                    <div class="form-row d-none oculto-p" style="margin: 5px -5px;">
                        <div class="col"><textarea class="form-control" id="descripcion-producto-mod" name="descripcionProducto" style="resize:none;" maxlength="200"></textarea></div>
                    </div>
                    <div class="form-row d-none oculto-p" style="margin: 5px -5px;">
                        <div class="col"><input type="text" class="form-control" id="codigo-barras-mod" name="codigoBarras" placeholder="Codigo de barras" /></div>
                        <div class="col"><input type="text" class="form-control" id="marca-mod" placeholder="Marca" name="marca" /></div>
                        <div class="col"><button class="btn btn-primary" type="button" onclick="modificarProducto()" style="background-color: #3F3F42;color: rgb(255,255,255);">Modificar producto</button></div>
                    </div>
                    <input type="hidden" name="modulo" value="modPro">
                </form>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col">
        <div class="card shadow mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="font-weight-bold m-0">Ultimos productos añadidos</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive table mt-2" id="dataTable-1" role="grid" aria-describedby="dataTable_info">
                    <table class="table my-0" id="dataTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Marca</th>
                                <th>Subcategoría</th>
                                <th>Categoría</th>
                                <th>Descripción</th>
                                <th>CB</th>
                                <th>Creador</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                foreach($dataTableProductos as $row) {
                                    print("<tr>");
                                    print("<td>".$row['id_producto']."</td>");
                                    print("<td>".$row['nombre_producto']."</td>");
                                    print("<td>".$row['marca']."</td>");
                                    print("<td>".$row['nombre_subcategoria']."</td>");
                                    print("<td>".$row['nombre_categoria']."</td>");
                                    print("<td>".$row['descripcion_producto']."</td>");
                                    print("<td>".$row['codigo_barras']."</td>");
                                    print("<td>".$row['usuario']."</td>");
                                    print("</tr>");
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<hr>
<div class="row">
    <div class="col">
        <h5 class="d-xl-flex justify-content-xl-end">Administrar remesas</h5>
    </div>
</div>
<div class="row">
    <div class="col">
        <div class="row">
            <div class="col">
                <div class="card shadow mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="font-weight-bold m-0">Eliminar una remesa</h6>
                    </div>
                    <div class="card-body">
                        <form id="formEliRem">
                            <div class="form-row">
                                <div class="col"><input type="number" class="form-control" id="id-stock" min="0" placeholder="ID de la remesa *" name="idStock" /></div>
                                <div class="col"><button class="btn btn-primary" type="button" onclick="eliminarRemesa()" style="background-color: rgb(223,87,78);color: rgb(255,255,255);">Eliminar</button></div>
                            </div>
                            <input type="hidden" name="modulo" value="eliRem">
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="card shadow mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="font-weight-bold m-0">Crear una remesa</h6>
                    </div>
                    <div class="card-body">
                        <form id="formCreRem">
                            <div class="form-row">
                                <div class="col">
                                    <input list="lista-productos" class="custom-select" id="remesa-producto" name="producto" placeholder="Producto">
                                    <datalist id="lista-productos">
                                        <?php
                                        cargarProductos();
                                        if($productos -> rowCount() > 0) {
                                            foreach($productos as $row) {
                                                echo '<option value="'.$row["nombre_producto"].'">';
                                            }
                                        }else {
                                            echo '<option value="NULL">No hay productos</option>';
                                        }
                                        ?>
                                    </datalist>
                                </div>
                                <div class="col">
                                    <div class="form-row">
                                        <div class="col"><input type="number" class="form-control" id="stock" name="stock" min="0" placeholder="Stock *" /></div>
                                        <div class="col">
                                            <input list="lista-unidades" class="custom-select" id="remesa-tipo" name="tipoUnidad" placeholder="Tipo de unidad *">
                                            <datalist id="lista-unidades">
                                                <?php
                                                cargarTipos();
                                                if($productos -> rowCount() > 0) {
                                                    foreach($tipos as $row) {
                                                        echo '<option value="'.$row["tipo"].'">';
                                                    }
                                                }else {
                                                    echo '<option value="NULL">No hay tipos de unidades</option>';
                                                }
                                                ?>
                                            </datalist>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row" style="margin: 5px -5px;">
                                <div class="col"><input type="text" class="form-control" id="caducidad-remesa" placeholder="Caducidad" name="caducidad" onfocus="(this.type=&#39;date&#39;)" /></div>
                                <div class="col"><button class="btn btn-primary" type="button" onclick="crearRemesa()" style="background-color: #3F3F42;color: rgb(255,255,255);">Crear producto</button></div>
                            </div>
                            <input type="hidden" name="modulo" value="crearRem">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card shadow mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="font-weight-bold m-0">Modificar una remesa</h6>
            </div>
            <div class="card-body">
                <form id="formModRem">
                    <div class="form-row">
                        <div class="col"><input type="number" class="form-control" id="id-producto-rem-mod" placeholder="ID de la remesa *" min="0" name="idStock" /></div>
                        <div class="col"><button class="btn btn-primary" type="button" onclick="buscarRemesa()" style="background-color: #3F3F42;">Buscar</button></div>
                    </div>
                    <div class="form-row d-none oculto-r" style="margin: 5px -5px;">
                        <div class="col">
                            <input list="lista-productos" class="custom-select" id="remesa-producto-mod" name="producto" placeholder="Producto">
                            <datalist id="lista-productos">
                                <?php
                                cargarProductos();
                                if($productos -> rowCount() > 0) {
                                    foreach($productos as $row) {
                                        echo '<option value="'.$row["nombre_producto"].'">';
                                    }
                                }else {
                                    echo '<option value="NULL">No hay productos</option>';
                                }
                                ?>
                            </datalist>
                        </div>
                        <div class="col">
                            <div class="form-row">
                                <div class="col"><input type="number" class="form-control" id="stock-mod" name="stock" min="0" placeholder="Stock *" /></div>
                                <div class="col">
                                    <input list="lista-unidades" class="custom-select" id="remesa-tipo-mod" name="tipoUnidad" placeholder="Tipo de unidad *">
                                    <datalist id="lista-unidades">
                                        <?php
                                        cargarTipos();
                                        if($productos -> rowCount() > 0) {
                                            foreach($tipos as $row) {
                                                echo '<option value="'.$row["tipo"].'">';
                                            }
                                        }else {
                                            echo '<option value="NULL">No hay tipos de unidades</option>';
                                        }
                                        ?>
                                    </datalist>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row d-none oculto-r" style="margin: 5px -5px;">
                        <div class="col" placeholder="Caducidad"><input type="text" class="form-control" id="caducidad-remesa-mod" placeholder="Caducidad" name="caducidad" onfocus="(this.type=&#39;date&#39;)" /></div>
                        <div class="col"><button class="btn btn-primary" onclick="modificarRemesa()" type="button" style="background-color: #3F3F42;color: rgb(255,255,255);">Modificar producto</button></div>
                    </div>
                    <input type="hidden" name="modulo" value="modRem">
                </form>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col">
        <div class="card shadow mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="font-weight-bold m-0">Ultimas remesas añadidas</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive table mt-2" id="dataTable-1" role="grid" aria-describedby="dataTable_info">
                    <table class="table my-0" id="dataTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Producto</th>
                                <th>Unidades</th>
                                <th>Caducidad</th>
                                <th>Subcategoria</th>
                                <th>Categoria</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                foreach($dataTableRemesas as $row) {
                                    print("<tr>");
                                    print("<td>".$row['id_stock']."</td>");
                                    print("<td>".$row['nombre_producto']."</td>");
                                    print("<td>".$row['stock'].$row['tipo']."</td>");
                                    print("<td>".$row['caducidad']."</td>");
                                    print("<td>".$row['nombre_subcategoria']."</td>");
                                    print("<td>".$row['nombre_categoria']."</td>");
                                    print("</tr>");
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>