document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const searchForm = document.getElementById('searchForm');

    if (!searchInput || !searchForm) {
        console.warn('Live search tidak aktif di halaman ini.');
        return;
    }

    let typingTimer;
    const doneTypingInterval = 500;

    function debounce(func, delay) {
        let timeout;
        return function(...args) {
            const context = this;
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(context, args), delay);
        };
    }

    searchInput.addEventListener('keyup', debounce(function() {
        searchForm.submit();
    }, doneTypingInterval));
});
