const username = sessionStorage.getItem("username");

if (!username) {
  window.location.href = "../../pages/login.html";
}

$("#logout").on("click", () => {
  sessionStorage.removeItem("username");

  window.location.href = "../../pages/login.html";
});

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
  jenisSampah: "",
  beratSampah: "",
  tanggalPenyerahan: "",
  metodePenyerahan: "",
  catatan: "",
};

$("#submit-step-1").on("click", () => {
  if ($("input[name='sampah']").is(":checked")) {
    dataSetorSampah.jenisSampah = $("input[name='sampah']:checked").val();

    $("#step-1").addClass("hidden");
    $("#step-2").removeClass("hidden");
  } else {
    alert("Mohon mengisi seluruh formulir penyetoran!");
    return;
  }
});

$("#submit-step-2").on("click", () => {
  dataSetorSampah.beratSampah = $("#estimasi-berat").val();
  dataSetorSampah.tanggalPenyerahan = $("#tanggal-penyerahan").val();
  dataSetorSampah.metodePenyerahan = $("#metode-penyerahan").val();
  dataSetorSampah.catatan = $("#setor-note").val();

  if (
    !(dataSetorSampah.beratSampah > 0.01) ||
    !dataSetorSampah.tanggalPenyerahan ||
    !dataSetorSampah.metodePenyerahan
  ) {
    alert("Mohon mengisi seluruh formulir penyetoran!");
    return;
  }

  $("#step-2").addClass("hidden");
  $("#step-3").removeClass("hidden");

  $("#konfirmasi-jenis-sampah").text(dataSetorSampah.jenisSampah);
  $("#konfirmasi-estimasi-berat").text(dataSetorSampah.beratSampah + " Kg");
  $("#konfirmasi-jadwal-penyerahan").text(
    dataSetorSampah.tanggalPenyerahan.replace("T", " Pukul "),
  );
  $("#konfirmasi-jenis-penyerahan").text(dataSetorSampah.metodePenyerahan);
  if (dataSetorSampah.catatan)
    $("#konfirmasi-catatan").text(dataSetorSampah.catatan);
});
