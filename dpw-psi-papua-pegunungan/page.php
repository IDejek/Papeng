<?php
/**
 * Default Page Template
 * @package DPW_PSI_Papua_Pegunungan
 */
if ( ! defined( 'ABSPATH' ) ) exit;
get_header();
dpw_psi_breadcrumb();
?>
<section class="dpw-section dpw-page-section">
    <div class="container">
        <?php while ( have_posts() ) : the_post(); ?>
            <article class="dpw-page-content">
                <h1 class="dpw-page-title"><?php the_title(); ?></h1>
                <?php the_content(); ?>
                <?php wp_link_pages(); ?>
            </article>
        <?php endwhile; ?>
    </div>
</section>
<?php get_footer(); ?>
