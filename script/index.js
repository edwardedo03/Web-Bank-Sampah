const username = sessionStorage.getItem("username");

if (!username) {
  window.location.href = "./pages/login.html";
} else {
  $("#nama-user").text(`${username}`);
}

$("#logout").on("click", () => {
  sessionStorage.removeItem("username");

  window.location.href = "./pages/login.html";
});
