<?php
/**
 * Export / Import Module
 * @package Anggota_PSI
 */
namespace Anggota_PSI;

if ( ! defined( 'ABSPATH' ) ) exit;

class Export {

    public static function init() {
        add_action( 'wp_ajax_anggota_psi_export_csv', array( __CLASS__, 'export_csv' ) );
        add_action( 'wp_ajax_anggota_psi_export_excel', array( __CLASS__, 'export_excel' ) );
        add_action( 'wp_ajax_anggota_psi_import', array( __CLASS__, 'ajax_import' ) );
    }

    private static function get_export_data() {
        global $wpdb;
        $table = Database::get_members_table();
        return $wpdb->get_results( "SELECT member_id, full_name, nik, email, phone, dpd_region, address, join_date, status, notes FROM {$table} ORDER BY created_at DESC", ARRAY_A );
    }

    public static function export_csv() {
        check_ajax_referer( 'anggota_psi_nonce', 'nonce' );
        if ( ! current_user_can( 'export_anggota' ) ) wp_die( 'Akses ditolak.' );

        $rows = self::get_export_data();
        $headers = array( 'ID Anggota', 'Nama Lengkap', 'NIK', 'Email', 'Telepon', 'DPD Wilayah', 'Alamat', 'Tanggal Bergabung', 'Status', 'Catatan' );

        $filename = 'anggota-psi-papua-pegunungan-' . date( 'Y-m-d' ) . '.csv';

        header( 'Content-Type: text/csv; charset=utf-8' );
        header( 'Content-Disposition: attachment; filename="' . $filename . '"' );
        header( 'Cache-Control: no-cache, no-store, must-revalidate' );

        $output = fopen( 'php://output', 'w' );
        fprintf( $output, chr(0xEF) . chr(0xBB) . chr(0xBF) );
        fputcsv( $output, $headers );

        foreach ( $rows as $row ) {
            fputcsv( $output, array_values( $row ) );
        }

        fclose( $output );
        exit;
    }

    public static function export_excel() {
        check_ajax_referer( 'anggota_psi_nonce', 'nonce' );
        if ( ! current_user_can( 'export_anggota' ) ) wp_die( 'Akses ditolak.' );

        $rows = self::get_export_data();
        $headers = array( 'ID Anggota', 'Nama Lengkap', 'NIK', 'Email', 'Telepon', 'DPD Wilayah', 'Alamat', 'Tanggal Bergabung', 'Status', 'Catatan' );

        $filename = 'anggota-psi-papua-pegunungan-' . date( 'Y-m-d' ) . '.csv';

        header( 'Content-Type: application/vnd.ms-excel; charset=utf-8' );
        header( 'Content-Disposition: attachment; filename="' . $filename . '"' );
        header( 'Cache-Control: no-cache, no-store, must-revalidate' );

        $output = fopen( 'php://output', 'w' );
        fprintf( $output, chr(0xEF) . chr(0xBB) . chr(0xBF) );
        fputcsv( $output, $headers, "\t" );

        foreach ( $rows as $row ) {
            fputcsv( $output, array_values( $row ), "\t" );
        }

        fclose( $output );
        exit;
    }

    public static function ajax_import() {
        check_ajax_referer( 'anggota_psi_nonce', 'nonce' );
        if ( ! current_user_can( 'import_anggota' ) ) wp_send_json_error( array( 'message' => 'Akses ditolak.' ) );

        if ( empty( $_FILES['import_file'] ) ) {
            wp_send_json_error( array( 'message' => 'File tidak ditemukan.' ) );
        }

        $file = $_FILES['import_file'];
        $ext = strtolower( pathinfo( $file['name'], PATHINFO_EXTENSION ) );

        if ( ! in_array( $ext, array( 'csv' ) ) ) {
            wp_send_json_error( array( 'message' => 'Hanya file CSV yang didukung.' ) );
        }

        if ( $file['size'] > 5 * 1024 * 1024 ) {
            wp_send_json_error( array( 'message' => 'Ukuran file maksimal 5MB.' ) );
        }

        $handle = fopen( $file['tmp_name'], 'r' );
        if ( ! $handle ) {
            wp_send_json_error( array( 'message' => 'Gagal membaca file.' ) );
        }

        /* Skip BOM if present */
        $bom = fread( $handle, 3 );
        if ( $bom !== "\xEF\xBB\xBF" ) {
            rewind( $handle );
        }

        $headers = fgetcsv( $handle, 1000, ',' );
        if ( ! $headers ) {
            fclose( $handle );
            wp_send_json_error( array( 'message' => 'Format file tidak valid.' ) );
        }

        /* Normalize headers */
        $header_map = array();
        $known = array(
            'nama lengkap' => 'Nama Lengkap', 'nama' => 'Nama Lengkap', 'full_name' => 'Nama Lengkap', 'name' => 'Nama Lengkap',
            'nik' => 'NIK', 'nomor induk kependudukan' => 'NIK',
            'email' => 'Email', 'e-mail' => 'Email',
            'telepon' => 'Telepon', 'phone' => 'Telepon', 'no telepon' => 'Telepon', 'whatsapp' => 'Telepon',
            'dpd' => 'DPD', 'dpd wilayah' => 'DPD', 'wilayah' => 'DPD', 'dpd_region' => 'DPD', 'kabupaten' => 'DPD',
            'alamat' => 'Alamat', 'address' => 'Alamat',
            'tanggal bergabung' => 'Tanggal Bergabung', 'join_date' => 'Tanggal Bergabung', 'tgl gabung' => 'Tanggal Bergabung',
        );

        foreach ( $headers as $idx => $h ) {
            $lower = strtolower( trim( $h ) );
            $header_map[ $idx ] = $known[ $lower ] ?? trim( $h );
        }

        $rows = array();
        while ( ( $data = fgetcsv( $handle, 1000, ',' ) ) !== false ) {
            $row = array();
            foreach ( $header_map as $idx => $label ) {
                $row[ $label ] = $data[ $idx ] ?? '';
            }
            $rows[] = $row;
        }
        fclose( $handle );

        $result = Member::import_from_array( $rows );
        $msg = 'Berhasil import ' . $result['imported'] . ' anggota.';
        if ( ! empty( $result['errors'] ) ) {
            $msg .= ' Error: ' . implode( '; ', array_slice( $result['errors'], 0, 5 ) );
        }

        wp_send_json_success( array( 'message' => $msg, 'imported' => $result['imported'] ) );
    }
}
