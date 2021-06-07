<?php
    require_once "/var/www/Proyectos/prometeo/imp/db.php";
    require_once "../sesiones.php";

    $datosUsuario = recuperarDatosDeUsuario($_GET["idUsuario"]);

?>
<h3 class="text-dark mb-4">Perfil</h3>
<div class="row mb-3">
    <div class="col-lg-4">
        <form id="formImg" enctype="multipart/form-data">
            <div class="card mb-3">
                <div class="card-body text-center shadow"><img class="rounded-circle mb-3 mt-4" src="./includes/userspics/<?php echo $datosUsuario[8] ?>" width="160" height="160"/>
                    <div class="mb-3">
                        <button class="btn btn-primary btn-sm" onclick="mostrar()" type="button">Cambiar foto de perfil</button>
                    </div>
                    <div class="mb-3 d-none oculto-i">
                        <input type="file" id="foto-perfil" name="fotoPerfil" accept="image/png, image/jpeg">
                        <button class="btn btn-primary btn-sm" onclick="guardarFotoPerfil()" id="btn-guardar-foto" type="button">Guardar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="col-lg-8">
        <div class="row">
            <div class="col">
                <div class="card shadow mb-3">
                    <div class="card-header py-3">
                        <p class="m-0 font-weight-bold">Datos de usuario</p>
                    </div>
                    <div class="card-body">
                        <form id="formDatosUsuario">
                            <div class="form-row">
                                <div class="col">
                                    <div class="form-group"><label for="username"><strong>Nombre de usuario</strong></label><input type="text" class="form-control" value="<?php echo $datosUsuario[1] ?>" id="nombre-usuario" name="nombreUsuario" readonly /></div>
                                </div>
                                <div class="col">
                                    <div class="form-group"><label for="email"><strong>Correo electrónico</strong><br /></label><input type="email" class="form-control" value="<?php echo $datosUsuario[2] ?>" id="correo-electronico" name="email" /></div>
                                </div>
                                <div class="col">
                                    <div class="form-group"><label for="telefono"><strong>Teléfono</strong><br /></label><input type="tel" class="form-control" value="<?php echo $datosUsuario[7] ?>" id="telefono" name="telefono" /></div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col">
                                    <div class="form-group"><label for="first_name"><strong>Nombre</strong><br /></label><input type="text" class="form-control" value="<?php echo $datosUsuario[4] ?>" id="nombre" name="nombre" /></div>
                                </div>
                                <div class="col">
                                    <div class="form-group"><label for="last_name"><strong>Primer Apellido</strong><br /></label><input type="text" class="form-control" value="<?php echo $datosUsuario[5] ?>" id="apellido-1" name="apellido1" /></div>
                                </div>
                                <div class="col">
                                    <div class="form-group"><label for="last_name"><strong>Segundo Apellido</strong><br /></label><input type="text" class="form-control" value="<?php echo $datosUsuario[6] ?>" id="apellido-2" name="apellido2" /></div>
                                </div>
                            </div>
                            <div class="form-group"><button class="btn btn-primary btn-sm" onclick="modificarDatosUsuario()" type="button">Guardar datos de usuario</button></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row mb-3">
    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-body text-center shadow">
                <div class="row">
                    <div class="col">
                        <div class="form-group"><label for="last_name"><strong>Codigo de Aplicación:</strong><br /></label>
                            <?php echo "<p>".$datosUsuario[10]."</p>" ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-group"><label for="last_name"><strong>Rol de usuario:</strong><br /></label>
                            <?php echo "<p>".$datosUsuario[12]."</p>" ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <a href="./res/apk/PrometeoApp.apk" download>Descargar APP Android</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>