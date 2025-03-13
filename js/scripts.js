document.addEventListener("DOMContentLoaded", function () {
    // Verificar si estamos en la página de login
    const loginForm = document.getElementById("loginForm");
    if (loginForm) {
        loginForm.addEventListener("submit", function (event) {
            event.preventDefault();
            const username = document.getElementById("username").value;
            const password = document.getElementById("password").value;

            if (username === "admin" && password === "1234") {
                alert("Inicio de sesión exitoso");
                window.location.href = "dashboard.html";
            } else {
                alert("Usuario o contraseña incorrectos");
            }
        });
    }

    // Verificar si estamos en la página de reportes
    const reporteForm = document.getElementById("reporteForm");
    if (reporteForm) {
        reporteForm.addEventListener("submit", function (event) {
            event.preventDefault();
            
            const tipo = document.getElementById("tipo").value;
            const ubicacion = document.getElementById("ubicacion").value;
            const prioridad = document.getElementById("prioridad").value;
            
            const reporte = {
                id: Date.now(),
                tipo,
                ubicacion,
                prioridad,
                estado: "Pendiente"
            };
            
            let reportes = JSON.parse(localStorage.getItem("reportes")) || [];
            reportes.push(reporte);
            localStorage.setItem("reportes", JSON.stringify(reportes));
            
            alert("Reporte enviado exitosamente");
            window.location.reload();
        });
    }

    // Verificar si estamos en la página de administración de reportes
    const tablaReportes = document.getElementById("tablaReportes");
    if (tablaReportes) {
        cargarReportes();
    }
});

function cargarReportes() {
    let reportes = JSON.parse(localStorage.getItem("reportes")) || [];
    const tablaReportes = document.getElementById("tablaReportes");

    tablaReportes.innerHTML = "";
    reportes.forEach((reporte) => {
        const fila = document.createElement("tr");
        fila.innerHTML = `
            <td>${reporte.id}</td>
            <td>${reporte.tipo}</td>
            <td>${reporte.ubicacion}</td>
            <td>${reporte.prioridad}</td>
            <td>${reporte.estado}</td>
            <td>
                <button onclick="actualizarEstado(${reporte.id})">Actualizar Estado</button>
            </td>
        `;
        tablaReportes.appendChild(fila);
    });
}

function actualizarEstado(id) {
    let reportes = JSON.parse(localStorage.getItem("reportes")) || [];
    let reporte = reportes.find(r => r.id === id);
    if (reporte) {
        reporte.estado = "En Proceso";
        localStorage.setItem("reportes", JSON.stringify(reportes));
        cargarReportes();
    }
}
