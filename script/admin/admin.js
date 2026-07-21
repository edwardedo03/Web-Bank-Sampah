function showToast(message, isError = false) {
    let toast = document.getElementById('appToast');
    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'appToast';
        toast.className = 'fixed bottom-6 right-6 px-5 py-3 rounded-lg text-sm font-semibold text-white shadow-lg z-[200] opacity-0 translate-y-2 transition-all duration-200 pointer-events-none';
        document.body.appendChild(toast);
    }
    toast.textContent = message;
    toast.classList.toggle('bg-[#2E7D32]', !isError);
    toast.classList.toggle('bg-red-600', isError);
    toast.classList.remove('opacity-0', 'translate-y-2');
    toast.classList.add('opacity-100', 'translate-y-0');

    clearTimeout(toast._hideTimeout);
    toast._hideTimeout = setTimeout(() => {
        toast.classList.remove('opacity-100', 'translate-y-0');
        toast.classList.add('opacity-0', 'translate-y-2');
    }, 2500);
}

function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
}

function setupModalCloseHandlers() {
    document.querySelectorAll('.modal-overlay').forEach(function (overlay) {
        overlay.addEventListener('click', function (e) {
            if (e.target === overlay) {
                overlay.classList.add('hidden');
                overlay.classList.remove('flex');
            }
        });
    });
    document.querySelectorAll('[data-modal-close]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            closeModal(btn.getAttribute('data-modal-close'));
        });
    });
}

function setupDropdown(triggerId, menuId) {
    const trigger = document.getElementById(triggerId);
    const menu = document.getElementById(menuId);
    if (!trigger || !menu) return;

    trigger.addEventListener('click', function (e) {
        e.stopPropagation();
        menu.classList.toggle('hidden');
    });

    document.addEventListener('click', function () {
        menu.classList.add('hidden');
    });

    menu.addEventListener('click', function (e) {
        e.stopPropagation();
    });
}

document.addEventListener('DOMContentLoaded', setupModalCloseHandlers);
