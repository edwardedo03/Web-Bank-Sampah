import { getTabungan } from "../backend/global_function.js";
import { getLogoutFunction } from "../backend/global_function.js";

const username = sessionStorage.getItem("username");
const idAkun = sessionStorage.getItem("id_akun");

console.log("id_akun:", idAkun);

if (!username || !idAkun) {
  window.location.href = "./pages/login.html";
} else {
  $("#nama-user").text(`${username}`);
}

getLogoutFunction(".");

getTabungan("saldo-user", idAkun, ".");
