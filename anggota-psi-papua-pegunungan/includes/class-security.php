<?php
/**
 * Security Module
 * @package Anggota_PSI
 */
namespace Anggota_PSI;

if ( ! defined( 'ABSPATH' ) ) exit;

class Security {

    private static $login_attempts_key = 'anggota_psi_login_attempts_';
    private static $max_attempts = 5;
    private static $lockout_time = 900; /* 15 minutes */

    public static function init() {
        add_action( 'wp_login_failed', array( __CLASS__, 'track_failed_login' ) );
        add_filter( 'authenticate', array( __CLASS__, 'check_login_lockout' ), 99, 3 );
        add_action( 'clear_auth_cookie', array( __CLASS__, 'clear_login_attempts' ) );

        /* Disable XML-RPC */
        add_filter( 'xmlrpc_enabled', '__return_false' );

        /* Remove version from scripts/styles */
        add_filter( 'style_loader_src', array( __CLASS__, 'remove_version' ), 9999 );
        add_filter( 'script_loader_src', array( __CLASS__, 'remove_version' ), 9999 );

        /* Disable unneeded headers */
        add_action( 'wp_headers', array( __CLASS__, 'security_headers' ) );

        /* Disable file editing in admin */
        if ( ! defined( 'DISALLOW_FILE_EDIT' ) ) {
            define( 'DISALLOW_FILE_EDIT', true );
        }
    }

    public static function log_action( $action, $details = '' ) {
        global $wpdb;
        $table = Database::get_log_table();
        $wpdb->insert( $table, array(
            'user_id'    => get_current_user_id(),
            'action'     => sanitize_text_field( $action ),
            'details'    => sanitize_text_field( $details ),
            'ip_address' => self::get_ip(),
        ), array( '%d', '%s', '%s', '%s' ) );
    }

    public static function get_ip() {
        $keys = array( 'HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP', 'REMOTE_ADDR' );
        foreach ( $keys as $key ) {
            if ( ! empty( $_SERVER[ $key ] ) ) {
                $ips = explode( ',', sanitize_text_field( wp_unslash( $_SERVER[ $key ] ) ) );
                $ip = trim( $ips[0] );
                if ( filter_var( $ip, FILTER_VALIDATE_IP ) ) return $ip;
            }
        }
        return '0.0.0.0';
    }

    public static function track_failed_login( $username ) {
        $ip = self::get_ip();
        $key = self::$login_attempts_key . $ip;
        $attempts = (int) get_transient( $key );
        set_transient( $key, $attempts + 1, self::$lockout_time );
        self::log_action( 'login_failed', 'Failed login attempt for: ' . sanitize_text_field( $username ) );
    }

    public static function check_login_lockout( $user, $username, $password ) {
        if ( is_wp_error( $user ) ) return $user;

        $ip = self::get_ip();
        $key = self::$login_attempts_key . $ip;
        $attempts = (int) get_transient( $key );

        if ( $attempts >= self::$max_attempts ) {
            return new \WP_Error( 'locked_out', 'Terlalu banyak percobaan login. Silakan coba lagi dalam 15 menit.' );
        }

        return $user;
    }

    public static function clear_login_attempts() {
        $ip = self::get_ip();
        delete_transient( self::$login_attempts_key . $ip );
    }

    public static function remove_version( $src ) {
        if ( strpos( $src, 'ver=' ) ) {
            $src = remove_query_arg( 'ver', $src );
        }
        return $src;
    }

    public static function security_headers( $headers ) {
        unset( $headers['X-Pingback'] );
        $headers['X-Content-Type-Options'] = 'nosniff';
        $headers['X-Frame-Options'] = 'SAMEORIGIN';
        $headers['X-XSS-Protection'] = '1; mode=block';
        $headers['Referrer-Policy'] = 'strict-origin-when-cross-origin';
        return $headers;
    }
}
