const notificationSound =
new Audio(
  "../assets/sounds/notification.mp3"
);

let ultimoId = 0;

if(data.length > 0){

    const nuevoId = parseInt(data[0].id);

    if(
        ultimoId > 0 &&
        nuevoId > ultimoId
    ){
        notificationSound.play();
    }

    ultimoId = nuevoId;
}

if (
  Notification.permission !== "granted"
){
  Notification.requestPermission();
}

new Notification(
  "BarberConnect",
  {
    body:
    "Nueva reserva recibida"
  }
);

document
.getElementById("barber")
.addEventListener("change", cargarPerfilBarbero);

function cargarPerfilBarbero(){

    const barber =
    document.getElementById("barber").value;

    fetch(
        `../api/get_barber_profile.php?id=${barber}`
    )
    .then(res=>res.json())
    .then(data=>{

        document.getElementById(
          "barberProfile"
        ).innerHTML = `
        <div class="barber-card">

            <img src="../uploads/${data.photo}">

            <h3>${data.specialty}</h3>

            <p>${data.description}</p>

            <p>
            💰 $${data.price}
            </p>

            <p>
            ⭐ ${data.experience}
            </p>

        </div>
        `;

    });

}