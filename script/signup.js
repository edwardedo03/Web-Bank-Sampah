$("#signup-form").on("submit", function (e) {
  e.preventDefault();
  let invalid = false;

  const dataSignup = {
    email: $("#email").val(),
    username: $("#username").val(),
    password: $("#password").val(),
    role: $("#role").val(),
  };

  if ($("#konfirmasi-password").val().trim() === "") {
    alert("Mohon Mengisi Verifikasi Kata Sandi");
    return;
  } else if (dataSignup.password !== $("#konfirmasi-password").val()) {
    alert("Verifikasi Kata Sandi Berbeda");
    return;
  }

  for (const key in dataSignup) {
    if (dataSignup[key] === null || dataSignup[key].trim() === "") {
      invalid = true;
      break;
    }
  }

  if (invalid) {
    alert("Tolong isi seluruh form!");
    return;
  } else {
    $.ajax({
      url: "../backend/database/signup_db.php",
      type: "POST",
      contentType: "application/json",
      data: JSON.stringify(dataSignup),
      success: function (res) {
        if (res.success) {
          alert("Akun berhasil dibuat");

          $("#signup-form")[0].reset();

          setTimeout(() => {
            window.location.href = "../pages/login.html";
          }, 1000);
        } else {
          alert("gagal: " + res.message);
        }
      },

      error: function (xhr, status, error) {
        console.log("Status:", xhr.status);
        console.log("Response:", xhr.responseText);
        console.log("Error:", error);
      },
    });
  }
});
