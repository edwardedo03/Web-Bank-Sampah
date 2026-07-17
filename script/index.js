const username = sessionStorage.getItem("username");
const idAkun = sessionStorage.getItem("id_akun");

console.log("id_akun:", idAkun);

if (!username || !idAkun) {
  window.location.href = "./pages/login.html";
} else {
  $("#nama-user").text(`${username}`);
}

$("#logout").on("click", () => {
  sessionStorage.clear();

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
