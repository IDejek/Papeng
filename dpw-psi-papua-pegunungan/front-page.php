<?php
/**
 * Front Page / Homepage Template
 * @package DPW_PSI_Papua_Pegunungan
 */
if ( ! defined( 'ABSPATH' ) ) exit;
get_header();

/* ─── Hero Slider ─── */
 $slides = array();
for ( $i = 1; $i <= 5; $i++ ) {
    $img = get_theme_mod( "dpw_hero_{$i}_image", '' );
    if ( $img ) {
        $slides[] = array(
            'image'    => $img,
            'title'    => get_theme_mod( "dpw_hero_{$i}_title", '' ),
            'subtitle' => get_theme_mod( "dpw_hero_{$i}_subtitle", '' ),
            'btn_text' => get_theme_mod( "dpw_hero_{$i}_btn_text", '' ),
            'btn_url'  => get_theme_mod( "dpw_hero_{$i}_btn_url", '' ),
        );
    }
}
if ( empty( $slides ) ) {
    $slides[] = array(
        'image'    => DPW_PSI_URI . '/assets/images/hero-default.jpg',
        'title'    => 'DPW PSI Papua Pegunungan',
        'subtitle' => 'Solidaritas untuk Perubahan Nyata di Tanah Papua Pegunungan',
        'btn_text' => 'Bergabung Sekarang',
        'btn_url'  => 'https://psi.id/menjadi-anggota/',
    );
}
?>

<!-- Hero Slider -->
<section class="dpw-hero" id="dpw-hero">
    <div class="dpw-hero-slides">
        <?php foreach ( $slides as $idx => $slide ) : ?>
            <div class="dpw-hero-slide <?php echo $idx === 0 ? 'active' : ''; ?>" style="background-image:url('<?php echo esc_url( $slide['image'] ); ?>');">
                <div class="dpw-hero-overlay"></div>
                <div class="container">
                    <div class="dpw-hero-content">
                        <?php if ( $slide['title'] ) : ?><h1 class="dpw-hero-title"><?php echo esc_html( $slide['title'] ); ?></h1><?php endif; ?>
                        <?php if ( $slide['subtitle'] ) : ?><p class="dpw-hero-subtitle"><?php echo esc_html( $slide['subtitle'] ); ?></p><?php endif; ?>
                        <?php if ( $slide['btn_text'] && $slide['btn_url'] ) : ?>
                            <a href="<?php echo esc_url( $slide['btn_url'] ); ?>" class="dpw-btn dpw-btn-gold" <?php echo ( strpos( $slide['btn_url'], 'http' ) === 0 ) ? 'target="_blank" rel="noopener"' : ''; ?>><?php echo esc_html( $slide['btn_text'] ); ?> <i class="bi bi-arrow-right"></i></a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php if ( count( $slides ) > 1 ) : ?>
        <div class="dpw-hero-dots">
            <?php foreach ( $slides as $idx => $s ) : ?>
                <button class="dpw-hero-dot <?php echo $idx === 0 ? 'active' : ''; ?>" data-slide="<?php echo $idx; ?>" aria-label="Slide <?php echo $idx + 1; ?>"></button>
            <?php endforeach; ?>
        </div>
        <button class="dpw-hero-arrow dpw-hero-prev" aria-label="Previous"><i class="bi bi-chevron-left"></i></button>
        <button class="dpw-hero-arrow dpw-hero-next" aria-label="Next"><i class="bi bi-chevron-right"></i></button>
    <?php endif; ?>
</section>

