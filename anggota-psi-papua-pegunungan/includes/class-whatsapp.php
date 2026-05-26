<?php
/**
 * WhatsApp Integration Module
 * @package Anggota_PSI
 */
namespace Anggota_PSI;

if ( ! defined( 'ABSPATH' ) ) exit;

class WhatsApp {

    public static function init() {
        /* WhatsApp integration is primarily handled by the theme's floating button.
           This module provides additional shortcodes and utilities. */
        add_shortcode( 'psi_wa_link', array( __CLASS__, 'shortcode_wa_link' ) );
        add_shortcode( 'psi_wa_button', array( __CLASS__, 'shortcode_wa_button' ) );
    }

    public static function generate_wa_link( $number = '', $message = '' ) {
        if ( empty( $number ) ) {
            $number = get_theme_mod( 'dpw_wa_float_number', '082267218125' );
        }
        if ( empty( $message ) ) {
            $message = get_theme_mod( 'dpw_wa_float_greeting', 'Halo, saya tertarik dengan PSI Papua Pegunungan.' );
        }
        $clean = preg_replace( '/[^0-9]/', '', $number );
        return 'https://wa.me/' . $clean . '?text=' . urlencode( $message );
    }

    public static function shortcode_wa_link( $atts ) {
        $atts = shortcode_atts( array(
            'number'  => '',
            'message' => '',
            'text'    => 'Chat WhatsApp',
        ), $atts );
        return '<a href="' . esc_url( self::generate_wa_link( $atts['number'], $atts['message'] ) ) . '" target="_blank" rel="noopener">' . esc_html( $atts['text'] ) . '</a>';
    }

    public static function shortcode_wa_button( $atts ) {
        $atts = shortcode_atts( array(
            'number'  => '',
            'message' => '',
            'text'    => 'Chat WhatsApp',
            'class'   => '',
        ), $atts );
        $class = ! empty( $atts['class'] ) ? esc_attr( $atts['class'] ) : 'dpw-btn dpw-btn-red';
        return '<a href="' . esc_url( self::generate_wa_link( $atts['number'], $atts['message'] ) ) . '" target="_blank" rel="noopener" class="' . $class . '">' . esc_html( $atts['text'] ) . '</a>';
    }
}
