/* ==========================================================
   Bank Sampah Selaras — Admin UI
   Shared JS helpers: toast, modal, dropdown
   ========================================================== */

/**
 * Menampilkan notifikasi kecil di pojok kanan bawah.
 * Dipakai buat kasih feedback ke admin setelah aksi (simpan, hapus, dll).
 */
function showToast(message, isError = false) {
    let toast = document.getElementById('appToast');
    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'appToast';
        toast.className = 'toast';
        document.body.appendChild(toast);
    }
    toast.textContent = message;
    toast.classList.toggle('is-error', isError);
    toast.classList.add('is-visible');

    clearTimeout(toast._hideTimeout);
    toast._hideTimeout = setTimeout(() => {
        toast.classList.remove('is-visible');
    }, 2500);
}

/** Buka modal berdasarkan id overlay-nya */
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) modal.classList.add('is-open');
}

/** Tutup modal berdasarkan id overlay-nya */
function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) modal.classList.remove('is-open');
}

/**
 * Setup semua modal-overlay di halaman supaya bisa ditutup dengan:
 * - klik tombol yang punya [data-modal-close]
 * - klik area gelap di luar modal-box
 */
function setupModalCloseHandlers() {
    document.querySelectorAll('.modal-overlay').forEach(function (overlay) {
        overlay.addEventListener('click', function (e) {
            if (e.target === overlay) {
                overlay.classList.remove('is-open');
            }
        });
    });
    document.querySelectorAll('[data-modal-close]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const modalId = btn.getAttribute('data-modal-close');
            closeModal(modalId);
        });
    });
}

/**
 * Setup dropdown menu sederhana (misalnya menu di avatar admin).
 * triggerId = elemen yang diklik untuk buka/tutup menu
 * menuId    = elemen menu yang mau ditampilkan/disembunyikan
 */
function setupDropdown(triggerId, menuId) {
    const trigger = document.getElementById(triggerId);
    const menu = document.getElementById(menuId);
    if (!trigger || !menu) return;

    trigger.addEventListener('click', function (e) {
        e.stopPropagation();
        menu.classList.toggle('is-open');
    });

    document.addEventListener('click', function () {
        menu.classList.remove('is-open');
    });

    menu.addEventListener('click', function (e) {
        e.stopPropagation();
    });
}

document.addEventListener('DOMContentLoaded', setupModalCloseHandlers);
