<?php
/**
 * Member Management
 * @package Anggota_PSI
 */
namespace Anggota_PSI;

if ( ! defined( 'ABSPATH' ) ) exit;

class Member {

    public static function generate_member_id() {
        global $wpdb;
        $table = Database::get_members_table();
        $year = date( 'Y' );
        $prefix = 'PSI-PP-' . $year . '-';

        $last = $wpdb->get_var( $wpdb->prepare(
            "SELECT member_id FROM {$table} WHERE member_id LIKE %s ORDER BY id DESC LIMIT 1",
            $wpdb->esc_like( $prefix ) . '%'
        ) );

        if ( $last ) {
            $parts = explode( '-', $last );
            $num = intval( end( $parts ) ) + 1;
        } else {
            $num = 1;
        }

        return $prefix . str_pad( $num, 4, '0', STR_PAD_LEFT );
    }

    public static function add( $data ) {
        global $wpdb;
        $table = Database::get_members_table();

        $member_id = self::generate_member_id();

        $result = $wpdb->insert( $table, array(
            'member_id'  => $member_id,
            'full_name'  => sanitize_text_field( $data['full_name'] ?? '' ),
            'nik'        => sanitize_text_field( $data['nik'] ?? '' ),
            'email'      => sanitize_email( $data['email'] ?? '' ),
            'phone'      => sanitize_text_field( $data['phone'] ?? '' ),
            'dpd_region' => sanitize_text_field( $data['dpd_region'] ?? '' ),
            'address'    => sanitize_textarea_field( $data['address'] ?? '' ),
            'join_date'  => ! empty( $data['join_date'] ) ? sanitize_text_field( $data['join_date'] ) : current_time( 'Y-m-d' ),
            'status'     => sanitize_text_field( $data['status'] ?? 'active' ),
            'notes'      => sanitize_textarea_field( $data['notes'] ?? '' ),
        ), array( '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s' ) );

        if ( $result ) {
            Security::log_action( 'member_added', 'Added member: ' . $member_id );
            return $member_id;
        }
        return false;
    }

    public static function update( $id, $data ) {
        global $wpdb;
        $table = Database::get_members_table();

        $fields = array();
        $formats = array();

        $allowed = array( 'full_name', 'nik', 'email', 'phone', 'dpd_region', 'address', 'join_date', 'status', 'notes' );
        foreach ( $allowed as $key ) {
            if ( isset( $data[ $key ] ) ) {
                $fields[ $key ] = sanitize_text_field( $data[ $key ] );
                $formats[] = '%s';
            }
        }

        if ( empty( $fields ) ) return false;

        $result = $wpdb->update( $table, $fields, array( 'id' => absint( $id ) ), $formats, array( '%d' ) );

        if ( $result !== false ) {
            Security::log_action( 'member_updated', 'Updated member ID: ' . $id );
        }
        return $result;
    }

    public static function delete( $id ) {
        global $wpdb;
        $table = Database::get_members_table();
        $result = $wpdb->delete( $table, array( 'id' => absint( $id ) ), array( '%d' ) );
        if ( $result ) {
            Security::log_action( 'member_deleted', 'Deleted member ID: ' . $id );
        }
        return $result;
    }

    public static function get( $id ) {
        global $wpdb;
        $table = Database::get_members_table();
        return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table} WHERE id = %d", absint( $id ) ) );
    }

    public static function get_all( $args = array() ) {
        global $wpdb;
        $table = Database::get_members_table();

        $per_page = isset( $args['per_page'] ) ? absint( $args['per_page'] ) : 20;
        $page = isset( $args['page'] ) ? max( 1, absint( $args['page'] ) ) : 1;
        $offset = ( $page - 1 ) * $per_page;

        $where = '1=1';
        $values = array();

        if ( ! empty( $args['search'] ) ) {
            $where .= ' AND (full_name LIKE %s OR member_id LIKE %s OR email LIKE %s OR phone LIKE %s)';
            $s = '%' . $wpdb->esc_like( $args['search'] ) . '%';
            $values[] = $s; $values[] = $s; $values[] = $s; $values[] = $s;
        }
        if ( ! empty( $args['dpd_region'] ) ) {
            $where .= ' AND dpd_region = %s';
            $values[] = sanitize_text_field( $args['dpd_region'] );
        }
        if ( ! empty( $args['status'] ) ) {
            $where .= ' AND status = %s';
            $values[] = sanitize_text_field( $args['status'] );
        }

        $total = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$table} WHERE {$where}", $values ) );

        $order_by = ! empty( $args['orderby'] ) ? sanitize_text_field( $args['orderby'] ) : 'created_at';
        $order = ! empty( $args['order'] ) ? sanitize_text_field( $args['order'] ) : 'DESC';

        if ( ! empty( $values ) ) {
            $rows = $wpdb->get_results( $wpdb->prepare(
                "SELECT * FROM {$table} WHERE {$where} ORDER BY {$order_by} {$order} LIMIT %d OFFSET %d",
                array_merge( $values, array( $per_page, $offset ) )
            ) );
        } else {
            $rows = $wpdb->get_results( "SELECT * FROM {$table} WHERE {$where} ORDER BY {$order_by} {$order} LIMIT {$per_page} OFFSET {$offset}" );
        }

        return array(
            'rows' => $rows,
            'total' => (int) $total,
            'per_page' => $per_page,
            'page' => $page,
            'total_pages' => ceil( $total / $per_page ),
        );
    }

    public static function count_by_region() {
        global $wpdb;
        $table = Database::get_members_table();
        return $wpdb->get_results( "SELECT dpd_region, COUNT(*) as total FROM {$table} WHERE status = 'active' GROUP BY dpd_region ORDER BY total DESC" );
    }

    public static function get_regions() {
        global $wpdb;
        $table = Database::get_members_table();
        $regions = $wpdb->get_col( "SELECT DISTINCT dpd_region FROM {$table} WHERE dpd_region != '' ORDER BY dpd_region" );
        return $regions ? $regions : array();
    }

    public static function total_count( $status = 'active' ) {
        global $wpdb;
        $table = Database::get_members_table();
        if ( $status === 'all' ) {
            return (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$table}" );
        }
        return (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$table} WHERE status = %s", $status ) );
    }

    public static function import_from_array( $rows ) {
        $imported = 0;
        $errors = array();
        foreach ( $rows as $idx => $row ) {
            $full_name = sanitize_text_field( $row['Nama Lengkap'] ?? $row['nama'] ?? $row['full_name'] ?? '' );
            if ( empty( $full_name ) ) {
                $errors[] = 'Baris ' . ( $idx + 2 ) . ': Nama kosong, dilewati.';
                continue;
            }
            $result = self::add( array(
                'full_name'  => $full_name,
                'nik'        => sanitize_text_field( $row['NIK'] ?? $row['nik'] ?? '' ),
                'email'      => sanitize_email( $row['Email'] ?? $row['email'] ?? '' ),
                'phone'      => sanitize_text_field( $row['Telepon'] ?? $row['phone'] ?? '' ),
                'dpd_region' => sanitize_text_field( $row['DPD'] ?? $row['dpd_region'] ?? $row['Wilayah'] ?? '' ),
                'address'    => sanitize_textarea_field( $row['Alamat'] ?? $row['address'] ?? '' ),
                'join_date'  => ! empty( $row['Tanggal Bergabung'] ) ? sanitize_text_field( $row['Tanggal Bergabung'] ) : '',
            ) );
            if ( $result ) $imported++;
        }
        return array( 'imported' => $imported, 'errors' => $errors );
    }
}
