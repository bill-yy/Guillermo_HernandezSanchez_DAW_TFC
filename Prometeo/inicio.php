<?php
    require_once "/var/www/Proyectos/prometeo/imp/db.php";
    require_once "sesiones.php";

    sesionNoIniciada();
    $idUsuario = idUsuarioCorreo($_SESSION["user"]);
    $imagen = recuperarImagenUsuario($_SESSION["user"])[0];

    $totalVentasMes = 0;
    $totalVentasAnual = 0;
    $totalProductos = 0;
    $totalClientes = 0;
    $ultimasVentas;
    $totalProductosSinStock;
    $totalProductosCaducados;
    //$fechaActual = date("Y-m-d H:i:s");

    function construirAtributos() {
        global $totalVentasMes, $totalVentasAnual, $totalProductos, $totalClientes, $ultimasVentas, $totalProductosSinStock, $totalProductosCaducados;

        //Atributos inicio
        $conexionBD = conexionBd();
        $resulTotalVentasMes = $conexionBD -> query("SELECT total_venta FROM ventas WHERE YEAR(fecha_venta) = YEAR(NOW()) AND MONTH(fecha_venta) = MONTH(NOW())");
        foreach($resulTotalVentasMes as $ventaMes) {
            $totalVentasMes += $ventaMes["total_venta"];
        }

        $resulTotalVentasAnual = $conexionBD -> query("SELECT total_venta FROM ventas WHERE YEAR(fecha_venta) = YEAR(NOW())");
        foreach($resulTotalVentasAnual as $ventaAnual) {
            $totalVentasAnual += $ventaAnual["total_venta"];
        }

        $resulTotalProductos = $conexionBD -> query("SELECT id_producto FROM productos");
        $totalProductos = $resulTotalProductos -> rowCount();

        $resulTotalClientes = $conexionBD -> query("SELECT id_cliente FROM clientes");
        $totalClientes = $resulTotalClientes -> rowCount();

        $ultimasVentas = $conexionBD -> query("SELECT * FROM ventas INNER JOIN clientes ON ventas.id_cliente = clientes.id_cliente INNER JOIN tipos_clientes ON clientes.tipo_cliente = tipos_clientes.id_tipo ORDER BY ventas.id_venta DESC LIMIT 20");
        
        //Total productos sin stock
        $totalProductosSinStock = $conexionBD -> query("SELECT * FROM productos INNER JOIN subcategorias ON productos.id_subcat = subcategorias.id_subcat AND productos.id_producto NOT IN (SELECT id_producto FROM stock)");
        

        //Total productos caducados
        $totalProductosCaducados = $conexionBD -> query("SELECT * FROM stock INNER JOIN productos ON stock.id_producto = productos.id_producto INNER JOIN subcategorias ON productos.id_subcat = subcategorias.id_subcat AND DATE(stock.caducidad) < DATE(NOW())");
        
    }

    construirAtributos();

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Prometeo - Inicio</title>
    <link rel="shortcut icon" type="image/jpg" href="./res/img/prometeoLogo.png"/>
    <link rel="stylesheet" href="./assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.12.0/css/all.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="./assets/fonts/fontawesome5-overrides.min.css">
    <link rel="stylesheet" href="./assets/css/inicio.css">
    <link rel="stylesheet" href="./assets/css/style.css">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no" />
    <title>Prometeo - Inicio</title>
</head>
</head>

