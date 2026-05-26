/**
 * Anggota PSI — Admin JavaScript
 */
(function($) {
    'use strict';

    /* ─── Save Member via AJAX ─── */
    $(document).on('submit', '#anggota-psi-form', function(e) {
        e.preventDefault();
        var form = $(this);
        var btn = $('#anggota-submit-btn');
        var msg = $('#anggota-result-msg');

        btn.prop('disabled', true).html('<span class="spinner is-active" style="float:none;"></span> Menyimpan...');
        msg.html('');

        $.ajax({
            url: anggotaPsi.ajaxurl,
            type: 'POST',
            data: {
                action: 'anggota_psi_save_member',
                nonce: anggotaPsi.nonce,
                full_name: form.find('[name="full_name"]').val(),
                nik: form.find('[name="nik"]').val(),
                email: form.find('[name="email"]').val(),
                phone: form.find('[name="phone"]').val(),
                dpd_region: form.find('[name="dpd_region"]').val(),
                address: form.find('[name="address"]').val(),
                join_date: form.find('[name="join_date"]').val(),
                status: form.find('[name="status"]').val(),
                notes: form.find('[name="notes"]').val()
            },
            success: function(res) {
                btn.prop('disabled', false).html('<span class="dashicons dashicons-plus-alt2"></span> Simpan Anggota');
                if (res.success) {
                    msg.html('<span style="color:#155724;font-weight:600;">✓ ' + res.data.message + '</span>');
                    form[0].reset();
                    form.find('[name="join_date"]').val(new Date().toISOString().split('T')[0]);
                } else {
                    msg.html('<span style="color:#721c24;font-weight:600;">✗ ' + res.data.message + '</span>');
                }
            },
            error: function() {
                btn.prop('disabled', false).html('<span class="dashicons dashicons-plus-alt2"></span> Simpan Anggota');
                msg.html('<span style="color:#721c24;font-weight:600;">✗ Terjadi kesalahan jaringan.</span>');
            }
        });
    });

    /* ─── Delete Member ─── */
    $(document).on('click', '.anggota-delete-btn', function() {
        var id = $(this).data('id');
        var name = $(this).data('name');
        if (!confirm('Hapus anggota "' + name + '"? Tindakan ini tidak dapat dibatalkan.')) return;

        var btn = $(this);
        btn.prop('disabled', true).text('Menghapus...');

        $.ajax({
            url: anggotaPsi.ajaxurl,
            type: 'POST',
            data: { action: 'anggota_psi_delete_member', nonce: anggotaPsi.nonce, id: id },
            success: function(res) {
                if (res.success) {
                    btn.closest('tr').fadeOut(300, function() { $(this).remove(); });
                } else {
                    alert(res.data.message);
                    btn.prop('disabled', false).text('Hapus');
                }
            },
            error: function() {
                alert('Terjadi kesalahan jaringan.');
                btn.prop('disabled', false).text('Hapus');
            }
        });
    });

    /* ─── Import File ─── */
    $(document).on('submit', '#anggota-import-form', function(e) {
        e.preventDefault();
        var form = $(this);
        var btn = $('#anggota-import-btn');
        var msg = $('#import-result-msg');
        var fileInput = form.find('[name="import_file"]');

        if (!fileInput[0].files.length) {
            msg.html('<span style="color:#721c24;">Pilih file terlebih dahulu.</span>');
            return;
        }

        var formData = new FormData();
        formData.append('action', 'anggota_psi_import');
        formData.append('nonce', anggotaPsi.nonce);
        formData.append('import_file', fileInput[0].files[0]);

        btn.prop('disabled', true).html('<span class="spinner is-active" style="float:none;"></span> Mengimport...');
        msg.html('');

        $.ajax({
            url: anggotaPsi.ajaxurl,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(res) {
                btn.prop('disabled', false).html('<span class="dashicons dashicons-upload"></span> Import File');
                if (res.success) {
                    msg.html('<span style="color:#155724;font-weight:600;">✓ ' + res.data.message + '</span>');
                    form[0].reset();
                } else {
                    msg.html('<span style="color:#721c24;font-weight:600;">✗ ' + res.data.message + '</span>');
                }
            },
            error: function() {
                btn.prop('disabled', false).html('<span class="dashicons dashicons-upload"></span> Import File');
                msg.html('<span style="color:#721c24;font-weight:600;">✗ Terjadi kesalahan jaringan.</span>');
            }
        });
    });

})(jQuery);
