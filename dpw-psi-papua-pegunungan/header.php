<?php
/**
 * Header Template
 * @package DPW_PSI_Papua_Pegunungan
 */
if ( ! defined( 'ABSPATH' ) ) exit;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="dpw-header" id="dpw-header">
    <div class="dpw-topbar">
        <div class="container">
            <div class="dpw-topbar-inner">
                <div class="dpw-topbar-left">
                    <span class="dpw-topbar-item"><i class="bi bi-geo-alt"></i> Papua Pegunungan</span>
                    <span class="dpw-topbar-item d-none d-md-inline"><i class="bi bi-envelope"></i> <?php echo esc_html( get_theme_mod( 'dpw_email', 'info@psipapeng.id' ) ); ?></span>
                </div>
                <div class="dpw-topbar-right">
                    <?php
                    $socials = array(
                        'dpw_facebook'  => 'bi-facebook',
                        'dpw_instagram' => 'bi-instagram',
                        'dpw_tiktok'    => 'bi-tiktok',
                        'dpw_youtube'   => 'bi-youtube',
                        'dpw_twitter'   => 'bi-twitter-x',
                    );
                    foreach ( $socials as $key => $icon ) :
                        $url = get_theme_mod( $key, '' );
                        if ( $url ) : ?>
                            <a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener" class="dpw-social-icon" aria-label="<?php echo esc_attr( $key ); ?>"><i class="bi <?php echo esc_attr( $icon ); ?>"></i></a>
                        <?php endif;
                    endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <nav class="dpw-navbar" id="dpw-navbar">
        <div class="container">
            <div class="dpw-navbar-inner">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="dpw-logo" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
                    <?php if ( has_custom_logo() ) :
                        the_custom_logo();
                    else : ?>
                        <span class="dpw-logo-text">
                            <span class="dpw-logo-psi">PSI</span>
                            <span class="dpw-logo-dpw">DPW Papua Pegunungan</span>
                        </span>
                    <?php endif; ?>
                </a>

                <div class="dpw-nav-menu" id="dpw-nav-menu">
                    <?php
                    wp_nav_menu( array(
                        'theme_location' => 'primary',
                        'container'      => false,
                        'menu_class'     => 'dpw-menu',
                        'fallback_cb'    => 'dpw_psi_fallback_menu',
                        'depth'          => 3,
                    ) );
                    ?>
                </div>

                <div class="dpw-nav-cta d-none d-lg-block">
                    <a href="<?php echo esc_url( get_theme_mod( 'dpw_cta_btn_url', 'https://psi.id/menjadi-anggota/' ) ); ?>" class="dpw-btn dpw-btn-red" target="_blank" rel="noopener">Gabung PSI</a>
                </div>

                <button class="dpw-hamburger" id="dpw-hamburger" aria-label="Toggle Menu" aria-expanded="false">
                    <span></span><span></span><span></span>
                </button>
            </div>
        </div>
    </nav>

    <div class="dpw-mobile-menu" id="dpw-mobile-menu">
        <div class="dpw-mobile-menu-inner">
            <?php
            wp_nav_menu( array(
                'theme_location' => 'mobile',
                'container'      => false,
                'menu_class'     => 'dpw-mobile-nav',
                'fallback_cb'    => 'dpw_psi_fallback_menu',
                'depth'          => 2,
            ) );
            ?>
            <a href="<?php echo esc_url( get_theme_mod( 'dpw_cta_btn_url', 'https://psi.id/menjadi-anggota/' ) ); ?>" class="dpw-btn dpw-btn-red dpw-w100" target="_blank" rel="noopener">Gabung PSI</a>
        </div>
    </div>
</header>

<?php
if ( ! function_exists( 'dpw_psi_fallback_menu' ) ) {
function dpw_psi_fallback_menu() {
    $items = array(
        array( 'label' => 'Beranda', 'url' => home_url( '/' ) ),
        array( 'label' => 'Profil', 'url' => home_url( '/profil/' ) ),
        array( 'label' => 'DPD', 'url' => home_url( '/dpd/' ) ),
        array( 'label' => 'Berita', 'url' => home_url( '/berita/' ) ),
        array( 'label' => 'Galeri', 'url' => home_url( '/galeri/' ) ),
        array( 'label' => 'Video', 'url' => home_url( '/video/' ) ),
        array( 'label' => 'Kontak', 'url' => home_url( '/kontak/' ) ),
    );
    echo '<ul class="dpw-menu">';
    foreach ( $items as $item ) {
        $current_path = trailingslashit( $_SERVER['REQUEST_URI'] ?? '/' );
        $item_path = trailingslashit( parse_url( $item['url'], PHP_URL_PATH ) );
        $active = ( $current_path === $item_path ) ? ' current-menu-item' : '';
        echo '<li class="menu-item' . $active . '"><a href="' . esc_url( $item['url'] ) . '">' . esc_html( $item['label'] ) . '</a></li>';
    }
    echo '</ul>';
}
}
