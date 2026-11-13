import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('submit', (event) => {
    const form = event.target;

    if (!(form instanceof HTMLFormElement) || !form.matches('[data-logout-form]')) {
        return;
    }

    if (!window.confirm('Apakah Anda yakin ingin logout?')) {
        event.preventDefault();
        return;
    }

    if (!window.confirm('Konfirmasi sekali lagi untuk keluar dari akun Anda.')) {
        event.preventDefault();
    }
});
