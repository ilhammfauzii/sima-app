class ArsipFilter {
    constructor() {
        this.tipeSelect = document.getElementById('tipe');
        this.kategoriSelect = document.getElementById('kategori');
        this.init();
    }

    init() {
        if (this.tipeSelect && this.kategoriSelect) {
            this.tipeSelect.addEventListener('change', () => this.updateFilter());
            this.kategoriSelect.addEventListener('change', () => this.updateFilter());
        }
    }

    updateFilter() {
        const tipe = this.tipeSelect.value;
        const kategori = this.kategoriSelect.value;
        const baseUrl = window.location.href.split('?')[0];
        window.location.href = `${baseUrl}?tipe=${tipe}&kategori=${kategori}`;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    new ArsipFilter();
});