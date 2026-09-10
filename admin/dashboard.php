<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../login.html");
    exit;
}

if ($_SESSION['user']['role'] !== 'admin') {
    header("Location: ../login.html");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>BarberConnect - Administración</title>

<link rel="stylesheet"
      href="../assets/css/admin.css">

</head>

<body>

<div class="admin-layout">

    <!-- SIDEBAR -->

    <aside class="sidebar">

        <div class="admin-logo">
            💈 BarberConnect
        </div>

        <div class="admin-user">

            <div class="admin-avatar">
                <?php
                echo strtoupper(
                    substr($_SESSION['user']['name'], 0, 1)
                );
                ?>
            </div>

            <div>

                <strong>
                    <?php
                    echo htmlspecialchars(
                        $_SESSION['user']['name']
                    );
                    ?>
                </strong>

                <small>Administrador</small>

            </div>

        </div>

        <nav>

            <button class="menu-item active"
                    onclick="mostrarSeccion('dashboard', this)">
                📊 Dashboard
            </button>

            <button class="menu-item"
                    onclick="mostrarSeccion('clientes', this)">
                👤 Clientes
            </button>

            <button class="menu-item"
                    onclick="mostrarSeccion('barberos', this)">
                💈 Barberos
            </button>

            <button class="menu-item"
                    onclick="mostrarSeccion('reservas', this)">
                📅 Reservas
            </button>

            <button class="menu-item"
                    onclick="mostrarSeccion('servicios', this)">
                ✂️ Servicios
            </button>

            <button class="menu-item"
                    onclick="mostrarSeccion('notificaciones', this)">
                🔔 Notificaciones
            </button>

            <button class="menu-item"
                    onclick="mostrarSeccion('configuracion', this)">
                ⚙️ Configuración
            </button>

        </nav>

        <button class="logout-btn"
                onclick="logout()">
            🚪 Cerrar sesión
        </button>

    </aside>


    <!-- CONTENIDO -->

    <main class="admin-content">


        <!-- DASHBOARD -->

        <section id="dashboard"
                 class="admin-section active">

            <div class="page-header">

                <div>

                    <h1>Dashboard</h1>

                    <p>
                        Bienvenido al panel administrativo
                        de BarberConnect.
                    </p>

                </div>

            </div>


            <div class="stats-grid">

                <div class="stat-card">

                    <span>👤</span>

                    <div>
                        <small>Clientes</small>
                        <strong id="totalClientes">
                            0
                        </strong>
                    </div>

                </div>


                <div class="stat-card">

                    <span>💈</span>

                    <div>
                        <small>Barberos</small>
                        <strong id="totalBarberos">
                            0
                        </strong>
                    </div>

                </div>


                <div class="stat-card">

                    <span>⏳</span>

                    <div>
                        <small>Reservas pendientes</small>
                        <strong id="totalPendientes">
                            0
                        </strong>
                    </div>

                </div>


                <div class="stat-card">

                    <span>✅</span>

                    <div>
                        <small>Reservas confirmadas</small>
                        <strong id="totalConfirmadas">
                            0
                        </strong>
                    </div>

                </div>

            </div>


            <div class="dashboard-grid">

                <div class="panel-card">

                    <div class="panel-header">

                        <h2>Últimas reservas</h2>

                        <button onclick="mostrarSeccion('reservas')">
                            Ver todas
                        </button>

                    </div>

                    <div id="ultimasReservas">
                        Cargando...
                    </div>

                </div>


                <div class="panel-card">

                    <div class="panel-header">

                        <h2>Barberos</h2>

                        <button onclick="mostrarSeccion('barberos')">
                            Ver todos
                        </button>

                    </div>

                    <div id="resumenBarberos">
                        Cargando...
                    </div>

                </div>

            </div>

        </section>



        <!-- CLIENTES -->

        <section id="clientes"
                 class="admin-section">

            <div class="page-header">

                <div>

                    <h1>Clientes</h1>

                    <p>
                        Gestiona los clientes registrados.
                    </p>

                </div>

            </div>


            <div class="panel-card">

                <div class="search-box">

                    <input
                        type="text"
                        id="buscarCliente"
                        placeholder="🔎 Buscar cliente..."
                        oninput="filtrarClientes()"
                    >

                </div>


                <div class="table-container">

                    <table>

                        <thead>

                            <tr>
                                <th>Cliente</th>
                                <th>Email</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>

                        </thead>

                        <tbody id="tablaClientes">

                            <tr>
                                <td colspan="4">
                                    Cargando...
                                </td>
                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>

        </section>



        <!-- BARBEROS -->

        <section id="barberos"
                 class="admin-section">

            <div class="page-header">

                <div>

                    <h1>Barberos</h1>

                    <p>
                        Gestiona los profesionales de BarberConnect.
                    </p>

                </div>

            </div>


            <div id="listaBarberos"
                 class="barbers-grid">

                Cargando...

            </div>

        </section>



        <!-- RESERVAS -->

        <section id="reservas" class="admin-section">

    <div class="section-header">
        <div>
            <h2>📅 Gestión de reservas</h2>
            <p>Administra todas las reservas de BarberConnect</p>
        </div>
    </div>

    <div class="reservas-filtros">

        <input
            type="text"
            id="buscarReserva"
            placeholder="🔎 Buscar cliente, barbero o servicio..."
            oninput="filtrarReservasAdmin()"
        >

        <select
            id="filtroEstadoReserva"
            onchange="filtrarReservasAdmin()"
        >
            <option value="">Todos los estados</option>
            <option value="pending">Pendientes</option>
            <option value="accepted">Aceptadas</option>
            <option value="rejected">Rechazadas</option>
            <option value="cancelled">Canceladas</option>
        </select>

        <input
            type="date"
            id="filtroFechaReserva"
            onchange="filtrarReservasAdmin()"
        >

    </div>

    <div id="tablaReservas"></div>

</section>

        <!-- SERVICIOS -->
    <section id="servicios" class="admin-section">

    <div class="section-header">

        <div>
            <h2>✂️ Servicios</h2>
            <p>Administra los servicios ofrecidos por la barbería</p>
        </div>

        <button
            class="btn-primary"
            onclick="abrirModalServicio()"
        >
            + Nuevo servicio
        </button>

    </div>

    <div id="listaServicios"></div>

</section>




        <!-- NOTIFICACIONES -->

        <section id="notificaciones"
                 class="admin-section">

            <div class="page-header">

                <h1>Notificaciones</h1>

            </div>

            <div class="panel-card">

                <p>
                    Aquí aparecerán las notificaciones
                    administrativas.
                </p>

            </div>

        </section>



        <!-- CONFIGURACIÓN -->

        <section id="configuracion"
                 class="admin-section">

            <div class="page-header">

                <h1>Configuración</h1>

            </div>

            <div class="panel-card">

                <h2>Cuenta administrativa</h2>

                <p>
                    Gestiona la configuración del sistema.
                </p>

            </div>

        </section>


    </main>

</div>


<!-- TOAST -->

<div id="toastContainer"></div>


<script>

let serviciosAdmin = [];
let clientesData = [];
let barberosData = [];


// =====================================================
// NAVEGACIÓN
// =====================================================

function mostrarSeccion(id, boton = null) {

    document
        .querySelectorAll(".admin-section")
        .forEach(section => {
            section.classList.remove("active");
        });

    const seccion = document.getElementById(id);

    if (seccion) {
        seccion.classList.add("active");
    }

    document
        .querySelectorAll(".menu-item")
        .forEach(item => {
            item.classList.remove("active");
        });

    if (boton) {
        boton.classList.add("active");
    }

}


// =====================================================
// TOAST
// =====================================================

function showToast(message, type = "info") {

    const container =
        document.getElementById("toastContainer");

    if (!container) return;

    const toast =
        document.createElement("div");

    toast.className = `toast ${type}`;

    toast.innerHTML = message;

    container.appendChild(toast);

    setTimeout(() => {

        toast.classList.add("hide");

        setTimeout(() => {
            toast.remove();
        }, 300);

    }, 3500);

}


// =====================================================
// DASHBOARD - ESTADÍSTICAS
// =====================================================

function cargarEstadisticas() {

    fetch("../api/get_admin_stats.php")

        .then(response => response.json())

        .then(data => {

            console.log("Estadísticas:", data);

            if (data.status !== "success") {

                showToast(
                    data.message || "Error cargando estadísticas",
                    "error"
                );

                return;
            }


            document.getElementById(
                "totalClientes"
            ).textContent = data.clientes;


            document.getElementById(
                "totalBarberos"
            ).textContent = data.barberos;


            document.getElementById(
                "totalPendientes"
            ).textContent = data.pendientes;


            document.getElementById(
                "totalConfirmadas"
            ).textContent = data.confirmadas;

        })

        .catch(error => {

            console.error(
                "Error estadísticas:",
                error
            );

        });

}


// =====================================================
// CLIENTES
// =====================================================

function cargarClientes() {

    fetch("../api/get_admin_clients.php")

        .then(response => response.json())

        .then(data => {

            console.log("Clientes:", data);

            if (data.status !== "success") {

                showToast(
                    data.message || "Error cargando clientes",
                    "error"
                );

                return;
            }


            clientesData = data.data || [];

            renderClientes(clientesData);

        })

        .catch(error => {

            console.error(
                "Error clientes:",
                error
            );

            document.getElementById(
                "tablaClientes"
            ).innerHTML = `
                <tr>
                    <td colspan="4">
                        Error cargando clientes
                    </td>
                </tr>
            `;

        });

}


function renderClientes(clientes) {

    const tabla =
        document.getElementById("tablaClientes");


    if (!clientes.length) {

        tabla.innerHTML = `
            <tr>
                <td colspan="4">
                    No hay clientes registrados.
                </td>
            </tr>
        `;

        return;
    }


    let html = "";


    clientes.forEach(cliente => {

        const bloqueado =
            cliente.status === "blocked";


        html += `

        <tr>

            <td>

                <strong>
                    ${escapeHTML(cliente.name)}
                </strong>

            </td>


            <td>
                ${escapeHTML(cliente.email)}
            </td>


            <td>

                <span class="status ${
                    bloqueado
                    ? "rejected"
                    : "accepted"
                }">

                    ${
                        bloqueado
                        ? "Bloqueado"
                        : "Activo"
                    }

                </span>

            </td>


            <td>

                <button
                    class="btn-primary"
                    onclick="cambiarEstadoUsuario(
                        ${cliente.id},
                        '${bloqueado ? "active" : "blocked"}'
                    )"
                >

                    ${
                        bloqueado
                        ? "Activar"
                        : "Bloquear"
                    }

                </button>


                <button
                    class="btn-danger"
                    onclick="eliminarUsuario(${cliente.id})"
                >
                    Eliminar
                </button>

            </td>

        </tr>

        `;

    });


    tabla.innerHTML = html;

}

function cambiarEstadoUsuario(id, status) {

    const accion =
        status === "blocked"
        ? "bloquear"
        : "activar";


    const confirmar =
        confirm(
            `¿Seguro que deseas ${accion} este usuario?`
        );


    if (!confirmar) {
        return;
    }


    const fd = new FormData();

    fd.append("user_id", id);
    fd.append("status", status);


    fetch(
        "../api/admin_update_user_status.php",
        {
            method: "POST",
            body: fd
        }
    )

    .then(response => response.json())

    .then(data => {

        if (data.status === "success") {

            showToast(
                data.message,
                "success"
            );

            cargarClientes();
            cargarBarberos();

        } else {

            showToast(
                data.message,
                "error"
            );

        }

    })

    .catch(error => {

        console.error(error);

        showToast(
            "Error de conexión",
            "error"
        );

    });

}

function eliminarUsuario(id) {

    const confirmar =
        confirm(
            "⚠️ Esta acción eliminará el usuario. ¿Deseas continuar?"
        );


    if (!confirmar) {
        return;
    }


    const fd = new FormData();

    fd.append("user_id", id);


    fetch(
        "../api/admin_delete_user.php",
        {
            method: "POST",
            body: fd
        }
    )

    .then(response => response.json())

    .then(data => {

        if (data.status === "success") {

            showToast(
                data.message,
                "success"
            );

            cargarClientes();
            cargarBarberos();
            cargarEstadisticas();

        } else {

            showToast(
                data.message,
                "error"
            );

        }

    })

    .catch(error => {

        console.error(error);

        showToast(
            "Error de conexión",
            "error"
        );

    });

}

function filtrarClientes() {

    const texto =
        document
            .getElementById("buscarCliente")
            .value
            .toLowerCase();


    const filtrados =
        clientesData.filter(cliente => {

            return (

                cliente.name
                    .toLowerCase()
                    .includes(texto)

                ||

                cliente.email
                    .toLowerCase()
                    .includes(texto)

            );

        });


    renderClientes(filtrados);

}


function verCliente(id) {

    const cliente =
        clientesData.find(
            c => Number(c.id) === Number(id)
        );

    if (!cliente) return;


    showToast(
        `👤 ${escapeHTML(cliente.name)}<br>${escapeHTML(cliente.email)}`,
        "info"
    );

}


// =====================================================
// BARBEROS
// =====================================================

function cargarBarberos() {

    console.log("🔵 Cargando barberos...");

    fetch("../api/get_admin_barbers.php")

        .then(response => {

            console.log(
                "HTTP barberos:",
                response.status
            );

            return response.text();
        })

        .then(texto => {

            console.log(
                "Respuesta barberos:",
                texto
            );

            let data;

            try {
                data = JSON.parse(texto);
            } catch (error) {

                console.error(
                    "❌ Respuesta barberos no es JSON:",
                    texto
                );

                return;
            }

            if (data.status !== "success") {

                console.error(
                    "❌ Error barberos:",
                    data.message
                );

                return;
            }

            const barberos = data.data || [];

            console.log(
                "✅ Barberos encontrados:",
                barberos.length
            );

            renderBarberos(barberos);

            renderResumenBarberos(barberos);

        })

        .catch(error => {

            console.error(
                "❌ Error cargando barberos:",
                error
            );

        });
}


function renderBarberos(barberos) {

    const container =
        document.getElementById("listaBarberos");


    if (!barberos.length) {

        container.innerHTML = `
            <div class="panel-card">
                No hay barberos registrados.
            </div>
        `;

        return;
    }


    let html = "";


    barberos.forEach(barbero => {

        let foto =
            barbero.photo ||
            "https://i.pravatar.cc/150";


        html += `

        <div class="barber-card-admin">

            <img
                src="${foto}"
                alt="Foto de ${escapeHTML(barbero.name)}"
                onerror="this.src='https://i.pravatar.cc/150'"
            >

            <h3>
                ${escapeHTML(barbero.name)}
            </h3>

            <p>
                📧 ${escapeHTML(barbero.email || "Sin email")}
            </p>

            <p>
                ✂️ ${
                    escapeHTML(
                        barbero.specialty ||
                        "Sin especialidad"
                    )
                }
            </p>

            <p>
                ⭐ ${
                    barbero.experience ||
                    0
                } años de experiencia
            </p>

            <button
                class="btn-primary"
                onclick="verBarbero(${barbero.id})"
            >
                Ver perfil
            </button>

        </div>

        `;

    });


    container.innerHTML = html;

}


function verBarbero(id) {

    const barbero =
        barberosData.find(
            b => Number(b.id) === Number(id)
        );

    if (!barbero) return;


    showToast(
        `💈 ${escapeHTML(barbero.name)}<br>
         ✂️ ${escapeHTML(barbero.specialty || "Sin especialidad")}`,
        "info"
    );

}


// =====================================================
// RESERVAS
// =====================================================

let todasLasReservasAdmin = [];

function cargarReservasAdmin() {

    console.log("🔵 Cargando reservas...");

    fetch("../api/get_admin_bookings.php")

        .then(response => response.json())

        .then(data => {

            console.log("Reservas recibidas:", data);

            if (data.status !== "success") {

                console.error(data.message);

                return;
            }

            todasLasReservasAdmin = data.data || [];

            // Tabla completa
            renderReservasAdmin(
                todasLasReservasAdmin
            );

            // Últimas reservas del dashboard
            renderUltimasReservas(
                todasLasReservasAdmin
            );

        })

        .catch(error => {

            console.error(
                "❌ Error cargando reservas:",
                error
            );

        });
}


function renderReservasAdmin(reservas) {

    const container = document.getElementById("tablaReservas");

    console.log("Renderizando reservas:", reservas);

    if (!container) {
        console.error("❌ No existe #tablaReservas");
        return;
    }

    if (!reservas || reservas.length === 0) {

        container.innerHTML = `
            <div class="sin-reservas">
                <h3>📅 No hay reservas</h3>
                <p>No existen reservas para mostrar.</p>
            </div>
        `;

        return;
    }

    let html = `
        <div class="reserva-table-wrapper">

            <table class="reserva-table">

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Barbero</th>
                        <th>Servicio</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Precio</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
    `;

    reservas.forEach(reserva => {

        const estado = reserva.status || "pending";

        let estadoTexto = "Pendiente";

        if (estado === "accepted") {
            estadoTexto = "Aceptada";
        }

        if (estado === "rejected") {
            estadoTexto = "Rechazada";
        }

        if (estado === "cancelled") {
            estadoTexto = "Cancelada";
        }

        let fecha = reserva.booking_date || "";

        if (fecha) {
            const partes = fecha.split("-");

            if (partes.length === 3) {
                fecha = `${partes[2]}/${partes[1]}/${partes[0]}`;
            }
        }

        let hora = reserva.booking_time || "";

        // Quitar segundos: 16:00:00 → 16:00
        if (hora.length >= 5) {
            hora = hora.substring(0, 5);
        }

        html += `
            <tr>

                <td>
                    <strong>#${reserva.id}</strong>
                </td>

                <td>
                    <div class="reserva-info">
                        <strong>
                            ${escapeHTML(reserva.client_name || "Sin nombre")}
                        </strong>

                        <small>
                            ${escapeHTML(reserva.client_email || "")}
                        </small>
                    </div>
                </td>

                <td>
                    ${escapeHTML(reserva.barber_name || "Sin barbero")}
                </td>

                <td>
                    ${escapeHTML(reserva.service_name || "Sin servicio")}
                </td>

                <td class="reserva-fecha">
                    ${fecha}
                </td>

                <td class="reserva-hora">
                    ${hora}
                </td>

                <td>
                    R$ ${parseFloat(reserva.price || 0).toFixed(2)}
                </td>

                <td>

                    <select
                        class="admin-status-select estado-${estado}"
                        onchange="cambiarEstadoReserva(${reserva.id}, this.value)"
                    >

                        <option value="pending" ${estado === "pending" ? "selected" : ""}>
                            Pendiente
                        </option>

                        <option value="accepted" ${estado === "accepted" ? "selected" : ""}>
                            Aceptada
                        </option>

                        <option value="rejected" ${estado === "rejected" ? "selected" : ""}>
                            Rechazada
                        </option>

                        <option value="cancelled" ${estado === "cancelled" ? "selected" : ""}>
                            Cancelada
                        </option>

                    </select>

                </td>

                <td>

                    <button
                        class="btn-admin-action btn-ver-reserva"
                        onclick="verReservaAdmin(${reserva.id})"
                    >
                        Ver
                    </button>

                </td>

            </tr>
        `;
    });

    html += `
                </tbody>

            </table>

        </div>
    `;

    container.innerHTML = html;

    console.log("✅ Reservas renderizadas correctamente");
}


function filtrarReservasAdmin() {

    const texto = (
        document.getElementById("buscarReserva")?.value || ""
    ).toLowerCase().trim();

    const estado = (
        document.getElementById("filtroEstadoReserva")?.value || ""
    );

    const fecha = (
        document.getElementById("filtroFechaReserva")?.value || ""
    );

    const filtradas = todasLasReservasAdmin.filter(reserva => {

        const coincideTexto =
            !texto ||
            (reserva.client_name || "").toLowerCase().includes(texto) ||
            (reserva.client_email || "").toLowerCase().includes(texto) ||
            (reserva.barber_name || "").toLowerCase().includes(texto) ||
            (reserva.service_name || "").toLowerCase().includes(texto);

        const coincideEstado =
            !estado ||
            reserva.status === estado;

        const coincideFecha =
            !fecha ||
            reserva.booking_date === fecha;

        return (
            coincideTexto &&
            coincideEstado &&
            coincideFecha
        );
    });

    renderReservasAdmin(filtradas);
}

function cambiarEstadoReserva(bookingId, nuevoEstado) {

    const formData = new FormData();

    formData.append("booking_id", bookingId);
    formData.append("status", nuevoEstado);

    fetch("../api/admin_update_booking_status.php", {
        method: "POST",
        body: formData
    })

    .then(response => response.json())

    .then(data => {

        console.log("Cambio estado:", data);

        if (data.status === "success") {

            showToast(
                data.message,
                "success"
            );

            cargarReservasAdmin();
            cargarEstadisticas();

        } else {

            showToast(
                data.message || "No se pudo actualizar",
                "error"
            );

            cargarReservasAdmin();
        }

    })

    .catch(error => {

        console.error(error);

        showToast(
            "Error al actualizar la reserva",
            "error"
        );

        cargarReservasAdmin();
    });
}

function verReservaAdmin(id) {

    const reserva = todasLasReservasAdmin.find(
        r => parseInt(r.id) === parseInt(id)
    );

    if (!reserva) {
        showToast("No se encontró la reserva", "error");
        return;
    }

    const estados = {
        pending: "Pendiente",
        accepted: "Aceptada",
        rejected: "Rechazada",
        cancelled: "Cancelada"
    };

    const mensaje = `
        <strong>Reserva #${reserva.id}</strong><br><br>

        👤 <strong>Cliente:</strong>
        ${escapeHTML(reserva.client_name || "N/A")}<br>

        📧 <strong>Email:</strong>
        ${escapeHTML(reserva.client_email || "N/A")}<br><br>

        💈 <strong>Barbero:</strong>
        ${escapeHTML(reserva.barber_name || "N/A")}<br>

        ✂️ <strong>Servicio:</strong>
        ${escapeHTML(reserva.service_name || "N/A")}<br>

        💰 <strong>Precio:</strong>
        R$ ${parseFloat(reserva.price || 0).toFixed(2)}<br><br>

        📅 <strong>Fecha:</strong>
        ${formatearFechaAdmin(reserva.booking_date)}<br>

        🕐 <strong>Hora:</strong>
        ${escapeHTML(reserva.booking_time || "N/A")}<br>

        📌 <strong>Estado:</strong>
        ${estados[reserva.status] || reserva.status}
    `;

    showToast(mensaje, "info");
}

function formatearFechaAdmin(fecha) {

    if (!fecha) return "";

    const partes = fecha.split("-");

    if (partes.length !== 3) {
        return fecha;
    }

    return `${partes[2]}/${partes[1]}/${partes[0]}`;
}


function renderUltimasReservas(reservas) {

    const container = document.getElementById("ultimasReservas");

    if (!container) {
        console.error("❌ No existe #ultimasReservas");
        return;
    }

    if (!reservas || reservas.length === 0) {

        container.innerHTML = `
            <div class="sin-reservas">
                <p>No hay reservas todavía.</p>
            </div>
        `;

        return;
    }

    // Tomamos las 5 más recientes
    const ultimas = reservas.slice(0, 5);

    let html = "";

    ultimas.forEach(reserva => {

        let estadoTexto = "Pendiente";

        if (reserva.status === "accepted") {
            estadoTexto = "Aceptada";
        }

        if (reserva.status === "rejected") {
            estadoTexto = "Rechazada";
        }

        if (reserva.status === "cancelled") {
            estadoTexto = "Cancelada";
        }

        let fecha = reserva.booking_date || "";

        if (fecha) {
            const partes = fecha.split("-");

            if (partes.length === 3) {
                fecha = `${partes[2]}/${partes[1]}/${partes[0]}`;
            }
        }

        let hora = reserva.booking_time || "";

        if (hora.length >= 5) {
            hora = hora.substring(0, 5);
        }

        html += `
            <div class="ultima-reserva">

                <div class="ultima-reserva-info">

                    <strong>
                        ${escapeHTML(reserva.client_name || "Cliente")}
                    </strong>

                    <span>
                        ${escapeHTML(reserva.service_name || "Servicio")}
                    </span>

                    <small>
                        ${fecha} · ${hora}
                    </small>

                </div>

                <div class="ultima-reserva-right">

                    <strong>
                        ${escapeHTML(reserva.barber_name || "Sin barbero")}
                    </strong>

                    <span class="status ${reserva.status}">
                        ${estadoTexto}
                    </span>

                </div>

            </div>
        `;
    });

    container.innerHTML = html;

    console.log("✅ Últimas reservas cargadas");
}


// =====================================================
// SERVICIOS
// =====================================================

function cargarServicios() {

    fetch("../api/get_admin_services.php")

        .then(response => response.json())

        .then(data => {

            if (data.status !== "success") {

                showToast(
                    data.message || "Error cargando servicios",
                    "error"
                );

                return;
            }

            serviciosAdmin = data.data || [];

            renderServicios(serviciosAdmin);

        })

        .catch(error => {

            console.error(error);

            showToast(
                "Error al cargar servicios",
                "error"
            );

        });
}

function eliminarServicio(id) {

    const servicio = serviciosAdmin.find(
        s => parseInt(s.id) === parseInt(id)
    );

    if (!servicio) {
        return;
    }

    const confirmar = confirm(
        `¿Eliminar el servicio "${servicio.name}"?`
    );

    if (!confirmar) {
        return;
    }

    const formData = new FormData();

    formData.append("id", id);

    fetch("../api/admin_delete_service.php", {

        method: "POST",

        body: formData

    })

    .then(response => response.json())

    .then(data => {

        if (data.status === "success") {

            showToast(
                data.message,
                "success"
            );

            cargarServicios();

        } else {

            showToast(
                data.message || "No se pudo eliminar",
                "error"
            );

        }

    })

    .catch(error => {

        console.error(error);

        showToast(
            "Error al eliminar servicio",
            "error"
        );

    });
}


function renderServicios(servicios) {

    const container = document.getElementById("listaServicios");

    if (!container) return;

    if (!servicios || servicios.length === 0) {

        container.innerHTML = `
            <div class="sin-reservas">

                <h3>✂️ No hay servicios</h3>

                <p>
                    Crea el primer servicio para comenzar.
                </p>

            </div>
        `;

        return;
    }

    let html = `
        <div class="servicios-grid">
    `;

    servicios.forEach(servicio => {

        const precio = parseFloat(
            servicio.price || 0
        ).toFixed(2);

        html += `

            <div class="servicio-admin-card">

                <div class="servicio-icon">
                    ✂️
                </div>

                <div class="servicio-admin-info">

                    <h3>
                        ${escapeHTML(servicio.name)}
                    </h3>

                    <div class="servicio-precio">
                        R$ ${precio}
                    </div>

                    <div class="servicio-duracion">
                        ⏱️ ${servicio.duration} minutos
                    </div>

                </div>

                <div class="servicio-admin-actions">

                    <button
                        class="btn-admin-action btn-ver-reserva"
                        onclick="editarServicio(${servicio.id})"
                    >
                        ✏️ Editar
                    </button>

                    <button
                        class="btn-admin-action btn-eliminar"
                        onclick="eliminarServicio(${servicio.id})"
                    >
                        🗑️ Eliminar
                    </button>

                </div>

            </div>

        `;
    });

    html += `
        </div>
    `;

    container.innerHTML = html;
}

function abrirModalServicio() {

    document.getElementById("modalServicioTitulo")
        .textContent = "Nuevo servicio";

    document.getElementById("servicioId")
        .value = "";

    document.getElementById("servicioNombre")
        .value = "";

    document.getElementById("servicioPrecio")
        .value = "";

    document.getElementById("servicioDuracion")
        .value = "";

    document.getElementById("modalServicio")
        .classList.add("active");
}

function cerrarModalServicio() {

    document.getElementById("modalServicio")
        .classList.remove("active");
}

const formServicio = document.getElementById("formServicio");

if (formServicio) {

    formServicio.addEventListener("submit", function(e) {

        e.preventDefault();

        const id =
            document.getElementById("servicioId").value;

        const name =
            document.getElementById("servicioNombre").value.trim();

        const price =
            document.getElementById("servicioPrecio").value;

        const duration =
            document.getElementById("servicioDuracion").value;

        if (!name || !price || !duration) {

            showToast(
                "Completa todos los campos",
                "warning"
            );

            return;
        }

        const formData = new FormData();

        formData.append("name", name);
        formData.append("price", price);
        formData.append("duration", duration);

        let url;

        if (id) {

            url = "../api/admin_update_service.php";

            formData.append("id", id);

        } else {

            url = "../api/admin_create_service.php";

        }

        fetch(url, {
            method: "POST",
            body: formData
        })

        .then(response => response.json())

        .then(data => {

            console.log("Respuesta servicio:", data);

            if (data.status === "success") {

                showToast(
                    data.message,
                    "success"
                );

                cerrarModalServicio();

                cargarServicios();

            } else {

                showToast(
                    data.message || "Error al guardar",
                    "error"
                );

            }

        })

        .catch(error => {

            console.error(
                "Error guardando servicio:",
                error
            );

            showToast(
                "Error al guardar el servicio",
                "error"
            );

        });

    });

}

function editarServicio(id) {

    const servicio = serviciosAdmin.find(
        s => parseInt(s.id) === parseInt(id)
    );

    if (!servicio) {

        showToast(
            "No se encontró el servicio",
            "error"
        );

        return;
    }

    document.getElementById("modalServicioTitulo")
        .textContent = "Editar servicio";

    document.getElementById("servicioId")
        .value = servicio.id;

    document.getElementById("servicioNombre")
        .value = servicio.name;

    document.getElementById("servicioPrecio")
        .value = servicio.price;

    document.getElementById("servicioDuracion")
        .value = servicio.duration;

    document.getElementById("modalServicio")
        .classList.add("active");
}


// =====================================================
// RESUMEN DE BARBEROS
// =====================================================

function renderResumenBarberos(barberos) {

    const container = document.getElementById("resumenBarberos");

    if (!container) {
        console.error("❌ No existe #resumenBarberos");
        return;
    }

    if (!barberos || barberos.length === 0) {

        container.innerHTML = `
            <div class="sin-reservas">
                <p>No hay barberos registrados.</p>
            </div>
        `;

        return;
    }

    let html = "";

    barberos.forEach(barbero => {

        const nombre = barbero.name || "Barbero";

        const inicial =
            nombre.trim().charAt(0).toUpperCase();

        const foto = barbero.photo
            ? `../${barbero.photo}`
            : "";

        html += `

            <div class="barbero-resumen">

                <div class="barbero-avatar">

                    ${
                        foto
                        ?
                        `<img
                            src="${foto}"
                            alt="${escapeHTML(nombre)}"
                            onerror="this.style.display='none'; this.parentElement.innerHTML='${inicial}'"
                        >`
                        :
                        inicial
                    }

                </div>

                <div class="barbero-resumen-info">

                    <strong>
                        ${escapeHTML(nombre)}
                    </strong>

                    <span>
                        ${escapeHTML(
                            barbero.specialty || "Barbero profesional"
                        )}
                    </span>

                </div>

                <div class="barbero-estado">

                    ${
                        barbero.status === "blocked"
                        ?
                        `<span class="status rejected">Bloqueado</span>`
                        :
                        `<span class="status accepted">Activo</span>`
                    }

                </div>

            </div>

        `;
    });

    container.innerHTML = html;

    console.log("✅ Resumen de barberos cargado");
}


// =====================================================
// SEGURIDAD HTML
// =====================================================

function escapeHTML(text) {

    if (text === null || text === undefined) {
        return "";
    }

    return String(text)
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");

}


// =====================================================
// LOGOUT
// =====================================================

function logout() {

    fetch("../api/logout.php")

        .then(() => {

            window.location.href =
                "../login.html";

        })

        .catch(error => {

            console.error(
                "Error logout:",
                error
            );

        });

}


// =====================================================
// INICIALIZAR
// =====================================================

document.addEventListener(
    "DOMContentLoaded",
    function () {

        cargarEstadisticas();

        cargarClientes();

        cargarBarberos();

        cargarReservasAdmin();

        cargarServicios();

    }
);

</script>

<div id="modalServicio" class="modal-admin">

    <div class="modal-admin-content">

        <div class="modal-header">

            <div>
                <h2 id="modalServicioTitulo">
                    Nuevo servicio
                </h2>

                <p>
                    Completa los datos del servicio
                </p>
            </div>

            <button
                class="modal-close"
                onclick="cerrarModalServicio()"
            >
                ×
            </button>

        </div>

        <form id="formServicio">

            <input
                type="hidden"
                id="servicioId"
            >

            <label>
                Nombre del servicio
            </label>

            <input
                type="text"
                id="servicioNombre"
                placeholder="Ej: Corte Premium"
                required
            >

            <label>
                Precio
            </label>

            <input
                type="number"
                id="servicioPrecio"
                placeholder="40.00"
                min="0"
                step="0.01"
                required
            >

            <label>
                Duración (minutos)
            </label>

            <input
                type="number"
                id="servicioDuracion"
                placeholder="45"
                min="1"
                required
            >

            <div class="modal-actions">

                <button
                    type="button"
                    class="btn-secondary"
                    onclick="cerrarModalServicio()"
                >
                    Cancelar
                </button>

                <button
                    type="submit"
                    class="btn-primary"
                >
                    Guardar servicio
                </button>

            </div>

        </form>

    </div>

</div>

</body>

</html>