<?php
/**
 * DPW PSI Papua Pegunungan Theme Functions
 *
 * @package DPW_PSI_Papua_Pegunungan
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'DPW_PSI_VERSION', '1.0.0' );
define( 'DPW_PSI_DIR', get_template_directory() );
define( 'DPW_PSI_URI', get_template_directory_uri() );

/* ─── Theme Setup ─── */
function dpw_psi_setup() {
    load_theme_textdomain( 'dpw-psi-papeng', DPW_PSI_DIR . '/languages' );
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
    add_theme_support( 'customize-selective-refresh-widgets' );
    add_theme_support( 'responsive-embeds' );
    add_theme_support( 'align-wide' );
    add_theme_support( 'custom-logo', array(
        'height'      => 100,
        'width'       => 350,
        'flex-height' => true,
        'flex-width'  => true,
    ) );
    add_theme_support( 'custom-background', array( 'default-color' => 'ffffff' ) );

    register_nav_menus( array(
        'primary'   => esc_html__( 'Primary Menu', 'dpw-psi-papeng' ),
        'footer'    => esc_html__( 'Footer Menu', 'dpw-psi-papeng' ),
        'mobile'    => esc_html__( 'Mobile Menu', 'dpw-psi-papeng' ),
    ) );

    add_image_size( 'dpw-hero', 1920, 800, true );
    add_image_size( 'dpw-thumb', 400, 300, true );
    add_image_size( 'dpw-gallery', 600, 400, true );
    add_image_size( 'dpw-org', 300, 380, true );
    add_image_size( 'dpw-dpd', 400, 500, true );
}
add_action( 'after_setup_theme', 'dpw_psi_setup' );

