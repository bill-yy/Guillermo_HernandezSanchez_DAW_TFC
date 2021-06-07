function añadirAlCarro() {
    let producto = document.getElementById("remesa-buscar").value;
    let tabla = document.getElementById("cuerpoTabla");
    let resultado = `<tr id="${producto.split(" - ")[0].replace("Id: ","")}">`;
    let remesas = document.getElementById("hidden-remesa");

    eliminarDelCarrito(producto.split(" - ")[0].replace("Id: ",""))

    //id
    resultado += `<td>${producto.split(" - ")[0].replace("Id: ","")}</td>`;

    //nombre
    resultado += `<td>${producto.split(" - ")[1].replace("Nombre: ","")}</td>`;

    //stock
    resultado += `<td>${producto.split(" - ")[2].replace("Stock: ","").replace("  "," ")}</td>`;

    //caducidad
    resultado += `<td>${producto.split(" - ")[3].replace("Caducidad: ","")}</td>`;

    //cantdad a venter
    resultado += `<td><input class="form-control" type="number" id="cantidad-${producto.split(" - ")[0].replace("Id: ","")}" min="1" max="${producto.split(" - ")[2].replace("Stock: ","").split("  ")[0]}"></td>`;

    //eliminar
    resultado += `<td><a href="#" onclick="eliminarDelCarrito(${producto.split(" - ")[0].replace("Id: ","")})">Eliminar</a></td>`;
    resultado += "</tr>";

    tabla.innerHTML += resultado;
    remesas.value += `${producto.split(" - ")[0].replace("Id: ","")}-`;

    document.getElementById("remesa-buscar").value = "";
    
}

function eliminarDelCarrito(id) {
    $(`#${id}`).remove()
    let remesas = document.getElementById("hidden-remesa");
    nuevoValor =  remesas.value.replace(`${id}-`,"");
    remesas.value = nuevoValor;
}

function setCantidad() {
    let remesas = new Array(document.getElementById("hidden-remesa").value.split("-").length);
    remesas = document.getElementById("hidden-remesa").value.split("-");

    let cantidades = document.getElementById("hidden-cantidad");
    cantidades.value = "";

    for(let i = 0; 1 < remesas.length; i++) {
        if(remesas[i] === "" || remesas[i] === " ") {
            break;
        }else {
            let cantidad = document.getElementById(`cantidad-${remesas[i]}`).value;
            if(cantidad == "") {
                alert(`ERROR, HAY CANTIDADES SIN RELLENAR ID: ${remesas[i]}`);
                return false;
            }else {
                cantidades.value += `${cantidad}-`;
            }
        }
    }
    return true;
}

function crearVenta() {
    if(setCantidad() == true) {
        let datos = new FormData(document.getElementById("formCrearVenta"));
        
        peticionVenta(datos);
    }
}

function eliminarVenta() {
    let datos = new FormData(document.getElementById("formEliVenta"));

    peticionVenta(datos);
}

function peticionVenta(datos) {
    fetch('https://prometeo.sytes.net/includes/admin-ventas.php', {
        method: "POST",
        body: datos
    }).then(response => response.text())
    .then(respuesta => {
        recargarModulo('https://prometeo.sytes.net/includes/content-admin-ventas.php');
        alert(respuesta);
    }).catch(error => console.log(error));
};