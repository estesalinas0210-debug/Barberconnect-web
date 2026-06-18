<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../login.html");
    exit;
}

if ($_SESSION['user']['role'] != 'client') {
    header("Location: ../login.html");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
  <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
  <meta charset="UTF-8">
  <title>Dashboard - BarberApp</title>
  <h2>
      Bienvenido,
      <?php echo $_SESSION['user']['name']; ?>
  </h2>
</head>
<body>
<link rel="stylesheet" href="../assets/css/style.css">
<link rel="stylesheet" href="../assets/css/style.css">

<div class="navbar">
  <div class="logo">💈 Barberconnect</div>
  <button class="btn-danger" onclick="logout()">Salir</button>
</div>

<div class="container fade">

  <div class="card">
    <h2>Reservar turno</h2>

    <select id="barber"></select>
    <h3>Servicio</h3>

    <select id="service"></select>

    <div id="serviceInfo"></div>
    <div id="barberProfile"></div>

    <div id="horarios"></div>
    
  <div id="calendar"></div>
  <input type="hidden" id="date">
    <button class="btn-primary" onclick="crearReserva()">Confirmar</button>
  </div>

</div>

<script>
function cargarServicios() {

    fetch("../api/get_services.php")
    .then(res => res.json())
    .then(data => {

        let html = "";

        data.forEach(service => {

            html += `
            <option value="${service.id}"
                    data-price="${service.price}"
                    data-duration="${service.duration}">
                ${service.name}
            </option>
            `;

        });

        document.getElementById("service").innerHTML = html;
        document.getElementById("service")
        .addEventListener(
            "change",
            mostrarServicio
);

        mostrarServicio();

    });

}

function mostrarServicio() {

    const select =
    document.getElementById("service");

    const option =
    select.options[select.selectedIndex];

    document.getElementById(
      "serviceInfo"
    ).innerHTML = `
        <p>💰 Precio: $${option.dataset.price}</p>
        <p>⏱ Duración: ${option.dataset.duration} min</p>
    `;

}

cargarReservas();

setInterval(() => {
  cargarReservas();
}, 5000);
function cargarBarberos() {
  fetch("../api/get_barbers.php")
    .then(res => res.json())
    .then(data => {
      let html = "";
      data.forEach(b => {
        html += `<option value="${b.id}">${b.name}</option>`;
      });
      document.getElementById("barber").innerHTML = html;
    });
}

function crearReserva() {

  if (!document.getElementById("date").value) {
      alert("Selecciona una fecha");
      return;
  }

  if (!horaSeleccionada) {
      alert("Selecciona una hora");
      return;
  }

  const fd = new FormData();

  fd.append(
    "service_id",
    document.getElementById("service").value
);

  fd.append(
      "barber_id",
      document.getElementById("barber").value
  );

  fd.append(
      "date",
      document.getElementById("date").value
  );

  fd.append(
      "time",
      horaSeleccionada
  );

  fetch("../api/create_booking.php", {
      method: "POST",
      body: fd
  })
  .then(res => res.json())
  .then(data => {

      alert("Reserva creada correctamente");

      cargarReservas();
      cargarHorarios();

  });

}

function cargarReservas() {
  fetch("../api/get_my_bookings.php")
    .then(res => res.json())
    .then(data => {
      let html = "";
      data.forEach(b => {
        html += `
        <div class="card">
          <p><b>${b.barber_name}</b></p>
          <p>${b.booking_date} - ${b.booking_time}</p>
          <span class="status ${b.status}">
            ${b.status}
          </span>
        </div>`;
      });
    });
}

function cargarHorarios() {

    const barber =
        document.getElementById("barber").value;

    const date =
        document.getElementById("date").value;

    fetch(
        `../api/get_booked_hours.php?barber_id=${barber}&date=${date}`
    )
    .then(res => res.json())
    .then(booked => {

        const horarios = [];

        for(let h=9; h<18; h++){

        horarios.push(
        `${String(h).padStart(2,'0')}:00:00`
        );

        horarios.push(
        `${String(h).padStart(2,'0')}:30:00`
        );

}

        let html = "";

        horarios.forEach(hora => {

            const ocupado =
                booked.includes(hora);

            html += `
            <button
                class="hora-btn ${ocupado ? 'ocupado' : ''}"
                ${ocupado ? 'disabled' : ''}
                onclick="seleccionarHora('${hora}')"
            >
                ${hora.substring(0,5)}
            </button>
            `;

        });

        document.getElementById("horarios").innerHTML = html;

    });

}

let horaSeleccionada = "";

    function seleccionarHora(hora){

    horaSeleccionada = hora;

    document
    .querySelectorAll(".hora-btn")
    .forEach(btn => {

        btn.classList.remove("selected");

        if(
            btn.innerText === hora.substring(0,5)
        ){
            btn.classList.add("selected");
        }

    });

}

window.seleccionarHora = seleccionarHora;

function logout() {
  fetch("../api/logout.php").then(() => {
    window.location.href = "../login.html";
  });
}

cargarBarberos();
cargarReservas();
cargarServicios();
document.addEventListener('DOMContentLoaded', function () {

    const calendarEl = document.getElementById('calendar');

    const calendar = new FullCalendar.Calendar(
    calendarEl,
    {

    initialView:'dayGridMonth',

    locale:'es',

    events:
    '../api/get_calendar_events.php',

    dateClick:function(info){

        document
        .getElementById("date")
        .value = info.dateStr;

        cargarHorarios();

    }

});

    calendar.render();

});
</script>

</div>
</body>
</html>