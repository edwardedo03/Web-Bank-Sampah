import { getLogoutFunction } from "../../backend/global_function.js";

const username = sessionStorage.getItem("username");
const idAkun = sessionStorage.getItem("id_akun");

if (!username || !idAkun) {
  window.location.href = "../login.html";
} else {
  $("#nama-user").text(`${username}`);
}

getLogoutFunction("../..");

let timerPencarian;

$("#cari-nasabah").on("input", function () {
  const keyword = $(this).val().trim();

  clearTimeout(timerPencarian);

  if (!keyword) {
    $("#card-nasabah-pencarian").empty();
    $("#nasabah-ketemu").text("0");
    return;
  }

  timerPencarian = setTimeout(() => {
    const currentKeyword = $("#cari-nasabah").val().trim();
    if (currentKeyword === "") {
      $("#card-nasabah-pencarian").empty();
      $("#nasabah-ketemu").text("0");
      return;
    }

    $.ajax({
      url: "../../backend/database/petugas/get_all_nasabah.php",
      type: "GET",
      data: { keyword: keyword },
      dataType: "json",
      success: function (res) {
        $("#card-nasabah-pencarian").empty();

        if (res.success && res.nasabah.length > 0) {
          $("#nasabah-ketemu").text(res.nasabah.length);

          res.nasabah.forEach((item) => {
            const cardHTML = `
                <div
                    class="shadow-md bg-[#F7FBF0] border-2 border-[#0D631B]/20 rounded-xl flex flex-row items-center gap-2 py-1 px-5 w-full justify-between"
                >
                    <div class="flex flex-row gap-5 items-center py-2">
                        <img
                            src="../../assets/img/orang-profile.png"
                            alt="profile-icon"
                            class="p-1 w-16 h-16 rounded-full bg-[#D9E6DA] select-none shadow-sm border-2 border-[#0D631B]/10"
                        />
                        <div class="flex flex-col">
                            <p
                                class="text-lg font-bold text-[#0D631B]"
                            >
                                ${item.nama_nasabah}
                            </p>
                            <p
                                class="text-sm font-semibold text-[#0D631B]"
                            >
                                @${item.username_nasabah}
                            </p>
                            <p
                                class="text-sm font-semibold text-[#0D631B]"
                            >
                                ${item.no_telepon_nasabah}
                            </p>
                        </div>
                    </div>
                    <button
                        type="button"
                        data-username="${item.username_nasabah}"
                        class="btn-pilih-nasabah py-2 px-5 bg-[#0D631B] text-white rounded-lg font-semibold hover:bg-[#2E7D32] duration-200 active:scale-95"
                    >
                        Pilih
                    </button>
                </div>
            `;

            $("#card-nasabah-pencarian").append(cardHTML);
          });
        } else {
          $("#nasabah-ketemu").text("0");
          $("#card-nasabah-pencarian").html(`
            <p class="text-center text-gray-500 font-medium py-3">
              Tidak ada data nasabah yang cocok.
            </p>
          `);
        }
      },
      error: function (xhr) {
        console.log("Error", xhr.responseText);
      },
    });
  }, 300);
});

$(document).on("click", ".btn-pilih-nasabah", function () {
  const usernameNasabah = $(this).attr("data-username");
  $.ajax({
    url: "../../backend/database/petugas/get_detail_transaksi_nasabah.php",
    type: "GET",
    data: { username_nasabah: usernameNasabah },
    dataType: "json",
    success: function (res) {
      if (res.success) {
        renderPopup(res.nasabah, res.transaksi);
      } else {
        alert(res.message);
      }
    },
    error: function (xhr) {
      console.log("Error:", xhr.responseText);
    },
  });
});

