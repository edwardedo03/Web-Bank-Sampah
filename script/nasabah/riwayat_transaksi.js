import { getTabungan } from "../../backend/global_function.js";
import { getLogoutFunction } from "../../backend/global_function.js";
import { getTotalSampah } from "../../backend/global_function.js";

const username = sessionStorage.getItem("username");
const idAkun = sessionStorage.getItem("id_akun");

if (!username || !idAkun) {
  window.location.href = "../../pages/login.html";
}

getLogoutFunction("../..");

$(".btn-filter").on("click", function () {
  $(".btn-filter")
    .removeClass("bg-[#0D631B] text-[#F7FBF0]")
    .addClass("bg-gray-800/30 text-gray-800/80");

  $(this)
    .removeClass("bg-gray-800/30 text-gray-800/80")
    .addClass("bg-[#0D631B] text-[#F7FBF0]");

  $(".content-kategori").addClass("hidden");

  const targetId = $(this).attr("data-target");

  $(`#${targetId}`).removeClass("hidden");
});

getTabungan("saldo-tabungan", idAkun, "../..");

getTotalSampah("total-sampah", idAkun, "../..");
