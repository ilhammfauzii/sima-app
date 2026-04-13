function initSelect2Global() {
    if (typeof jQuery === 'undefined' || typeof $.fn.select2 === 'undefined') {
        console.warn('jQuery / Select2 belum siap');
        return;
    }

    $('.select2-item').each(function () {
        const placeholder = $(this).data('placeholder') || '-- Pilih --';

        $(this).select2({
            width: '100%',
            placeholder: placeholder,
            allowClear: true
        });
    });

    $('.select2-pic').select2({ width: '100%', placeholder: '-- Pilih PIC --' });
    $('.select2-borrower').select2({ width: '100%', placeholder: '-- Pilih Peminjam --' });
}

function initPengeluaranDynamicForm() {
    const addItemBtn = document.getElementById('add-item-btn');
    const itemsContainer = document.getElementById('items-container');

    if (!addItemBtn || !itemsContainer) return;

    const formElement = itemsContainer.closest('form');
    if (!formElement) return;

    const itemName = formElement.dataset.itemName;
    const firstItemSelectElement = itemsContainer.querySelector('.select2-item');

    let initialSelectOptions = '';

    if (firstItemSelectElement) {
        initialSelectOptions = firstItemSelectElement.innerHTML;

        const placeholder = $(firstItemSelectElement).data('placeholder') || '-- Pilih --';

        $(firstItemSelectElement).select2({
            placeholder: placeholder,
            width: '100%',
            allowClear: true
        });
    }

    let itemIndex = 1;

    addItemBtn.addEventListener('click', function () {
        const newItemRow = document.createElement('div');
        newItemRow.className = 'item-row row mb-2 align-items-end';

        newItemRow.innerHTML = `
            <div class="col-md-5">
                <label>Nama Barang - Kode Barang</label>
                <select 
                    name="items[${itemIndex}][${itemName}]" 
                    class="form-control select2-item" 
                    id="item-select-${itemIndex}" 
                    data-placeholder="${$(firstItemSelectElement).data('placeholder') || '-- Pilih --'}"
                    required
                >
                    ${initialSelectOptions}
                </select>
            </div>
            <div class="col-md-5">
                <label>Jumlah Keluar</label>
                <input type="number" name="items[${itemIndex}][jumlah_keluar]" 
                       class="form-control" min="1" value="1" required>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger remove-item-btn">Hapus</button>
            </div>
        `;

        itemsContainer.appendChild(newItemRow);

        const newSelect = document.getElementById(`item-select-${itemIndex}`);
        const placeholder = $(newSelect).data('placeholder') || '-- Pilih --';

        $(newSelect).select2({
            placeholder: placeholder,
            width: '100%',
            allowClear: true
        });

        itemIndex++;
    });
}

document.addEventListener('DOMContentLoaded', function () {
    initSelect2Global();
    initPengeluaranDynamicForm();
});

document.addEventListener('turbo:load', function () {
    initSelect2Global();
    initPengeluaranDynamicForm();
});