function renderPopup(nasabah, listTransaksi) {
  const container = $("#popup-detail");
  container.empty();

  const profileNasabah = `
    <div class="flex flex-row justify-end items-center">
      <button type="button" class="text-gray-400 text-lg hover:text-red-800 active:scale-95 duration-200 w-8 h-8 font-bold" id="close-popup">
        ✕
      </button>
    </div>

    <div
      class="flex flex-row justify-start items-center gap-4 bg-[#EBEFE5] px-3 py-3 rounded-xl border-2 border-[#0D631B]/20 w-full"
    >
      <img
        src="../../assets/img/orang-profile.png"
        alt="orang"
        class="rounded-full border-[#0D631B]/20 border-2 w-16 h-16 select-none"
      />
      <div class="flex flex-col justify-between items-start">
        <h3 class="text-lg font-bold" id="username-nasabah-popup">
          ${nasabah.username_nasabah}
        </h3>
        <p class="text-sm font-semibold">
          ID: #<span id="id-nasabah-popup">${nasabah.id_nasabah}</span>
        </p>
        <p class="text-sm font-semibold">
          Wilayah: <span id="wilayah-nasabah-popup">${nasabah.kecamatan}</span>
        </p>
      </div>
    </div>
    <hr />
  `;
  container.append(profileNasabah);

  // --

  if (listTransaksi && listTransaksi.length > 0) {
    listTransaksi.forEach((item) => {
      const hargaSampah = item.harga_sampah_per_kg;

      let cardTransaksi = `
        <div class="card-transaksi flex flex-col gap-2 border-b border-black/20 pb-5 w-full" data-harga-per-kg="${hargaSampah}">
          <div class="flex flex-col items-start justify-between gap-2 mb-2">
            <p class="font-semibold text-[#0D631B]">
              ID Transaksi: #<span id="id-transaksi">${item.id_detail}</span>
            </p>
            <p class="font-semibold text-[#0D631B]">
              Status:
              <span
                id="status-transaksi"
                class="bg-yellow-100 px-2 py-1 rounded-full"
                >${item.status}</span
              >
            </p>
            <p class="font-semibold text-[#0D631B]">
              Jenis Sampah: <span id="id-transaksi">${item.jenis_sampah}</span>
            </p>
          </div>
  
          <div class="flex flex-col gap-3 items-start w-full">
            <div
              class="flex flex-row justify-between items-center gap-4 bg-[#EBEFE5] py-2 px-4 rounded-xl border-2 border-[#0D631B]/20 w-full"
            >
              <div class="flex flex-row gap-3 items-center">
                <img
                  src="../../assets/icon/setor-sampah/sampah/Plastik.svg"
                  alt="icon-sampah"
                  class="bg-white rounded-xl p-2 w-12 h-12 select-none"
                />
                <div class="flex flex-col">
                  <p class="font-semibold text-md">Estimasi Nasabah</p>
                  <p class="font-light text-sm">
                    Rp <span id="harga-estimasi-nasabah">${Number(item.subtotal_nominal).toLocaleString("id-ID")}</span>
                  </p>
                </div>
              </div>
              <div
                class="flex flex-row gap-3 items-center border-2 border-black/70 rounded-lg py-2 px-3 w-24 justify-end"
              >
                <span
                  id="berat-estimasi-nasabah"
                  class="font-semibold text-xl text-black w-18"
                  >${item.berat_sampah}</span
                >
                <p class="font-semibold text-sm">kg</p>
              </div>
            </div>
  
            <div
              class="flex flex-row justify-between items-center gap-4 bg-[#EBEFE5] py-2 px-4 rounded-xl border-2 border-[#0D631B]/20 w-full"
            >
              <div class="flex flex-row gap-3 items-center">
                <img
                  src="../../assets/icon/setor-sampah/sampah/Kertas.svg"
                  alt="icon-sampah"
                  class="bg-white rounded-xl p-2 w-12 h-12 select-none"
                />
                <div class="flex flex-col">
                  <p class="font-semibold text-md">Berat Aktual</p>
                  <p class="font-light text-sm">
                    Rp <span id="harga-aktual-nasabah" class="harga-aktual-nasabah">0</span>
                  </p>
                </div>
              </div>
              <div
                class="flex flex-row gap-4 items-center border-2 border-black/70 rounded-lg py-2 px-2 shadow-md w-24 justify-end"
              >
                <input
                  id="berat-aktual-nasabah"
                  type="number"
                  step="0.1"
                  placeholder="0.0"
                  class="input-berat-aktual font-semibold text-xl text-black outline-none bg-transparent w-full min-w-0 text-right appearance-none [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                />
                <p class="font-semibold text-sm">kg</p>
              </div>
            </div>
          </div>
  
          <div class="flex flex-row justify-start mt-2 w-full gap-3">
            <button
              data-id-detail="${item.id_detail}"
              type="button"
              class="btn-tolak-detail bg-red-800 py-2 px-3 rounded-lg font-semibold text-white text-md active:scale-95 hover:bg-red-800/90 duration-200 w-full"
            >
              Tolak
            </button>
            <button
              data-id-detail="${item.id_detail}"
              type="button"
              class="btn-simpan-detail bg-[#0D631B] py-2 px-3 rounded-lg font-semibold text-white text-md active:scale-95 hover:bg-[#2E7D32] duration-200 w-full"
            >
              Simpan
            </button>
          </div>
        </div>
      `;
      container.append(cardTransaksi);
    });
  } else {
    container.append(`
        <div class="font-semibold text-lg text-gray-800/60 text-center">
          <p>Nasabah belum melakukan transaksi</p>
        </div>
      `);
  }

  // --

  $("#overlay-popup").removeClass("hidden");
  $("#popup-detail").removeClass("hidden");
  setTimeout(() => {
    $("#overlay-popup").removeClass("opacity-0").addClass("opacity-100");
    $("#popup-detail")
      .removeClass("translate-x-full")
      .addClass("translate-x-0");
  }, 10);
}

