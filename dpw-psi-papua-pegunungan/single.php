<?php
/**
 * Single Post Template (for all CPTs)
 * @package DPW_PSI_Papua_Pegunungan
 */
if ( ! defined( 'ABSPATH' ) ) exit;
get_header();
dpw_psi_breadcrumb();

 $post_type = get_post_type();
?>

<?php if ( $post_type === 'psi_news' ) : ?>
<section class="dpw-section dpw-single-section">
    <div class="container">
        <div class="dpw-single-layout">
            <main class="dpw-single-main">
                <?php while ( have_posts() ) : the_post(); ?>
                    <article class="dpw-article">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <div class="dpw-article-featured-img"><?php the_post_thumbnail( 'dpw-hero' ); ?></div>
                        <?php endif; ?>

                        <div class="dpw-article-header">
                            <?php $cats = get_the_terms( get_the_ID(), 'news_category' ); if ( $cats && ! is_wp_error( $cats ) ) : ?>
                                <span class="dpw-news-cat"><?php echo esc_html( $cats[0]->name ); ?></span>
                            <?php endif; ?>
                            <h1 class="dpw-article-title"><?php the_title(); ?></h1>
                            <div class="dpw-article-meta">
                                <span><i class="bi bi-calendar3"></i> <?php echo get_the_date(); ?></span>
                                <span><i class="bi bi-person"></i> <?php echo get_the_author(); ?></span>
                                <?php if ( get_comments_number() > 0 ) : ?>
                                    <span><i class="bi bi-chat-dots"></i> <?php echo get_comments_number(); ?> Komentar</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="dpw-article-body">
                            <?php the_content(); ?>
                            <?php wp_link_pages(); ?>
                        </div>

                        <div class="dpw-article-footer">
                            <?php dpw_psi_share_buttons(); ?>
                            <?php
                            $tags = get_the_tags();
                            if ( $tags ) : ?>
                                <div class="dpw-article-tags">
                                    <?php foreach ( $tags as $tag ) : ?>
                                        <a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>" class="dpw-tag"><?php echo esc_html( $tag->name ); ?></a>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <?php if ( comments_open() || get_comments_number() ) : comments_template(); endif; ?>
                    </article>
                <?php endwhile; ?>

                <!-- Related Posts -->
                <?php
                $related = new WP_Query( array( 'post_type' => 'psi_news', 'posts_per_page' => 4, 'post__not_in' => array( get_the_ID() ), 'post_status' => 'publish' ) );
                if ( $related->have_posts() ) : ?>
                    <div class="dpw-related-posts">
                        <h3 class="dpw-related-title">Berita Terkait</h3>
                        <div class="dpw-related-grid">
                            <?php while ( $related->have_posts() ) : $related->the_post(); ?>
                                <a href="<?php the_permalink(); ?>" class="dpw-news-card dpw-news-card-small">
                                    <div class="dpw-news-card-img"><?php if ( has_post_thumbnail() ) : the_post_thumbnail( 'dpw-thumb' ); else : ?><div class="dpw-no-img"><i class="bi bi-newspaper"></i></div><?php endif; ?></div>
                                    <div class="dpw-news-card-body">
                                        <h4><?php the_title(); ?></h4>
                                        <span class="dpw-news-date"><i class="bi bi-calendar3"></i> <?php echo get_the_date(); ?></span>
                                    </div>
                                </a>
                            <?php endwhile; wp_reset_postdata(); ?>
                        </div>
                    </div>
                <?php endif; ?>
            </main>

            <aside class="dpw-single-sidebar">
                <?php if ( is_active_sidebar( 'ad-sidebar' ) ) dynamic_sidebar( 'ad-sidebar' ); ?>

                <!-- Trending -->
                <div class="widget dpw-trending-widget">
                    <h4 class="widget-title">Berita Populer</h4>
                    <?php
                    $trending = new WP_Query( array( 'post_type' => 'psi_news', 'posts_per_page' => 5, 'orderby' => 'comment_count', 'post_status' => 'publish' ) );
                    if ( $trending->have_posts() ) :
                        $t_num = 1;
                        while ( $trending->have_posts() ) : $trending->the_post(); ?>
                            <a href="<?php the_permalink(); ?>" class="dpw-trending-item">
                                <span class="dpw-trending-num"><?php echo $t_num++; ?></span>
                                <span class="dpw-trending-text"><?php the_title(); ?></span>
                            </a>
                        <?php endwhile; wp_reset_postdata();
                    endif; ?>
                </div>

                <?php if ( is_active_sidebar( 'sidebar-blog' ) ) dynamic_sidebar( 'sidebar-blog' ); ?>
            </aside>
        </div>
    </div>
