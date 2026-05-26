<?php
/**
 * Custom Widgets
 * @package DPW_PSI_Papua_Pegunungan
 */
if ( ! defined( 'ABSPATH' ) ) exit;

/* ─── Trending News Widget ─── */
class DPW_PSI_Trending_Widget extends \WP_Widget {

    public function __construct() {
        parent::__construct(
            'dpw_trending_widget',
            'PSI — Berita Populer',
            array( 'description' => 'Menampilkan berita PSI terpopuler berdasarkan jumlah komentar.' )
        );
    }

    public function widget( $args, $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : 'Berita Populer';
        $count = ! empty( $instance['count'] ) ? absint( $instance['count'] ) : 5;

        echo $args['before_widget'];
        echo $args['before_title'] . esc_html( $title ) . $args['after_title'];

        $query = new \WP_Query( array(
            'post_type'      => 'psi_news',
            'posts_per_page' => $count,
            'orderby'        => 'comment_count',
            'order'          => 'DESC',
            'post_status'    => 'publish',
        ) );

        if ( $query->have_posts() ) {
            $num = 1;
            echo '<div class="dpw-trending-widget-list">';
            while ( $query->have_posts() ) {
                $query->the_post();
                echo '<a href="' . esc_url( get_permalink() ) . '" class="dpw-trending-item">';
                echo '<span class="dpw-trending-num">' . $num++ . '</span>';
                echo '<span class="dpw-trending-text">' . esc_html( get_the_title() ) . '</span>';
                echo '</a>';
            }
            echo '</div>';
            wp_reset_postdata();
        }

        echo $args['after_widget'];
    }

    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : 'Berita Populer';
        $count = ! empty( $instance['count'] ) ? $instance['count'] : 5;
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">Judul:</label>
            <input type="text" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $title ); ?>" class="widefat">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>">Jumlah:</label>
            <input type="number" id="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'count' ) ); ?>" value="<?php echo esc_attr( $count ); ?>" min="1" max="20" class="widefat">
        </p>
        <?php
    }

    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = sanitize_text_field( $new_instance['title'] );
        $instance['count'] = absint( $new_instance['count'] );
        return $instance;
    }
}

/* ─── Ad Banner Widget ─── */
class DPW_PSI_Ad_Widget extends \WP_Widget {

    public function __construct() {
        parent::__construct(
            'dpw_ad_widget',
            'PSI — Banner Iklan',
            array( 'description' => 'Menampilkan banner iklan dengan gambar dan link.' )
        );
    }

    public function widget( $args, $instance ) {
        $image = ! empty( $instance['image'] ) ? $instance['image'] : '';
        $link  = ! empty( $instance['link'] ) ? $instance['link'] : '';
        $alt   = ! empty( $instance['alt'] ) ? $instance['alt'] : 'Advertisement';

        if ( ! $image ) return;

        echo $args['before_widget'];
        if ( $link ) {
            echo '<a href="' . esc_url( $link ) . '" target="_blank" rel="noopener sponsored">';
        }
        echo '<img src="' . esc_url( $image ) . '" alt="' . esc_attr( $alt ) . '" loading="lazy">';
        if ( $link ) {
            echo '</a>';
        }
        echo $args['after_widget'];
    }

    public function form( $instance ) {
        $image = ! empty( $instance['image'] ) ? $instance['image'] : '';
        $link  = ! empty( $instance['link'] ) ? $instance['link'] : '';
        $alt   = ! empty( $instance['alt'] ) ? $instance['alt'] : 'Advertisement';
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'image' ) ); ?>">URL Gambar:</label>
            <input type="url" id="<?php echo esc_attr( $this->get_field_id( 'image' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'image' ) ); ?>" value="<?php echo esc_attr( $image ); ?>" class="widefat">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'link' ) ); ?>">URL Tujuan:</label>
            <input type="url" id="<?php echo esc_attr( $this->get_field_id( 'link' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'link' ) ); ?>" value="<?php echo esc_attr( $link ); ?>" class="widefat">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'alt' ) ); ?>">Alt Text:</label>
            <input type="text" id="<?php echo esc_attr( $this->get_field_id( 'alt' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'alt' ) ); ?>" value="<?php echo esc_attr( $alt ); ?>" class="widefat">
        </p>
        <?php
    }

    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['image'] = esc_url_raw( $new_instance['image'] );
        $instance['link']  = esc_url_raw( $new_instance['link'] );
        $instance['alt']   = sanitize_text_field( $new_instance['alt'] );
        return $instance;
    }
}

/* ─── Social Links Widget ─── */
class DPW_PSI_Social_Widget extends \WP_Widget {

    public function __construct() {
        parent::__construct(
            'dpw_social_widget',
            'PSI — Media Sosial',
            array( 'description' => 'Tautan media sosial PSI.' )
        );
    }

    public function widget( $args, $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : 'Ikuti Kami';
        echo $args['before_widget'];
        echo $args['before_title'] . esc_html( $title ) . $args['after_title'];
        echo '<div class="dpw-widget-socials">';
        $socials = array(
            'dpw_facebook'  => 'bi-facebook',
            'dpw_instagram' => 'bi-instagram',
            'dpw_tiktok'    => 'bi-tiktok',
            'dpw_youtube'   => 'bi-youtube',
            'dpw_twitter'   => 'bi-twitter-x',
        );
        foreach ( $socials as $key => $icon ) {
            $url = get_theme_mod( $key, '' );
            if ( $url ) {
                echo '<a href="' . esc_url( $url ) . '" target="_blank" rel="noopener" class="dpw-widget-social-link" style="--ws-color:var(--psi-red);"><i class="bi ' . esc_attr( $icon ) . '"></i></a>';
            }
        }
        echo '</div>';
        echo $args['after_widget'];
    }

    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : 'Ikuti Kami';
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">Judul:</label>
            <input type="text" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $title ); ?>" class="widefat">
        </p>
        <p><em>Link diambil dari pengaturan Customizer > Kontak & Media Sosial.</em></p>
        <?php
    }

    public function update( $new_instance, $old_instance ) {
        return array( 'title' => sanitize_text_field( $new_instance['title'] ) );
    }
}

/* ─── Register Widgets ─── */
add_action( 'widgets_init', function() {
    register_widget( 'DPW_PSI_Trending_Widget' );
    register_widget( 'DPW_PSI_Ad_Widget' );
    register_widget( 'DPW_PSI_Social_Widget' );
});
