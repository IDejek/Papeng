<?php
/**
 * Footer Template
 * @package DPW_PSI_Papua_Pegunungan
 */
if ( ! defined( 'ABSPATH' ) ) exit;
?>

<footer class="dpw-footer">
    <!-- Main Footer -->
    <div class="dpw-footer-main">
        <div class="container">
            <div class="dpw-footer-grid">
                <!-- Column 1: Logo & About -->
                <div class="dpw-footer-col dpw-footer-about">
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="dpw-footer-logo">
                        <?php if ( has_custom_logo() ) : the_custom_logo(); else : ?>
                            <span class="dpw-logo-text">
                                <span class="dpw-logo-psi">PSI</span>
                                <span class="dpw-logo-dpw">DPW Papua Pegunungan</span>
                            </span>
                        <?php endif; ?>
                    </a>
                    <p class="dpw-footer-desc"><?php echo esc_html( get_bloginfo( 'description' ) ); ?></p>
                    <div class="dpw-footer-socials">
                        <?php
                        $socials = array( 'dpw_facebook' => 'bi-facebook', 'dpw_instagram' => 'bi-instagram', 'dpw_tiktok' => 'bi-tiktok', 'dpw_youtube' => 'bi-youtube', 'dpw_twitter' => 'bi-twitter-x' );
                        foreach ( $socials as $key => $icon ) :
                            $url = get_theme_mod( $key, '' );
                            if ( $url ) : ?>
                                <a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr( $key ); ?>"><i class="bi <?php echo esc_attr( $icon ); ?>"></i></a>
                            <?php endif;
                        endforeach; ?>
                    </div>
                </div>

                <!-- Column 2: Quick Links -->
                <div class="dpw-footer-col">
                    <?php if ( is_active_sidebar( 'footer-1' ) ) : dynamic_sidebar( 'footer-1' ); else : ?>
                        <h5 class="footer-widget-title">Tautan Cepat</h5>
                        <ul class="dpw-footer-links">
                            <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Beranda</a></li>
                            <li><a href="<?php echo esc_url( home_url( '/profil/' ) ); ?>">Profil</a></li>
                            <li><a href="<?php echo esc_url( home_url( '/dpd/' ) ); ?>">DPD Kabupaten</a></li>
                            <li><a href="<?php echo esc_url( home_url( '/berita/' ) ); ?>">Berita</a></li>
                            <li><a href="<?php echo esc_url( home_url( '/galeri/' ) ); ?>">Galeri</a></li>
                            <li><a href="<?php echo esc_url( home_url( '/video/' ) ); ?>">Video</a></li>
                        </ul>
                    <?php endif; ?>
                </div>

                <!-- Column 3: More Links -->
                <div class="dpw-footer-col">
                    <?php if ( is_active_sidebar( 'footer-2' ) ) : dynamic_sidebar( 'footer-2' ); else : ?>
                        <h5 class="footer-widget-title">Informasi</h5>
                        <ul class="dpw-footer-links">
                            <li><a href="<?php echo esc_url( home_url( '/struktur-organisasi/' ) ); ?>">Struktur Organisasi</a></li>
                            <li><a href="<?php echo esc_url( home_url( '/agenda/' ) ); ?>">Agenda</a></li>
                            <li><a href="<?php echo esc_url( home_url( '/dokumen/' ) ); ?>">Dokumen</a></li>
                            <li><a href="<?php echo esc_url( home_url( '/kontak/' ) ); ?>">Kontak</a></li>
                            <li><a href="https://psi.id" target="_blank" rel="noopener">PSI Pusat</a></li>
                        </ul>
                    <?php endif; ?>
                </div>

                <!-- Column 4: Contact -->
                <div class="dpw-footer-col">
                    <?php if ( is_active_sidebar( 'footer-3' ) ) : dynamic_sidebar( 'footer-3' ); else : ?>
                        <h5 class="footer-widget-title">Hubungi Kami</h5>
                        <ul class="dpw-footer-contact">
                            <?php if ( get_theme_mod( 'dpw_address' ) ) : ?>
                                <li><i class="bi bi-geo-alt"></i> <span><?php echo esc_html( get_theme_mod( 'dpw_address' ) ); ?></span></li>
                            <?php endif; ?>
                            <?php if ( get_theme_mod( 'dpw_email' ) ) : ?>
                                <li><i class="bi bi-envelope"></i> <a href="mailto:<?php echo esc_attr( get_theme_mod( 'dpw_email' ) ); ?>"><?php echo esc_html( get_theme_mod( 'dpw_email' ) ); ?></a></li>
                            <?php endif; ?>
                            <?php if ( get_theme_mod( 'dpw_wa_number' ) ) : ?>
                                <li><i class="bi bi-whatsapp"></i> <a href="https://wa.me/<?php echo esc_attr( preg_replace( '/[^0-9]/', '', get_theme_mod( 'dpw_wa_number' ) ) ); ?>"><?php echo esc_html( get_theme_mod( 'dpw_wa_number' ) ); ?></a></li>
                            <?php endif; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Footer -->
    <div class="dpw-footer-bottom">
        <div class="container">
            <div class="dpw-footer-bottom-inner">
                <p>&copy; <?php echo date( 'Y' ); ?> <?php echo esc_html( get_bloginfo( 'name' ) ); ?>. Hak Cipta Dilindungi.</p>
                <p class="dpw-footer-dev">Dikembangkan oleh <a href="mailto:tombinawaiqbal@gmail.com">tombinawaiqbal@gmail.com</a></p>
            </div>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
