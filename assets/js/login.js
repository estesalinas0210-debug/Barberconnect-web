function login() {
  
  const formData = new FormData();
  formData.append("email", document.getElementById("email").value);
  formData.append("password", document.getElementById("password").value);

  fetch("api/login.php", {
    method: "POST",
    body: formData
  })
  .then(res => res.json())
  .then(data => {
    console.log(data);

    if (data.status === "ok") {
      if (data.role === "client") {
        window.location.href = "client/dashboard.php";
      } else {
        window.location.href = "barber/dashboard.php";
      }
    } else {
      alert("Credenciales incorrectas");
    }
  })
  .catch(error => {
    console.error(error);
  });
  
}