<!-- Welcome / Sambutan Ketua -->
<section class="dpw-section dpw-welcome-section">
    <div class="container">
        <div class="dpw-section-header">
            <span class="dpw-section-badge">Sambutan Pimpinan</span>
            <h2 class="dpw-section-title">Selamat Datang</h2>
        </div>
        <div class="dpw-welcome-grid">
            <div class="dpw-welcome-text">
                <p class="dpw-welcome-message"><?php echo esc_html( get_theme_mod( 'dpw_welcome_text', 'Selamat datang di website resmi DPW PSI Papua Pegunungan. Kami berkomitmen untuk membawa perubahan nyata bagi masyarakat Papua Pegunungan melalui solidaritas dan aksi nyata.' ) ); ?></p>
            </div>
            <div class="dpw-welcome-leaders">
                <?php
                $leaders = array(
                    array( 'photo' => 'dpw_chairman_photo', 'name' => 'dpw_chairman_name', 'label' => 'Ketua' ),
                    array( 'photo' => 'dpw_secretary_photo', 'name' => 'dpw_secretary_name', 'label' => 'Sekretaris' ),
                    array( 'photo' => 'dpw_treasurer_photo', 'name' => 'dpw_treasurer_name', 'label' => 'Bendahara' ),
                );
                foreach ( $leaders as $leader ) :
                    $photo = get_theme_mod( $leader['photo'], '' );
                    $name  = get_theme_mod( $leader['name'], '' );
                    if ( $name ) : ?>
                        <div class="dpw-welcome-leader-card">
                            <div class="dpw-leader-photo">
                                <?php if ( $photo ) : ?>
                                    <img src="<?php echo esc_url( $photo ); ?>" alt="<?php echo esc_attr( $name ); ?>" loading="lazy">
                                <?php else : ?>
                                    <div class="dpw-leader-placeholder"><i class="bi bi-person"></i></div>
                                <?php endif; ?>
                            </div>
                            <h4 class="dpw-leader-name"><?php echo esc_html( $name ); ?></h4>
                            <span class="dpw-leader-position"><?php echo esc_html( $leader['label'] ); ?></span>
                        </div>
                    <?php endif;
                endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- Latest News -->
<section class="dpw-section dpw-news-section dpw-section-gray">
    <div class="container">
        <div class="dpw-section-header">
            <span class="dpw-section-badge">Berita Terkini</span>
            <h2 class="dpw-section-title">Berita & Informasi</h2>
            <a href="<?php echo esc_url( get_post_type_archive_link( 'psi_news' ) ); ?>" class="dpw-section-link">Lihat Semua <i class="bi bi-arrow-right"></i></a>
        </div>
        <div class="dpw-news-grid">
            <?php
            $news_query = new WP_Query( array(
                'post_type'      => 'psi_news',
                'posts_per_page' => 6,
                'post_status'    => 'publish',
            ) );
            if ( $news_query->have_posts() ) :
                $count = 0;
                while ( $news_query->have_posts() ) : $news_query->the_post();
                    $count++;
                    $featured = get_post_meta( get_the_ID(), '_news_featured', true );
                    if ( $count === 1 ) : ?>
                        <div class="dpw-news-featured">
                            <a href="<?php the_permalink(); ?>" class="dpw-news-card dpw-news-card-large">
                                <div class="dpw-news-card-img">
                                    <?php if ( has_post_thumbnail() ) : the_post_thumbnail( 'dpw-hero' ); else : ?>
                                        <div class="dpw-no-img"><i class="bi bi-newspaper"></i></div>
                                    <?php endif; ?>
                                    <span class="dpw-news-badge-featured">Berita Utama</span>
                                </div>
                                <div class="dpw-news-card-body">
                                    <?php $cats = get_the_terms( get_the_ID(), 'news_category' ); if ( $cats && ! is_wp_error( $cats ) ) : ?>
                                        <span class="dpw-news-cat"><?php echo esc_html( $cats[0]->name ); ?></span>
                                    <?php endif; ?>
                                    <h3><?php the_title(); ?></h3>
                                    <p><?php echo esc_html( get_the_excerpt() ); ?></p>
                                    <div class="dpw-news-meta">
                                        <span><i class="bi bi-calendar3"></i> <?php echo get_the_date(); ?></span>
                                        <span><i class="bi bi-person"></i> <?php echo get_the_author(); ?></span>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="dpw-news-list">
                    <?php else : ?>
                            <a href="<?php the_permalink(); ?>" class="dpw-news-card dpw-news-card-small">
                                <div class="dpw-news-card-img">
                                    <?php if ( has_post_thumbnail() ) : the_post_thumbnail( 'dpw-thumb' ); else : ?>
                                        <div class="dpw-no-img"><i class="bi bi-newspaper"></i></div>
                                    <?php endif; ?>
                                </div>
                                <div class="dpw-news-card-body">
                                    <?php $cats = get_the_terms( get_the_ID(), 'news_category' ); if ( $cats && ! is_wp_error( $cats ) ) : ?>
                                        <span class="dpw-news-cat"><?php echo esc_html( $cats[0]->name ); ?></span>
                                    <?php endif; ?>
                                    <h4><?php the_title(); ?></h4>
                                    <span class="dpw-news-date"><i class="bi bi-calendar3"></i> <?php echo get_the_date(); ?></span>
                                </div>
                            </a>
                    <?php endif;
                endwhile;
                if ( $count > 1 ) echo '</div>';
                wp_reset_postdata();
            else : ?>
                <p class="dpw-empty">Belum ada berita.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Activity Videos -->
