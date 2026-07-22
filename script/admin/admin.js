const username = sessionStorage.getItem("username");
const idAkun = sessionStorage.getItem("id_akun");

if (!username || !idAkun) {
  window.location.href = "../../pages/login.html";
}

function showToast(message, isError = false) {
  let $toast = $("#appToast");
  if ($toast.length === 0) {
    $toast = $('<div id="appToast"></div>').addClass(
      "fixed bottom-6 right-6 px-5 py-3 rounded-lg text-sm font-semibold text-white shadow-lg z-[200] opacity-0 translate-y-2 transition-all duration-200 pointer-events-none",
    );
    $("body").append($toast);
  }
  $toast.text(message);
  $toast.toggleClass("bg-[#2E7D32]", !isError);
  $toast.toggleClass("bg-red-600", isError);
  $toast
    .removeClass("opacity-0 translate-y-2")
    .addClass("opacity-100 translate-y-0");

  clearTimeout($toast.data("hideTimeout"));
  const timeout = setTimeout(function () {
    $toast
      .removeClass("opacity-100 translate-y-0")
      .addClass("opacity-0 translate-y-2");
  }, 2500);
  $toast.data("hideTimeout", timeout);
}

function openModal(modalId) {
  $("#" + modalId)
    .removeClass("hidden")
    .addClass("flex");
}

function closeModal(modalId) {
  $("#" + modalId)
    .addClass("hidden")
    .removeClass("flex");
}

function setupModalCloseHandlers() {
  $(".modal-overlay").on("click", function (e) {
    if (e.target === this) {
      $(this).addClass("hidden").removeClass("flex");
    }
  });
  $("[data-modal-close]").on("click", function () {
    closeModal($(this).attr("data-modal-close"));
  });
}

function setupDropdown(triggerId, menuId) {
  const $trigger = $("#" + triggerId);
  const $menu = $("#" + menuId);
  if ($trigger.length === 0 || $menu.length === 0) return;

  $trigger.on("click", function (e) {
    e.stopPropagation();
    $menu.toggleClass("hidden");
  });

  $(document).on("click", function () {
    $menu.addClass("hidden");
  });

  $menu.on("click", function (e) {
    e.stopPropagation();
  });
}

$(document).ready(function () {
  setupModalCloseHandlers();
});
