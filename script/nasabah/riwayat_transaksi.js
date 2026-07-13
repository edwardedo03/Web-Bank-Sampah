const username = sessionStorage.getItem("username");

if (!username) {
  window.location.href = "../../pages/login.html";
}

$("#logout").on("click", () => {
  sessionStorage.removeItem("username");

  window.location.href = "../../pages/login.html";
});

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
