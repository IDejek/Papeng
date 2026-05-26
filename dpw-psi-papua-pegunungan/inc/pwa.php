<?php
/**
 * PWA Manifest REST API Endpoint
 * @package DPW_PSI_Papua_Pegunungan
 */
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'rest_api_init', function() {
    register_rest_route( 'dpw/v1', '/manifest', array(
        'methods'  => 'GET',
        'callback' => 'dpw_psi_pwa_manifest_api',
        'permission_callback' => '__return_true',
    ) );
});

function dpw_psi_pwa_manifest_api() {
    $logo_url = '';
    if ( has_custom_logo() ) {
        $logo_id  = get_theme_mod( 'custom_logo' );
        $logo_url = wp_get_attachment_image_url( $logo_id, 'full' );
    }
    if ( ! $logo_url ) {
        $logo_url = DPW_PSI_URI . '/assets/icons/icon-512.png';
    }

    return array(
        'name'             => get_bloginfo( 'name' ),
        'short_name'       => 'PSI Papeng',
        'description'      => get_bloginfo( 'description' ),
        'start_url'        => home_url( '/' ),
        'display'          => 'standalone',
        'background_color' => '#ffffff',
        'theme_color'      => get_theme_mod( 'dpw_psi_red', '#D4213D' ),
        'orientation'      => 'portrait-primary',
        'icons'            => array(
            array(
                'src'   => DPW_PSI_URI . '/assets/icons/icon-192.png',
                'sizes' => '192x192',
                'type'  => 'image/png',
                'purpose' => 'any maskable',
            ),
            array(
                'src'   => DPW_PSI_URI . '/assets/icons/icon-512.png',
                'sizes' => '512x512',
                'type'  => 'image/png',
                'purpose' => 'any maskable',
            ),
        ),
    );
}