<?php
 $video_query = new WP_Query( array( 'post_type' => 'psi_video', 'posts_per_page' => 3, 'post_status' => 'publish' ) );
if ( $video_query->have_posts() ) : ?>
<section class="dpw-section dpw-video-section">
    <div class="container">
        <div class="dpw-section-header">
            <span class="dpw-section-badge">Kegiatan</span>
            <h2 class="dpw-section-title">Video Kegiatan</h2>
            <a href="<?php echo esc_url( get_post_type_archive_link( 'psi_video' ) ); ?>" class="dpw-section-link">Lihat Semua <i class="bi bi-arrow-right"></i></a>
        </div>
        <div class="dpw-video-grid">
            <?php while ( $video_query->have_posts() ) : $video_query->the_post();
                $video_url = get_post_meta( get_the_ID(), '_video_url', true );
                $embed = '';
                if ( $video_url ) {
                    preg_match( '/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]+)/', $video_url, $matches );
                    if ( ! empty( $matches[1] ) ) $embed = 'https://www.youtube.com/embed/' . $matches[1];
                }
                ?>
                <div class="dpw-video-card">
                    <div class="dpw-video-thumb">
                        <?php if ( $embed ) : ?>
                            <div class="dpw-video-embed" data-src="<?php echo esc_url( $embed ); ?>">
                                <img src="https://img.youtube.com/vi/<?php echo esc_attr( $matches[1] ?? '' ); ?>/hqdefault.jpg" alt="<?php echo esc_attr( get_the_title() ); ?>" loading="lazy">
                                <div class="dpw-play-btn"><i class="bi bi-play-fill"></i></div>
                            </div>
                        <?php elseif ( has_post_thumbnail() ) : the_post_thumbnail( 'dpw-thumb' ); endif; ?>
                    </div>
                    <h4 class="dpw-video-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                </div>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Organization Leaders -->
<?php
 $org_query = new WP_Query( array( 'post_type' => 'psi_org', 'posts_per_page' => 8, 'orderby' => 'menu_order', 'order' => 'ASC', 'post_status' => 'publish' ) );
if ( $org_query->have_posts() ) : ?>
<section class="dpw-section dpw-org-section dpw-section-gray">
    <div class="container">
        <div class="dpw-section-header">
            <span class="dpw-section-badge">Pimpinan</span>
            <h2 class="dpw-section-title">Pimpinan Organisasi</h2>
            <a href="<?php echo esc_url( home_url( '/struktur-organisasi/' ) ); ?>" class="dpw-section-link">Selengkapnya <i class="bi bi-arrow-right"></i></a>
        </div>
        <div class="dpw-org-grid">
            <?php while ( $org_query->have_posts() ) : $org_query->the_post();
                $position = get_post_meta( get_the_ID(), '_org_position', true ); ?>
                <div class="dpw-org-card">
                    <div class="dpw-org-photo">
                        <?php if ( has_post_thumbnail() ) : the_post_thumbnail( 'dpw-org' ); else : ?>
                            <div class="dpw-org-placeholder"><i class="bi bi-person-fill"></i></div>
                        <?php endif; ?>
                    </div>
                    <h4 class="dpw-org-name"><?php the_title(); ?></h4>
                    <p class="dpw-org-position"><?php echo esc_html( $position ); ?></p>
                </div>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Statistics -->
