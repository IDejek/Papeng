<?php
/**
 * Archive Template
 * @package DPW_PSI_Papua_Pegunungan
 */
if ( ! defined( 'ABSPATH' ) ) exit;
get_header();
dpw_psi_breadcrumb();

 $post_type = get_post_type();
 $pt_obj = get_post_type_object( $post_type );
 $section_title = $pt_obj ? $pt_obj->labels->name : 'Arsip';
?>

<section class="dpw-section dpw-archive-section">
    <div class="container">
        <div class="dpw-archive-header">
            <h1 class="dpw-archive-title"><?php echo esc_html( $section_title ); ?></h1>
            <?php if ( is_tax() ) : ?>
                <p class="dpw-archive-desc"><?php echo esc_html( single_term_title( '', false ) ); ?></p>
            <?php endif; ?>
        </div>

        <?php if ( $post_type === 'psi_news' ) : ?>
            <div class="dpw-single-layout">
                <main class="dpw-archive-main">
                    <?php if ( have_posts() ) : ?>
                        <div class="dpw-news-grid dpw-news-grid-archive">
                            <?php while ( have_posts() ) : the_post(); ?>
                                <a href="<?php the_permalink(); ?>" class="dpw-news-card dpw-news-card-small">
                                    <div class="dpw-news-card-img"><?php if ( has_post_thumbnail() ) : the_post_thumbnail( 'dpw-thumb' ); else : ?><div class="dpw-no-img"><i class="bi bi-newspaper"></i></div><?php endif; ?></div>
                                    <div class="dpw-news-card-body">
                                        <?php $cats = get_the_terms( get_the_ID(), 'news_category' ); if ( $cats && ! is_wp_error( $cats ) ) : ?><span class="dpw-news-cat"><?php echo esc_html( $cats[0]->name ); ?></span><?php endif; ?>
                                        <h4><?php the_title(); ?></h4>
                                        <p><?php echo esc_html( get_the_excerpt() ); ?></p>
                                        <span class="dpw-news-date"><i class="bi bi-calendar3"></i> <?php echo get_the_date(); ?></span>
                                    </div>
                                </a>
                            <?php endwhile; ?>
                        </div>
                        <div class="dpw-pagination"><?php the_posts_pagination( array( 'mid_size' => 2, 'prev_text' => '<i class="bi bi-chevron-left"></i>', 'next_text' => '<i class="bi bi-chevron-right"></i>' ) ); ?></div>
                    <?php else : ?>
                        <p class="dpw-empty">Belum ada konten.</p>
                    <?php endif; ?>
                </main>
                <aside class="dpw-single-sidebar">
                    <?php if ( is_active_sidebar( 'sidebar-blog' ) ) dynamic_sidebar( 'sidebar-blog' ); ?>
                    <?php if ( is_active_sidebar( 'ad-sidebar' ) ) dynamic_sidebar( 'ad-sidebar' ); ?>
                </aside>
            </div>

        <?php elseif ( $post_type === 'psi_gallery' ) : ?>
            <?php if ( have_posts() ) : ?>
                <div class="dpw-gallery-grid dpw-gallery-masonry">
                    <?php while ( have_posts() ) : the_post(); ?>
                        <a href="<?php echo esc_url( wp_get_attachment_url( get_post_thumbnail_id() ) ); ?>" class="dpw-gallery-item" data-lightbox="gallery" data-title="<?php echo esc_attr( get_the_title() ); ?>">
                            <?php if ( has_post_thumbnail() ) : the_post_thumbnail( 'dpw-gallery' ); else : ?><div class="dpw-no-img"><i class="bi bi-image"></i></div><?php endif; ?>
                            <div class="dpw-gallery-overlay"><span><?php the_title(); ?></span><i class="bi bi-zoom-in"></i></div>
                        </a>
                    <?php endwhile; ?>
                </div>
                <div class="dpw-pagination"><?php the_posts_pagination( array( 'mid_size' => 2, 'prev_text' => '<i class="bi bi-chevron-left"></i>', 'next_text' => '<i class="bi bi-chevron-right"></i>' ) ); ?></div>
            <?php else : ?>
                <p class="dpw-empty">Belum ada galeri.</p>
            <?php endif; ?>

        <?php elseif ( $post_type === 'psi_video' ) : ?>
            <?php if ( have_posts() ) : ?>
                <div class="dpw-video-grid dpw-video-archive">
                    <?php while ( have_posts() ) : the_post();
                        $video_url = get_post_meta( get_the_ID(), '_video_url', true );
                        $embed = '';
                        if ( $video_url ) {
                            preg_match( '/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]+)/', $video_url, $matches );
                            if ( ! empty( $matches[1] ) ) $embed = $matches[1];
                        }
                        ?>
                        <div class="dpw-video-card">
                            <a href="<?php the_permalink(); ?>" class="dpw-video-thumb">
                                <?php if ( $embed ) : ?>
                                    <img src="https://img.youtube.com/vi/<?php echo esc_attr( $embed ); ?>/hqdefault.jpg" alt="<?php echo esc_attr( get_the_title() ); ?>" loading="lazy">
                                    <div class="dpw-play-btn"><i class="bi bi-play-fill"></i></div>
                                <?php elseif ( has_post_thumbnail() ) : the_post_thumbnail( 'dpw-thumb' ); endif; ?>
                            </a>
                            <h4 class="dpw-video-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                        </div>
                    <?php endwhile; ?>
                </div>
                <div class="dpw-pagination"><?php the_posts_pagination( array( 'mid_size' => 2, 'prev_text' => '<i class="bi bi-chevron-left"></i>', 'next_text' => '<i class="bi bi-chevron-right"></i>' ) ); ?></div>
            <?php else : ?>
                <p class="dpw-empty">Belum ada video.</p>
            <?php endif; ?>

        <?php elseif ( $post_type === 'psi_dpd' ) : ?>
            <?php if ( have_posts() ) : ?>
                <div class="dpw-dpd-grid">
                    <?php while ( have_posts() ) : the_post();
                        $chairman = get_post_meta( get_the_ID(), '_dpd_chairman', true );
                        $members  = get_post_meta( get_the_ID(), '_dpd_members', true ); ?>
                        <div class="dpw-dpd-card">
                            <div class="dpw-dpd-photo"><?php if ( has_post_thumbnail() ) : the_post_thumbnail( 'dpw-dpd' ); else : ?><div class="dpw-dpd-placeholder"><i class="bi bi-person-fill"></i></div><?php endif; ?></div>
                            <div class="dpw-dpd-info">
                                <h4><?php the_title(); ?></h4>
                                <?php if ( $chairman ) : ?><p class="dpw-dpd-chairman"><i class="bi bi-person"></i> <?php echo esc_html( $chairman ); ?></p><?php endif; ?>
                                <?php if ( $members ) : ?><span class="dpw-dpd-members"><i class="bi bi-people"></i> <?php echo esc_html( $members ); ?> Anggota</span><?php endif; ?>
                                <a href="<?php the_permalink(); ?>" class="dpw-dpd-link">Detail <i class="bi bi-arrow-right"></i></a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else : ?>
                <p class="dpw-empty">Belum ada data DPD.</p>
            <?php endif; ?>

        <?php elseif ( $post_type === 'psi_org' ) : ?>
            <?php if ( have_posts() ) : ?>
                <div class="dpw-org-grid dpw-org-grid-full">
                    <?php while ( have_posts() ) : the_post();
                        $position = get_post_meta( get_the_ID(), '_org_position', true ); ?>
                        <div class="dpw-org-card">
                            <div class="dpw-org-photo"><?php if ( has_post_thumbnail() ) : the_post_thumbnail( 'dpw-org' ); else : ?><div class="dpw-org-placeholder"><i class="bi bi-person-fill"></i></div><?php endif; ?></div>
                            <h4 class="dpw-org-name"><?php the_title(); ?></h4>
                            <p class="dpw-org-position"><?php echo esc_html( $position ); ?></p>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else : ?>
                <p class="dpw-empty">Belum ada data pengurus.</p>
            <?php endif; ?>

        <?php else : ?>
            <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                <article class="dpw-page-content" style="margin-bottom:2rem;">
                    <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    <p><?php echo esc_html( get_the_excerpt() ); ?></p>
                </article>
            <?php endwhile; ?>
            <div class="dpw-pagination"><?php the_posts_pagination(); ?></div>
            <?php else : ?>
                <p class="dpw-empty">Belum ada konten.</p>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>

<?php get_footer(); ?>
