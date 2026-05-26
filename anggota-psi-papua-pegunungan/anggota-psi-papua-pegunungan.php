<?php
/**
 * Plugin Name: Anggota PSI Papua Pegunungan
 * Plugin URI: https://psipapeng.id
 * Description: Sistem manajemen keanggotaan, statistik dashboard, WhatsApp integration, export/import, security tools, dan CAPTCHA untuk DPW PSI Papua Pegunungan.
 * Version: 1.0.0
 * Author: tombinawaiqbal@gmail.com
 * Author URI: https://psipapeng.id
 * License: GPL v2 or later
 * Text Domain: anggota-psi-papeng
 * Requires PHP: 8.0
 * Requires at least: 6.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'ANGGOTA_PSI_VERSION', '1.0.0' );
define( 'ANGGOTA_PSI_DIR', plugin_dir_path( __FILE__ ) );
define( 'ANGGOTA_PSI_URI', plugin_dir_url( __FILE__ ) );
define( 'ANGGOTA_PSI_BASENAME', plugin_basename( __FILE__ ) );

/* ─── Autoload Modules ─── */
require_once ANGGOTA_PSI_DIR . 'includes/class-database.php';
require_once ANGGOTA_PSI_DIR . 'includes/class-member.php';
require_once ANGGOTA_PSI_DIR . 'includes/class-dashboard.php';
require_once ANGGOTA_PSI_DIR . 'includes/class-statistics.php';
require_once ANGGOTA_PSI_DIR . 'includes/class-export.php';
require_once ANGGOTA_PSI_DIR . 'includes/class-security.php';
require_once ANGGOTA_PSI_DIR . 'includes/class-whatsapp.php';
require_once ANGGOTA_PSI_DIR . 'includes/class-captcha.php';

/* ─── Initialize Plugin ─── */
function anggota_psi_init() {
    Anggota_PSI\Database::init();
    Anggota_PSI\Security::init();
    Anggota_PSI\Captcha::init();
    Anggota_PSI\WhatsApp::init();

    if ( is_admin() ) {
        Anggota_PSI\Dashboard::init();
        Anggota_PSI\Export::init();
    }
}
add_action( 'plugins_loaded', 'anggota_psi_init' );

/* ─── Activation Hook ─── */
function anggota_psi_activate() {
    Anggota_PSI\Database::create_tables();
    $role = get_role( 'administrator' );
    if ( $role ) {
        $role->add_cap( 'manage_anggota' );
        $role->add_cap( 'export_anggota' );
        $role->add_cap( 'import_anggota' );
    }
    $op_role = get_role( 'psi_operator' );
    if ( $op_role ) {
        $op_role->add_cap( 'manage_anggota' );
    }
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'anggota_psi_activate' );

/* ─── Deactivation Hook ─── */
function anggota_psi_deactivate() {
    flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'anggota_psi_deactivate' );

/* ─── Plugin Action Links ─── */
function anggota_psi_action_links( $links ) {
    $custom = array( '<a href="' . admin_url( 'admin.php?page=anggota-psi-dashboard' ) . '">Dashboard</a>' );
    return array_merge( $custom, $links );
}
add_filter( 'plugin_action_links_' . ANGGOTA_PSI_BASENAME, 'anggota_psi_action_links' );