$(document).on("input", ".input-berat-aktual", function () {
  const parentCard = $(this).closest(".card-transaksi");
  const berat = parseFloat($(this).val());
  const hargaPerKg = parseFloat(parentCard.attr("data-harga-per-kg"));

  const totalHargaAktual = berat * hargaPerKg;

  parentCard
    .find(".harga-aktual-nasabah")
    .text(totalHargaAktual.toLocaleString("id-ID"));
});

$(document).on("click", "#close-popup", function () {
  $("#overlay-popup").removeClass("opacity-100").addClass("opacity-0");
  $("#popup-detail").removeClass("translate-x-0").addClass("translate-x-full");

  setTimeout(() => {
    $("#overlay-popup").addClass("hidden");
    $("#popup-detail").addClass("hidden");
  }, 300);
});

function updateDetailTransaksi(
  idDetail,
  status,
  beratAktual,
  subtotalAktual,
  parentCard,
) {
  $.ajax({
    url: "../../backend/database/petugas/update_detail_transaksi.php",
    type: "POST",
    contentType: "application/json",
    data: JSON.stringify({
      id_detail: idDetail,
      status: status,
      berat_aktual: beratAktual,
      subtotal_aktual: subtotalAktual,
    }),
    dataType: "json",
    success: function (res) {
      if (res.success) {
        parentCard.fadeOut(300, function () {
          $(this).remove();

          const sisaKartu = $(".card-transaksi").length;

          if (sisaKartu === 0) {
            $("#popup-detail").append(`
              <p class="text-center text-gray-500 py-6 font-md">
                Semua transaksi nasabah ini telah selesai diproses.
              </p>
            `);
          }
        });
      } else {
        alert("Gagal: " + res.message);
      }
    },
    error: function (xhr) {
      console.log("Error:", xhr.responseText);
    },
  });
}

$(document).on("click", ".btn-simpan-detail", function () {
  const idDetail = $(this).attr("data-id-detail");
  const parentCard = $(this).closest(".card-transaksi");
  const beratAktual = parentCard.find(".input-berat-aktual").val();
  const hargaPerKg = parseFloat(parentCard.attr("data-harga-per-kg"));

  if (!beratAktual || parseFloat(beratAktual) <= 0) {
    alert("Masukkan berat aktual terlebih dahulu!");
    return;
  }

  const subtotalAktual = beratAktual * hargaPerKg;

  updateDetailTransaksi(
    idDetail,
    "Proses",
    beratAktual,
    subtotalAktual,
    parentCard,
  );
});

$(document).on("click", ".btn-tolak-detail", function () {
  const idDetail = $(this).attr("data-id-detail");
  const parentCard = $(this).closest(".card-transaksi");

  if (confirm("Apakah Anda yakin ingin menolak transaksi sampah ini?")) {
    updateDetailTransaksi(idDetail, "Gagal", null, null, parentCard);
  }
});
