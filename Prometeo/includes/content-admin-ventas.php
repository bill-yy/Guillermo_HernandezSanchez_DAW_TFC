<?php
    require_once "/var/www/Proyectos/prometeo/imp/db.php";
    require_once "../sesiones.php";

    sesionNoIniciada();

    $productosStock;
    $clientes;
    $ventas;

    function construirDatalists() {
        global $productosStock, $clientes, $ventas;
        $conexionDB = conexionBd();

        $productosStock = $conexionDB -> query("SELECT * FROM stock INNER JOIN productos ON stock.id_producto = productos.id_producto INNER JOIN tipos_unidades ON stock.id_unidad = tipos_unidades.id_unidad AND DATE(stock.caducidad) > DATE(NOW()) AND stock.stock > 1");
        $clientes = $conexionDB -> query("SELECT * FROM clientes INNER JOIN tipos_clientes ON clientes.tipo_cliente = tipos_clientes.id_tipo");

        $ventas = $conexionDB -> query("SELECT * FROM ventas INNER JOIN clientes ON ventas.id_cliente = clientes.id_cliente INNER JOIN tipos_clientes ON clientes.tipo_cliente = tipos_clientes.id_tipo");

    }

    construirDatalists();
?>
<div class="d-sm-flex justify-content-between align-items-center mb-4">
    <h3 class="text-dark mb-0">Administrador de ventas</h3>
</div>
<div class="row">
    <div class="col">
        <div class="card shadow mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="font-weight-bold m-0">Eliminar una venta</h6>
            </div>
            <div class="card-body">
                <form id="formEliVenta">
                    <div class="form-row">
                        <div class="col"><input class="form-control" type="number" id="id-venta" placeholder="ID venta *" name="idVenta" min="0" /></div>
                        <div class="col"><button class="btn btn-primary" onclick="eliminarVenta()" type="button" style="background-color: rgb(223,87,78);color: rgb(255,255,255);">Eliminar</button></div>
                    </div>
                    <input type="hidden" name="modulo" value="eliVenta" />
                </form>
            </div>
        </div>
    </div>
    <div class="col">
    </div>
</div>
<div class="row">
    <div class="col">
        <div class="card shadow mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="font-weight-bold m-0">Crear una venta</h6>
            </div>
            <div class="card-body">
                <form id="formCrearVenta">
                    <div class="form-row">
                        <div class="col">
                            <div>
                                <input list="lista-productos-ventas" class="custom-select" id="remesa-buscar" name="producto" placeholder="Producto *" />
                                <datalist id="lista-productos-ventas">
                                <?php
                                    if($productosStock -> rowCount() > 0) {
                                        foreach($productosStock as $row) {
                                            echo '<option value="Id: '.$row["id_stock"].' - Nombre: '.$row["nombre_producto"].' - Stock: '.$row["stock"]."  ".$row["tipo"].' - Caducidad: '.$row["caducidad"].'">';
                                        }
                                    }else {
                                        echo '<option value="NULL">No hay subcategorias</option>';
                                    }
                                ?>
                                </datalist>
                            </div>
                        </div>
                        <div class="col"><button class="btn btn-primary" onclick="añadirAlCarro()" type="button" style="background-color: #3f3f42;color: rgb(255,255,255);">Añadir producto</button><button class="btn btn-primary" onclick="crearVenta()" type="button" style="background-color: #3f3f42;color: rgb(255,255,255);margin-left: 10px;">Crear Venta</button></div>
                    </div>
                    <div class="form-row">
                        <div class="col">
                        <div class="table-responsive table mt-2" id="dataTable-1" role="grid" aria-describedby="dataTable_info">
                            <table class="table my-0" id="dataTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre del Producto</th>
                                        <th>Stock disponible</th>
                                        <th>Caducidad</th>
                                        <th>Cantidad a vender</th>
                                        <th>Eliminar</th>
                                    </tr>
                                </thead>
                                <tbody id="cuerpoTabla">
                                    
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre del Producto</th>
                                        <th>Stock disponible</th>
                                        <th>Caducidad</th>
                                        <th>Cantidad a vender</th>
                                        <th>Eliminar</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col"><label for="total-venta">Total de la venta *:</label><input class="form-control" type="number" id="total-venta" name="total" /></div>
                        <div class="col"><label for="fecha-venta">Fecha de la venta*:</label><input class="form-control" id="fecha-venta" type="date" name="fechaVenta" /></div>
                        <div class="col"><label>Cliente*:</label>
                            <div>
                                <input list="lista-clientes" class="custom-select" id="clientes-ventas" name="cliente" />
                                <datalist id="lista-clientes">
                                <?php
                                    if($clientes -> rowCount() > 0) {
                                        foreach($clientes as $row) {
                                            echo '<option value="'.$row["nombre"].'">';
                                        }
                                    }else {
                                        echo '<option value="NULL">No hay subcategorias</option>';
                                    }
                                ?>
                                </datalist>
                            </div>
                        </div>
                        <div class="col"></div>
                        <div class="col"></div>
                    </div>
                    <input type="hidden" name="modulo" value="crearVenta" />
                    <input type="hidden" id="hidden-remesa" name="remesa" value="" />
                    <input type="hidden" id="hidden-cantidad" name="cantidad" value="" />
                </form>
            </div>
        </div>
    </div>
</div>
<div class="card shadow" style="margin-bottom:30px;">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="font-weight-bold m-0">Listado de ventas</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive table mt-2" id="dataTable-1" role="grid" aria-describedby="dataTable_info">
            <table class="table dataTable my-0" id="dataTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Nombre de empresa</th>
                        <th>Total de la venta</th>
                        <th>Fecha de venta</th>
                        <th>Tipo de cliente</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach($ventas as $row) {
                            print("<tr>");
                            print("<td>".$row['id_venta']."</td>");
                            print("<td>".$row['nombre']."</td>");
                            print("<td>".$row['nombre_empresa']."</td>");
                            print("<td>".$row['total_venta']."€</td>");
                            print("<td>".$row['fecha_venta']."</td>");
                            print("<td>".$row['tipo']."</td>");
                            print("</tr>");
                        }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Nombre de empresa</th>
                        <th>Total de la venta</th>
                        <th>Fecha de venta</th>
                        <th>Tipo de cliente</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>