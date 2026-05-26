<?php
/**
 * Search Results Template
 * @package DPW_PSI_Papua_Pegunungan
 */
if ( ! defined( 'ABSPATH' ) ) exit;
get_header();
dpw_psi_breadcrumb();
?>
<section class="dpw-section">
    <div class="container">
        <h1 class="dpw-page-title">Hasil Pencarian: "<?php echo esc_html( get_search_query() ); ?>"</h1>
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
            <article class="dpw-page-content" style="margin-bottom:2rem;">
                <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                <p><?php echo esc_html( get_the_excerpt() ); ?></p>
            </article>
        <?php endwhile; ?>
        <div class="dpw-pagination"><?php the_posts_pagination(); ?></div>
        <?php else : ?>
            <p class="dpw-empty">Tidak ada hasil ditemukan.</p>
        <?php endif; ?>
    </div>
</section>
<?php get_footer(); ?>
