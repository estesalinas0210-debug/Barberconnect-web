<?php session_start(); 

  if ($_SESSION['user']['role'] != 'barber') {
    header("Location: ../login.html");
    exit;
}

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Dashboard - BarberApp</title>
  <h2>
      Bienvenido,
      <?php echo $_SESSION['user']['name']; ?>
  </h2>
</head>
<body>

<link rel="stylesheet" href="../assets/css/style.css">

<div class="navbar">
  <div class="logo">✂️ Panel Barbero</div>
  <button class="btn-danger" onclick="logout()">Salir</button>
</div>

<div class="container fade">

  <div class="card">
    <h2>Reservas</h2>
    <div id="reservas"></div>
  </div>

</div>

<script>
function cargarReservas() {
  fetch("../api/get_bookings.php")
    .then(res => res.json())
    .then(data => {
      console.log("RESERVAS:");
      console.log(data);
      let html = "";
      data.forEach(b => {
        html += `
<div class="card">
  <p><b>${b.client_name}</b></p>

  <p>
    📅 ${b.booking_date}
  </p>

  <p>
    ⏰ ${b.booking_time}
  </p>

  <span class="status ${b.status}">
    ${b.status}
  </span>

  ${b.status === 'pending' ? `
    <button class="btn-success"
      onclick="update(${b.id}, 'accepted')">
      Aceptar
    </button>

    <button class="btn-danger"
      onclick="update(${b.id}, 'rejected')">
      Rechazar
    </button>
  ` : ''}
</div>`;
      });
      document.getElementById("reservas").innerHTML = html;
    });
}

function update(id, status) {

  const fd = new FormData();

  fd.append("id", id);
  fd.append("status", status);

  fetch("../api/update_booking.php", {
    method: "POST",
    body: fd
  })
  .then(res => res.json())
  .then(data => {

    alert(data.message);

    if (data.status === "success") {
      cargarReservas();
    }

  })
  .catch(error => {
    console.error(error);
    alert("Error al actualizar reserva");
  });

}

function logout() {
  fetch("../api/logout.php").then(() => {
    window.location.href = "../login.html";
  });
}

cargarReservas();
</script>

</body>
</html>