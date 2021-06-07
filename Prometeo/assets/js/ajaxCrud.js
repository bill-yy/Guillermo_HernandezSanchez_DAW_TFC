// ---------------Productos--------------------
function eliminarProducto() {
    let datos = new FormData(document.getElementById("formEliPro"));

    peticion(datos);
};

function crearProducto() {
    let datos = new FormData(document.getElementById("formCrePro"));
    let idUsuario = document.getElementById("idUsuario").value;

    datos.append("idUsuario", document.getElementById("idUsuario").value);

    peticion(datos);
};

function buscarProducto() {
    let datos = new FormData();
    datos.append("modulo", "buscarPro");
    datos.append("idProducto", document.getElementById("id-producto-mod").value);

    fetch('https://prometeo.sytes.net/includes/admin-inventario.php', {
        method: "POST",
        body: datos
    }).then(response => response.text())
    .then(respuesta => {
        datosProducto = respuesta.split("|");

        document.getElementById("nombre-producto-mod").value = datosProducto[0];
        document.getElementById("subcategoria-producto-mod").value = datosProducto[1];
        document.getElementById("descripcion-producto-mod").value = datosProducto[2];
        document.getElementById("codigo-barras-mod").value = datosProducto[3];
        document.getElementById("marca-mod").value = datosProducto[4];

        $('.oculto-p').removeClass("d-none");
    }).catch(error => console.log(error));
};

function modificarProducto() {
    let datos = new FormData(document.getElementById("formModPro"));

    peticion(datos);
};
// -------------------------------------------------------------------------------------------
// ------------------------------------Remesas-----------------------------------------------
function eliminarRemesa() {
    let datos = new FormData(document.getElementById("formEliRem"));

    peticion(datos);
};

function crearRemesa() {
    let datos = new FormData(document.getElementById("formCreRem"));

    peticion(datos);
};

function buscarRemesa() {
    let datos = new FormData();
    datos.append("modulo", "buscarRem");
    datos.append("idStock", document.getElementById("id-producto-rem-mod").value);

    fetch('https://prometeo.sytes.net/includes/admin-inventario.php', {
        method: "POST",
        body: datos
    }).then(response => response.text())
    .then(respuesta => {
        datosProducto = respuesta.split("|");
        document.getElementById("remesa-producto-mod").value = datosProducto[0];
        document.getElementById("stock-mod").value = datosProducto[1];
        document.getElementById("remesa-tipo-mod").value = datosProducto[2];
        document.getElementById("caducidad-remesa-mod").value = datosProducto[3];

        $('.oculto-r').removeClass("d-none");
    }).catch(error => console.log(error));
};

function modificarRemesa() {
    let datos = new FormData(document.getElementById("formModRem"));

    peticion(datos);
};
// -------------------------------------------------------------------------------------------
// -------------------------------------Clientes----------------------------------------------
function crearCliente() {
    let datos = new FormData(document.getElementById("formCreCli"));

    peticionCli(datos);
};

function eliminarCliente() {
    let datos = new FormData(document.getElementById("formEliCli"));

    peticionCli(datos);
};

function buscarCliente() {
    let datos = new FormData();
    datos.append("modulo", "buscarCli");
    datos.append("idCliente", document.getElementById("id-cliente-mod").value);

    fetch('https://prometeo.sytes.net/includes/admin-clientes.php', {
        method: "POST",
        body: datos
    }).then(response => response.text())
    .then(respuesta => {
        datosProducto = respuesta.split("|");
        document.getElementById("clientes-tipo-mod").value = datosProducto[0];
        document.getElementById("nombre-empresa-mod").value = datosProducto[1];
        document.getElementById("nombre-cliente-mod").value = datosProducto[2];
        document.getElementById("corre-cliente-mod").value = datosProducto[3];
        document.getElementById("telefono-cliente-mod").value = datosProducto[4];

        $('.oculto-c').removeClass("d-none");
    }).catch(error => console.log(error));
};

function modificarCliente() {
    let datos = new FormData(document.getElementById("formModCli"));

    peticionCli(datos);
};
// -------------------------------------------------------------------------------------------
// ---------------------------------------Categorias------------------------------------------
function eliminarCategoria() {
    let datos = new FormData(document.getElementById("formEliCat"));

    peticionCat(datos);
};

