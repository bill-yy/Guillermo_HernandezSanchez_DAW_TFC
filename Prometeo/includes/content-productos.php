<?php
    require_once "/var/www/Proyectos/prometeo/imp/db.php";

    $totalProductos = 0;
    $totalProductosSinStock = 0;
    $totalProductosCaducados = 0;
    $productos;
    $remesas;
    $fechaActual = date("Y-m-d H:i:s");

    function construirAtributos() {
        global $totalProductos, $totalProductosSinStock, $totalProductosCaducados, $productos, $fechaActual, $remesas;

        //Atributo productos
        $conexionBD = conexionBd();
        $productos = $conexionBD -> query("SELECT * FROM productos INNER JOIN subcategorias ON productos.id_subcat = subcategorias.id_subcat INNER JOIN categorias ON subcategorias.id_categoria = categorias.id_categoria INNER JOIN usuarios ON productos.creador = usuarios.id_usuario");
        $remesas = $conexionBD -> query("SELECT * FROM stock INNER JOIN tipos_unidades ON stock.id_unidad = tipos_unidades.id_unidad INNER JOIN productos ON stock.id_producto = productos.id_producto");

        //Total productos
        $totalProductos = $productos -> rowCount();

        //Total productos sin stock
        $resulTotalProductosSinStock = $conexionBD -> query("SELECT id_producto FROM productos WHERE productos.id_producto NOT IN (SELECT id_producto FROM stock)");
        $totalProductosSinStock = $resulTotalProductosSinStock -> rowCount();

        //Total productos caducados
        $resulTotalProductosCaducados = $conexionBD -> prepare("SELECT id_stock FROM stock WHERE DATE(caducidad) < DATE(?)");
        $resulTotalProductosCaducados -> execute([$fechaActual]);
        $totalProductosCaducados = $resulTotalProductosCaducados -> rowCount();
    }

    construirAtributos();
?>
<div class="d-sm-flex justify-content-between align-items-center mb-4">
    <h3 class="text-dark mb-0" id="titulo">Productos</h3>
</div>
<div class="row">
    <div class="col-md-6 col-xl-3 mb-4">
        <div class="card shadow border-left-primary py-2">
            <div class="card-body">
                <div class="row align-items-center no-gutters">
                    <div class="col mr-2">
                        <div class="text-uppercase text-primary font-weight-bold text-xs mb-1"><span>TOTAL PRODUCTOS</span>
                        </div>
                        <div class="row no-gutters align-items-center">
                            <div class="col-auto">
                                <div class="text-dark font-weight-bold h5 mb-0 mr-3"><span id="totalProductos"><?php echo $totalProductos; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto"><i class="fas fa-clipboard-list fa-2x text-gray-300"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3 mb-4">
        <div class="card shadow border-left-warning py-2">
            <div class="card-body">
                <div class="row align-items-center no-gutters">
                    <div class="col mr-2">
                        <div class="text-uppercase text-warning font-weight-bold text-xs mb-1"><span>PRODUCTOS SIN STOCK</span>
                        </div>
                        <div class="text-dark font-weight-bold h5 mb-0"><span id="totalSinStock"><?php echo $totalProductosSinStock; ?></span></div>
                    </div>
                    <div class="col-auto"><i class="fas fa-times fa-2x text-gray-300"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3 mb-4">
        <div class="card shadow border-left-danger py-2">
            <div class="card-body">
                <div class="row align-items-center no-gutters">
                    <div class="col mr-2">
                        <div class="text-uppercase text-danger font-weight-bold text-xs mb-1">
                            <span>PRODUCTOS CADUCADOS</span></div>
                        <div class="text-dark font-weight-bold h5 mb-0"><span id="totalProductosCaducados"><?php echo $totalProductosCaducados; ?></span></div>
                    </div>
                    <div class="col-auto"><i class="fas fa-hourglass-end fa-2x text-gray-300"></i></div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="card shadow" style="margin: 0px 0px 10px 0px;">
    <div class="card-header py-3">
        <p class=" m-0 font-weight-bold" class="titulo-card">Listado de productos</p>
    </div>
    <div class="card-body">
        <div class="table-responsive table mt-2" id="dataTable-1" role="grid" aria-describedby="dataTable_info">
            <table class="table dataTable my-0" id="dataTable">
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
                        foreach($productos as $row) {
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
                <tfoot>
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
                </tfoot>
            </table>
        </div>
    </div>
</div>
<div class="card shadow" style="margin: 0px 0px 10px 0px;">
    <div class="card-header py-3">
        <p class=" m-0 font-weight-bold" class="titulo-card">Listado de remesas</p>
    </div>
    <div class="card-body">
        <div class="table-responsive table mt-2" id="dataTable-1" role="grid" aria-describedby="dataTable_info">
            <table class="table dataTable my-0" id="dataTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Marca</th>
                        <th>Stock</th>
                        <th>Caducidad</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach($remesas as $row) {
                            print("<tr>");
                            print("<td>".$row['id_stock']."</td>");
                            print("<td>".$row['nombre_producto']."</td>");
                            print("<td>".$row['marca']."</td>");
                            print("<td>".$row['stock'].$row['tipo']."</td>");
                            print("<td>".$row['caducidad']."</td>");
                            print("</tr>");
                        }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Marca</th>
                        <th>Stock</th>
                        <th>Caducidad</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>