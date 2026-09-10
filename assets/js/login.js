function login() {

    const email =
        document.getElementById("email").value.trim();

    const password =
        document.getElementById("password").value;


    if (!email || !password) {

        alert("Completa todos los campos");

        return;
    }


    const formData = new FormData();

    formData.append("email", email);
    formData.append("password", password);


    fetch("api/login.php", {

        method: "POST",
        body: formData

    })

    .then(response => response.json())

    .then(data => {

        console.log("Respuesta login:", data);


        if (data.status === "ok") {

            if (data.role === "admin") {

                window.location.href =
                    "admin/dashboard.php";


            } else if (data.role === "barber") {

                window.location.href =
                    "barber/dashboard.php";


            } else {

                window.location.href =
                    "client/dashboard.php";

            }


        } else {

            alert(
                data.message ||
                "Correo o contraseña incorrectos"
            );

        }

    })

    .catch(error => {

        console.error("Error login:", error);

        alert(
            "Ocurrió un error al iniciar sesión"
        );

    });

}