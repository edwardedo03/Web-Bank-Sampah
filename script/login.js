$("#login-form").on("submit", function (e) {
  e.preventDefault();
  let invalid = false;

  const dataLogin = {
    username: $("#username").val(),
    password: $("#password").val(),
  };

  for (const key in dataLogin) {
    if (dataLogin[key] === null || dataLogin[key].trim() === "") {
      invalid = true;
      break;
    }
  }

  if (invalid) {
    alert("Tolong isi seluruh form!");
    return;
  } else {
    $.ajax({
      url: "../backend/database/login_db.php",
      type: "POST",
      contentType: "application/json",
      data: JSON.stringify(dataLogin),
      success: function (res) {
        if (res.success) {
          sessionStorage.setItem("username", res.user.username);

          setTimeout(() => {
            if (res.user.role === "admin") {
              window.location.href = "../pages/admin/admin_dashboard.html";
            } else if (res.user.role === "petugas") {
              window.location.href = "../pages/petugas/petugas_dashboard.html";
            } else {
              window.location.href = "../index.html";
            }
          }, 1000);
        } else {
          alert(res.message);
        }
      },
      error: function (xhr, status, error) {
        console.log(xhr.responseText);
      },
    });
  }
});
