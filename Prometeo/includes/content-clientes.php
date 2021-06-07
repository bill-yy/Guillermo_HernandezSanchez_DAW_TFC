<?php
    require_once "/var/www/Proyectos/prometeo/imp/db.php";
    $totalClientes = 0;
    $clientes;

    function construirAtributos() {
        global $totalClientes, $totalClientesMes, $clientes;

        //Atributos Clientes
        $conexionBD = conexionBd();
        $clientes = $conexionBD -> query("SELECT * FROM clientes INNER JOIN tipos_clientes ON clientes.tipo_cliente = tipos_clientes.id_tipo");
        $totalClientes = $clientes -> rowCount();
    }

    construirAtributos();
?>
<div class="d-sm-flex justify-content-between align-items-center mb-4">
    <h3 class="text-dark mb-0">Clientes</h3></div>
<div class="row">
    <div class="col-md-6 col-xl-3 mb-4">
        <div class="card shadow border-left-success py-2">
            <div class="card-body">
                <div class="row align-items-center no-gutters">
                    <div class="col mr-2">
                        <div class="text-uppercase text-success font-weight-bold text-xs mb-1"><span>ToTAL CLIENTES</span></div>
                        <div class="text-dark font-weight-bold h5 mb-0"><span id="totalVentasAnual"><?php echo $totalClientes; ?></span></div>
                    </div>
                    <div class="col-auto"><i class="fas fa-dollar-sign fa-2x text-gray-300"></i></div>
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
                <h6 class="font-weight-bold m-0">Listado de clientes</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive table mt-2" id="dataTable-1" role="grid" aria-describedby="dataTable_info">
                    <table class="table dataTable my-0" id="dataTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tipo de cliente</th>
                                <th>Nombre</th>
                                <th>Nombre de empresa</th>
                                <th>Correo electrónico</th>
                                <th>Teléfono</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                foreach($clientes as $row) {
                                    print("<tr>");
                                    print("<td>".$row['id_cliente']."</td>");
                                    print("<td>".$row['tipo']."</td>");
                                    print("<td>".$row['nombre']."</td>");
                                    print("<td>".$row['nombre_empresa']."</td>");
                                    print("<td>".$row['correo']."</td>");
                                    print("<td>".$row['telefono']."</td>");
                                    print("</tr>");
                                }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Tipo de cliente</th>
                                <th>Nombre</th>
                                <th>Nombre de empresa</th>
                                <th>Correo electrónico</th>
                                <th>Teléfono</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>