<section class="dpw-section dpw-stats-section">
    <div class="container">
        <div class="dpw-stats-grid">
            <?php
            $stats = array(
                array( 'icon' => 'bi-building', 'value' => get_theme_mod( 'dpw_stat_dpd_count', '8' ), 'label' => 'DPD Kabupaten' ),
                array( 'icon' => 'bi-people', 'value' => get_theme_mod( 'dpw_stat_member_count', '0' ), 'label' => 'Total Anggota' ),
                array( 'icon' => 'bi-calendar-check', 'value' => get_theme_mod( 'dpw_stat_activity_count', '0' ), 'label' => 'Kegiatan' ),
                array( 'icon' => 'bi-geo-alt', 'value' => get_theme_mod( 'dpw_stat_regency_count', '8' ), 'label' => 'Kabupaten' ),
            );
            foreach ( $stats as $stat ) : ?>
                <div class="dpw-stat-card">
                    <div class="dpw-stat-icon"><i class="bi <?php echo esc_attr( $stat['icon'] ); ?>"></i></div>
                    <div class="dpw-stat-value" data-count="<?php echo esc_attr( $stat['value'] ); ?>">0</div>
                    <div class="dpw-stat-label"><?php echo esc_html( $stat['label'] ); ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="dpw-section dpw-cta-section">
    <div class="dpw-cta-bg"></div>
    <div class="container">
        <div class="dpw-cta-content">
            <h2 class="dpw-cta-title"><?php echo esc_html( get_theme_mod( 'dpw_cta_text', 'Bergabunglah Bersama Kami!' ) ); ?></h2>
            <p class="dpw-cta-desc"><?php echo esc_html( get_theme_mod( 'dpw_cta_desc', 'Menjadi bagian dari perubahan untuk Papua Pegunungan yang lebih baik.' ) ); ?></p>
            <a href="<?php echo esc_url( get_theme_mod( 'dpw_cta_btn_url', 'https://psi.id/menjadi-anggota/' ) ); ?>" class="dpw-btn dpw-btn-gold dpw-btn-lg" target="_blank" rel="noopener"><?php echo esc_html( get_theme_mod( 'dpw_cta_btn_text', 'Gabung Keanggotaan PSI' ) ); ?> <i class="bi bi-arrow-right"></i></a>
        </div>
    </div>
</section>

<!-- DPD Grid -->
<?php
 $dpd_query = new WP_Query( array( 'post_type' => 'psi_dpd', 'posts_per_page' => 8, 'post_status' => 'publish' ) );
