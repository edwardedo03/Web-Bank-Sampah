const username = sessionStorage.getItem("username");
const idAkun = sessionStorage.getItem("id_akun");

if (!username || !idAkun) {
  window.location.href = "../../pages/login.html";
}

$("#logout").on("click", () => {
  sessionStorage.clear();

  window.location.href = "../../pages/login.html";
});

let listSampah = [];

$.ajax({
  url: "../../backend/database/sampah/get_sampah.php",
  type: "GET",
  success: function (res) {
    if (res.success) {
      listSampah = res.data;
    } else {
      console.log("Gagal mengambil data dari db", res.message);
    }
  },
  error: function (xhr, status, error) {
    console.log("Error mengambil data sampah");
    console.log(xhr.responseText);
  },
});

// let totalBerat = 0; // berat total dalam satu setoran
// let totalNominal = 0; // seluruh sampah dalam satu setoran

//

$(document).ready(function () {
  const sekarang = new Date();

  const yyyy = sekarang.getFullYear();
  let mm = sekarang.getMonth() + 1;
  let dd = sekarang.getDate();
  let hh = sekarang.getHours();
  let min = sekarang.getMinutes();

  if (mm < 10) mm = "0" + mm;
  if (dd < 10) dd = "0" + dd;
  if (hh < 10) hh = "0" + hh;
  if (min < 10) min = "0" + min;

  const formatDatetime = `${yyyy}-${mm}-${dd}T${hh}:${min}`;

  $("#tanggal-penyerahan").attr("min", formatDatetime);
});

const dataSetorSampah = {
  detailTransaksi: [],
  idNasabah: "",
  tanggalPenyerahan: "",
  metodePenyerahan: "",
  totalNominal: 0,
  totalBerat: 0,
};

let detailTransaksi = {
  jenisSampah: "",
  beratSampah: 0,
  subtotalNominal: 0,
  catatan: "",
};

$("#submit-step-1").on("click", () => {
  if ($("input[name='sampah']").is(":checked")) {
    detailTransaksi.jenisSampah = $("input[name='sampah']:checked").val();

    $("#step-1").addClass("hidden");
    $("#step-2").removeClass("hidden");
  } else {
    alert("Mohon mengisi seluruh formulir penyetoran!");
    return;
  }
});

$("#submit-step-2").on("click", () => {
  dataSetorSampah.tanggalPenyerahan = $("#tanggal-penyerahan").val();
  dataSetorSampah.metodePenyerahan = $("#metode-penyerahan").val();
  detailTransaksi.beratSampah = $("#estimasi-berat").val();
  detailTransaksi.catatan = $("#setor-note").val();

  if (
    !(detailTransaksi.beratSampah > 0.01) ||
    !dataSetorSampah.tanggalPenyerahan ||
    !dataSetorSampah.metodePenyerahan
  ) {
    alert("Mohon mengisi seluruh formulir penyetoran!");
    return;
  }

  sampahTerpilih = listSampah.find(
    (sampah) => sampah.jenis_sampah === detailTransaksi.jenisSampah,
  );

  let hargaSampah = 0;
  hargaSampah = parseFloat(sampahTerpilih.harga_sampah_per_kg) || 0;

  detailTransaksi.subtotalNominal = detailTransaksi.beratSampah * hargaSampah;

  $("#konfirmasi-jenis-sampah").text(detailTransaksi.jenisSampah);
  $("#konfirmasi-estimasi-berat").text(detailTransaksi.beratSampah + " Kg");
  $("#konfirmasi-jadwal-penyerahan").text(
    dataSetorSampah.tanggalPenyerahan.replace("T", " Pukul "),
  );
  $("#konfirmasi-jenis-penyerahan").text(dataSetorSampah.metodePenyerahan);
  if (detailTransaksi.catatan)
    $("#konfirmasi-catatan").text(detailTransaksi.catatan);
  $("#subtotal-nominal").text(detailTransaksi.subtotalNominal);
  $("#total-nominal").text(
    dataSetorSampah.totalNominal + detailTransaksi.subtotalNominal,
  );

  $("#step-2").addClass("hidden");
  $("#step-3").removeClass("hidden");
});

$("#setor-lagi").on("click", () => {
  $("#tanggal-penyerahan")
    .attr("disabled", true)
    .addClass("bg-gray-100 cursor-not-allowed");
  $("#metode-penyerahan")
    .attr("disabled", true)
    .addClass("bg-gray-100 cursor-not-allowed");

  $("input[name='sampah']").prop("checked", false);
  $("#estimasi-berat").val("");
  $("#setor-note").val("");

  dataSetorSampah.totalNominal += detailTransaksi.subtotalNominal;
  dataSetorSampah.totalBerat += parseFloat(detailTransaksi.beratSampah);

  dataSetorSampah.detailTransaksi.push({ ...detailTransaksi });

  detailTransaksi = {
    jenisSampah: "",
    beratSampah: 0,
    subtotalNominal: 0,
    catatan: "",
  };

  $("#step-3").addClass("hidden");
  $("#step-1").removeClass("hidden");

  // test data untuk masuk database
  // dataSetorSampah.detailTransaksi.forEach((sampah, index) => {
  //   console.log(
  //     `sampah ke ${index + 1} ${sampah.jenisSampah} ${sampah.beratSampah} ${sampah.catatan} ${sampah.subtotalNominal}`,
  //   );
  // });
});

$("#back-step-2").on("click", () => {
  $("#step-2").addClass("hidden");
  $("#step-1").removeClass("hidden");
});

$("#back-step-3").on("click", () => {
  $("#step-3").addClass("hidden");
  $("#step-2").removeClass("hidden");
});

// push ke database

$("#submit-form-setor").on("click", () => {
  dataSetorSampah.idNasabah = sessionStorage.getItem("id_akun");

  if (!dataSetorSampah.idNasabah) {
    alert("Session login tidak ditemukan. Silakan login ulang.");
    window.location.href = "../../pages/login.html";
    return;
  }

  dataSetorSampah.totalNominal += detailTransaksi.subtotalNominal;
  dataSetorSampah.totalBerat += parseFloat(detailTransaksi.beratSampah);

  dataSetorSampah.detailTransaksi.push({ ...detailTransaksi });

  $.ajax({
    url: "../../backend/database/transaksi/post_transaksi.php",
    type: "POST",
    contentType: "application/json",
    data: JSON.stringify(dataSetorSampah),
    success: function (res) {
      if (res.success) {
        alert("Sukses: " + res.message);
        window.location.href = "./riwayat_transaksi.html";
      } else {
        alert("Gagal menyimpan data setoran" + res.message);
      }
    },
    error: function (xhr) {
      console.log(xhr.responseText);
      alert("Kesalahan pada server saat menyimpan data setoran");
    },
  });
});

// test
console.log(sessionStorage.getItem("id_akun"));
console.log(sessionStorage.getItem("username"));
