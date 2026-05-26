<?php
/**
 * WordPress Cleanup & Performance
 * @package DPW_PSI_Papua_Pegunungan
 */
if ( ! defined( 'ABSPATH' ) ) exit;

/* ─── Remove unnecessary head tags ─── */
add_action( 'init', function() {
    remove_action( 'wp_head', 'wp_generator' );
    remove_action( 'wp_head', 'wlwmanifest_link' );
    remove_action( 'wp_head', 'rsd_link' );
    remove_action( 'wp_head', 'wp_shortlink_wp_head' );
    remove_action( 'wp_head', 'rest_output_link_wp_head' );
    remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
    remove_action( 'wp_head', 'wp_oembed_add_host_js' );
    remove_action( 'template_redirect', 'rest_output_link_header', 11 );
}, 20 );

/* ─── Disable emojis ─── */
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );
remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
add_filter( 'tiny_mce_plugins', function( $plugins ) {
    return is_array( $plugins ) ? array_diff( $plugins, array( 'wpemoji' ) ) : array();
});
add_filter( 'wp_resource_hints', function( $urls, $relation_type ) {
    if ( 'dns-prefetch' === $relation_type ) {
        $emoji_url = apply_filters( 'emoji_svg_url', 'https://s.w.org/images/core/emoji/' );
        $urls = array_filter( $urls, function( $url ) use ( $emoji_url ) {
            return ! is_string( $url ) || strpos( $url, $emoji_url ) === false;
        });
    }
    return $urls;
}, 10, 2 );

/* ─── Lazy loading for images (native, already default in WP 5.5+) ─── */
add_filter( 'wp_get_attachment_image_attributes', function( $attr ) {
    $attr['loading'] = 'lazy';
    return $attr;
});

/* ─── Add fetchpriority="high" for hero/above-fold images ─── */
add_filter( 'wp_get_attachment_image_attributes', function( $attr, $attachment ) {
    /* Only for the first image in hero context — handled via custom code if needed */
    return $attr;
}, 10, 2 );

/* ─── Defer non-essential scripts ─── */
add_filter( 'script_loader_tag', function( $tag, $handle ) {
    $defer_scripts = array( 'dpw-main' );
    if ( in_array( $handle, $defer_scripts, true ) ) {
        return str_replace( ' src', ' defer src', $tag );
    }
    return $tag;
}, 10, 2 );

/* ─── Preload critical fonts ─── */
add_action( 'wp_head', function() {
    echo '<link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
}, 1 );

/* ─── Disable jQuery Migrate on frontend ─── */
add_action( 'wp_default_scripts', function( $scripts ) {
    if ( ! is_admin() && isset( $scripts->registered['jquery'] ) ) {
        $scripts->registered['jquery']->deps = array_diff(
            $scripts->registered['jquery']->deps,
            array( 'jquery-migrate' )
        );
    }
});

/* ─── Limit post revisions ─── */
if ( ! defined( 'WP_POST_REVISIONS' ) ) {
    define( 'WP_POST_REVISIONS', 5 );
}

/* ─── Set autosave interval ─── */
if ( ! defined( 'AUTOSAVE_INTERVAL' ) ) {
    define( 'AUTOSAVE_INTERVAL', 120 );
}

/* ─── Disable self pingback ─── */
add_action( 'pre_ping', function( &$links ) {
    $home = get_option( 'home' );
    foreach ( $links as $l => $link ) {
        if ( strpos( $link, $home ) === 0 ) {
            unset( $links[$l] );
        }
    }
});

/* ─── Remove default WordPress widgets we don't need ─── */
add_action( 'widgets_init', function() {
    unregister_widget( 'WP_Widget_Pages' );
    unregister_widget( 'WP_Widget_Calendar' );
    unregister_widget( 'WP_Widget_Tag_Cloud' );
    unregister_widget( 'WP_Widget_RSS' );
}, 20 );