if ( $dpd_query->have_posts() ) : ?>
<section class="dpw-section dpw-dpd-section dpw-section-gray">
    <div class="container">
        <div class="dpw-section-header">
            <span class="dpw-section-badge">Wilayah</span>
            <h2 class="dpw-section-title">DPD PSI Se-Papua Pegunungan</h2>
            <a href="<?php echo esc_url( get_post_type_archive_link( 'psi_dpd' ) ); ?>" class="dpw-section-link">Lihat Detail <i class="bi bi-arrow-right"></i></a>
        </div>
        <div class="dpw-dpd-grid">
            <?php while ( $dpd_query->have_posts() ) : $dpd_query->the_post();
                $chairman = get_post_meta( get_the_ID(), '_dpd_chairman', true );
                $members  = get_post_meta( get_the_ID(), '_dpd_members', true ); ?>
                <div class="dpw-dpd-card">
                    <div class="dpw-dpd-photo">
                        <?php if ( has_post_thumbnail() ) : the_post_thumbnail( 'dpw-dpd' ); else : ?>
                            <div class="dpw-dpd-placeholder"><i class="bi bi-person-fill"></i></div>
                        <?php endif; ?>
                    </div>
                    <div class="dpw-dpd-info">
                        <h4><?php the_title(); ?></h4>
                        <?php if ( $chairman ) : ?><p class="dpw-dpd-chairman"><i class="bi bi-person"></i> <?php echo esc_html( $chairman ); ?></p><?php endif; ?>
                        <?php if ( $members ) : ?><span class="dpw-dpd-members"><i class="bi bi-people"></i> <?php echo esc_html( $members ); ?> Anggota</span><?php endif; ?>
                        <a href="<?php the_permalink(); ?>" class="dpw-dpd-link">Detail <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Gallery -->
<?php
 $gallery_query = new WP_Query( array( 'post_type' => 'psi_gallery', 'posts_per_page' => 8, 'post_status' => 'publish' ) );
if ( $gallery_query->have_posts() ) : ?>
<section class="dpw-section dpw-gallery-section">
    <div class="container">
        <div class="dpw-section-header">
            <span class="dpw-section-badge">Dokumentasi</span>
            <h2 class="dpw-section-title">Galeri Foto</h2>
            <a href="<?php echo esc_url( get_post_type_archive_link( 'psi_gallery' ) ); ?>" class="dpw-section-link">Lihat Semua <i class="bi bi-arrow-right"></i></a>
        </div>
        <div class="dpw-gallery-grid">
            <?php while ( $gallery_query->have_posts() ) : $gallery_query->the_post(); ?>
                <a href="<?php echo esc_url( wp_get_attachment_url( get_post_thumbnail_id() ) ); ?>" class="dpw-gallery-item" data-lightbox="gallery" data-title="<?php echo esc_attr( get_the_title() ); ?>">
                    <?php if ( has_post_thumbnail() ) : the_post_thumbnail( 'dpw-gallery' ); else : ?>
                        <div class="dpw-no-img"><i class="bi bi-image"></i></div>
                    <?php endif; ?>
                    <div class="dpw-gallery-overlay"><i class="bi bi-zoom-in"></i></div>
                </a>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Social Media Feed -->
<section class="dpw-section dpw-social-section dpw-section-gray">
    <div class="container">
        <div class="dpw-section-header">
            <span class="dpw-section-badge">Ikuti Kami</span>
            <h2 class="dpw-section-title">Media Sosial</h2>
        </div>
        <div class="dpw-social-grid">
            <?php
            $social_links = array(
                array( 'key' => 'dpw_facebook', 'icon' => 'bi-facebook', 'label' => 'Facebook', 'color' => '#1877F2' ),
                array( 'key' => 'dpw_instagram', 'icon' => 'bi-instagram', 'label' => 'Instagram', 'color' => '#E4405F' ),
                array( 'key' => 'dpw_tiktok', 'icon' => 'bi-tiktok', 'label' => 'TikTok', 'color' => '#000000' ),
                array( 'key' => 'dpw_youtube', 'icon' => 'bi-youtube', 'label' => 'YouTube', 'color' => '#FF0000' ),
            );
            foreach ( $social_links as $social ) :
                $url = get_theme_mod( $social['key'], '' );
                if ( $url ) : ?>
                    <a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener" class="dpw-social-card" style="--social-color:<?php echo esc_attr( $social['color'] ); ?>">
                        <i class="bi <?php echo esc_attr( $social['icon'] ); ?>"></i>
                        <span><?php echo esc_html( $social['label'] ); ?></span>
                        <i class="bi bi-box-arrow-up-right"></i>
                    </a>
                <?php endif;
            endforeach; ?>
        </div>
    </div>
</section>

<?php get_footer(); ?>
