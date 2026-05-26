<?php
/**
 * Statistics Module
 * @package Anggota_PSI
 */
namespace Anggota_PSI;

if ( ! defined( 'ABSPATH' ) ) exit;

class Statistics {

    public static function init() {
        /* Stats are handled within Dashboard class for admin.
           This class provides data for frontend shortcodes if needed. */
    }

    public static function get_summary() {
        return array(
            'total_active' => Member::total_count( 'active' ),
            'total_all'    => Member::total_count( 'all' ),
            'regions'      => Member::count_by_region(),
        );
    }

    public static function shortcode_stats( $atts ) {
        $atts = shortcode_atts( array( 'type' => 'total' ), $atts );
        $summary = self::get_summary();

        if ( $atts['type'] === 'total' ) {
            return number_format( $summary['total_active'] );
        }
        return '';
    }
}
add_shortcode( 'psi_member_count', array( 'Anggota_PSI\Statistics', 'shortcode_stats' ) );
