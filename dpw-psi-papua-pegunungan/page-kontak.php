<?php
/**
 * Template Name: Kontak
 * @package DPW_PSI_Papua_Pegunungan
 */
if ( ! defined( 'ABSPATH' ) ) exit;
get_header();
dpw_psi_breadcrumb();
?>
<section class="dpw-section dpw-contact-section">
    <div class="container">
        <div class="dpw-contact-header">
            <h1 class="dpw-page-title">Hubungi Kami</h1>
            <p class="dpw-contact-intro">Silakan hubungi kami melalui formulir di bawah ini atau melalui informasi kontak yang tersedia.</p>
        </div>

        <div class="dpw-contact-grid">
            <!-- Contact Info -->
            <div class="dpw-contact-info">
                <div class="dpw-contact-card">
                    <div class="dpw-contact-icon"><i class="bi bi-geo-alt"></i></div>
                    <div>
                        <h4>Alamat Kantor</h4>
                        <p><?php echo esc_html( get_theme_mod( 'dpw_address', 'Papua Pegunungan, Indonesia' ) ); ?></p>
                    </div>
                </div>
                <div class="dpw-contact-card">
                    <div class="dpw-contact-icon"><i class="bi bi-envelope"></i></div>
                    <div>
                        <h4>Email</h4>
                        <p><a href="mailto:<?php echo esc_attr( get_theme_mod( 'dpw_email', 'info@psipapeng.id' ) ); ?>"><?php echo esc_html( get_theme_mod( 'dpw_email', 'info@psipapeng.id' ) ); ?></a></p>
                    </div>
                </div>
                <div class="dpw-contact-card">
                    <div class="dpw-contact-icon"><i class="bi bi-whatsapp"></i></div>
                    <div>
                        <h4>WhatsApp</h4>
                        <p><a href="https://wa.me/<?php echo esc_attr( preg_replace( '/[^0-9]/', '', get_theme_mod( 'dpw_wa_number', '082267218125' ) ) ); ?>"><?php echo esc_html( get_theme_mod( 'dpw_wa_number', '082267218125' ) ); ?></a></p>
                    </div>
                </div>

                <!-- Google Maps -->
                <?php $maps = get_theme_mod( 'dpw_maps_embed', '' ); if ( $maps ) : ?>
                    <div class="dpw-contact-maps">
                        <?php echo $maps; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Contact Form -->
            <div class="dpw-contact-form-wrap">
                <form id="dpw-contact-form" class="dpw-contact-form" method="post">
                    <?php wp_nonce_field( 'dpw_psi_nonce', 'contact_nonce' ); ?>
                    <div class="dpw-form-group">
                        <label for="contact-name">Nama Lengkap <span class="required">*</span></label>
                        <input type="text" id="contact-name" name="name" required placeholder="Masukkan nama lengkap">
                    </div>
                    <div class="dpw-form-group">
                        <label for="contact-email">Email <span class="required">*</span></label>
                        <input type="email" id="contact-email" name="email" required placeholder="Masukkan email">
                    </div>
                    <div class="dpw-form-group">
                        <label for="contact-subject">Subjek</label>
                        <input type="text" id="contact-subject" name="subject" placeholder="Masukkan subjek">
                    </div>
                    <div class="dpw-form-group">
                        <label for="contact-message">Pesan <span class="required">*</span></label>
                        <textarea id="contact-message" name="message" rows="6" required placeholder="Tulis pesan Anda..."></textarea>
                    </div>
                    <!-- Simple CAPTCHA -->
                    <div class="dpw-form-group dpw-captcha-group">
                        <label for="contact-captcha">Verifikasi: <?php echo esc_html( (int)dpw_psi_get_captcha_a() . ' + ' . (int)dpw_psi_get_captcha_b() ); ?> = ? <span class="required">*</span></label>
                        <input type="number" id="contact-captcha" name="captcha" required placeholder="Jawaban">
                    </div>
                    <button type="submit" class="dpw-btn dpw-btn-red dpw-w100" id="dpw-contact-submit">
                        <span class="dpw-btn-text">Kirim Pesan</span>
                        <span class="dpw-btn-loading" style="display:none;"><i class="bi bi-arrow-repeat spin"></i> Mengirim...</span>
                    </button>
                    <div id="dpw-contact-result" style="margin-top:1rem;display:none;"></div>
                </form>
            </div>
        </div>
    </div>
</section>
<?php
function dpw_psi_get_captcha_a() { return rand( 1, 9 ); }
function dpw_psi_get_captcha_b() { return rand( 1, 9 ); }
get_footer(); ?>
