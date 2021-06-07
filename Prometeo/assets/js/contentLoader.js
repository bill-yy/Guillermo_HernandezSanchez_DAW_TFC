let cajaContenido = document.getElementById("contenido");

function montarDataTable() {
    $('.dataTable').DataTable({
        language: {
            "decimal": "",
            "emptyTable": "No hay información",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
            "infoEmpty": "Mostrando 0 a 0 de 0 Entradas",
            "infoFiltered": "(Filtrado de _MAX_ total entradas)",
            "infoPostFix": "",
            "thousands": ",",
            "lengthMenu": "Mostrar _MENU_ Entradas",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "search": "Buscar:",
            "zeroRecords": "Sin resultados encontrados",
            "paginate": {
                "first": "Primero",
                "last": "Ultimo",
                "next": "Siguiente",
                "previous": "Anterior"
            }
        },
    });
};

// Event Listeners

document.getElementById("btnInventario").addEventListener("click", function (event) {
    event.preventDefault();
    fetch('https://prometeo.sytes.net/includes/content-productos.php')
        .then(response => response.text())
        .then(respuesta => {
            cajaContenido.innerHTML = respuesta;
            montarDataTable();
        }).catch(error => cajaContenido.innerHTML = error)
});

document.getElementById("btnvCategorias").addEventListener("click", function (event) {
    event.preventDefault();
    fetch('https://prometeo.sytes.net/includes/content-categorias.php')
        .then(response => response.text())
        .then(respuesta => {
            cajaContenido.innerHTML = respuesta;
            montarDataTable();
        }).catch(error => cajaContenido.innerHTML = error)
});

document.getElementById("btnvClientes").addEventListener("click", function (event) {
    event.preventDefault();
    fetch('https://prometeo.sytes.net/includes/content-clientes.php')
        .then(response => response.text())
        .then(respuesta => {
            cajaContenido.innerHTML = respuesta;
            montarDataTable();
        }).catch(error => cajaContenido.innerHTML = error)
});

document.getElementById("btnAdminInv").addEventListener("click", function (event) {
    event.preventDefault();
    fetch('https://prometeo.sytes.net/includes/content-admin-inventario.php')
        .then(response => response.text())
        .then(respuesta => {
            cajaContenido.innerHTML = respuesta;
        }).catch(error => cajaContenido.innerHTML = error)
});

document.getElementById("btnPerfil").addEventListener("click", function (event) {
    event.preventDefault();
    let idUsuario = document.getElementById("idUsuario").value;
    fetch('https://prometeo.sytes.net/includes/content-perfil.php?idUsuario='+idUsuario)
        .then(response => response.text())
        .then(respuesta => {
            cajaContenido.innerHTML = respuesta;
        }).catch(error => cajaContenido.innerHTML = error)
});

document.getElementById("btnAdminCat").addEventListener("click", function (event) {
    event.preventDefault();
    fetch('https://prometeo.sytes.net/includes/content-admin-categorias.php')
        .then(response => response.text())
        .then(respuesta => {
            cajaContenido.innerHTML = respuesta;
        }).catch(error => cajaContenido.innerHTML = error)
});

document.getElementById("btnAdminClientes").addEventListener("click", function (event) {
    event.preventDefault();
    fetch('https://prometeo.sytes.net/includes/content-admin-clientes.php')
        .then(response => response.text())
        .then(respuesta => {
            cajaContenido.innerHTML = respuesta;
        }).catch(error => cajaContenido.innerHTML = error)
});

document.getElementById("btnAdminVentas").addEventListener("click", function (event) {
    event.preventDefault();
    fetch('https://prometeo.sytes.net/includes/content-admin-ventas.php')
        .then(response => response.text())
        .then(respuesta => {
            cajaContenido.innerHTML = respuesta;
            montarDataTable();
        }).catch(error => cajaContenido.innerHTML = error)
});

document.getElementById("btnConfiguracion").addEventListener("click", function (event) {
    event.preventDefault();
    fetch('https://prometeo.sytes.net/includes/content-configuracion.php')
        .then(response => response.text())
        .then(respuesta => {
            cajaContenido.innerHTML = respuesta;
            montarDataTable();
        }).catch(error => cajaContenido.innerHTML = error)
});