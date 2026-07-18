import { getLogoutFunction } from "../../backend/global_function.js";
import { getNasabahProfile } from "../../backend/global_function.js";

const username = sessionStorage.getItem("username");
const idAkun = sessionStorage.getItem("id_akun");

if (!username || !idAkun) {
  window.location.href = "../../pages/login.html";
}

getLogoutFunction("../..");

getNasabahProfile(idAkun, "../..");
