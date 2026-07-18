// Get Tabungan
export function getTabungan(idHTML, idAkun, rootPath = ".") {
  return $.ajax({
    url: `${rootPath}/backend/database/nasabah/get_tabungan.php`,
    type: "GET",
    dataType: "json",
    data: { id_akun: idAkun },
    success: function (res) {
      if (res.success) {
        console.log("berhasil dapat tabungan nasabah");
        const tabunganNasabah = res.jumlah_tabungan;

        $(`#${idHTML}`).text(Number(tabunganNasabah).toLocaleString("id-ID"));
      } else {
        console.log("Gagal mengambil tabungan:", res.message);
      }
    },

    error: function (xhr, status, error) {
      console.log("Gagal mengambil tabungan:", status, error, xhr.responseText);
    },
  });
}

// Navbar Link

export function getLogoutFunction(rootPath = ".") {
  return $("#logout").on("click", () => {
    sessionStorage.clear();

    window.location.href = `${rootPath}/pages/login.html`;
  });
}

// Get Total Sampah Nasabah

export function getTotalSampah(idHTML, idAkun, rootPath = ".") {
  return $.ajax({
    url: `${rootPath}/backend/database/nasabah/get_total_sampah.php`,
    type: "GET",
    dataType: "json",
    data: { id_akun: idAkun },
    success: function (res) {
      if (res.success) {
        console.log("berhasil dapat total sampah nasabah");
        const totalSampahnNasabah = res.total_sampah;

        $(`#${idHTML}`).text(totalSampahnNasabah);
      } else {
        console.log("Gagal mengambil total sampah:", res.message);
      }
    },

    error: function (xhr, status, error) {
      console.log(
        "Gagal mengambil total sampah:",
        status,
        error,
        xhr.responseText,
      );
    },
  });
}

// GET Total Setoran

export function getTotalSetoran(idHTML, idAkun, rootPath = ".") {
  return $.ajax({
    url: `${rootPath}/backend/database/nasabah/get_jumlah_setoran.php`,
    type: "GET",
    dataType: "json",
    data: { id_akun: idAkun },
    success: function (res) {
      if (res.success) {
        console.log("berhasil dapat total setoran nasabah");
        const totalSetoranNasabah = res.jumlah_setoran;

        $(`#${idHTML}`).text(totalSetoranNasabah);
      } else {
        console.log("Gagal mengambil total setoran:", res.message);
      }
    },

    error: function (xhr, status, error) {
      console.log(
        "Gagal mengambil total setoran:",
        status,
        error,
        xhr.responseText,
      );
    },
  });
}

// Get Profile Nasabah

export function getNasabahProfile(idAkun, rootPath = "") {
  return $.ajax({
    url: `${rootPath}/backend/database/nasabah/get_profile_nasabah.php`,
    type: "GET",
    dataType: "json",
    data: { id_akun: idAkun },
    success: function (res) {
      if (res.success) {
        const user = res.data_nasabah;

        const tanggalMentah = user.tanggal_bergabung;
        const objekTanggal = new Date(tanggalMentah);

        const tanggalIndonesia = new Intl.DateTimeFormat("id-ID", {
          day: "numeric",
          month: "long",
          year: "numeric",
        }).format(objekTanggal);

        let noTelepon = user.no_telepon_nasabah
          ? user.no_telepon_nasabah.toString().trim()
          : "";

        if (noTelepon.startsWith("0")) {
          noTelepon = noTelepon.substring(1);
        }

        $("#username-title").text(user.username_nasabah);
        $("#id-nasabah").text(user.id_nasabah);
        $("#tanggal-bergabung-nasabah").text(tanggalIndonesia);
        $("#username").val(user.username_nasabah);
        $("#nama").val(user.nama_nasabah);
        $("#email").val(user.email_nasabah);
        $("#no-telepon").val(noTelepon);
        $("#alamat").val(user.alamat_nasabah);
        $("#rt").val(user.rt);
        $("#rw").val(user.rw);
        $("#kelurahan").val(user.kelurahan);
        $("#kecamatan").val(user.kecamatan);
      }
    },
    error: function (xhr) {
      console.log("Error", xhr.responseText);
    },
  });
}

// POST Profile Nasabah

export function postNasabahProfile(dataProfile, rootPath = ".") {
  return $.ajax({
    url: `${rootPath}/backend/database/nasabah/post_profile_nasabah.php`,
    type: "POST",
    contentType: "application/json",
    dataType: "json",
    data: JSON.stringify(dataProfile),
    success: function (res) {
      if (res.success) {
        console.log("Success", res.message);

        window.location.reload();
      }
    },
    error: function (xhr) {
      console.log("Error", xhr.responseText);
    },
  });
}

// GET Detail Transaksi

export function getDetailTransaksi(idAKun, rootPath = ".") {
  return $.ajax({
    url: `${rootPath}/backend/database/transaksi/get_detail_transaksi.php`,
    type: "GET",
    dataType: "json",
    data: { id_akun: idAkun },
    success: function (res) {
      if (res.success && res.history.length > 0) {
        res.history.forEach((item) => {
          const dateTime = new Date(item.tanggal_penyerahan);

          const formatTanggal = {
            day: "numeric",
            month: "long",
            year: "numeric",
          };
          const tanggal = new Intl.DateTimeFormat(
            "id-ID",
            formatTanggal,
          ).format(dateTime);

          const formatWaktu = {
            hour: "2-digit",
            minute: "2-digit",
          };
          const waktu = new Intl.DateTimeFormat("id-ID", formatWaktu).format(
            dateTime,
          );
        });
      }
    },
    error: function (xhr) {
      console.log("Error", xhr.responseText);
    },
  });
}
