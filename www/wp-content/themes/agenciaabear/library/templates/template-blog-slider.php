<?php 
$kopa_setting = kopa_get_template_setting();

if ( 'blog-5' == $kopa_setting['layout_id'] ) {
    $kopa_blog_slider_image_size = 'flexslider-image-size';
} else {
    $kopa_blog_slider_image_size = 'gallery-image-size';
}

// get options
$kopa_blog_slider_category_id = (int) get_option( 'kopa_theme_options_blog_slider_category_id' );
$kopa_theme_options_blog_slider_posts_number = (int) get_option( 'kopa_theme_options_blog_slider_posts_number', 3 );
$kopa_blog_slider_settings = array(
    'animation'       => get_option( 'kopa_theme_options_blog_slider_effect', 'slide' ),
    'slideshow_speed' => (int) get_option( 'kopa_theme_options_blog_slider_slideshow_speed', 7000 ),
    'animation_speed' => (int) get_option( 'kopa_theme_options_blog_slider_animation_speed', 600 ),
    'autoplay'        => get_option( 'kopa_theme_options_blog_slider_autoplay', 'false' )
);

// validate options
if ( $kopa_theme_options_blog_slider_posts_number <= 0 ) {
    $kopa_theme_options_blog_slider_posts_number = 3;
}

// new query posts
$kopa_blog_slider_posts = new WP_Query( array(
    'cat'            => $kopa_blog_slider_category_id,
    'posts_per_page' => $kopa_theme_options_blog_slider_posts_number,
) );

if ( $kopa_blog_slider_posts->have_posts() && 'show' == get_option( 'kopa_theme_options_display_blog_slider', 'show' ) ) { ?>
    <div class="kp-slider-widget widget">
        <div class="home-slider flexslider loading" data-animation="<?php echo $kopa_blog_slider_settings['animation']; ?>" data-slideshow_speed="<?php echo $kopa_blog_slider_settings['slideshow_speed']; ?>" data-animation_speed="<?php echo $kopa_blog_slider_settings['animation_speed']; ?>" data-autoplay="<?php echo $kopa_blog_slider_settings['autoplay']; ?>" data-direction="horizontal">
            <ul class="slides">
            <?php while ( $kopa_blog_slider_posts->have_posts() ) { 
                $kopa_blog_slider_posts->the_post(); ?>
                <?php if ( has_post_thumbnail() ) { ?>
                    <li>
                        <article>
                            <?php the_post_thumbnail( $kopa_blog_slider_image_size ); ?>
                            <div class="flex-caption">
                                <header>
                                    
                                </header>
                                <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                                <?php the_field('linha-fina'); ?>
                            </div>
                            <!-- flex-caption -->
                        </article>
                    </li>
                <?php } // endif ?>
            <?php } // endwhile ?>
            </ul>
        </div>
    </div>
<?php } // endif

wp_reset_postdata();
