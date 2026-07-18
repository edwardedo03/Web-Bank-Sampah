import { getTabungan } from "../backend/global_function.js";
import { getLogoutFunction } from "../backend/global_function.js";

const username = sessionStorage.getItem("username");
const idAkun = sessionStorage.getItem("id_akun");

console.log("id_akun:", idAkun);

const waktu = new Date();
const jam = waktu.getHours();
const menit = waktu.getMinutes();
const waktuSekarang = jam * 100 + menit;

if (waktuSekarang > 0 && waktuSekarang <= 1130) {
  $("#waktu").text("Selamat Pagi, ");
} else if (waktuSekarang > 1130 && waktuSekarang <= 1500) {
  $("#waktu").text("Selamat Siang, ");
} else if (waktuSekarang > 1500 && waktuSekarang <= 1830) {
  $("#waktu").text("Selamat Sore, ");
} else {
  $("#waktu").text("Selamat Malam, ");
}

if (!username || !idAkun) {
  window.location.href = "./pages/login.html";
} else {
  $("#nama-user").text(`${username}`);
}

$("#setor-sampah-dashboard").on("click", function () {
  window.location.href = "./pages/nasabah/setor_sampah.html";
});

$("#riwayat-transaksi-dashboard").on("click", function () {
  window.location.href = "./pages/nasabah/riwayat_transaksi.html";
});

$("#tarik-saldo-dashboard").on("click", function () {
  window.location.href = "./pages/nasabah/riwayat_transaksi.html";
});

getLogoutFunction(".");

getTabungan("saldo-user", idAkun, ".");
