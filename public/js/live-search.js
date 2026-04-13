document.addEventListener("turbo:load", initLiveSearch);
document.addEventListener("DOMContentLoaded", initLiveSearch);

function initLiveSearch() {
    const inputs = document.querySelectorAll("[data-search-url]");
    if (inputs.length === 0) return;

    inputs.forEach(input => {
        let timeout = null;
        console.log(`✅ Live search aktif: ${input.dataset.searchUrl}`);

        input.addEventListener("keyup", function () {
            clearTimeout(timeout);
            const query = this.value.trim();
            const url = this.dataset.searchUrl;
            const targetSelector = this.dataset.searchTarget;

            timeout = setTimeout(() => {
                fetch(`${url}?query=${encodeURIComponent(query)}`)
                    .then(res => res.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, "text/html");
                        const newContent = doc.querySelector(targetSelector);
                        const oldContent = document.querySelector(targetSelector);
                        if (newContent && oldContent) {
                            oldContent.innerHTML = newContent.innerHTML;
                        }
                    })
                    .catch(err => console.error("❌ Fetch error:", err));
            }, 400);
        });
    });
}