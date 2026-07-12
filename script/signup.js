$("#signup-form").on("submit", function (e) {
  e.preventDefault();

  const dataSignup = {
    email: $("#email").val(),
    username: $("#username").val(),
    password: $("#password").val(),
    role: $("#role").val(),
  };

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
});
