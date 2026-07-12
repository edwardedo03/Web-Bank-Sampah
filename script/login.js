$("#login-form").on("submit", function (e) {
  e.preventDefault();

  const usernameValue = $("#username").val();
  sessionStorage.setItem("username", usernameValue);

  window.location.href = "../index.html";
});
