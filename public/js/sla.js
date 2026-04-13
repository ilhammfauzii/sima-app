document.addEventListener('DOMContentLoaded', function () {

    $('#customer_id').select2({
        placeholder: "-- Pilih Customer --",
        allowClear: true
    });

    $('#departemen_id').select2({
        placeholder: "-- Pilih Departemen --",
        allowClear: true
    });

    $('#service_type_id').select2({
        placeholder: "-- Pilih Jenis Layanan --",
        allowClear: true
    });

    $('#PIC_id').select2({
        placeholder: "-- Pilih PIC --",
        allowClear: true
    });

    $('#marketing_id').select2({
        placeholder: "-- Pilih Marketing --",
        allowClear: true
    });

    $('#pic_filter').select2({
        placeholder: "-- Semua Pegawai --",
        allowClear: true,
        width: '100%'
    });

    $('#customer_filter').select2({
        placeholder: "-- Semua Customer --",
        allowClear: true,
        width: '100%'
    });
});