/* ─── Enqueue Scripts & Styles ─── */
function dpw_psi_scripts() {
    wp_enqueue_style( 'dpw-google-fonts', 'https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap', array(), null );
    wp_enqueue_style( 'dpw-bootstrap-icons', 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css', array(), '1.11.3' );
    wp_enqueue_style( 'dpw-custom', DPW_PSI_URI . '/assets/css/custom.css', array(), DPW_PSI_VERSION );
    wp_enqueue_style( 'dpw-style', get_stylesheet_uri(), array( 'dpw-custom' ), DPW_PSI_VERSION );

    wp_enqueue_script( 'dpw-main', DPW_PSI_URI . '/assets/js/custom.js', array(), DPW_PSI_VERSION, true );
    wp_localize_script( 'dpw-main', 'dpwAjax', array(
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'nonce'   => wp_create_nonce( 'dpw_psi_nonce' ),
        'home'    => home_url( '/' ),
    ) );

    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}
add_action( 'wp_enqueue_scripts', 'dpw_psi_scripts' );

/* ─── Widget Areas ─── */
function dpw_psi_widgets_init() {
    register_sidebar( array(
        'name'          => esc_html__( 'Blog Sidebar', 'dpw-psi-papeng' ),
        'id'            => 'sidebar-blog',
        'description'   => esc_html__( 'Sidebar for news/blog pages.', 'dpw-psi-papeng' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ) );
    register_sidebar( array(
        'name'          => esc_html__( 'Footer Column 1', 'dpw-psi-papeng' ),
        'id'            => 'footer-1',
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h5 class="footer-widget-title">',
        'after_title'   => '</h5>',
    ) );
    register_sidebar( array(
        'name'          => esc_html__( 'Footer Column 2', 'dpw-psi-papeng' ),
        'id'            => 'footer-2',
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h5 class="footer-widget-title">',
        'after_title'   => '</h5>',
    ) );
    register_sidebar( array(
        'name'          => esc_html__( 'Footer Column 3', 'dpw-psi-papeng' ),
        'id'            => 'footer-3',
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h5 class="footer-widget-title">',
        'after_title'   => '</h5>',
    ) );
    register_sidebar( array(
        'name'          => esc_html__( 'Ad Banner Sidebar', 'dpw-psi-papeng' ),
        'id'            => 'ad-sidebar',
        'before_widget' => '<div id="%1$s" class="ad-widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<span class="sr-only">',
        'after_title'   => '</span>',
    ) );
}
add_action( 'widgets_init', 'dpw_psi_widgets_init' );

/* ─── Custom Post Types ─── */
function dpw_psi_register_cpts() {

    register_post_type( 'psi_news', array(
        'labels' => array( 'name' => 'Berita', 'singular_name' => 'Berita', 'add_new' => 'Tambah Berita', 'add_new_item' => 'Tambah Berita Baru', 'edit_item' => 'Edit Berita', 'view_item' => 'Lihat Berita', 'all_items' => 'Semua Berita', 'search_items' => 'Cari Berita', 'not_found' => 'Tidak ada berita ditemukan.' ),
        'public' => true, 'has_archive' => true, 'rewrite' => array( 'slug' => 'berita', 'with_front' => false ),
        'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt', 'author', 'comments', 'custom-fields' ),
        'menu_icon' => 'dashicons-media-document', 'show_in_rest' => true, 'capability_type' => 'post',
    ) );

    register_post_type( 'psi_gallery', array(
        'labels' => array( 'name' => 'Galeri', 'singular_name' => 'Galeri', 'add_new' => 'Tambah Galeri', 'add_new_item' => 'Tambah Galeri Baru' ),
        'public' => true, 'has_archive' => true, 'rewrite' => array( 'slug' => 'galeri', 'with_front' => false ),
        'supports' => array( 'title', 'thumbnail', 'editor' ),
        'menu_icon' => 'dashicons-format-gallery', 'show_in_rest' => true,
    ) );

    register_post_type( 'psi_video', array(
        'labels' => array( 'name' => 'Video', 'singular_name' => 'Video', 'add_new' => 'Tambah Video', 'add_new_item' => 'Tambah Video Baru' ),
        'public' => true, 'has_archive' => true, 'rewrite' => array( 'slug' => 'video', 'with_front' => false ),
        'supports' => array( 'title', 'thumbnail', 'editor', 'excerpt' ),
        'menu_icon' => 'dashicons-video-alt3', 'show_in_rest' => true,
    ) );

    register_post_type( 'psi_org', array(
        'labels' => array( 'name' => 'Struktur Organisasi', 'singular_name' => 'Pengurus', 'add_new' => 'Tambah Pengurus', 'add_new_item' => 'Tambah Pengurus Baru' ),
        'public' => true, 'has_archive' => false, 'rewrite' => array( 'slug' => 'struktur-organisasi', 'with_front' => false ),
        'supports' => array( 'title', 'thumbnail', 'editor', 'page-attributes' ),
        'menu_icon' => 'dashicons-groups', 'show_in_rest' => true,
    ) );

    register_post_type( 'psi_dpd', array(
        'labels' => array( 'name' => 'DPD Kabupaten', 'singular_name' => 'DPD', 'add_new' => 'Tambah DPD', 'add_new_item' => 'Tambah DPD Baru' ),
        'public' => true, 'has_archive' => true, 'rewrite' => array( 'slug' => 'dpd', 'with_front' => false ),
        'supports' => array( 'title', 'thumbnail', 'editor' ),
        'menu_icon' => 'dashicons-location-alt', 'show_in_rest' => true,
    ) );

    register_post_type( 'psi_agenda', array(
        'labels' => array( 'name' => 'Agenda', 'singular_name' => 'Agenda', 'add_new' => 'Tambah Agenda', 'add_new_item' => 'Tambah Agenda Baru' ),
        'public' => true, 'has_archive' => true, 'rewrite' => array( 'slug' => 'agenda', 'with_front' => false ),
        'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
        'menu_icon' => 'dashicons-calendar-alt', 'show_in_rest' => true,
    ) );

    register_post_type( 'psi_document', array(
        'labels' => array( 'name' => 'Dokumen', 'singular_name' => 'Dokumen', 'add_new' => 'Tambah Dokumen', 'add_new_item' => 'Tambah Dokumen Baru' ),
        'public' => true, 'has_archive' => true, 'rewrite' => array( 'slug' => 'dokumen', 'with_front' => false ),
        'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
        'menu_icon' => 'dashicons-media-text', 'show_in_rest' => true,
    ) );

    register_taxonomy( 'news_category', 'psi_news', array(
        'labels' => array( 'name' => 'Kategori Berita', 'singular_name' => 'Kategori Berita' ),
        'hierarchical' => true, 'public' => true, 'rewrite' => array( 'slug' => 'kategori-berita' ), 'show_in_rest' => true,
    ) );

    register_taxonomy( 'video_category', 'psi_video', array(
        'labels' => array( 'name' => 'Kategori Video', 'singular_name' => 'Kategori Video' ),
        'hierarchical' => true, 'public' => true, 'rewrite' => array( 'slug' => 'kategori-video' ), 'show_in_rest' => true,
    ) );

    register_taxonomy( 'dpd_region', 'psi_dpd', array(
        'labels' => array( 'name' => 'Wilayah DPD', 'singular_name' => 'Wilayah' ),
        'hierarchical' => true, 'public' => true, 'rewrite' => array( 'slug' => 'wilayah-dpd' ), 'show_in_rest' => true,
    ) );
}
add_action( 'init', 'dpw_psi_register_cpts' );

/* ─── Custom Meta Boxes ─── */
function dpw_psi_register_meta_boxes() {
    add_meta_box( 'dpw_video_url', 'URL Video YouTube', 'dpw_psi_video_url_cb', 'psi_video', 'normal', 'high' );
    add_meta_box( 'dpw_org_position', 'Jabatan', 'dpw_psi_org_position_cb', 'psi_org', 'normal', 'high' );
    add_meta_box( 'dpw_dpd_info', 'Informasi DPD', 'dpw_psi_dpd_info_cb', 'psi_dpd', 'normal', 'high' );
    add_meta_box( 'dpw_news_featured', 'Pengaturan Berita', 'dpw_psi_news_featured_cb', 'psi_news', 'side', 'high' );
    add_meta_box( 'dpw_agenda_date', 'Tanggal Agenda', 'dpw_psi_agenda_date_cb', 'psi_agenda', 'normal', 'high' );
    add_meta_box( 'dpw_document_file', 'File Dokumen', 'dpw_psi_document_file_cb', 'psi_document', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'dpw_psi_register_meta_boxes' );

function dpw_psi_video_url_cb( $post ) {
    wp_nonce_field( 'dpw_psi_save_meta', 'dpw_psi_meta_nonce' );
    $val = get_post_meta( $post->ID, '_video_url', true );
    echo '<p><label>URL YouTube:</label><br><input type="url" name="video_url" value="' . esc_attr( $val ) . '" style="width:100%;max-width:600px;" placeholder="https://www.youtube.com/watch?v=..." /></p>';
}

function dpw_psi_org_position_cb( $post ) {
    wp_nonce_field( 'dpw_psi_save_meta', 'dpw_psi_meta_nonce' );
    $val = get_post_meta( $post->ID, '_org_position', true );
    echo '<p><label>Jabatan:</label><br><input type="text" name="org_position" value="' . esc_attr( $val ) . '" style="width:100%;max-width:600px;" placeholder="Ketua DPW PSI Papua Pegunungan" /></p>';
}

function dpw_psi_dpd_info_cb( $post ) {
    wp_nonce_field( 'dpw_psi_save_meta', 'dpw_psi_meta_nonce' );
    $chairman = get_post_meta( $post->ID, '_dpd_chairman', true );
    $members  = get_post_meta( $post->ID, '_dpd_members', true );
    $desc     = get_post_meta( $post->ID, '_dpd_short_desc', true );
    echo '<p><label>Nama Ketua:</label><br><input type="text" name="dpd_chairman" value="' . esc_attr( $chairman ) . '" style="width:100%;max-width:600px;" /></p>';
    echo '<p><label>Jumlah Anggota:</label><br><input type="number" name="dpd_members" value="' . esc_attr( $members ) . '" style="width:200px;" /></p>';
    echo '<p><label>Deskripsi Singkat:</label><br><textarea name="dpd_short_desc" rows="3" style="width:100%;max-width:600px;">' . esc_textarea( $desc ) . '</textarea></p>';
}

function dpw_psi_news_featured_cb( $post ) {
    wp_nonce_field( 'dpw_psi_save_meta', 'dpw_psi_meta_nonce' );
    $featured = get_post_meta( $post->ID, '_news_featured', true );
    echo '<p><label><input type="checkbox" name="news_featured" value="1" ' . checked( $featured, '1', false ) . ' /> Jadikan Berita Utama</label></p>';
}

function dpw_psi_agenda_date_cb( $post ) {
    wp_nonce_field( 'dpw_psi_save_meta', 'dpw_psi_meta_nonce' );
    $date = get_post_meta( $post->ID, '_agenda_date', true );
    $time = get_post_meta( $post->ID, '_agenda_time', true );
    $loc  = get_post_meta( $post->ID, '_agenda_location', true );
    echo '<p><label>Tanggal:</label><br><input type="date" name="agenda_date" value="' . esc_attr( $date ) . '" /></p>';
    echo '<p><label>Waktu:</label><br><input type="time" name="agenda_time" value="' . esc_attr( $time ) . '" /></p>';
    echo '<p><label>Lokasi:</label><br><input type="text" name="agenda_location" value="' . esc_attr( $loc ) . '" style="width:100%;max-width:600px;" /></p>';
}

function dpw_psi_document_file_cb( $post ) {
    wp_nonce_field( 'dpw_psi_save_meta', 'dpw_psi_meta_nonce' );
    $file = get_post_meta( $post->ID, '_document_file_url', true );
    echo '<p><label>URL File:</label><br><input type="url" name="document_file_url" value="' . esc_attr( $file ) . '" style="width:100%;max-width:600px;" placeholder="https://..." /></p>';
}

function dpw_psi_save_meta_boxes( $post_id ) {
    if ( ! isset( $_POST['dpw_psi_meta_nonce'] ) || ! wp_verify_nonce( $_POST['dpw_psi_meta_nonce'], 'dpw_psi_save_meta' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    $fields = array(
        'video_url'        => '_video_url',
        'org_position'     => '_org_position',
        'dpd_chairman'     => '_dpd_chairman',
        'dpd_members'      => '_dpd_members',
        'dpd_short_desc'   => '_dpd_short_desc',
        'agenda_date'      => '_agenda_date',
        'agenda_time'      => '_agenda_time',
        'agenda_location'  => '_agenda_location',
        'document_file_url'=> '_document_file_url',
    );

    foreach ( $fields as $post_key => $meta_key ) {
        if ( isset( $_POST[ $post_key ] ) ) {
            update_post_meta( $post_id, $meta_key, sanitize_text_field( wp_unslash( $_POST[ $post_key ] ) ) );
        }
    }

    update_post_meta( $post_id, '_news_featured', isset( $_POST['news_featured'] ) ? '1' : '0' );
}
add_action( 'save_post', 'dpw_psi_save_meta_boxes' );

/* ─── Custom User Roles ─── */
function dpw_psi_add_roles() {
    remove_role( 'psi_operator' );
    remove_role( 'psi_regional_editor' );

    add_role( 'psi_operator', 'PSI Operator', array(
        'read'                   => true,
        'edit_posts'             => true,
        'edit_published_posts'   => true,
        'upload_files'           => true,
        'publish_posts'          => true,
        'delete_posts'           => true,
        'delete_published_posts' => true,
        'edit_pages'             => false,
        'manage_options'         => false,
    ) );

    add_role( 'psi_regional_editor', 'PSI Regional Editor', array(
        'read'                   => true,
        'edit_posts'             => true,
        'edit_published_posts'   => true,
        'upload_files'           => true,
        'publish_posts'          => true,
        'delete_posts'           => false,
        'delete_published_posts' => false,
    ) );
}
add_action( 'init', 'dpw_psi_add_roles' );

/* ─── Custom Login Page ─── */
function dpw_psi_custom_login() {
    if ( ! isset( $_GET['action'] ) || $_GET['action'] !== 'logout' ) {
        wp_enqueue_style( 'dpw-login', DPW_PSI_URI . '/assets/css/login.css', array(), DPW_PSI_VERSION );
    }
}
add_action( 'login_enqueue_scripts', 'dpw_psi_custom_login' );

function dpw_psi_login_logo_url() { return home_url( '/' ); }
add_filter( 'login_headerurl', 'dpw_psi_login_logo_url' );

function dpw_psi_login_logo_title() { return get_bloginfo( 'name' ); }
add_filter( 'login_headertext', 'dpw_psi_login_logo_title' );

/* ─── Breadcrumb ─── */
function dpw_psi_breadcrumb() {
    if ( is_front_page() ) return;
    echo '<nav class="dpw-breadcrumb" aria-label="Breadcrumb"><div class="container"><ol class="breadcrumb-list">';
    echo '<li><a href="' . esc_url( home_url( '/' ) ) . '"><i class="bi bi-house-door"></i> Beranda</a></li>';

    if ( is_singular( 'psi_news' ) ) {
        echo '<li><a href="' . esc_url( get_post_type_archive_link( 'psi_news' ) ) . '">Berita</a></li>';
        echo '<li class="active">' . esc_html( get_the_title() ) . '</li>';
    } elseif ( is_singular() ) {
        echo '<li class="active">' . esc_html( get_the_title() ) . '</li>';
    } elseif ( is_post_type_archive() ) {
        $pt = get_post_type_object( get_post_type() );
        echo '<li class="active">' . esc_html( $pt->labels->name ) . '</li>';
    } elseif ( is_category() || is_tax() ) {
        echo '<li class="active">' . esc_html( single_term_title( '', false ) ) . '</li>';
    } elseif ( is_search() ) {
        echo '<li class="active">Hasil Pencarian</li>';
    } elseif ( is_404() ) {
        echo '<li class="active">Halaman Tidak Ditemukan</li>';
    }

    echo '</ol></div></nav>';
}

/* ─── SEO: Meta Tags ─── */
function dpw_psi_seo_meta() {
    global $post;

    $site_name = get_bloginfo( 'name' );
    $desc = get_bloginfo( 'description' );
    $title = wp_title( '|', false, 'right' ) . $site_name;
    $url = get_permalink();
    $image = '';
    $type = 'website';

    if ( is_singular() && $post ) {
        $desc = has_excerpt( $post ) ? $post->post_excerpt : wp_trim_words( $post->post_content, 30, '...' );
        $image = has_post_thumbnail( $post ) ? get_the_post_thumbnail_url( $post, 'large' ) : '';
        $type = 'article';
    }

    $ga_id   = get_theme_mod( 'dpw_ga4_id', '' );
    $gtm_id  = get_theme_mod( 'dpw_gtm_id', '' );
    $meta_px = get_theme_mod( 'dpw_meta_pixel', '' );
    $tt_px   = get_theme_mod( 'dpw_tiktok_pixel', '' );
    $gsc_verify = get_theme_mod( 'dpw_gsc_verify', '' );

    echo '<meta name="description" content="' . esc_attr( $desc ) . '" />' . "\n";
    echo '<meta property="og:title" content="' . esc_attr( $title ) . '" />' . "\n";
    echo '<meta property="og:description" content="' . esc_attr( $desc ) . '" />' . "\n";
    echo '<meta property="og:url" content="' . esc_url( $url ) . '" />' . "\n";
    echo '<meta property="og:type" content="' . esc_attr( $type ) . '" />' . "\n";
    echo '<meta property="og:site_name" content="' . esc_attr( $site_name ) . '" />' . "\n";
    if ( $image ) echo '<meta property="og:image" content="' . esc_url( $image ) . '" />' . "\n";
    echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
    echo '<meta name="twitter:title" content="' . esc_attr( $title ) . '" />' . "\n";
    echo '<meta name="twitter:description" content="' . esc_attr( $desc ) . '" />' . "\n";
    if ( $image ) echo '<meta name="twitter:image" content="' . esc_url( $image ) . '" />' . "\n";
    if ( $gsc_verify ) echo '<meta name="google-site-verification" content="' . esc_attr( $gsc_verify ) . '" />' . "\n";
    echo '<link rel="canonical" href="' . esc_url( $url ) . '" />' . "\n";

    // JSON-LD Organization Schema
    $logo_url = has_custom_logo() ? wp_get_attachment_url( get_theme_mod( 'custom_logo' ) ) : '';
    $schema = array(
        '@context' => 'https://schema.org',
        '@type'    => 'Organization',
        'name'     => $site_name,
        'url'      => home_url( '/' ),
        'logo'     => $logo_url ? $logo_url : '',
        'description' => $desc,
        'sameAs'   => array_filter( array(
            get_theme_mod( 'dpw_facebook', '' ),
            get_theme_mod( 'dpw_instagram', '' ),
            get_theme_mod( 'dpw_youtube', '' ),
            get_theme_mod( 'dpw_tiktok', '' ),
        ) ),
    );
    echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";

    // Article Schema
    if ( is_singular( 'psi_news' ) && $post ) {
        $article_schema = array(
            '@context'       => 'https://schema.org',
            '@type'          => 'NewsArticle',
            'headline'       => get_the_title(),
            'datePublished'  => get_the_date( 'c' ),
            'dateModified'   => get_the_modified_date( 'c' ),
            'author'         => array( '@type' => 'Person', 'name' => get_the_author_meta( 'display_name', $post->post_author ) ),
            'publisher'      => array( '@type' => 'Organization', 'name' => $site_name ),
            'description'    => $desc,
        );
        if ( $image ) $article_schema['image'] = $image;
        echo '<script type="application/ld+json">' . wp_json_encode( $article_schema, JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
    }

    // Breadcrumb Schema
    if ( ! is_front_page() ) {
        $bc_items = array( array( '@type' => 'ListItem', 'position' => 1, 'name' => 'Beranda', 'item' => home_url( '/' ) ) );
        if ( is_singular() ) {
            $bc_items[] = array( '@type' => 'ListItem', 'position' => 2, 'name' => get_the_title(), 'item' => get_permalink() );
        } elseif ( is_post_type_archive() ) {
            $pt = get_post_type_object( get_post_type() );
            $bc_items[] = array( '@type' => 'ListItem', 'position' => 2, 'name' => $pt->labels->name );
        }
        $bc_schema = array( '@context' => 'https://schema.org', '@type' => 'BreadcrumbList', 'itemListElement' => $bc_items );
        echo '<script type="application/ld+json">' . wp_json_encode( $bc_schema, JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
    }

    // Tracking Pixels
    if ( $ga_id ) echo "<!-- GA4 --><script async src=\"https://www.googletagmanager.com/gtag/js?id=" . esc_js( $ga_id ) . "\"></script><script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','" . esc_js( $ga_id ) . "');</script>\n";
    if ( $gtm_id ) echo "<!-- GTM --><script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','" . esc_js( $gtm_id ) . "');</script>\n";
    if ( $meta_px ) echo "<!-- Meta Pixel --><script>!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,document,'script','https://connect.facebook.net/en_US/fbevents.js');fbq('init','" . esc_js( $meta_px ) . "');fbq('track','PageView');</script>\n";
    if ( $tt_px ) echo "<!-- TikTok Pixel --><script>!function(e,t,a,n,g){e[n]=e[n]||[],e[n].push({'gtm.start':(new Date).getTime(),event:'gtm.js'});var r=t.getElementsByTagName(a)[0],s=t.createElement(a);s.async=!0;s.src=g;r.parentNode.insertBefore(s,r)}(window,document,'script','ttq','" . esc_js( $tt_px ) . "');</script>\n";
}
add_action( 'wp_head', 'dpw_psi_seo_meta', 1 );

/* ─── Header/Footer Scripts ─── */
function dpw_psi_header_script() {
    $code = get_theme_mod( 'dpw_header_script', '' );
    if ( $code ) echo "\n" . $code . "\n";
}
add_action( 'wp_head', 'dpw_psi_header_script', 99 );

function dpw_psi_footer_script() {
    $code = get_theme_mod( 'dpw_footer_script', '' );
    if ( $code ) echo "\n" . $code . "\n";
}
add_action( 'wp_footer', 'dpw_psi_footer_script', 99 );

/* ─── Customizer ─── */
function dpw_psi_customizer( $wp_customize ) {

    $wp_customize->add_panel( 'dpw_identity', array( 'title' => 'Identitas & Branding', 'priority' => 10 ) );

    $wp_customize->add_section( 'dpw_logo_section', array( 'title' => 'Logo & Favicon', 'panel' => 'dpw_identity' ) );
    $wp_customize->add_setting( 'dpw_favicon', array( 'sanitize_callback' => 'esc_url_raw' ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'dpw_favicon', array( 'label' => 'Favicon', 'section' => 'dpw_logo_section' ) ) );

    $wp_customize->add_section( 'dpw_colors_section', array( 'title' => 'Warna Utama', 'panel' => 'dpw_identity' ) );
    $wp_customize->add_setting( 'dpw_psi_red', array( 'default' => '#D4213D', 'sanitize_callback' => 'sanitize_hex_color' ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'dpw_psi_red', array( 'label' => 'Warna Merah PSI', 'section' => 'dpw_colors_section' ) ) );
    $wp_customize->add_setting( 'dpw_gold', array( 'default' => '#C9A96E', 'sanitize_callback' => 'sanitize_hex_color' ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'dpw_gold', array( 'label' => 'Warna Emas Premium', 'section' => 'dpw_colors_section' ) ) );
    $wp_customize->add_setting( 'dpw_dark', array( 'default' => '#1a1a1a', 'sanitize_callback' => 'sanitize_hex_color' ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'dpw_dark', array( 'label' => 'Warna Gelap', 'section' => 'dpw_colors_section' ) ) );

    $wp_customize->add_panel( 'dpw_homepage', array( 'title' => 'Pengaturan Beranda', 'priority' => 20 ) );

    $wp_customize->add_section( 'dpw_hero_section', array( 'title' => 'Hero Slider', 'panel' => 'dpw_homepage' ) );
    for ( $i = 1; $i <= 5; $i++ ) {
        $wp_customize->add_setting( "dpw_hero_{$i}_image", array( 'sanitize_callback' => 'esc_url_raw' ) );
        $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, "dpw_hero_{$i}_image", array( 'label' => "Slide {$i} - Gambar", 'section' => 'dpw_hero_section' ) ) );
        $wp_customize->add_setting( "dpw_hero_{$i}_title", array( 'default' => '', 'sanitize_callback' => 'sanitize_text_field' ) );
        $wp_customize->add_control( "dpw_hero_{$i}_title", array( 'label' => "Slide {$i} - Judul", 'section' => 'dpw_hero_section', 'type' => 'text' ) );
        $wp_customize->add_setting( "dpw_hero_{$i}_subtitle", array( 'default' => '', 'sanitize_callback' => 'sanitize_textarea_field' ) );
        $wp_customize->add_control( "dpw_hero_{$i}_subtitle", array( 'label' => "Slide {$i} - Sub Judul", 'section' => 'dpw_hero_section', 'type' => 'textarea' ) );
        $wp_customize->add_setting( "dpw_hero_{$i}_btn_text", array( 'default' => '', 'sanitize_callback' => 'sanitize_text_field' ) );
        $wp_customize->add_control( "dpw_hero_{$i}_btn_text", array( 'label' => "Slide {$i} - Teks Tombol", 'section' => 'dpw_hero_section', 'type' => 'text' ) );
        $wp_customize->add_setting( "dpw_hero_{$i}_btn_url", array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
        $wp_customize->add_control( "dpw_hero_{$i}_btn_url", array( 'label' => "Slide {$i} - URL Tombol", 'section' => 'dpw_hero_section', 'type' => 'url' ) );
    }

    $wp_customize->add_section( 'dpw_welcome_section', array( 'title' => 'Sambutan Ketua', 'panel' => 'dpw_homepage' ) );
    $wp_customize->add_setting( 'dpw_welcome_text', array( 'default' => 'Selamat datang di website resmi DPW PSI Papua Pegunungan.', 'sanitize_callback' => 'sanitize_textarea_field' ) );
    $wp_customize->add_control( 'dpw_welcome_text', array( 'label' => 'Teks Sambutan', 'section' => 'dpw_welcome_section', 'type' => 'textarea' ) );
    $wp_customize->add_setting( 'dpw_chairman_photo', array( 'sanitize_callback' => 'esc_url_raw' ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'dpw_chairman_photo', array( 'label' => 'Foto Ketua', 'section' => 'dpw_welcome_section' ) ) );
    $wp_customize->add_setting( 'dpw_chairman_name', array( 'default' => '', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'dpw_chairman_name', array( 'label' => 'Nama Ketua', 'section' => 'dpw_welcome_section', 'type' => 'text' ) );
    $wp_customize->add_setting( 'dpw_secretary_photo', array( 'sanitize_callback' => 'esc_url_raw' ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'dpw_secretary_photo', array( 'label' => 'Foto Sekretaris', 'section' => 'dpw_welcome_section' ) ) );
    $wp_customize->add_setting( 'dpw_secretary_name', array( 'default' => '', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'dpw_secretary_name', array( 'label' => 'Nama Sekretaris', 'section' => 'dpw_welcome_section', 'type' => 'text' ) );
    $wp_customize->add_setting( 'dpw_treasurer_photo', array( 'sanitize_callback' => 'esc_url_raw' ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'dpw_treasurer_photo', array( 'label' => 'Foto Bendahara', 'section' => 'dpw_welcome_section' ) ) );
    $wp_customize->add_setting( 'dpw_treasurer_name', array( 'default' => '', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'dpw_treasurer_name', array( 'label' => 'Nama Bendahara', 'section' => 'dpw_welcome_section', 'type' => 'text' ) );

    $wp_customize->add_section( 'dpw_stats_section', array( 'title' => 'Statistik Keanggotaan', 'panel' => 'dpw_homepage' ) );
    $stat_items = array( 'dpd_count' => 'Jumlah DPD', 'member_count' => 'Total Anggota', 'activity_count' => 'Kegiatan', 'regency_count' => 'Kabupaten' );
    foreach ( $stat_items as $key => $label ) {
        $wp_customize->add_setting( "dpw_stat_{$key}", array( 'default' => '0', 'sanitize_callback' => 'absint' ) );
        $wp_customize->add_control( "dpw_stat_{$key}", array( 'label' => $label, 'section' => 'dpw_stats_section', 'type' => 'number' ) );
    }

    $wp_customize->add_section( 'dpw_cta_section', array( 'title' => 'CTA Bergabung', 'panel' => 'dpw_homepage' ) );
    $wp_customize->add_setting( 'dpw_cta_text', array( 'default' => 'Bergabunglah Bersama Kami!', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'dpw_cta_text', array( 'label' => 'Teks CTA', 'section' => 'dpw_cta_section', 'type' => 'text' ) );
    $wp_customize->add_setting( 'dpw_cta_desc', array( 'default' => 'Menjadi bagian dari perubahan untuk Papua Pegunungan yang lebih baik.', 'sanitize_callback' => 'sanitize_textarea_field' ) );
    $wp_customize->add_control( 'dpw_cta_desc', array( 'label' => 'Deskripsi CTA', 'section' => 'dpw_cta_section', 'type' => 'textarea' ) );
    $wp_customize->add_setting( 'dpw_cta_btn_text', array( 'default' => 'Gabung Keanggotaan PSI', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'dpw_cta_btn_text', array( 'label' => 'Teks Tombol', 'section' => 'dpw_cta_section', 'type' => 'text' ) );
    $wp_customize->add_setting( 'dpw_cta_btn_url', array( 'default' => 'https://psi.id/menjadi-anggota/', 'sanitize_callback' => 'esc_url_raw' ) );
    $wp_customize->add_control( 'dpw_cta_btn_url', array( 'label' => 'URL Tombol', 'section' => 'dpw_cta_section', 'type' => 'url' ) );

    $wp_customize->add_panel( 'dpw_contact_panel', array( 'title' => 'Kontak & Media Sosial', 'priority' => 30 ) );

    $wp_customize->add_section( 'dpw_contact_section', array( 'title' => 'Informasi Kontak', 'panel' => 'dpw_contact_panel' ) );
    $contact_fields = array( 'dpw_address' => 'Alamat Kantor', 'dpw_email' => 'Email Resmi', 'dpw_phone' => 'Telepon', 'dpw_wa_number' => 'Nomor WhatsApp', 'dpw_maps_embed' => 'Google Maps Embed URL' );
    foreach ( $contact_fields as $key => $label ) {
        $wp_customize->add_setting( $key, array( 'default' => '', 'sanitize_callback' => 'sanitize_text_field' ) );
        $wp_customize->add_control( $key, array( 'label' => $label, 'section' => 'dpw_contact_section', 'type' => 'text' ) );
    }

    $wp_customize->add_section( 'dpw_social_section', array( 'title' => 'Media Sosial', 'panel' => 'dpw_contact_panel' ) );
    $social_fields = array( 'dpw_facebook' => 'Facebook URL', 'dpw_instagram' => 'Instagram URL', 'dpw_tiktok' => 'TikTok URL', 'dpw_youtube' => 'YouTube URL', 'dpw_twitter' => 'Twitter/X URL' );
    foreach ( $social_fields as $key => $label ) {
        $wp_customize->add_setting( $key, array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
        $wp_customize->add_control( $key, array( 'label' => $label, 'section' => 'dpw_social_section', 'type' => 'url' ) );
    }

    $wp_customize->add_section( 'dpw_wa_section', array( 'title' => 'WhatsApp Floating', 'panel' => 'dpw_contact_panel' ) );
    $wp_customize->add_setting( 'dpw_wa_float_number', array( 'default' => '082267218125', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'dpw_wa_float_number', array( 'label' => 'Nomor WhatsApp', 'section' => 'dpw_wa_section', 'type' => 'text' ) );
    $wp_customize->add_setting( 'dpw_wa_float_greeting', array( 'default' => 'Halo, saya tertarik dengan PSI Papua Pegunungan.', 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'dpw_wa_float_greeting', array( 'label' => 'Pesan Otomatis', 'section' => 'dpw_wa_section', 'type' => 'text' ) );
    $wp_customize->add_setting( 'dpw_wa_float_enabled', array( 'default' => '1', 'sanitize_callback' => 'absint' ) );
    $wp_customize->add_control( 'dpw_wa_float_enabled', array( 'label' => 'Aktifkan Floating WhatsApp', 'section' => 'dpw_wa_section', 'type' => 'checkbox' ) );

    $wp_customize->add_panel( 'dpw_seo_panel', array( 'title' => 'SEO & Pelacakan', 'priority' => 40 ) );

    $wp_customize->add_section( 'dpw_tracking_section', array( 'title' => 'Tracking & Pixel', 'panel' => 'dpw_seo_panel' ) );
    $tracking_fields = array( 'dpw_ga4_id' => 'Google Analytics 4 ID', 'dpw_gtm_id' => 'Google Tag Manager ID', 'dpw_meta_pixel' => 'Meta Pixel ID', 'dpw_tiktok_pixel' => 'TikTok Pixel ID', 'dpw_gsc_verify' => 'Google Search Console Verification Code' );
    foreach ( $tracking_fields as $key => $label ) {
        $wp_customize->add_setting( $key, array( 'default' => '', 'sanitize_callback' => 'sanitize_text_field' ) );
        $wp_customize->add_control( $key, array( 'label' => $label, 'section' => 'dpw_tracking_section', 'type' => 'text' ) );
    }

    $wp_customize->add_section( 'dpw_scripts_section', array( 'title' => 'Header & Footer Scripts', 'panel' => 'dpw_seo_panel' ) );
    $wp_customize->add_setting( 'dpw_header_script', array( 'default' => '', 'sanitize_callback' => 'wp_kses_post' ) );
    $wp_customize->add_control( 'dpw_header_script', array( 'label' => 'Header Script (setelah <head>)', 'section' => 'dpw_scripts_section', 'type' => 'textarea' ) );
    $wp_customize->add_setting( 'dpw_footer_script', array( 'default' => '', 'sanitize_callback' => 'wp_kses_post' ) );
    $wp_customize->add_control( 'dpw_footer_script', array( 'label' => 'Footer Script (sebelum </body>)', 'section' => 'dpw_scripts_section', 'type' => 'textarea' ) );

    $wp_customize->add_section( 'dpw_maintenance_section', array( 'title' => 'Mode Maintenance', 'priority' => 50 ) );
    $wp_customize->add_setting( 'dpw_maintenance', array( 'default' => '0', 'sanitize_callback' => 'absint' ) );
    $wp_customize->add_control( 'dpw_maintenance', array( 'label' => 'Aktifkan Mode Maintenance', 'section' => 'dpw_maintenance_section', 'type' => 'checkbox' ) );
    $wp_customize->add_setting( 'dpw_maintenance_msg', array( 'default' => 'Website sedang dalam pemeliharaan. Silakan kembali beberapa saat lagi.', 'sanitize_callback' => 'sanitize_textarea_field' ) );
    $wp_customize->add_control( 'dpw_maintenance_msg', array( 'label' => 'Pesan Maintenance', 'section' => 'dpw_maintenance_section', 'type' => 'textarea' ) );
}
add_action( 'customize_register', 'dpw_psi_customizer' );

/* ─── Customizer CSS Output ─── */
function dpw_psi_customizer_css() {
    $red  = get_theme_mod( 'dpw_psi_red', '#D4213D' );
    $gold = get_theme_mod( 'dpw_gold', '#C9A96E' );
    $dark = get_theme_mod( 'dpw_dark', '#1a1a1a' );
    echo "<style>:root{--psi-red:{$red};--psi-gold:{$gold};--psi-dark:{$dark};}</style>\n";
    if ( get_theme_mod( 'dpw_favicon' ) ) {
        echo '<link rel="icon" href="' . esc_url( get_theme_mod( 'dpw_favicon' ) ) . '" type="image/x-icon" />' . "\n";
    }
}
add_action( 'wp_head', 'dpw_psi_customizer_css', 5 );

/* ─── Customizer Button Control & Demo Import ─── */
if ( class_exists( 'WP_Customize_Control' ) && ! class_exists( 'DPW_PSI_Button_Control' ) ) {
    class DPW_PSI_Button_Control extends \WP_Customize_Control {
        public $type = 'button';
        public $button_class = '';
        public $link = '';

        public function render_content() {
            echo '<span class="customize-control-title">' . esc_html( $this->label ) . '</span>';
            if ( $this->description ) {
                echo '<span class="description customize-control-description">' . esc_html( $this->description ) . '</span>';
            }
            echo '<a href="' . esc_url( $this->link ) . '" class="button ' . esc_attr( $this->button_class ) . '">' . esc_html( $this->label ) . '</a>';
        }
    }
}

add_action( 'customize_register', function( $wp_customize ) {
    $wp_customize->add_section( 'dpw_demo_import', array( 'title' => 'Import Data Demo', 'priority' => 999 ) );
    $wp_customize->add_setting( 'dpw_demo_import_btn', array( 'sanitize_callback' => 'esc_url_raw' ) );
    $wp_customize->add_control( new DPW_PSI_Button_Control( $wp_customize, 'dpw_demo_import_btn', array(
        'section'      => 'dpw_demo_import',
        'label'        => 'Klik untuk Import Data Demo (DPD, Pengurus, Berita)',
        'button_class' => 'button button-primary',
        'link'         => wp_nonce_url( admin_url( 'customize.php?dpw_import_demo=1' ), 'dpw_demo_import', '_wpnonce' ),
    ) ) );
}, 100 );

/* ─── Maintenance Mode ─── */
function dpw_psi_maintenance_mode() {
    if ( get_theme_mod( 'dpw_maintenance', 0 ) && ! is_user_logged_in() && ! is_admin() ) {
        wp_die( '<div style="text-align:center;padding:80px 20px;font-family:sans-serif;"><h1 style="color:#D4213D;">🔧 Maintenance Mode</h1><p>' . esc_html( get_theme_mod( 'dpw_maintenance_msg', 'Website sedang dalam pemeliharaan.' ) ) . '</p></div>', 'Maintenance', array( 'response' => 503 ) );
    }
}
add_action( 'get_header', 'dpw_psi_maintenance_mode' );

/* ─── Security Hardening ─── */
function dpw_psi_security() {
    remove_action( 'wp_head', 'wp_generator' );
    remove_action( 'wp_head', 'wlwmanifest_link' );
    remove_action( 'wp_head', 'rsd_link' );
    remove_action( 'wp_head', 'wp_shortlink_wp_head' );
    add_filter( 'xmlrpc_enabled', '__return_false' );
    add_filter( 'login_errors', function() { return 'Informasi login salah.'; } );
}
add_action( 'init', 'dpw_psi_security' );

/* ─── Disable REST API for non-logged users (except PWA manifest) ─── */
function dpw_psi_disable_rest() {
    if ( is_user_logged_in() ) return;

    $rest_route = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';

    if ( strpos( $rest_route, '/wp-json/dpw/v1/manifest' ) !== false ) return;

    add_filter( 'rest_authentication_errors', function( $result ) {
        return new WP_Error( 'rest_disabled', 'API tidak tersedia.', array( 'status' => 403 ) );
    });
}
add_action( 'init', 'dpw_psi_disable_rest' );

/* ─── Contact Form AJAX Handler ─── */
function dpw_psi_contact_form() {
    check_ajax_referer( 'dpw_psi_nonce', 'nonce' );

    $name    = sanitize_text_field( wp_unslash( $_POST['name'] ?? '' ) );
    $email   = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
    $subject = sanitize_text_field( wp_unslash( $_POST['subject'] ?? '' ) );
    $message = sanitize_textarea_field( wp_unslash( $_POST['message'] ?? '' ) );
    $captcha = isset( $_POST['captcha_answer'] ) ? absint( $_POST['captcha_answer'] ) : 0;
    $expected = isset( $_POST['captcha_expected'] ) ? absint( $_POST['captcha_expected'] ) : 0;

    if ( empty( $name ) || empty( $email ) || empty( $message ) ) {
        wp_send_json_error( array( 'message' => 'Mohon lengkapi semua field yang wajib diisi.' ) );
    }

    if ( $expected === 0 || $captcha !== $expected ) {
        wp_send_json_error( array( 'message' => 'Jawaban verifikasi salah. Silakan coba lagi.' ) );
    }

    $to = get_theme_mod( 'dpw_email', get_option( 'admin_email' ) );
    $headers = array( 'Content-Type: text/plain; charset=UTF-8', 'Reply-To: ' . $email );
    $body = "Nama: {$name}\nEmail: {$email}\nSubjek: {$subject}\n\nPesan:\n{$message}";
    $sent = wp_mail( $to, "Kontak Website: {$subject}", $body, $headers );

    if ( $sent ) {
        wp_send_json_success( array( 'message' => 'Pesan Anda berhasil dikirim. Terima kasih!' ) );
    } else {
        wp_send_json_error( array( 'message' => 'Gagal mengirim pesan. Silakan coba lagi.' ) );
    }
}
add_action( 'wp_ajax_dpw_contact', 'dpw_psi_contact_form' );
add_action( 'wp_ajax_nopriv_dpw_contact', 'dpw_psi_contact_form' );

/* ─── Excerpt Length ─── */
function dpw_psi_excerpt_length( $length ) { return 25; }
add_filter( 'excerpt_length', 'dpw_psi_excerpt_length' );

function dpw_psi_excerpt_more( $more ) { return '...'; }
add_filter( 'excerpt_more', 'dpw_psi_excerpt_more' );

/* ─── Share Buttons ─── */
function dpw_psi_share_buttons() {
    $url   = urlencode( get_permalink() );
    $title = urlencode( get_the_title() );
    echo '<div class="dpw-share-buttons">';
    echo '<a href="https://www.facebook.com/sharer/sharer.php?u=' . $url . '" target="_blank" rel="noopener" class="share-btn share-fb"><i class="bi bi-facebook"></i></a>';
    echo '<a href="https://twitter.com/intent/tweet?url=' . $url . '&text=' . $title . '" target="_blank" rel="noopener" class="share-btn share-tw"><i class="bi bi-twitter-x"></i></a>';
    echo '<a href="https://wa.me/?text=' . $title . '%20' . $url . '" target="_blank" rel="noopener" class="share-btn share-wa"><i class="bi bi-whatsapp"></i></a>';
    echo '<a href="https://t.me/share/url?url=' . $url . '&text=' . $title . '" target="_blank" rel="noopener" class="share-btn share-tg"><i class="bi bi-telegram"></i></a>';
    echo '</div>';
}

/* ─── WhatsApp Float ─── */
function dpw_psi_wa_float() {
    if ( ! get_theme_mod( 'dpw_wa_float_enabled', 1 ) ) return;
    $number   = get_theme_mod( 'dpw_wa_float_number', '082267218125' );
    $greeting = urlencode( get_theme_mod( 'dpw_wa_float_greeting', 'Halo, saya tertarik dengan PSI Papua Pegunungan.' ) );
    $url = 'https://wa.me/' . preg_replace( '/[^0-9]/', '', $number ) . '?text=' . $greeting;
    echo '<a href="' . esc_url( $url ) . '" target="_blank" rel="noopener" class="dpw-wa-float" aria-label="Chat WhatsApp"><i class="bi bi-whatsapp"></i></a>';
}
add_action( 'wp_footer', 'dpw_psi_wa_float' );

/* ─── Preloader ─── */
function dpw_psi_preloader() {
    echo '<div id="dpw-preloader"><div class="dpw-spinner"></div></div>';
}
add_action( 'wp_body_open', 'dpw_psi_preloader' );

/* ─── PWA Manifest ─── */
function dpw_psi_pwa_manifest() {
    $manifest = array(
        'name'        => get_bloginfo( 'name' ),
        'short_name'  => 'PSI Papeng',
        'description' => get_bloginfo( 'description' ),
        'start_url'   => home_url( '/' ),
        'display'     => 'standalone',
        'background_color' => '#ffffff',
        'theme_color' => get_theme_mod( 'dpw_psi_red', '#D4213D' ),
        'icons'       => array(
            array( 'src' => DPW_PSI_URI . '/assets/icons/icon-192.png', 'sizes' => '192x192', 'type' => 'image/png' ),
            array( 'src' => DPW_PSI_URI . '/assets/icons/icon-512.png', 'sizes' => '512x512', 'type' => 'image/png' ),
        ),
    );
    echo '<link rel="manifest" href="' . esc_url( home_url( '/wp-json/dpw/v1/manifest' ) ) . '">' . "\n";
    echo '<meta name="theme-color" content="' . esc_attr( get_theme_mod( 'dpw_psi_red', '#D4213D' ) ) . '">' . "\n";
    echo '<meta name="apple-mobile-web-app-capable" content="yes">' . "\n";
    echo '<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">' . "\n";
}
add_action( 'wp_head', 'dpw_psi_pwa_manifest', 6 );

/* ─── Demo Data Importer ─── */
function dpw_psi_import_demo_data() {
    if ( ! isset( $_GET['dpw_import_demo'] ) || ! current_user_can( 'manage_options' ) || ! wp_verify_nonce( $_GET['_wpnonce'] ?? '', 'dpw_demo_import' ) ) return;

    $regencies = array( 'Jayawijaya', 'Yahukimo', 'Pegunungan Bintang', 'Tolikara', 'Mamberamo Tengah', 'Yalimo', 'Lanny Jaya', 'Nduga' );
    foreach ( $regencies as $regency ) {
        if ( ! post_exists( "DPD PSI Kabupaten {$regency}", '', '', 'psi_dpd' ) ) {
            wp_insert_post( array(
                'post_title'  => "DPD PSI Kabupaten {$regency}",
                'post_type'   => 'psi_dpd',
                'post_status' => 'publish',
                'meta_input'  => array(
                    '_dpd_chairman'   => "Ketua DPD {$regency}",
                    '_dpd_members'    => rand( 50, 500 ),
                    '_dpd_short_desc' => "DPW PSI Papua Pegunungan - Kabupaten {$regency}",
                ),
            ) );
        }
    }

    $positions = array(
        'Ketua Dewan Penasehat',
        'Ketua DPW PSI Papua Pegunungan',
        'Wakil Ketua Bappilu',
        'Wakil Ketua Dewan Saksi',
        'Wakil Ketua Organisasi Keanggotaan & Kaderisasi',
        'Sekretaris',
        'Wakil Sekretaris',
        'Bendahara',
        'Wakil Bendahara',
        'Kepala Bidang Politik & Hukum',
        'Kepala Bidang Pendidikan & Pelatihan',
        'Kepala Bidang Humas & Media',
        'Kepala Bidang Sosial & Kemanusiaan',
        'Kepala Bidang Ekonomi & UMKM',
        'Kepala Biro Administrasi',
    );
    foreach ( $positions as $pos ) {
        if ( ! post_exists( $pos, '', '', 'psi_org' ) ) {
            wp_insert_post( array(
                'post_title'  => $pos,
                'post_type'   => 'psi_org',
                'post_status' => 'publish',
                'meta_input'  => array( '_org_position' => $pos ),
            ) );
        }
    }

    $sample_news = array(
        array( 'PSI Papua Pegunungan Gelar Rapat Koordinasi DPD Se-Wilayah', 'Rapat koordinasi yang dihadiri seluruh ketua DPD dari 8 kabupaten di Papua Pegunungan membahas program strategis tahun 2025.' ),
        array( 'Kader PSI Aktifkan Program Pendidikan di Daerah Terpencil', 'Program pendidikan gratis untuk masyarakat di pedalaman Papua Pegunungan terus digalakkan oleh kader-kader PSI.' ),
        array( 'PSI Dorong Pembangunan Infrastruktur Papua Pegunungan', 'Dewan Pimpinan Wilayah PSI mendorong pemerintah pusat untuk mempercepat pembangunan infrastruktur dasar.' ),
        array( 'Musyawarah Wilayah PSI Papua Pegunungan Sukses Digelar', 'Muswil PSI Papua Pegunungan berlangsung khidmat dengan dihadiri ratusan kader dari seluruh penjuru wilayah.' ),
        array( 'PSI Salurkan Bantuan Kemanusiaan untuk Korban Bencana', 'Bantuan logistik dan medis disalurkan langsung oleh relawan PSI ke lokasi terdampak bencana alam.' ),
    );
    foreach ( $sample_news as $i => $news ) {
        if ( ! post_exists( $news[0], '', '', 'psi_news' ) ) {
            wp_insert_post( array(
                'post_title'   => $news[0],
                'post_content' => '<p>' . $news[1] . '</p><p>DPW PSI Papua Pegunungan terus berkomitmen untuk membawa perubahan nyata bagi masyarakat di wilayah Papua Pegunungan. Berbagai program telah dijalankan dan akan terus ditingkatkan seiring dengan bertambahnya kekuatan organisasi di tingkat akar rumput.</p><p>Partai Solidaritas Indonesia percaya bahwa solidaritas adalah kunci untuk membangun Papua Pegunungan yang lebih maju, sejahtera, dan bermartabat. Melalui kerja keras dan dedikasi seluruh kader, kami optimis bahwa perubahan besar akan terwujud.</p>',
                'post_type'    => 'psi_news',
                'post_status'  => 'publish',
                'post_excerpt' => $news[1],
            ) );
        }
    }

    wp_safe_redirect( admin_url( 'customize.php' ) );
    exit;
}
add_action( 'admin_init', 'dpw_psi_import_demo_data' );

/* ─── Query Modifications for CPT Archives ─── */
function dpw_psi_pre_get_posts( $query ) {
    if ( ! is_admin() && $query->is_main_query() ) {
        if ( is_post_type_archive( 'psi_news' ) ) {
            $query->set( 'posts_per_page', 12 );
        }
        if ( is_post_type_archive( 'psi_gallery' ) ) {
            $query->set( 'posts_per_page', 24 );
        }
        if ( is_post_type_archive( 'psi_video' ) ) {
            $query->set( 'posts_per_page', 12 );
        }
    }
}
add_action( 'pre_get_posts', 'dpw_psi_pre_get_posts' );

/* ─── Flush rewrite rules on theme switch/activate ─── */
add_action( 'after_switch_theme', function() {
    flush_rewrite_rules();
});

/* ─── Include Inc Files ─── */
require_once DPW_PSI_DIR . '/inc/cleanup.php';
require_once DPW_PSI_DIR . '/inc/sitemaps.php';
require_once DPW_PSI_DIR . '/inc/pwa.php';
require_once DPW_PSI_DIR . '/inc/widgets.php';