</section>

<?php elseif ( $post_type === 'psi_video' ) : ?>
<section class="dpw-section dpw-single-section">
    <div class="container">
        <?php while ( have_posts() ) : the_post();
            $video_url = get_post_meta( get_the_ID(), '_video_url', true );
            $embed = '';
            if ( $video_url ) {
                preg_match( '/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]+)/', $video_url, $matches );
                if ( ! empty( $matches[1] ) ) $embed = 'https://www.youtube.com/embed/' . $matches[1];
            }
            ?>
            <article class="dpw-video-single">
                <h1 class="dpw-page-title"><?php the_title(); ?></h1>
                <?php if ( $embed ) : ?>
                    <div class="dpw-video-single-embed"><iframe src="<?php echo esc_url( $embed ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>" frameborder="0" allowfullscreen loading="lazy"></iframe></div>
                <?php endif; ?>
                <div class="dpw-article-body"><?php the_content(); ?></div>
                <?php dpw_psi_share_buttons(); ?>
            </article>
        <?php endwhile; ?>
    </div>
</section>

<?php elseif ( $post_type === 'psi_dpd' ) : ?>
<section class="dpw-section dpw-single-section">
    <div class="container">
        <?php while ( have_posts() ) : the_post();
            $chairman = get_post_meta( get_the_ID(), '_dpd_chairman', true );
            $members  = get_post_meta( get_the_ID(), '_dpd_members', true );
            ?>
            <article class="dpw-dpd-single">
                <div class="dpw-dpd-single-header">
                    <div class="dpw-dpd-single-photo"><?php if ( has_post_thumbnail() ) : the_post_thumbnail( 'dpw-dpd' ); else : ?><div class="dpw-dpd-placeholder"><i class="bi bi-person-fill"></i></div><?php endif; ?></div>
                    <div class="dpw-dpd-single-info">
                        <h1><?php the_title(); ?></h1>
                        <?php if ( $chairman ) : ?><p class="dpw-dpd-chairman"><strong>Ketua:</strong> <?php echo esc_html( $chairman ); ?></p><?php endif; ?>
                        <?php if ( $members ) : ?><p class="dpw-dpd-members"><strong>Jumlah Anggota:</strong> <?php echo esc_html( $members ); ?></p><?php endif; ?>
                    </div>
                </div>
                <div class="dpw-article-body"><?php the_content(); ?></div>
            </article>
        <?php endwhile; ?>
    </div>
</section>

<?php else : ?>
<section class="dpw-section dpw-single-section">
    <div class="container">
        <?php while ( have_posts() ) : the_post(); ?>
            <article class="dpw-page-content">
                <?php if ( has_post_thumbnail() ) : ?><div class="dpw-article-featured-img"><?php the_post_thumbnail( 'dpw-hero' ); ?></div><?php endif; ?>
                <h1 class="dpw-page-title"><?php the_title(); ?></h1>
                <div class="dpw-article-body"><?php the_content(); ?></div>
            </article>
        <?php endwhile; ?>
    </div>
</section>
<?php endif; ?>

<?php get_footer(); ?>
