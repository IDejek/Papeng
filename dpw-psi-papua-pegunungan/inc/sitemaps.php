<?php
/**
 * Custom Sitemaps for News & Video
 * @package DPW_PSI_Papua_Pegunungan
 */
if ( ! defined( 'ABSPATH' ) ) exit;

/* ─── Register Sitemaps ─── */
add_filter( 'init', function() {
    add_rewrite_rule( 'news-sitemap\.xml$', 'index.php?news_sitemap=1', 'top' );
    add_rewrite_rule( 'video-sitemap\.xml$', 'index.php?video_sitemap=1', 'top' );
});

add_filter( 'query_vars', function( $vars ) {
    $vars[] = 'news_sitemap';
    $vars[] = 'video_sitemap';
    return $vars;
});

add_action( 'template_redirect', function() {
    if ( get_query_var( 'news_sitemap' ) ) {
        dpw_psi_news_sitemap();
        exit;
    }
    if ( get_query_var( 'video_sitemap' ) ) {
        dpw_psi_video_sitemap();
        exit;
    }
});

function dpw_psi_news_sitemap() {
    header( 'Content-Type: application/xml; charset=utf-8' );
    echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:news="http://www.google.com/schemas/sitemap-news/0.9">' . "\n";

    $posts = get_posts( array(
        'post_type'      => 'psi_news',
        'posts_per_page' => 1000,
        'post_status'    => 'publish',
    ) );

    foreach ( $posts as $post ) {
        $author = get_the_author_meta( 'display_name', $post->post_author );
        echo '<url>' . "\n";
        echo '  <loc>' . esc_url( get_permalink( $post->ID ) ) . '</loc>' . "\n";
        echo '  <lastmod>' . esc_html( mysql2date( 'Y-m-d\TH:i:s+00:00', $post->post_modified, false ) ) . '</lastmod>' . "\n";
        echo '  <news:news>' . "\n";
        echo '    <news:publication>' . "\n";
        echo '      <news:name>' . esc_html( get_bloginfo( 'name' ) ) . '</news:name>' . "\n";
        echo '      <news:language>id</news:language>' . "\n";
        echo '    </news:publication>' . "\n";
        echo '    <news:publication_date>' . esc_html( mysql2date( 'Y-m-d\TH:i:s+00:00', $post->post_date, false ) ) . '</news:publication_date>' . "\n";
        echo '    <news:title>' . esc_html( $post->post_title ) . '</news:title>' . "\n";
        echo '    <news:author>' . esc_html( $author ) . '</news:author>' . "\n";
        echo '  </news:news>' . "\n";
        echo '</url>' . "\n";
    }

    echo '</urlset>';
}

function dpw_psi_video_sitemap() {
    header( 'Content-Type: application/xml; charset=utf-8' );
    echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:video="http://www.google.com/schemas/sitemap-video/1.1">' . "\n";

    $posts = get_posts( array(
        'post_type'      => 'psi_video',
        'posts_per_page' => 1000,
        'post_status'    => 'publish',
    ) );

    foreach ( $posts as $post ) {
        $video_url = get_post_meta( $post->ID, '_video_url', true );
        $thumb = '';
        if ( has_post_thumbnail( $post->ID ) ) {
            $thumb = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) );
        } elseif ( $video_url ) {
            preg_match( '/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]+)/', $video_url, $m );
            if ( ! empty( $m[1] ) ) $thumb = 'https://img.youtube.com/vi/' . $m[1] . '/hqdefault.jpg';
        }

        echo '<url>' . "\n";
        echo '  <loc>' . esc_url( get_permalink( $post->ID ) ) . '</loc>' . "\n";
        if ( $video_url ) {
            echo '  <video:video>' . "\n";
            echo '    <video:thumbnail_loc>' . esc_url( $thumb ) . '</video:thumbnail_loc>' . "\n";
            echo '    <video:title>' . esc_html( $post->post_title ) . '</video:title>' . "\n";
            echo '    <video:description>' . esc_html( wp_trim_words( $post->post_excerpt ? $post->post_excerpt : $post->post_content, 50 ) ) . '</video:description>' . "\n";
            echo '    <video:publication_date>' . esc_html( mysql2date( 'Y-m-d\TH:i:s+00:00', $post->post_date, false ) ) . '</video:publication_date>' . "\n";
            echo '    <video:player_loc>' . esc_url( $video_url ) . '</video:player_loc>' . "\n";
            echo '  </video:video>' . "\n";
        }
        echo '</url>' . "\n";
    }

    echo '</urlset>';
}
