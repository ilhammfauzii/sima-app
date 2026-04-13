document.addEventListener('DOMContentLoaded', function () {
    let counter = 1;
    const container = document.getElementById('penerima-container');
    const tambahPenerimaBtn = document.getElementById('tambah-penerima');

    // ⛔ Jika halaman ini tidak punya fitur penerima → STOP script dengan aman
    if (!container || !tambahPenerimaBtn) {
        console.warn("Fitur penerima tidak aktif di halaman ini.");
        return;
    }

    if (typeof jQuery === 'undefined' || typeof $.fn.select2 === 'undefined') {
        console.error("jQuery atau Select2 belum dimuat. Fungsi dinamis tidak aktif.");
        return;
    }

    function initSelect2(selector) {
        $(selector).select2({
            placeholder: "Pilih User",
            allowClear: true,
            width: '100%',
        });
    }

    const firstSelect = container.querySelector('.penerima-select');
    let selectOptions = firstSelect ? firstSelect.innerHTML : '';

    if (firstSelect) initSelect2(firstSelect);

    function updateHapusButtons() {
        const items = container.querySelectorAll('.penerima-item');
        items.forEach(item => {
            const btn = item.querySelector('.hapus-penerima');
            if (btn) {
                const onlyOne = items.length === 1;
                btn.disabled = onlyOne;
                btn.style.display = onlyOne ? 'none' : 'inline-block';
            }
        });
    }

    tambahPenerimaBtn.addEventListener('click', function () {
        const newId = `penerima-select-${counter}`;
        const newItem = document.createElement('div');

        newItem.className = 'penerima-item mb-3';
        newItem.innerHTML = `
            <div class="row g-2 align-items-center">
                <div class="col-12 col-md-10">
                    <select name="penerima[${counter}][user_id]" 
                            class="form-select penerima-select" 
                            id="${newId}">
                        ${selectOptions}
                    </select>
                </div>
                <div class="col-12 col-md-2 text-md-end mt-2 mt-md-0">
                    <button type="button" class="btn btn-danger btn-sm w-100 w-md-auto hapus-penerima">
                        <i class="fas fa-times"></i> <span class="d-none d-sm-inline">Hapus</span>
                    </button>
                </div>
            </div>
        `;

        container.appendChild(newItem);
        initSelect2(`#${newId}`);

        counter++;
        updateHapusButtons();
    });

    document.addEventListener('click', function (e) {
        const target = e.target.closest('.hapus-penerima');

        // ⛔ Klik di luar fitur penerima → abaikan
        if (!target || !container.contains(target)) return;

        const item = target.closest('.penerima-item');
        const items = container.querySelectorAll('.penerima-item');

        if (item && items.length > 1) {
            const select = item.querySelector('.penerima-select');
            if (select) $(select).select2('destroy');
            item.remove();
            updateHapusButtons();
        } else {
            alert("Anda tidak bisa menghapus penerima terakhir.");
        }
    });

    updateHapusButtons();
});     