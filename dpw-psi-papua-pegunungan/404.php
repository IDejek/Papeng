<?php
/**
 * 404 Error Template
 * @package DPW_PSI_Papua_Pegunungan
 */
if ( ! defined( 'ABSPATH' ) ) exit;
get_header();
?>
<section class="dpw-section dpw-404-section">
    <div class="container">
        <div class="dpw-404-content">
            <div class="dpw-404-code">404</div>
            <h1>Halaman Tidak Ditemukan</h1>
            <p>Maaf, halaman yang Anda cari tidak ditemukan atau telah dipindahkan.</p>
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="dpw-btn dpw-btn-red">Kembali ke Beranda</a>
        </div>
    </div>
</section>
<?php get_footer(); ?>
