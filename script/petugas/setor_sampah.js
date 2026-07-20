let timerPencarian;

$("#cari-nasabah").on("input", function () {
  const keyword = $(this).val().trim();

  clearTimeout(timerPencarian);

  timerPencarian = setTimeout(() => {
    $.ajax({
      url: "../../backend/database/petugas/get_all_nasabah.php",
      type: "GET",
      data: { keyword: keyword },
      dataType: "json",
      success: function (res) {
        $("#card-nasabah-pencarian").empty();

        if (res.success && res.nasabah && res.nasabah.length > 0) {
          $("#nasabah-ketemu").text(res.nasabah.length);

          res.nasabah.forEach((item) => {
            const cardHTML = `
                <div
                    class="shadow-md bg-[#F7FBF0] border-2 border-[#0D631B]/20 rounded-xl flex flex-row items-center gap-2 py-1 px-5 w-full justify-between"
                >
                    <div class="flex flex-row gap-5 items-center py-2">
                        <img
                            src="../../assets/icon/profil-account/profil-account.png"
                            alt="profile-icon"
                            class="p-1 w-14 h-14 rounded-full bg-[#D9E6DA] select-none shadow-sm border-2 border-[#0D631B]/10"
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
                                #${item.id_nasabah}
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
                        class="py-2 px-5 bg-[#0D631B] text-white rounded-lg font-semibold hover:bg-[#2E7D32] duration-200 active:scale-95"
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
