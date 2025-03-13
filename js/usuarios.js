document.addEventListener("DOMContentLoaded", function () {
    cargarUsuarios();
    
    document.getElementById("usuarioForm").addEventListener("submit", function (event) {
        event.preventDefault();
        guardarUsuario();
    });
});

function cargarUsuarios() {
    let usuarios = JSON.parse(localStorage.getItem("usuarios")) || [];
    const tablaUsuarios = document.getElementById("tablaUsuarios");
    tablaUsuarios.innerHTML = "";

    usuarios.forEach((usuario, index) => {
        const fila = document.createElement("tr");
        fila.innerHTML = `
            <td>${index + 1}</td>
            <td>${usuario.nombre}</td>
            <td>${usuario.rol}</td>
            <td>
                <button onclick="editarUsuario(${index})">Editar</button>
                <button onclick="eliminarUsuario(${index})">Eliminar</button>
            </td>
        `;
        tablaUsuarios.appendChild(fila);
    });
}

function guardarUsuario() {
    let usuarios = JSON.parse(localStorage.getItem("usuarios")) || [];
    const id = document.getElementById("userId").value;
    const nombre = document.getElementById("nombre").value;
    const rol = document.getElementById("rol").value;

    if (id) {
        usuarios[id] = { nombre, rol };
    } else {
        usuarios.push({ nombre, rol });
    }

    localStorage.setItem("usuarios", JSON.stringify(usuarios));
    document.getElementById("usuarioForm").reset();
    document.getElementById("userId").value = "";
    cargarUsuarios();
}

function editarUsuario(index) {
    let usuarios = JSON.parse(localStorage.getItem("usuarios")) || [];
    document.getElementById("userId").value = index;
    document.getElementById("nombre").value = usuarios[index].nombre;
    document.getElementById("rol").value = usuarios[index].rol;
}

function eliminarUsuario(index) {
    let usuarios = JSON.parse(localStorage.getItem("usuarios")) || [];
    usuarios.splice(index, 1);
    localStorage.setItem("usuarios", JSON.stringify(usuarios));
    cargarUsuarios();
}