<body id="page-top" style="background-color: rgb(255,252,255);">
    <div id="wrapper" style="color: rgb(153,149,179);">
        <nav class="navbar navbar-dark align-items-start sidebar sidebar-dark accordion p-0" style="background-color: #3F3F42;">
            <div class="container-fluid d-flex flex-column p-0">
                <a class="navbar-brand d-flex justify-content-center align-items-center sidebar-brand m-0" href="#">
                    <div class="sidebar-brand-icon rotate-n-15"><img style="width: 30px;height: 30px;" src="./res/img/prometeoLogo.png"></div>
                    <div class="sidebar-brand-text mx-3"><span>PROMETEO</span></div>
                </a>
                <hr class="sidebar-divider my-0">
                <ul class="nav navbar-nav text-light" id="accordionSidebar" style="height: 100%;width: 100%;">
                    <li class="nav-item" role="presentation"><a class="nav-link active" href="inicio.php"><i class="fas fa-home"></i><span>General</span></a></li>
                    <li class="nav-item" role="presentation"></li>
                    <li class="nav-item dropdown"><a class="dropdown-toggle nav-link" data-toggle="dropdown" aria-expanded="false" href="#productos"><i class="fa fa-cubes" style="width: 17px;"></i>Inventario</a>
                        <div class="dropdown-menu menu rounded-0" role="menu"><a class="dropdown-item" role="presentation" href="#inventario" id="btnInventario">Vista General</a><a class="dropdown-item" role="presentation" href="#admiinv" id="btnAdminInv">Administrador de Inventario</a></div>
                    </li>
                    <li class="nav-item dropdown"><a class="dropdown-toggle nav-link" data-toggle="dropdown" aria-expanded="false" href="#productos"><i class="fas fa-tags" style="width: 17px;"></i>Categorias</a>
                        <div class="dropdown-menu menu rounded-0" role="menu"><a class="dropdown-item" role="presentation" href="#vCategorias" id="btnvCategorias">Vista General</a><a class="dropdown-item" role="presentation" id="btnAdminCat" href="#">Administrar Categorias</a></div>
                    </li>
                    <li class="nav-item dropdown"><a class="dropdown-toggle nav-link" data-toggle="dropdown" aria-expanded="false" href="#productos"><i class="fas fa-shopping-basket" style="width: 17px;"></i>Ventas</a>
                        <div class="dropdown-menu menu rounded-0" role="menu"><a class="dropdown-item" role="presentation" href="#vVentas" id="btnAdminVentas">Añadir/Eliminar Ventas</a></div>
                    </li>
                    <li class="nav-item dropdown"><a data-toggle="dropdown" aria-expanded="false" class="dropdown-toggle nav-link" href="#productos"><i class="fas fa-user" style="width: 17px;"></i>Clientes</a>
                        <div class="dropdown-menu menu rounded-0" role="menu"><a class="dropdown-item" role="presentation" href="#vClientes" id="btnvClientes">Vista General</a><a class="dropdown-item" id="btnAdminClientes" role="presentation" href="#">Administrar Cliente</a></div>
                    </li>
                    <li class="nav-item" role="presentation"></li>
                    <?php
                        if($_SESSION["rol"] === "1") {
                            echo '<li class="nav-item" role="presentation"><a class="nav-link" id="btnConfiguracion" href="#"><i class="fas fa-cogs"></i><span>Configuración</span></a></li> ';
                        }
                    ?>
                </ul>
            </div>
        </nav>
        <div class="d-flex flex-column" id="content-wrapper">
            <div id="content">
                <nav class="navbar navbar-light navbar-expand shadow mb-4 topbar static-top" style="height: 5vh;background-color: #3f3f42;">
                    <input type="hidden" value="<?php echo $idUsuario[0]; ?>" id="idUsuario">
                    <div class="container-fluid">
                        <ul class="nav navbar-nav flex-nowrap ml-auto">
                            <!-- <li class="nav-item dropdown no-arrow mx-1" role="presentation">
                                <div class="nav-item dropdown no-arrow"><a class="dropdown-toggle nav-link" data-toggle="dropdown" aria-expanded="true" href="#"><span id="numAlertas" class="badge badge-danger badge-counter">0</span><i class="fas fa-bell fa-fw"></i></a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-list dropdown-menu-right animated--grow-in"
                                        role="menu">
                                        <h6 class="dropdown-header" style="background-color: #3f3f42;">NOTIFICACIONES</h6>
                                        <a class="d-flex align-items-center dropdown-item" href="#">
                                            <div class="mr-3">
                                                <div class="bg-primary icon-circle" id="tipoNoti">
                                                    <i class="fas fa-file-alt text-white"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <span id="fechaNoti" class="small text-gray-500">Fecha notificacion</span>
                                                <p id="textoNoti">Texto Notificación</p>
                                            </div>
                                        </a><a class="text-center dropdown-item small text-gray-500" href="#">Ver todas las Notificaciones</a></div>
                                </div>
                            </li> -->
                            <div class="d-none d-sm-block topbar-divider"></div>
                            <li class="nav-item dropdown no-arrow" role="presentation"><div class="nav-item dropdown no-arrow"><a data-toggle="dropdown" aria-expanded="true" class="dropdown-toggle nav-link" href="#"><span class="d-none d-lg-inline mr-2 text-gray-600 small" id="usuario"><?php echo $_SESSION["user"]; ?></span><img class="border rounded-circle img-profile" id="avatar" src="./includes/userspics/<?php echo $imagen; ?>" /></a>
                            <div role="menu" class="dropdown-menu shadow dropdown-menu-right animated--grow-in" style="background-color: #3f3f42;"><a id="btnPerfil" role="presentation" class="dropdown-item" href="#"><i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i> Perfil</a>
                                    <div class="dropdown-divider"></div><a role="presentation" class="dropdown-item" href="./logout.php"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i> Cerrar Sesión</a></div>
                            </div></li>
                        </ul>
                    </div>
                </nav>
                <!-- Contenido -->
                <div class="container-fluid" id="contenido">
                    <div class="d-sm-flex justify-content-between align-items-center mb-4">
                        <h3 class="text-dark mb-0" id="titulo">Inicio</h3>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-xl-3 mb-4">
                            <div class="card shadow border-left-primary py-2">
                                <div class="card-body">
                                    <div class="row align-items-center no-gutters">
                                        <div class="col mr-2">
                                            <div class="text-uppercase text-primary font-weight-bold text-xs mb-1"><span>Ventas (MES)</span>
                                            </div>
                                            <div class="text-dark font-weight-bold h5 mb-0"><span id="totalVentasMes"><?php echo $totalVentasMes; ?>€</span></div>
                                        </div>
                                        <div class="col-auto"><i class="fas fa-calendar fa-2x text-gray-300"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-3 mb-4">
                            <div class="card shadow border-left-success py-2">
                                <div class="card-body">
                                    <div class="row align-items-center no-gutters">
                                        <div class="col mr-2">
                                            <div class="text-uppercase text-success font-weight-bold text-xs mb-1">
                                                <span>VENTAS(ANUAL)</span></div>
                                            <div class="text-dark font-weight-bold h5 mb-0"><span id="totalVentasAnual"><?php echo $totalVentasAnual; ?>€</span></div>
                                        </div>
                                        <div class="col-auto"><i class="fas fa-dollar-sign fa-2x text-gray-300"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-3 mb-4">
                            <div class="card shadow border-left-info py-2">
                                <div class="card-body">
                                    <div class="row align-items-center no-gutters">
                                        <div class="col mr-2">
                                            <div class="text-uppercase text-info font-weight-bold text-xs mb-1"><span>TOTAL PRODUCTOS</span>
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
                                            <div class="text-uppercase text-warning font-weight-bold text-xs mb-1"><span>TOTAL
                                                    CLIENTES</span></div>
                                            <div class="text-dark font-weight-bold h5 mb-0"><span id="totalClientes"><?php echo $totalClientes; ?></span></div>
                                        </div>
                                        <div class="col-auto"><i class="fas fa-user-alt fa-2x text-gray-300"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card shadow" style="margin-bottom:30px;">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="font-weight-bold m-0">Listado de remesas caducadas</h6>
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
                                            <th>CB</th>
                                            <th>Caducidad</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            foreach($totalProductosCaducados as $row) {
                                                print("<tr>");
                                                print("<td>".$row['id_producto']."</td>");
                                                print("<td>".$row['nombre_producto']."</td>");
                                                print("<td>".$row['marca']."</td>");
                                                print("<td>".$row['nombre_subcategoria']."</td>");
                                                print("<td>".$row['codigo_barras']."</td>");
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
                                            <th>Subcategoría</th>
                                            <th>CB</th>
                                            <th>Caducidad</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card shadow" style="margin-bottom:30px;">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="font-weight-bold m-0">Listado de productos sin remesas</h6>
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
                                            <th>CB</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            foreach($totalProductosSinStock as $row) {
                                                print("<tr>");
                                                print("<td>".$row['id_producto']."</td>");
                                                print("<td>".$row['nombre_producto']."</td>");
                                                print("<td>".$row['marca']."</td>");
                                                print("<td>".$row['nombre_subcategoria']."</td>");
                                                print("<td>".$row['codigo_barras']."</td>");
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
                                            <th>CB</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card shadow" style="margin-bottom:30px;">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="font-weight-bold m-0">Ultimas ventas</h6>
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
                                            foreach($ultimasVentas as $row) {
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
                </div>
                <!-- Fin contenido -->
            </div>
        </div><a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fas fa-angle-up"></i></a></div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/fixedheader/3.1.8/js/dataTables.fixedHeader.min.js"></script>
    <script src="assets/js/theme.js"></script>
    <!-- JS -->
    <script src="./assets/js/contentLoader.js"></script>
    <script src="./assets/js/ajaxCrud.js"></script>
    <script src="./assets/js/perfil.js"></script>
    <script src="./assets/js/ventas.js"></script>
</body>
</html>
