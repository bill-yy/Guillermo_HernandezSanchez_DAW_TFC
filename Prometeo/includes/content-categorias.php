<?php
    require_once "/var/www/Proyectos/prometeo/imp/db.php";
    require_once "../sesiones.php";

    sesionNoIniciada();

    $totalCategorias = 0;
    $totalSubcategorias = 0;
    $categorias;
    $subcategorias;

    function construirAtributos() {
        global $totalCategorias, $totalSubcategorias, $categorias, $subcategorias;

        //Atributos categorias
        $conexionBD = conexionBd();
        $categorias = $conexionBD -> query("SELECT * FROM categorias");
        $totalCategorias = $categorias -> rowCount();

        $subcategorias = $conexionBD -> query("SELECT * FROM subcategorias INNER JOIN categorias ON subcategorias.id_categoria = categorias.id_categoria");
        $resulTotalSubcategorias = $conexionBD -> query("SELECT id_subcat FROM subcategorias");
        $totalSubcategorias = $resulTotalSubcategorias -> rowCount();

        
    }

    construirAtributos();

?>
<div class="d-sm-flex justify-content-between align-items-center mb-4">
    <h3 class="text-dark mb-0">Categorias</h3>
</div>
<div class="row">
    <div class="col-md-6 col-xl-3 mb-4">
        <div class="card shadow border-left-info py-2">
            <div class="card-body">
                <div class="row align-items-center no-gutters">
                    <div class="col mr-2">
                        <div class="text-uppercase text-info font-weight-bold text-xs mb-1"><span>TOTAL CATEGORIAS</span></div>
                        <div class="row no-gutters align-items-center">
                            <div class="col-auto">
                                <div class="text-dark font-weight-bold h5 mb-0 mr-3"><span id="totalProductos"><?php echo $totalCategorias; ?></span></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto"><i class="fas fa-tag fa-2x text-gray-300"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3 mb-4">
        <div class="card shadow border-left-warning py-2">
            <div class="card-body">
                <div class="row align-items-center no-gutters">
                    <div class="col mr-2">
                        <div class="text-uppercase text-warning font-weight-bold text-xs mb-1"><span>TOTAL SUBCATEGORIAS</span></div>
                        <div class="text-dark font-weight-bold h5 mb-0"><span id="totalClientes"><?php echo $totalSubcategorias; ?></span></div>
                    </div>
                    <div class="col-auto"><i class="fas fa-tags fa-2x text-gray-300"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3 mb-4"></div>
    <div class="col-md-6 col-xl-3 mb-4"></div>
</div>
<div class="row">
    <div class="col">
        <div class="card shadow mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="font-weight-bold m-0">Listado de categorias</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive table mt-2" id="dataTable-1" role="grid" aria-describedby="dataTable_info">
                    <table class="table dataTable my-0" id="dataTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                foreach($categorias as $row) {
                                    print("<tr>");
                                    print("<td>".$row['id_categoria']."</td>");
                                    print("<td>".$row['nombre_categoria']."</td>");
                                    print("<td>".$row['descripcion']."</td>");
                                    print("</tr>");
                                }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col">
        <div class="card shadow mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="font-weight-bold m-0">Listado de subcategorias</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive table mt-2" id="dataTable-1" role="grid" aria-describedby="dataTable_info">
                    <table class="table dataTable my-0" id="dataTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre Subcategoría</th>
                                <th>Categoría</th>
                                <th>Descripción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                foreach($subcategorias as $row) {
                                    print("<tr>");
                                    print("<td>".$row['id_subcat']."</td>");
                                    print("<td>".$row['nombre_subcategoria']."</td>");
                                    print("<td>".$row['nombre_categoria']."</td>");
                                    print("<td>".$row['descripcion_subcategoria']."</td>");
                                    print("</tr>");
                                }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Nombre Subcategoría</th>
                                <th>Categoría</th>
                                <th>Descripción</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>