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
