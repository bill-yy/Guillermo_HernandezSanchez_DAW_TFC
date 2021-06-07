<?php
    require_once "/var/www/Proyectos/prometeo/imp/db.php";
    require_once "../sesiones.php";

    sesionNoIniciada();
    $categorias;
    $subcategorias;
    $datalistCategorias;

    function construirTablas() {
        global $categorias, $subcategorias;

        //Atributos categorias
        $conexionBD = conexionBd();
        $categorias = $conexionBD -> query("SELECT * FROM categorias ORDER BY id_categoria DESC LIMIT 10");

        $subcategorias = $conexionBD -> query("SELECT * FROM subcategorias INNER JOIN categorias ON subcategorias.id_categoria = categorias.id_categoria ORDER BY id_subcat DESC LIMIT 10");

    }

    function datalist() {
        global $datalistCategorias;

        $conexionBD = conexionBd();
        $datalistCategorias = $conexionBD -> query("SELECT nombre_categoria FROM categorias");
    }

    construirTablas();
    datalist();

?>
<div class="d-sm-flex justify-content-between align-items-center mb-4">
    <h3 class="text-dark mb-0">Administrador de categorías</h3>
</div>
<div class="row">
    <div class="col">
        <h5 class="d-xl-flex justify-content-xl-end">Administrar categorías</h5>
    </div>
</div>
<div class="row">
    <div class="col">
        <div class="row">
            <div class="col">
                <div class="card shadow mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="font-weight-bold m-0">Eliminar una categoría</h6>
                    </div>
                    <div class="card-body">
                        <form id="formEliCat">
                            <div class="form-row">
                                <div class="col"><input class="form-control" type="text" id="id-categoria" placeholder="ID categoría *" name="idCategoria" min="0" /></div>
                                <div class="col"><button class="btn btn-primary" onclick="eliminarCategoria()" type="button" style="background-color: rgb(223,87,78);color: rgb(255,255,255);">Eliminar</button></div>
                            </div>
                            <input type="hidden" name="modulo" value="eliCat" />
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="card shadow mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="font-weight-bold m-0">Crear una categoría</h6>
                    </div>
                    <div class="card-body">
                        <form id="formCreCat">
                            <div class="form-row">
                                <div class="col"><input class="form-control" type="text" id="nombre-categoria" name="nombreCategoria" maxlength="100" placeholder="Nombre de la categoría" /></div>
                                <div class="col"></div>
                            </div>
                            <div class="form-row" style="margin: 5px -5px;">
                                <div class="col"><textarea class="form-control" id="descripcion-categoria" style="resize:none;" name="descripcionCategoria" maxlength="200" placeholder="Descripción de la categoría..."></textarea></div>
                            </div>
                            <div class="form-row" style="margin: 5px -5px;">
                                <div class="col"><button class="btn btn-primary" onclick="crearCategoria()" type="button" style="background-color: #3F3F42;color: rgb(255,255,255);">Crear categoría</button></div>
                                <div class="col"><input class="form-control" type="hidden" name="modulo" value="crearCat" /></div>
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
                <h6 class="font-weight-bold m-0">Modificar una categoría</h6>
            </div>
            <div class="card-body">
                <form id="formModCat">
                    <div class="form-row">
                        <div class="col"><input class="form-control" type="text" id="id-categoria-mod" placeholder="ID categoría *" name="idCategoria" min="0" /></div>
                        <div class="col"><button class="btn btn-primary" onclick="buscarCategoria()" type="button" style="background-color: #3F3F42;">Buscar</button></div>
                    </div>
                    <div class="form-row d-none oculto-cat" style="margin: 5px -5px;">
                        <div class="col"><input class="form-control" type="text" id="nombre-categoria-mod" name="nombreCategoria" maxlength="100" placeholder="Nombre de la categoría" /></div>
                        <div class="col"></div>
                    </div>
                    <div class="form-row d-none oculto-cat" style="margin: 5px -5px;">
                        <div class="col"><textarea class="form-control" id="descripcion-categoria-mod" style="resize:none;" name="descripcionCategoria" maxlength="200" placeholder="Descripción de la categoría..."></textarea></div>
                    </div>
                    <div class="form-row d-none oculto-cat" style="margin: 5px -5px;">
                        <div class="col"><button class="btn btn-primary" onclick="modificarCategoria()" type="button" style="background-color: #3F3F42;color: rgb(255,255,255);">Modificar categoría</button></div>
                        <div class="col"><input class="form-control" type="hidden" name="modulo" value="modCat" /></div>
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
                <h6 class="font-weight-bold m-0">Ultimas categorías añadidas</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive table mt-2" id="dataTable-1" role="grid" aria-describedby="dataTable_info">
                    <table class="table my-0" id="dataTable">
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
<hr />
<div class="row">
    <div class="col">
        <h5 class="d-xl-flex justify-content-xl-end">Administrar subcategorías</h5>
    </div>
