<?php
/**
 * Fallback Index Template
 * @package DPW_PSI_Papua_Pegunungan
 */
if ( ! defined( 'ABSPATH' ) ) exit;
get_header();
dpw_psi_breadcrumb();
?>
<section class="dpw-section">
    <div class="container">
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
            <article class="dpw-page-content" style="margin-bottom:2rem;">
                <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                <div><?php the_excerpt(); ?></div>
            </article>
        <?php endwhile; ?>
        <div class="dpw-pagination"><?php the_posts_pagination(); ?></div>
        <?php else : ?>
            <p class="dpw-empty">Tidak ditemukan.</p>
        <?php endif; ?>
    </div>
</section>
<?php get_footer(); ?>
