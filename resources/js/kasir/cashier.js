
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('search');

    if (searchInput) {
        searchInput.focus();

        // Tekan Enter untuk menjalankan pencarian.
        searchInput.addEventListener('keydown', function (event) {
            if (event.key === 'Enter') {
                searchInput.form.requestSubmit();
            }
        });
    }
});
