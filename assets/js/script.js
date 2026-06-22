document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.querySelector('[data-search-film]');
    const genreSelect = document.querySelector('[data-filter-genre]');
    const movieCards = document.querySelectorAll('[data-film-card]');

    function filterFilms() {
        if (!movieCards.length) return;
        const keyword = (searchInput?.value || '').toLowerCase();
        const genre = (genreSelect?.value || '').toLowerCase();

        movieCards.forEach(function (card) {
            const title = (card.dataset.title || '').toLowerCase();
            const cardGenre = (card.dataset.genre || '').toLowerCase();
            const matchKeyword = title.includes(keyword);
            const matchGenre = !genre || cardGenre === genre;
            card.classList.toggle('d-none', !(matchKeyword && matchGenre));
        });
    }

    searchInput?.addEventListener('input', filterFilms);
    genreSelect?.addEventListener('change', filterFilms);

    document.querySelectorAll('[data-confirm]').forEach(function (item) {
        item.addEventListener('click', function (event) {
            const message = item.dataset.confirm || 'Yakin ingin melanjutkan?';
            if (!confirm(message)) {
                event.preventDefault();
            }
        });
    });

    const seatInputs = document.querySelectorAll('.seat-check:not(:disabled)');
    const ticketCount = document.querySelector('[data-ticket-count]');
    const ticketTotal = document.querySelector('[data-ticket-total]');
    const selectedSeats = document.querySelector('[data-selected-seats]');
    const submitButton = document.querySelector('[data-submit-booking]');
    const price = Number(document.querySelector('[data-ticket-price]')?.dataset.ticketPrice || 0);

    function formatRupiah(number) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            maximumFractionDigits: 0,
        }).format(number);
    }

    function updateSeatSummary() {
        const checked = Array.from(seatInputs).filter(input => input.checked);
        const names = checked.map(input => input.dataset.seatName);
        if (ticketCount) ticketCount.textContent = checked.length;
        if (ticketTotal) ticketTotal.textContent = formatRupiah(checked.length * price);
        if (selectedSeats) selectedSeats.textContent = names.length ? names.join(', ') : '-';
        if (submitButton) submitButton.disabled = checked.length === 0;
    }

    seatInputs.forEach(function (input) {
        input.addEventListener('change', updateSeatSummary);
    });

    updateSeatSummary();
});
