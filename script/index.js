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

$("#setor-sampah-dashboard").on("click", function () {
  window.location.href = "./pages/nasabah/setor_sampah.html";
});

$("#riwayat-transaksi-dashboard").on("click", function () {
  window.location.href = "./pages/nasabah/riwayat_transaksi.html";
});

$("#tarik-saldo-dashboard").on("click", function () {
  window.location.href = "./pages/nasabah/riwayat_transaksi.html";
});
