function mostrar() {
    $('.oculto-i').removeClass("d-none");
}

function guardarFotoPerfil() {
    let datos = new FormData(document.getElementById("formImg"));
    datos.append("modulo", "fPerfil");
    datos.append("user", document.getElementById("correo-electronico").value);

    fetch('https://prometeo.sytes.net/includes/admin-perfil.php', {
        method: "POST",
        body: datos
    }).then(response => response.text())
    .then(respuesta => {
        recargarModuloPerfil();
        alert(respuesta);
    }).catch(error => console.log(error));
};

function modificarDatosUsuario() {
    let datos = new FormData(document.getElementById("formDatosUsuario"));
    datos.append("modulo", "modPerfil");

    fetch('https://prometeo.sytes.net/includes/admin-perfil.php', {
        method: "POST",
        body: datos
    }).then(response => response.text())
    .then(respuesta => {
        recargarModuloPerfil();
        alert(respuesta);
    }).catch(error => console.log(error));
}

function recargarModuloPerfil() {
    let idUsuario = document.getElementById("idUsuario").value;
    fetch('https://prometeo.sytes.net/includes/content-perfil.php?idUsuario='+idUsuario)
        .then(response => response.text())
        .then(respuesta => {
            cajaContenido.innerHTML = respuesta;
        }).catch(error => cajaContenido.innerHTML = error)
};