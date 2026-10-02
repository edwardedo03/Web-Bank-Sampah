import { getLogoutFunction } from "../../backend/global_function.js";
import { postPetugasProfile } from "../../backend/global_function.js";

const username = sessionStorage.getItem("username");
const idAkun = sessionStorage.getItem("id_akun");

if (!username || !idAkun) {
  window.location.href = "../login.html";
} else {
  $("#nama-user").text(`${username}`);
}

getLogoutFunction("../..");

$.ajax({
  url: `../../backend/database/petugas/get_profile_petugas.php`,
  type: "GET",
  dataType: "json",
  data: { id_akun: idAkun },
  success: function (res) {
    if (res.success) {
      const user = res.data_petugas;

      const tanggalMentah = user.tanggal_bergabung;
      const objekTanggal = new Date(tanggalMentah);

      const tanggalIndonesia = new Intl.DateTimeFormat("id-ID", {
        day: "numeric",
        month: "long",
        year: "numeric",
      }).format(objekTanggal);

      let noTelepon = user.no_telepon_petugas
        ? user.no_telepon_petugas.toString().trim()
        : "";

      if (noTelepon.startsWith("0")) {
        noTelepon = noTelepon.substring(1);
      }

      $("#username-title").text(user.username_petugas);
      $("#id-petugas").text(user.id_petugas);
      $("#tanggal-bergabung-petugas").text(tanggalIndonesia);
      $("#username").val(user.username_petugas);
      $("#nama").val(user.nama_petugas);
      $("#email").val(user.email_petugas);
      $("#no-telepon").val(noTelepon);
      $("#wilayah-tugas").val(user.wilayah_tugas);
    }
  },
  error: function (xhr) {
    console.log("Error", xhr.responseText);
  },
});

$("#form-update-profile").on("submit", (e) => {
  e.preventDefault();

  const dataProfile = {
    id_akun: idAkun,
    namaLengkap: $("#nama").val(),
    email: $("#email").val(),
    nomorTelepon: "0" + $("#no-telepon").val(),
    wilayahTugas: $("#wilayah-tugas").val(),
  };

  postPetugasProfile(dataProfile, "../..");
});
