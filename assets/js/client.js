document.addEventListener("DOMContentLoaded", () => {
  generarSemana();
  cargarBarberos();
});

function generarHoras() {
  let horas = [];
  for (let h = 9; h <= 18; h++) {
    horas.push(`${h.toString().padStart(2, '0')}:00`);
  }
  return horas;
}

// Función para bloquear los horarios ya reservados
function cargarHorarios() {
  const barber_id = document.getElementById("barber").value;
  const date = document.getElementById("date").value;

  fetch(`../api/get_booked_times.php?barber_id=${barber_id}&date=${date}`)
    .then(res => res.json())
    .then(ocupados => {

      const horas = generarHoras();
      let html = "";

      horas.forEach(hora => {
        const ocupado = ocupados.includes(hora + ":00") || ocupados.includes(hora);

        html += `
          <div class="card fade">
            <p><b>${b.client_name || ""}</b></p>
            <p>${b.date} - ${b.time}</p>
            <span class="status ${b.status}">${b.status}</span>
          </div>
          `;
      });

      document.getElementById("horarios").innerHTML = html;
    });
}

//selecionar hora
function cargarHorarios() {
  const barber_id = document.getElementById("barber").value;
  const date = document.getElementById("date").value;

  fetch(`../api/get_booked_times.php?barber_id=${barber_id}&date=${date}`)
    .then(res => res.json())
    .then(ocupados => {

      const horas = generarHoras();
      let html = "";

      horas.forEach(hora => {
        const ocupado = ocupados.includes(hora + ":00") || ocupados.includes(hora);

        html += `
          <button 
            onclick="seleccionarHora('${hora}')"
            style="
              margin:5px;
              padding:10px;
              border-radius:8px;
              border:none;
              cursor:${ocupado ? 'not-allowed' : 'pointer'};
              background:${ocupado ? '#555' : '#22c55e'};
            "
            ${ocupado ? 'disabled' : ''}
          >
            ${hora}
          </button>
        `;
      });

      document.getElementById("horarios").innerHTML = html;
    });
}

//funcion para crear reserva
function crearReserva() {
  if (!selectedDate || !selectedHour) {
   showToast(
"⚠ Debes seleccionar una fecha",
"warning"
);
    return;
  }

  const fd = new FormData();
  fd.append("barber_id", barber.value);
  fd.append("date", selectedDate);
  fd.append("time", selectedHour);

  fetch("../api/create_booking.php", {
    method: "POST",
    body: fd
  }).then(() => {
    showToast(
"✅ Reserva creada correctamente",
"success"
);
    cargarHorarios();
  });
}

let selectedDate = "";
let selectedHour = "";
//funcion para el calendario semanal
function generarSemana() {
  const calendar = document.getElementById("calendar");
  calendar.innerHTML = "";

  const today = new Date();

  for (let i = 0; i < 7; i++) {
    const day = new Date();
    day.setDate(today.getDate() + i);

    const fecha = day.toISOString().split("T")[0];

    const div = document.createElement("div");
    div.classList.add("day");

    div.innerHTML = `
      <strong>${day.toLocaleDateString("es-ES", { weekday: "short" })}</strong><br>
      ${day.getDate()}
    `;

    div.onclick = () => seleccionarDia(fecha, div);

    calendar.appendChild(div);
  }
}
//funcion para seleccionar dia
function seleccionarDia(fecha, elemento) {
  selectedDate = fecha;

  document.querySelectorAll(".day").forEach(d => d.classList.remove("active"));
  elemento.classList.add("active");

  cargarHorarios();
}
//funcion para generar los horios
function generarHoras() {
  let horas = [];
  for (let h = 9; h <= 18; h++) {
    horas.push(`${h.toString().padStart(2, '0')}:00`);
  }
  return horas;
}
//funcion para seleccionar hora
function seleccionarHora(hora) {
  selectedHour = hora;

  document.querySelectorAll("#horarios button").forEach(btn => {
    btn.style.border = "none";
  });

  event.target.style.border = "2px solid yellow";
}