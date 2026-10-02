import { getTabungan } from "../../backend/global_function.js";
import { getLogoutFunction } from "../../backend/global_function.js";
import { getTotalSampah } from "../../backend/global_function.js";
import { getTotalSetoran } from "../../backend/global_function.js";

const username = sessionStorage.getItem("username");
const idAkun = sessionStorage.getItem("id_akun");

if (!username || !idAkun) {
  window.location.href = "../../pages/login.html";
}

getLogoutFunction("../..");

$(".btn-filter").on("click", function () {
  $(".btn-filter")
    .removeClass("bg-[#0D631B] text-[#F7FBF0]")
    .addClass(
      "bg-gray-800/30 text-gray-800/80 hover:bg-[#0D631B] hover:text-[#F7FBF0]",
    );

  $(this)
    .removeClass("bg-gray-800/30 text-gray-800/80")
    .addClass("bg-[#0D631B] text-[#F7FBF0]");

  $("#tarik-saldo-form").addClass("hidden");

  $(".content-kategori").addClass("hidden");

  const targetId = $(this).attr("data-target");

  $(`#${targetId}`).removeClass("hidden");
});

getTabungan("saldo-tabungan", idAkun, "../..");

getTabungan("saldo-penarikan", idAkun, "../..");

getTotalSampah("total-sampah", idAkun, "../..");

getTotalSetoran("jumlah-setoran", idAkun, "../..");

// Get Detail Transaksi

$.ajax({
  url: "../../backend/database/transaksi/get_detail_transaksi.php",
  type: "GET",
  dataType: "json",
  data: { id_akun: idAkun },
  success: function (res) {
    if (res.success && res.history.length > 0) {
      const limitHistory = res.history.slice(0, 20);

      limitHistory.forEach((item) => {
        const dateTime = new Date(item.tanggal_penyerahan);

        const formatTanggal = {
          day: "numeric",
          month: "long",
          year: "numeric",
        };
        const tanggal = new Intl.DateTimeFormat("id-ID", formatTanggal).format(
          dateTime,
        );

        const formatWaktu = {
          hour: "2-digit",
          minute: "2-digit",
        };
        const waktu = new Intl.DateTimeFormat("id-ID", formatWaktu).format(
          dateTime,
        );

        let badgeColor = "bg-[#DCFCE7] text-[#0D631B]";
        if (item.status.toLowerCase() === "menunggu validasi") {
          badgeColor = "bg-yellow-100 text-yellow-800";
        } else if (item.status.toLowerCase() === "gagal") {
          badgeColor = "bg-red-100 text-red-800";
        }

        const cardHTML = `
          <div class="w-full bg-white rounded-xl px-5 shadow-md">
            <div class="flex flex-row justify-between items-center py-4 px-2">
              <div class="flex flex-row justify-start items-center gap-4">
                <img
                  src="../../assets/icon/setor-sampah/setor-light.png"
                  alt="icon-setor-sampah"
                  class="bg-[#0D631B] rounded-full p-3 select-none"
                />
                <div class="flex flex-col items-start">
                  <p class="text-lg font-semibold">${tanggal} ● ${waktu}</p>
                  <p class="font-light text-sm">${item.catatan}</p>
                </div>
              </div>
              <div class="flex flex-col gap-2 items-end justify-center">
                <h5 class="text-[#0D631B] font-semibold text-lg">
                  + Rp <span>${Number(item.subtotal_nominal_aktual).toLocaleString("id-ID")}</span>
                </h5>
                <p
                  class="${badgeColor} py-1 px-3 rounded-xl text-xs font-bold"
                >
                  ${item.status}
                </p>
              </div>
            </div>
            <hr class="border-t-1 border-black my-2" />
            <div class="py-4 px-2 flex flex-row justify-between">
              <p class="text-[#0D631B]/90">Berat: <span>${item.berat_sampah_aktual}</span> Kg</p>
              <a href="">
                <p
                  class="font-semibold text-[#0D631B] duration-200 hover:font-bold hover:text-[#2E7D32]"
                >
                  Detail >
                </p>
              </a>
            </div>
          </div>
        `;

        $("#kategori-semua").append(cardHTML);
        const jenisSampah = item.jenis_sampah.toLowerCase();
        if (jenisSampah.includes("plastik")) {
          $("#kategori-plastik").append(cardHTML);
        } else if (jenisSampah.includes("kertas")) {
          $("#kategori-kertas").append(cardHTML);
        } else if (jenisSampah.includes("logam")) {
          $("#kategori-logam").append(cardHTML);
        }
      });
    } else {
      $(".content-kategori").html(`
        <p class="text-center text-gray-500 font-medium py-10 bg-white rounded-xl shadow-md w-full">
          Belum ada riwayat transaksi penyetoran sampah.
        </p>
      `);
    }
  },
  error: function (xhr) {
    console.log("Error", xhr.responseText);
  },
});

// tarik saldo

$("#tarik-saldo").on("click", function () {
  $("#tarik-saldo-form").removeClass("hidden");
  $(".content-kategori").addClass("hidden");
});

$(document).on("click", "label:has(.radio-nominal)", function (e) {
  const radio = $(this).find(".radio-nominal");

  if (!radio.is(":checked")) {
    radio.prop("checked", true).trigger("change");
  }
});

$(document).on("change", ".radio-nominal", function () {
  const val = $(this).val();

  if (val === "tarik-semua") {
    const saldoTersedia = parseInt(
      $("#saldo-tabungan")
        .text()
        .replace(/[^0-9]/g, ""),
    );
    $("#nominal-penarikan").val(saldoTersedia);
  } else {
    $("#nominal-penarikan").val(val);
  }
});

$("#nominal-penarikan").on("input", function () {
  $(".radio-nominal").prop("checked", false);
});

$("#btn-tarik-saldo").on("click", function () {
  const nominalPenarikan = parseFloat($("#nominal-penarikan").val());
  const saldoTersedia =
    parseFloat(
      $("#saldo-tabungan")
        .text()
        .replace(/[^0-9]/g, ""),
    ) || 0;

  if (isNaN(nominalPenarikan) || nominalPenarikan < 5000) {
    alert("Nominal penarikan minimal Rp 5.000");
    return;
  } else if (nominalPenarikan > saldoTersedia) {
    alert("Jumlah penarikan melebihi saldo!");
    return;
  }

  const dataTarikSaldo = {
    id_akun: idAkun,
    nominal_tarik: nominalPenarikan,
  };

  $.ajax({
    url: "../../backend/database/nasabah/tarik_saldo.php",
    type: "POST",
    contentType: "application/json",
    data: JSON.stringify(dataTarikSaldo),
    success: function (res) {
      if (res.success) {
        alert("Sukses: " + res.message);
        $("#nominal-penarikan").val("");
        $(".radio-nominal").prop("checked", false);
      } else {
        alert("Permintaan gagal:" + res.message);
      }
    },
    error: function (xhr) {
      console.log(xhr.responseText);
      alert("Error: ", xhr.responseText);
    },
  });
});