</div>
<div class="row">
    <div class="col">
        <div class="row">
            <div class="col">
                <div class="card shadow mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="font-weight-bold m-0">Eliminar una subcategoría</h6>
                    </div>
                    <div class="card-body">
                        <form id="formEliSub">
                            <div class="form-row">
                                <div class="col"><input class="form-control" type="number" id="id-subcategoria" placeholder="ID de la subcategoría *" name="idSubcategoria" min="0" /></div>
                                <div class="col"><button class="btn btn-primary" onclick="eliminarSubcategoria()" type="button" style="background-color: rgb(223,87,78);color: rgb(255,255,255);">Eliminar</button></div>
                            </div>
                            <input type="hidden" name="modulo" value="eliSub" />
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="card shadow mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="font-weight-bold m-0">Crear una subcategoría</h6>
                    </div>
                    <div class="card-body">
                        <form id="formCreSub">
                            <div class="form-row">
                                <div class="col"><input class="form-control" type="text" id="nombre-subcategoria" name="nombreSubcategoria" maxlength="100" placeholder="Nombre de la subcategoría *" /></div>
                                <div class="col">
                                    <div class="form-row">
                                        <div class="col">
                                            <div>
                                                <input list="lista-categorias" class="custom-select" id="subcategoria-categoria" name="categoria" placeholder="Categoría *" />
                                                <datalist id="lista-categorias">
                                                    <?php
                                                    if($datalistCategorias -> rowCount() > 0) {
                                                        foreach($datalistCategorias as $row) {
                                                            echo '<option value="'.$row["nombre_categoria"].'">';
                                                        }
                                                    }else {
                                                        echo '<option value="NULL">No hay subcategorias</option>';
                                                    }
                                                    ?>
                                                </datalist>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row" style="margin: 5px -5px;">
                                <div class="col"><textarea class="form-control" id="descripcion-categoria-1" style="resize:none;" name="descripcionSubcategoria" maxlength="200" placeholder="Descripción de la subcategoría..."></textarea></div>
                            </div>
                            <div class="form-row" style="margin: 5px -5px;">
                                <div class="col"><button class="btn btn-primary" onclick="crearSubcategoria()" type="button" style="background-color: #3F3F42;color: rgb(255,255,255);">Crear producto</button></div>
                                <div class="col"><input class="form-control" type="hidden" name="modulo" value="crearSub" /></div>
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
                <h6 class="font-weight-bold m-0">Modificar una subcategoría</h6>
            </div>
            <div class="card-body">
                <form id="formModSub">
                    <div class="form-row">
                        <div class="col"><input class="form-control" type="number" id="id-subcategoria-mod" placeholder="ID de la subcategoría *" name="idSubcategoria" min="0" /></div>
                        <div class="col"><button class="btn btn-primary" onclick="buscarSubcategoria()" type="button" style="background-color: #3F3F42;">Buscar</button></div>
                    </div>
                    <div class="form-row d-none ocultar-s" style="margin: 5px -5px;">
                        <div class="col"><input class="form-control" type="text" id="nombre-subcategoria-mod" name="nombreSubcategoria" maxlength="100" placeholder="Nombre de la subcategoría *" /></div>
                        <div class="col">
                            <div class="form-row">
                                <div class="col">
                                    <div>
                                        <input list="lista-categorias-mod" class="custom-select" id="subcategoria-categoria-mod" name="categoria" placeholder="Categoría *" />
                                        <datalist id="lista-categorias-mod">
                                            <?php
                                            if($datalistCategorias -> rowCount() > 0) {
                                                foreach($datalistCategorias as $row) {
                                                    echo '<option value="'.$row["nombre_categoria"].'">';
                                                }
                                            }else {
                                                echo '<option value="NULL">No hay subcategorias</option>';
                                            }
                                            ?>
                                        </datalist>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row d-none ocultar-s" style="margin: 5px -5px;">
                        <div class="col"><textarea class="form-control" id="descripcion-subcategoria-mod" style="resize:none;" name="descripcionSubcategoria" maxlength="200" placeholder="Descripción de la subcategoría..."></textarea></div>
                    </div>
                    <div class="form-row d-none ocultar-s" style="margin: 5px -5px;">
                        <div class="col" placeholder="Caducidad"><button class="btn btn-primary" onclick="modificarSubcategoria()" type="button" style="background-color: #3F3F42;color: rgb(255,255,255);">Modificar producto</button></div>
                        <div class="col"><input class="form-control" type="hidden" name="modulo" value="modSub" /></div>
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
                <h6 class="font-weight-bold m-0">Ultimas subcategorías añadidas</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive table mt-2" id="dataTable-1" role="grid" aria-describedby="dataTable_info">
                    <table class="table my-0" id="dataTable">
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