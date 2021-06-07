<?php
    require_once "/var/www/Proyectos/prometeo/imp/db.php";
    require_once "../sesiones.php";

    sesionNoIniciada();

    $usuarios;
    $roles;

    function construirTabla() {
        global $usuarios, $roles;

        $conexionBD = conexionBd();

        $usuarios = $conexionBD -> query("SELECT * FROM usuarios INNER JOIN roles ON usuarios.rol = roles.id_rol");

        $roles = $conexionBD -> query("SELECT nombre_rol FROM roles");
    }

    construirTabla();
?>
<div class="d-sm-flex justify-content-between align-items-center mb-4">
    <h3 class="text-dark mb-0">Configuración</h3>
</div>
<h5 class="d-xl-flex justify-content-xl-end">Administrar usuarios</h5>
<div class="row">
    <div class="col">
        <div class="card shadow mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="font-weight-bold m-0">Crear un usuario</h6>
            </div>
            <div class="card-body">
                <form id="formCrearUsu">
                    <div class="form-row">
                        <div class="col">
                            <div class="form-group"><input class="form-control" type="text" id="nombre-usuario" name="nombreUsuario" placeholder="Nombre de usuario *" maxlength="50" /></div>
                        </div>
                        <div class="col">
                            <div class="form-group"><input class="form-control" type="email" id="correo-electronico" name="email" placeholder="Correo electrónico *" maxlength="50" /></div>
                        </div>
                        <div class="col">
                            <div class="form-group"><input class="form-control" type="tel" id="telefono" name="telefono" placeholder="Teléfono" maxlength="13" /></div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col">
                            <div class="form-group"><input class="form-control" type="text" id="nombre" name="nombre" placeholder="Nombre" maxlength="20" /></div>
                        </div>
                        <div class="col">
                            <div class="form-group"><input class="form-control" type="text" id="apellido-1" name="apellido1" placeholder="Primer Apellido" maxlength="20" /></div>
                        </div>
                        <div class="col">
                            <div class="form-group"><input class="form-control" type="text" id="apellido-2" name="apellido2" placeholder="Segundo Apellido" maxlength="20" /></div>
                        </div>
                    </div>
                    <div class="form-row" style="margin: 5px -5px;">
                        <div class="col"><input class="form-control" type="password" name="password" placeholder="Contraseña *" maxlength="50" /></div>
                        <div class="col">
                            <div>
                                <input list="lista-roles" class="custom-select" id="clientes-tipo-mod" name="rol" placeholder="Rol del usuario *" />
                                <datalist id="lista-roles">
                                <?php
                                    if($roles -> rowCount() === 0) {
                                        echo '<option value="NULL">No hay roles</option>';
                                    }else {
                                        foreach($roles as $row) {
                                            echo '<option value="'.$row["nombre_rol"].'">';
                                        }
                                    }
                                ?>
                                </datalist>
                            </div>
                        </div>
                        <div class="col"><input class="form-control" type="hidden" name="modulo" value="crearCat" /><button class="btn btn-primary" onclick="crearUsuario()" type="button" style="background-color: #3F3F42;color: rgb(255,255,255);">Crear usuario</button></div>
                    </div>
                        <input type="hidden" name="modulo" value="crearUser" />
                    </div>
                </form>
        </div>
    </div>
</div>
<div class="row">
    <div class="col">
        <div class="card shadow mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="font-weight-bold m-0">Listado de usuarios</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive table mt-2" id="dataTable-1" role="grid" aria-describedby="dataTable_info">
                    <table class="table my-0 dataTable" id="dataTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre usuario</th>
                                <th>Correo</th>
                                <th>Nombre</th>
                                <th>Primer Apellido</th>
                                <th>Segundo Apellido</th>
                                <th>Telefono</th>
                                <th>CodApp</th>
                                <th>Rol</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                if($usuarios -> rowCount() === 0) {

                                }else {
                                    foreach($usuarios as $row) {
                                        print("<tr>");
                                            print("<td>".$row['id_usuario']."</td>");
                                            print("<td>".$row['usuario']."</td>");
                                            print("<td>".$row['correo']."</td>");
                                            print("<td>".$row['nombre']."</td>");
                                            print("<td>".$row['apellido1']."</td>");
                                            print("<td>".$row['apellido2']."</td>");
                                            print("<td>".$row['telefono']."</td>");
                                            print("<td>".$row['cod_app']."</td>");
                                            print("<td>".$row['nombre_rol']."</td>");
                                        print("</tr>");
                                    }
                                }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Nombre usuario</th>
                                <th>Correo</th>
                                <th>Nombre</th>
                                <th>Primer Apellido</th>
                                <th>Segundo Apellido</th>
                                <th>Telefono</th>
                                <th>CodApp</th>
                                <th>Rol</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>