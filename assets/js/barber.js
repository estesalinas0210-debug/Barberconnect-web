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