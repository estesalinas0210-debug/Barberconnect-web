console.log("NUEVA VERSION DEL SCRIPT DE REGISTRO");

function register() {

  const name = document.getElementById("name").value;
  const email = document.getElementById("email").value;
  const password = document.getElementById("password").value;
  const confirmPassword = document.getElementById("confirmPassword").value;
  const role = document.getElementById("role").value;

  const message = document.getElementById("message");

  if (password !== confirmPassword) {
    message.style.display = "block";
    message.className = "message error";
    message.innerText = "Las contraseñas no coinciden";
    return;
  }

  const formData = new FormData();

  formData.append("name", name);
  formData.append("email", email);
  formData.append("password", password);
  formData.append("role", role);

  fetch("api/register.php", {
    method: "POST",
    body: formData
  })
  .then(res => res.json())
  .then(data => {

    console.log(data);

    message.style.display = "block";
    message.innerText = data.message;

    if (data.status === "success") {

      message.className = "message success";

      setTimeout(() => {
        window.location.href = "login.html";
      }, 2000);

    } else {

      message.className = "message error";

    }

  })
  .catch(error => {

    console.error(error);

    message.style.display = "block";
    message.className = "message error";
    message.innerText = "Ocurrió un error al registrar";

  });
}