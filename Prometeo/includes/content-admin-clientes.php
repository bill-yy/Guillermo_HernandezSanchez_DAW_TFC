<?php
    require_once "/var/www/Proyectos/prometeo/imp/db.php";

    $tipos;
    $dataTableClientes;

    function contruirTabla() {
        global $dataTableClientes;

        $conexionBD = conexionBd();
        $dataTableClientes = $conexionBD -> query("SELECT * FROM clientes INNER JOIN tipos_clientes ON clientes.tipo_cliente = tipos_clientes.id_tipo ORDER BY clientes.id_cliente DESC LIMIT 10");
    };

    function cargarTipos() {
        global $tipos;

        $conexionBD = conexionBd();
        $tipos = $conexionBD -> query("SELECT tipo FROM tipos_clientes");
    }

    contruirTabla();
?>
<div class="d-sm-flex justify-content-between align-items-center mb-4">
    <h3 class="text-dark mb-0">Administrador de clientes</h3>
</div>
<div class="row">
    <div class="col">
        <div class="row">
            <div class="col">
                <div class="card shadow mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="font-weight-bold m-0">Eliminar un cliente</h6>
                    </div>
                    <div class="card-body">
                        <form id="formEliCli">
                            <div class="form-row">
                                <div class="col"><input class="form-control" type="text" id="id-cliente" placeholder="ID cliente" name="idCliente" min="0" /></div>
                                <div class="col"><button class="btn btn-primary" onclick="eliminarCliente()" type="button" style="background-color: rgb(223,87,78);color: rgb(255,255,255);">Eliminar</button></div>
                            </div>
                            <input type="hidden" name="modulo" value="eliCli" />
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="card shadow mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="font-weight-bold m-0">Crear un cliente</h6>
                    </div>
                    <div class="card-body">
                        <form id="formCreCli">
                            <div class="form-row">
                                <div class="col">
                                    <input list="lista-tipos" class="custom-select" id="clientes-tipo" name="tipoCliente" placeholder="Tipo de cliente *" />
                                    <datalist id="lista-tipos">
                                        <?php
                                        cargarTipos();
                                        if($tipos -> rowCount() > 0) {
                                            foreach($tipos as $row) {
                                                echo '<option value="'.$row["tipo"].'">';
                                            }
                                        }else {
                                            echo '<option value="NULL">No hay tipos</option>';
                                        }
                                        ?>
                                    </datalist>
                                </div>
                                <div class="col"><input class="form-control" type="text" id="nombre-empresa" name="nombreEmpresa" placeholder="Nombre de empresa" maxlength="25" /></div>
                            </div>
                            <div class="form-row" style="margin: 5px -5px;">
                                <div class="col"><input class="form-control" type="text" id="nombre-cliente" name="nombreCliente" maxlength="25" placeholder="Nombre del cliente *" /></div>
                                <div class="col"><input class="form-control" type="email" id="corre-cliente" name="correoCliente" placeholder="Correo del cliente" maxlength="30" /></div>
                            </div>
                            <div class="form-row" style="margin: 5px -5px;">
                                <div class="col"><input class="form-control" type="tel" id="telefono-cliente" name="telefonoCliente" maxlength="13" placeholder="Telefono del cliente" /></div>
                                <div class="col"><input class="form-control" type="hidden" name="modulo" value="crearCli" /><button class="btn btn-primary" onclick="crearCliente()" type="button" style="background-color: #3F3F42;color: rgb(255,255,255);">Crear cliente</button></div>
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
                <h6 class="font-weight-bold m-0">Modificar un cliente</h6>
            </div>
            <div class="card-body">
                <form id="formModCli">
                    <div class="form-row">
                        <div class="col"><input class="form-control" type="text" id="id-cliente-mod" placeholder="ID cliente" name="idCliente" min="0" /></div>
                        <div class="col"><button class="btn btn-primary" type="button" onclick="buscarCliente()" style="background-color: #3F3F42;">Buscar</button></div>
                    </div>
                    <div class="form-row d-none oculto-c" style="margin: 5px -5px;">
                        <div class="col">
                            <input list="lista-tipos" class="custom-select" id="clientes-tipo-mod" name="tipoCliente" placeholder="Tipo de cliente *" />
                            <datalist id="lista-tipos">
                                <?php
                                cargarTipos();
                                if($tipos -> rowCount() > 0) {
                                    foreach($tipos as $row) {
                                        echo '<option value="'.$row["tipo"].'">';
                                    }
                                }else {
                                    echo '<option value="NULL">No hay tipos</option>';
                                }
                                ?>
                            </datalist>
                        </div>
                        <div class="col">
                            <input class="form-control" type="text" id="nombre-empresa-mod" name="nombreEmpresa" placeholder="Nombre de empresa" maxlength="25" />
                        </div>
                    </div>
                    <div class="form-row d-none oculto-c" style="margin: 5px -5px;">
                        <div class="col">
                            <input class="form-control" type="text" id="nombre-cliente-mod" name="nombreCliente" maxlength="25" placeholder="Nombre del cliente *" />
                        </div>
                        <div class="col">
                            <input class="form-control" type="email" id="corre-cliente-mod" name="correoCliente" placeholder="Correo del cliente" maxlength="30" />
                        </div>
                    </div>
                    <div class="form-row d-none oculto-c" style="margin: 5px -5px;">
                        <div class="col"><input class="form-control" type="tel" id="telefono-cliente-mod" name="telefonoCliente" maxlength="13" placeholder="Telefono del cliente" /></div>
                        <div class="col"><input class="form-control" type="hidden" name="modulo" value="modCli" /><button class="btn btn-primary" onclick="modificarCliente()" type="button" style="background-color: #3F3F42;color: rgb(255,255,255);">Modificar cliente</button></div>
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
                <h6 class="font-weight-bold m-0">Ultimos clientes añadidos</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive table mt-2" id="dataTable-1" role="grid" aria-describedby="dataTable_info">
                    <table class="table my-0">
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
                                foreach($dataTableClientes as $row) {
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