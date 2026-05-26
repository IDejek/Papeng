<?php
/**
 * CAPTCHA Module
 * @package Anggota_PSI
 */
namespace Anggota_PSI;

if ( ! defined( 'ABSPATH' ) ) exit;

class Captcha {

    public static function init() {
        add_action( 'wp_ajax_dpw_captcha_check', array( __CLASS__, 'ajax_check' ) );
        add_action( 'wp_ajax_nopriv_dpw_captcha_check', array( __CLASS__, 'ajax_check' ) );
    }

    public static function generate() {
        $a = rand( 1, 9 );
        $b = rand( 1, 9 );
        $session_key = 'dpw_captcha_' . wp_hash( session_id() );
        set_transient( $session_key, $a + $b, 300 );
        return array( 'a' => $a, 'b' => $b, 'answer' => $a + $b );
    }

    public static function verify( $answer ) {
        $session_key = 'dpw_captcha_' . wp_hash( session_id() );
        $correct = get_transient( $session_key );
        delete_transient( $session_key );
        return (int) $answer === (int) $correct;
    }

    public static function ajax_check() {
        $answer = isset( $_POST['answer'] ) ? absint( $_POST['answer'] ) : 0;
        if ( self::verify( $answer ) ) {
            wp_send_json_success( array( 'valid' => true ) );
        } else {
            wp_send_json_error( array( 'valid' => false, 'message' => 'Jawaban verifikasi salah.' ) );
        }
    }
}
