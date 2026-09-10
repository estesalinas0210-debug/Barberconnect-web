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
<div id="toastContainer"></div>
<body>

<link rel="stylesheet" href="../assets/css/style.css">

<div class="navbar">

  <div class="logo">
    💈 BarberConnect
  </div>

  <div class="notification-wrapper">

      <button
        class="bell-btn"
        onclick="toggleNotifications()"
      >
        🔔
        <span id="notificationCount">
          0
        </span>
      </button>

  </div>

  <button
    class="btn-danger"
    onclick="logout()"
  >
    Salir
  </button>

</div>

<div
  id="notificationPanel"
  class="notification-panel"
  style="display:none;"
>

    <h3>Notificaciones</h3>

    <div id="notifications"></div>

</div>

<div class="container fade">

  <div class="card">
    <h2>Reservas</h2>
    <div id="reservas"></div>
  </div>

</div>

<div class="card">

  <h2>Mi Perfil</h2>
  <h3>Foto de Perfil</h3>
  <input
    type="file"
    id="photo"
    accept="image/*"
  >
  <img
    id="preview"
    class="barber-photo"
    style="display:none;"
  >
  <h3>Biografía</h3>
  <textarea
    id="bio"
    placeholder="Describe tu experiencia"
  ></textarea>
  <h3>Especialidad</h3>
  <input
    type="text"
    id="specialty"
    placeholder="Especialidad"
  >
  <h3>Experiencia</h3>
  <input
    type="number"
    id="experience"
    placeholder="Años de experiencia"
  >

  <button
    class="btn-primary"
    onclick="guardarPerfil()"
  >
    Guardar Perfil
  </button>

</div>

<script>

function showToast(message,type="info"){

    const toast=document.createElement("div");

    toast.className=`toast ${type}`;

    toast.innerHTML=message;

    document
    .getElementById("toastContainer")
    .appendChild(toast);

    setTimeout(()=>{

        toast.classList.add("hide");

        setTimeout(()=>{

            toast.remove();

        },300);

    },3500);

}

const notificationSound =
new Audio("../assets/sounds/notification.mp3");

notificationSound.volume = 0.6;

let ultimoId = 0;
let primeraCarga = true;

document.addEventListener("DOMContentLoaded", () => {

    if (Notification.permission !== "granted") {
        Notification.requestPermission();
    }

    cargarNotificaciones();

    setInterval(() => {
        cargarNotificaciones();
    }, 3000);

});

function cargarNotificaciones(){

    fetch("../api/get_notifications.php")
    .then(res => res.json())
    .then(data => {

        let html = "";
        let unread = 0;

        if(data.length > 0){

            const nuevoId = parseInt(data[0].id);

            if(!primeraCarga && nuevoId > ultimoId){

                // 🔊 Sonido
                notificationSound.play().catch(()=>{});

                // 🔔 Animar campana
                const bell =
                document.querySelector(".bell-btn");

                if(bell){

                    bell.classList.add("bell-ring");

                    setTimeout(()=>{
                        bell.classList.remove("bell-ring");
                    },700);

                }

                // 🏷 Cambiar título
                document.title = "🔔 Nueva notificación";

                // 💻 Notificación del navegador
                if(Notification.permission === "granted"){

                    const n = new Notification(
                        "💈 BarberConnect",
                        {
                            body:data[0].message,
                            icon:"../assets/images/logo.png"
                        }
                    );

                    n.onclick = () => {

                        window.focus();

                        document.title = "BarberConnect";

                    };

                }

            }

            ultimoId = nuevoId;

        }

        primeraCarga = false;

        data.forEach(n => {

            if(n.is_read == 0){
                unread++;
            }

            html += `
            <div class="notification-item ${n.is_read==0?'unread':''}">
                ${n.message}
                <br>
                <small>${n.created_at}</small>
            </div>
            `;

        });

        document.getElementById("notifications").innerHTML = html;

        document.getElementById("notificationCount").innerText = unread;

    });

}

function toggleNotifications(){

    const panel =
    document.getElementById("notificationPanel");

    if(panel.style.display=="none" || panel.style.display==""){

        panel.style.display="block";

        fetch("../api/read_notifications.php")
        .then(()=>{

            cargarNotificaciones();

            document.title="BarberConnect";

        });

    }else{

        panel.style.display="none";

    }

}

function cargarPerfil() {

    fetch("../api/get_my_profile.php")
    .then(res => res.json())
    .then(profile => {

        if(!profile) return;

        if(profile.photo){

    const preview =
    document.getElementById("preview");

    preview.src = "../" + profile.photo;
    preview.style.display = "block";

}

        document.getElementById("bio").value =
            profile.bio || "";

        document.getElementById("specialty").value =
            profile.specialty || "";

        document.getElementById("experience").value =
            profile.experience || "";

    });

}

function guardarPerfil() {

    const fd = new FormData();

    const file =
    document.getElementById("photo").files[0];

    if(file){
        fd.append("photo", file);
    }

    fd.append(
      "bio",
      document.getElementById("bio").value
    );

    fd.append(
      "specialty",
      document.getElementById("specialty").value
    );

    fd.append(
      "experience",
      document.getElementById("experience").value
    );

    fetch(
      "../api/save_profile.php",
      {
        method:"POST",
        body:fd
      }
    )
    .then(res => res.json())
    .then(data => {

    if(data.status === "success"){

        alert(data.message);

        cargarPerfilBarbero();

    }else{

        alert("Error al guardar el perfil");

    }

});

}

document
.getElementById("photo")
.addEventListener("change", function(){

    const file = this.files[0];

    if(!file) return;

    const preview =
    document.getElementById("preview");

    preview.src =
    URL.createObjectURL(file);

    preview.style.display =
    "block";

});

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

document
.getElementById("photo")
.addEventListener("change", function(){

    const file =
    this.files[0];

    if(!file) return;

    const reader =
    new FileReader();

    reader.onload = function(e){

        const preview =
        document.getElementById(
          "preview"
        );

        preview.src = e.target.result;
        preview.style.display = "block";

    }

    reader.readAsDataURL(file);

});

cargarReservas();
cargarPerfil();
</script>

</body>
</html>