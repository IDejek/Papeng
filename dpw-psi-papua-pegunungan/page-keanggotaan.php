<?php
/**
 * Template Name: Keanggotaan
 * @package DPW_PSI_Papua_Pegunungan
 */
if ( ! defined( 'ABSPATH' ) ) exit;
get_header();
dpw_psi_breadcrumb();

 $cta_url = get_theme_mod( 'dpw_cta_btn_url', 'https://psi.id/menjadi-anggota/' );
?>
<section class="dpw-section dpw-membership-section">
    <div class="container">
        <div class="dpw-membership-content">
            <div class="dpw-membership-icon"><i class="bi bi-person-plus"></i></div>
            <h1>Bergabung dengan PSI</h1>
            <p>Jadilah bagian dari gerakan perubahan untuk Papua Pegunungan yang lebih baik. Bergabunglah sebagai anggota Partai Solidaritas Indonesia.</p>
            <a href="<?php echo esc_url( $cta_url ); ?>" class="dpw-btn dpw-btn-gold dpw-btn-lg" target="_blank" rel="noopener" id="dpw-join-btn">
                Gabung Keanggotaan PSI <i class="bi bi-arrow-right"></i>
            </a>
            <div class="dpw-membership-features">
                <div class="dpw-membership-feature"><i class="bi bi-check-circle"></i> Gratis</div>
                <div class="dpw-membership-feature"><i class="bi bi-check-circle"></i> Akses Program</div>
                <div class="dpw-membership-feature"><i class="bi bi-check-circle"></i> Jaringan Nasional</div>
                <div class="dpw-membership-feature"><i class="bi bi-check-circle"></i> Pelatihan Kader</div>
            </div>
        </div>
    </div>
</section>
<?php get_footer(); ?>