function crearCategoria() {
    let datos = new FormData(document.getElementById("formCreCat"));

    peticionCat(datos);
};

function buscarCategoria() {
    let datos = new FormData();
    datos.append("modulo", "buscarCat");
    datos.append("idCategoria", document.getElementById("id-categoria-mod").value);

    fetch('https://prometeo.sytes.net/includes/admin-categorias.php', {
        method: "POST",
        body: datos
    }).then(response => response.text())
    .then(respuesta => {
        datosProducto = respuesta.split("|");
        document.getElementById("nombre-categoria-mod").value = datosProducto[0];
        document.getElementById("descripcion-categoria-mod").value = datosProducto[1];

        $('.oculto-cat').removeClass("d-none");
    }).catch(error => console.log(error));
};

function modificarCategoria() {
    let datos = new FormData(document.getElementById("formModCat"));

    peticionCat(datos);
};
// -------------------------------------------------------------------------------------------
// -------------------------------------Subcategorias-----------------------------------------
function eliminarSubcategoria() {
    let datos = new FormData(document.getElementById("formEliSub"));

    peticionCat(datos);
};

function crearSubcategoria() {
    let datos = new FormData(document.getElementById("formCreSub"));

    peticionCat(datos);
};

function buscarSubcategoria() {
    let datos = new FormData();
    datos.append("modulo", "buscarSub");
    datos.append("idSubcategoria", document.getElementById("id-subcategoria-mod").value);

    fetch('https://prometeo.sytes.net/includes/admin-categorias.php', {
        method: "POST",
        body: datos
    }).then(response => response.text())
    .then(respuesta => {
        datosProducto = respuesta.split("|");
        document.getElementById("nombre-subcategoria-mod").value = datosProducto[0];
        document.getElementById("subcategoria-categoria-mod").value = datosProducto[1];
        document.getElementById("descripcion-subcategoria-mod").value = datosProducto[2];

        $('.ocultar-s').removeClass("d-none");
    }).catch(error => console.log(error));
};

function modificarSubcategoria() {
    let datos = new FormData(document.getElementById("formModSub"));

    peticionCat(datos);
};
// -------------------------------------------------------------------------------------------

function recargarModulo(modulo) {
    fetch(modulo)
        .then(response => response.text())
        .then(respuesta => {
            cajaContenido.innerHTML = respuesta;
            montarDataTable();
        }).catch(error => cajaContenido.innerHTML = error)
};

function peticion(datos) {
    fetch('https://prometeo.sytes.net/includes/admin-inventario.php', {
        method: "POST",
        body: datos
    }).then(response => response.text())
    .then(respuesta => {
        recargarModulo('https://prometeo.sytes.net/includes/content-admin-inventario.php');
        alert(respuesta);
    }).catch(error => console.log(error));
};

function peticionCli(datos) {
    fetch('https://prometeo.sytes.net/includes/admin-clientes.php', {
        method: "POST",
        body: datos
    }).then(response => response.text())
    .then(respuesta => {
        recargarModulo('https://prometeo.sytes.net/includes/content-admin-clientes.php');
        alert(respuesta);
    }).catch(error => console.log(error));
};

function peticionCat(datos) {
    fetch('https://prometeo.sytes.net/includes/admin-categorias.php', {
        method: "POST",
        body: datos
    }).then(response => response.text())
    .then(respuesta => {
        recargarModulo('https://prometeo.sytes.net/includes/content-admin-categorias.php');
        alert(respuesta);
    }).catch(error => console.log(error));
};
// -------------------------------------------------------------------------------------------
// ----------------------------------Confi----------------------------------------------------
function crearUsuario() {
    let datos = new FormData(document.getElementById("formCrearUsu"));

    peticionConf(datos);
};

function peticionConf(datos) {
    fetch('https://prometeo.sytes.net/includes/admin-configuracion.php', {
        method: "POST",
        body: datos
    }).then(response => response.text())
    .then(respuesta => {
        recargarModulo('https://prometeo.sytes.net/includes/content-configuracion.php');
        alert(respuesta);
    }).catch(error => console.log(error));
};

