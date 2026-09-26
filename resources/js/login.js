document.addEventListener('DOMContentLoaded', function () {

    const form = document.querySelector('form');
    const button = document.querySelector('.login-button');

    if (form && button) {
        form.addEventListener('submit', function () {
            button.disabled = true;
            button.textContent = 'Memproses...';
        });
    }

});