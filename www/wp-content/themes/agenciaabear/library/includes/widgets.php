<?php
/**
 * Widget Registration
 * @package FastNews
 */

add_action('widgets_init', 'kopa_widgets_init');

function kopa_widgets_init() {
    register_widget('Kopa_Widget_Text');
    register_widget('Kopa_Widget_Flexslider');
    register_widget('Kopa_Widget_Articles_List');
    register_widget('Kopa_Widget_Small_Articles_List');
    register_widget('Kopa_Widget_Articles_Carousel');
    register_widget('Kopa_Widget_Entry_List');
    register_widget('Kopa_Widget_Entry_List_2');
    register_widget('Kopa_Widget_Advertising');
    register_widget('Kopa_Widget_Gallery');
    register_widget('Kopa_Widget_Videos_Slider');
    register_widget('Kopa_Widget_Mailchimp_Subscribe');
    register_widget('Kopa_Widget_Feedburner_Subscribe');
}

add_action('admin_enqueue_scripts', 'kopa_widget_admin_enqueue_scripts');

function kopa_widget_admin_enqueue_scripts($hook) {
    if ('widgets.php' === $hook) {
        $dir = get_template_directory_uri() . '/library';
        wp_enqueue_style('kopa_widget_admin', "{$dir}/css/widget.css");
        wp_enqueue_script('kopa_widget_admin', "{$dir}/js/widget.js", array('jquery'));
    }
}

function kopa_widget_article_build_query($query_args = array()) {
    $args = array(
        'post_type' => array('post'),
        'posts_per_page' => $query_args['number_of_article']
    );

    $tax_query = array();

    if ($query_args['categories']) {
        $tax_query[] = array(
            'taxonomy' => 'category',
            'field' => 'id',
            'terms' => $query_args['categories']
        );
    }
    if ($query_args['tags']) {
        $tax_query[] = array(
            'taxonomy' => 'post_tag',
            'field' => 'id',
            'terms' => $query_args['tags']
        );
    }
    if ($query_args['relation'] && count($tax_query) == 2) {
        $tax_query['relation'] = $query_args['relation'];
    }

    if ($tax_query) {
        $args['tax_query'] = $tax_query;
    }

    switch ($query_args['orderby']) {
        case 'popular':
            $args['meta_key'] = 'kopa_' . kopa_get_domain() . '_total_view';
            $args['orderby'] = 'meta_value_num';
            break;
        case 'most_comment':
            $args['orderby'] = 'comment_count';
            break;
        case 'random':
            $args['orderby'] = 'rand';
            break;
        default:
            $args['orderby'] = 'date';
            break;
    }
    if (isset($query_args['post__not_in']) && $query_args['post__not_in']) {
        $args['post__not_in'] = $query_args['post__not_in'];
    }
    return new WP_Query($args);
}

function kopa_widget_posttype_build_query( $query_args = array() ) {
    $default_query_args = array(
        'post_type'      => 'post',
        'posts_per_page' => -1,
        'post__not_in'   => array(),
        'ignore_sticky_posts' => 1,
        'categories'     => array(),
        'tags'           => array(),
        'relation'       => 'OR',
        'orderby'        => 'latest',
        'cat_name'       => 'category',
        'tag_name'       => 'post_tag'
    );

    $query_args = wp_parse_args( $query_args, $default_query_args );

    $args = array(
        'post_type'           => $query_args['post_type'],
        'posts_per_page'      => $query_args['posts_per_page'],
        'post__not_in'        => $query_args['post__not_in'],
        'ignore_sticky_posts' => $query_args['ignore_sticky_posts']
    );

    $tax_query = array();

    if ( $query_args['categories'] ) {
        $tax_query[] = array(
            'taxonomy' => $query_args['cat_name'],
            'field'    => 'id',
            'terms'    => $query_args['categories']
        );
    }
    if ( $query_args['tags'] ) {
        $tax_query[] = array(
            'taxonomy' => $query_args['tag_name'],
            'field'    => 'id',
            'terms'    => $query_args['tags']
        );
    }
    if ( $query_args['relation'] && count( $tax_query ) == 2 ) {
        $tax_query['relation'] = $query_args['relation'];
    }

    if ( $tax_query ) {
        $args['tax_query'] = $tax_query;
    }

    switch ( $query_args['orderby'] ) {
    case 'popular':
        $args['meta_key'] = 'kopa_' . kopa_get_domain() . '_total_view';
        $args['orderby'] = 'meta_value_num';
        break;
    case 'most_comment':
        $args['orderby'] = 'comment_count';
        break;
    case 'random':
        $args['orderby'] = 'rand';
        break;
    default:
        $args['orderby'] = 'date';
        break;
    }

    return new WP_Query( $args );
}

class Kopa_Widget_Text extends WP_Widget {

    function __construct() {
        $widget_ops = array('classname' => 'kopa_widget_text', 'description' => __('Arbitrary text, HTML or shortcodes', kopa_get_domain()));
        $control_ops = array('width' => 600, 'height' => 400);
        parent::__construct('kopa_widget_text', __('Kopa Text', kopa_get_domain()), $widget_ops, $control_ops);
    }

    function widget($args, $instance) {
        extract($args);
        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
        $text = apply_filters('widget_text', empty($instance['text']) ? '' : $instance['text'], $instance);

        echo $before_widget;
        if ( !empty( $title ) ) {
            echo $before_title . $title . $after_title;
        }
        ?>
        <?php echo !empty($instance['filter']) ? wpautop($text) : $text; ?>
        <?php
        echo $after_widget;
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        if (current_user_can('unfiltered_html')) {
            $instance['text'] = $new_instance['text'];
        } else {
            $instance['text'] = stripslashes(wp_filter_post_kses(addslashes($new_instance['text'])));
        }
        $instance['filter'] = isset($new_instance['filter']);
        return $instance;
    }

    function form($instance) {
        $instance = wp_parse_args((array) $instance, array('title' => '', 'text' => ''));
        $title = strip_tags($instance['title']);
        $text = esc_textarea($instance['text']);
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>        
        <ul class="kopa_shortcode_icons">
            <?php
            $shortcodes = array(
                'one_half'           => __( 'One Half Column', kopa_get_domain() ),
                'one_third'          => __( 'One Thirtd Column', kopa_get_domain() ),
                'two_third'          => __( 'Two Third Column', kopa_get_domain() ),
                'one_fourth'         => __( 'One Fourth Column', kopa_get_domain() ),
                'three_fourth'       => __( 'Three Fourth Column', kopa_get_domain() ),
                'dropcaps'           => __( 'Add Dropcaps Text', kopa_get_domain() ),
                'button'             => __( 'Add A Button', kopa_get_domain() ),
                'alert'              => __( 'Add A Alert Box', kopa_get_domain() ),
                'tabs'               => __( 'Add A Tabs Content', kopa_get_domain() ),
                'accordions'         => __( 'Add A Accordions Content', kopa_get_domain() ),
                'toggle'             => __( 'Add A Toggle Content', kopa_get_domain() ),
                'contact_form'       => __( 'Add A Contact Form', kopa_get_domain() ),
                'posts_lastest'      => __( 'Add A List Latest Post', kopa_get_domain() ),
                'posts_popular'      => __( 'Add A List Popular Post', kopa_get_domain() ),
                'posts_most_comment' => __( 'Add A List Most Comment Post', kopa_get_domain() ),
                'posts_random'       => __( 'Add A List Random Post', kopa_get_domain() ),
                'youtube'            => __( 'Add A Yoube Video Box', kopa_get_domain() ),
                'vimeo'              => __( 'Add A Vimeo Video Box', kopa_get_domain() ),
            );
            foreach ($shortcodes as $rel => $title):
                ?>
                <li>
                    <a onclick="return kopa_shortcode_icon_click('<?php echo $rel; ?>', jQuery('#<?php echo $this->get_field_id('text'); ?>'));" href="#" class="<?php echo "kopa-icon-{$rel}"; ?>" rel="<?php echo $rel; ?>" title="<?php echo $title; ?>"></a>
                </li>
            <?php endforeach; ?>
        </ul>        
        <textarea class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo $text; ?></textarea>
        <p>
            <input id="<?php echo $this->get_field_id('filter'); ?>" name="<?php echo $this->get_field_name('filter'); ?>" type="checkbox" <?php checked(isset($instance['filter']) ? $instance['filter'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id('filter'); ?>"><?php _e('Automatically add paragraphs', kopa_get_domain()); ?></label>
        </p>
        <?php
    }

}

/**
 * Flexslider Widget Class
 * @since FastNews 1.0
 */
class Kopa_Widget_Flexslider extends WP_Widget {
    function __construct() {
        $widget_ops = array('classname' => 'kp-slider-widget', 'description' => __('A Posts Slider Widget', kopa_get_domain()));
        $control_ops = array('width' => '500', 'height' => 'auto');
        parent::__construct('kopa_widget_flexslider', __('Kopa Flexslider', kopa_get_domain()), $widget_ops, $control_ops);
    }

    function widget( $args, $instance ) {
        extract( $args );
        $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
        $query_args['categories'] = $instance['categories'];
        $query_args['relation'] = $instance['relation'];
        $query_args['tags'] = $instance['tags'];
        $query_args['posts_per_page'] = $instance['number_of_article'];
        $query_args['orderby'] = $instance['orderby'];

        echo $before_widget;

        if ( ! empty( $title ) ) {
            echo $before_title . $title . $after_title;
        }

        $posts = kopa_widget_posttype_build_query( $query_args );

        if ( $posts->have_posts() ) { ?>
        <div class="loading flexslider home-slider" data-animation="<?php echo $instance['animation']; ?>" data-direction="<?php echo $instance['direction'] ?>" data-slideshow_speed="<?php echo $instance['slideshow_speed']; ?>" data-animation_speed="<?php echo $instance['animation_speed']; ?>" data-autoplay="<?php echo $instance['is_auto_play']; ?>">
            <ul class="slides">
            <?php while ( $posts->have_posts() ) { $posts->the_post(); ?>
                <li>
                    <article>
                        <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'flexslider-image-size' ); ?></a>
                        <div class="flex-caption">
                            <header>
                                <span class="entry-categories"><?php _e( 'in:', kopa_get_domain() ); ?> <?php the_category( ', ' ); ?></span>
                                <span class="entry-met">&nbsp;|&nbsp;</span>
                                <span class="entry-date"><?php the_time( get_option( 'date_format' ) ); ?></span>
                            </header>
                            <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                            <?php the_excerpt(); ?>
                        </div>
                        <!-- flex-caption -->
                    </article>
                </li>
            <?php } // endwhile ?>
            </ul>
            <!-- slides -->
        </div>
        <?php
        } // endif $posts->have_posts()

        wp_reset_postdata();

        echo $after_widget;
    }

    function form($instance) {
        $defaults = array(
            'title'             => '',
            'categories'        => array(),
            'relation'          => 'OR',
            'tags'              => array(),
            'number_of_article' => 10,
            'orderby'           => 'latest',
            'animation'         => 'slide',
            'direction'         => 'horizontal',
            'slideshow_speed'   => '7000',
            'animation_speed'   => '600',
            'is_auto_play'      => 'true'
        );
        $instance = wp_parse_args( (array) $instance, $defaults );
        $title = strip_tags( $instance['title'] );

        $form['categories'] = $instance['categories'];
        $form['relation'] = esc_attr($instance['relation']);
        $form['tags'] = $instance['tags'];
        $form['number_of_article'] = (int) $instance['number_of_article'];
        $form['orderby'] = $instance['orderby'];
        $form['animation'] = $instance['animation'];
        $form['direction'] = $instance['direction'];
        $form['slideshow_speed'] = (int) $instance['slideshow_speed'];
        $form['animation_speed'] = (int) $instance['animation_speed'];
        $form['is_auto_play'] = $instance['is_auto_play'];
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <div class="kopa-one-half">
            <p>
                <label for="<?php echo $this->get_field_id('categories'); ?>"><?php _e('Categories:', kopa_get_domain()); ?></label>                
                <select class="widefat" id="<?php echo $this->get_field_id('categories'); ?>" name="<?php echo $this->get_field_name('categories'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                    <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                    <?php
                    $categories = get_categories();
                    foreach ($categories as $category) {
                        printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $category->term_id, $category->name, $category->count, (in_array($category->term_id, $form['categories'])) ? 'selected="selected"' : '');
                    }
                    ?>
                </select>

            </p>
            <p>
                <label for="<?php echo $this->get_field_id('relation'); ?>"><?php _e('Relation:', kopa_get_domain()); ?></label>                
                <select class="widefat" id="<?php echo $this->get_field_id('relation'); ?>" name="<?php echo $this->get_field_name('relation'); ?>" autocomplete="off">
                    <?php
                    $relation = array(
                        'AND' => __('And', kopa_get_domain()),
                        'OR' => __('Or', kopa_get_domain())
                    );
                    foreach ($relation as $value => $title) {
                        printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['relation']) ? 'selected="selected"' : '');
                    }
                    ?>
                </select>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('tags'); ?>"><?php _e('Tags:', kopa_get_domain()); ?></label>                
                <select class="widefat" id="<?php echo $this->get_field_id('tags'); ?>" name="<?php echo $this->get_field_name('tags'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                    <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                    <?php
                    $tags = get_tags();
                    foreach ($tags as $tag) {
                        printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $tag->term_id, $tag->name, $tag->count, (in_array($tag->term_id, $form['tags'])) ? 'selected="selected"' : '');
                    }
                    ?>
                </select>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('number_of_article'); ?>"><?php _e('Number of article:', kopa_get_domain()); ?></label>                
                <input class="widefat" type="number" min="2" id="<?php echo $this->get_field_id('number_of_article'); ?>" name="<?php echo $this->get_field_name('number_of_article'); ?>" value="<?php echo esc_attr( $form['number_of_article'] ); ?>">
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e('Orderby:', kopa_get_domain()); ?></label>                
                <select class="widefat" id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>" autocomplete="off">
                    <?php
                    $orderby = array(
                        'latest' => __('Latest', kopa_get_domain()),
                        'popular' => __('Popular by View Count', kopa_get_domain()),
                        'most_comment' => __('Popular by Comment Count', kopa_get_domain()),
                        'random' => __('Random', kopa_get_domain()),
                    );
                    foreach ($orderby as $value => $title) {
                        printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['orderby']) ? 'selected="selected"' : '');
                    }
                    ?>
                </select>
            </p>
        </div>
        <div class="kopa-one-half last">
            <p>
                <label for="<?php echo $this->get_field_id('animation'); ?>"><?php _e('Animation:', kopa_get_domain()); ?></label>                
                <select class="widefat" id="<?php echo $this->get_field_id('animation'); ?>" name="<?php echo $this->get_field_name('animation'); ?>" autocomplete="off">
                    <?php
                    $animation = array(
                        'slide' => __('Slide', kopa_get_domain()),
                        'fade'  => __('Fade', kopa_get_domain()),
                    );
                    foreach ($animation as $value => $title) {
                        printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['animation']) ? 'selected="selected"' : '');
                    }
                    ?>
                </select>
            </p>

            <p>
                <label for="<?php echo $this->get_field_id('direction'); ?>"><?php _e('Direction:', kopa_get_domain()); ?></label>                
                <select class="widefat" id="<?php echo $this->get_field_id('direction'); ?>" name="<?php echo $this->get_field_name('direction'); ?>" autocomplete="off">
                    <?php
                    $direction = array(
                        'horizontal' => __('Horizontal', kopa_get_domain()),
                        'vertical'   => __('Vertical', kopa_get_domain()),
                    );
                    foreach ($direction as $value => $title) {
                        printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['direction']) ? 'selected="selected"' : '');
                    }
                    ?>
                </select>
            </p>

            <p>
                <label for="<?php echo $this->get_field_id('slideshow_speed'); ?>"><?php _e('Speed of the slideshow cycling:', kopa_get_domain()); ?></label> 
                <input class="widefat" id="<?php echo $this->get_field_id('slideshow_speed'); ?>" name="<?php echo $this->get_field_name('slideshow_speed'); ?>" type="number" value="<?php echo $form['slideshow_speed']; ?>" />
            </p>

            <p>
                <label for="<?php echo $this->get_field_id('animation_speed'); ?>"><?php _e('Speed of animations:', kopa_get_domain()); ?></label>                
                <input class="widefat" id="<?php echo $this->get_field_id('animation_speed'); ?>" name="<?php echo $this->get_field_name('animation_speed'); ?>" type="number" value="<?php echo $form['animation_speed']; ?>" />
            </p>

            <p>
                <input class="" id="<?php echo $this->get_field_id('is_auto_play'); ?>" name="<?php echo $this->get_field_name('is_auto_play'); ?>" type="checkbox" value="true" <?php echo ('true' === $form['is_auto_play']) ? 'checked="checked"' : ''; ?> />
                <label for="<?php echo $this->get_field_id('is_auto_play'); ?>"><?php _e('Auto Play', kopa_get_domain()); ?></label>                                
            </p>
        </div>
        <div class="kopa-clear"></div>
        <?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['categories'] = (empty($new_instance['categories'])) ? array() : array_filter($new_instance['categories']);
        $instance['relation'] = $new_instance['relation'];
        $instance['tags'] = (empty($new_instance['tags'])) ? array() : array_filter($new_instance['tags']);
        $instance['number_of_article'] = (int) $new_instance['number_of_article'];
        if ( 0 >= $instance['number_of_article'] ) {
            $instance['number_of_article'] = 10;
        }
        $instance['orderby'] = $new_instance['orderby'];
        $instance['animation'] = $new_instance['animation'];
        $instance['direction'] = $new_instance['direction'];
        $instance['slideshow_speed'] = (int) $new_instance['slideshow_speed'];
        $instance['animation_speed'] = (int) $new_instance['animation_speed'];
        $instance['is_auto_play'] = isset($new_instance['is_auto_play']) ? $new_instance['is_auto_play'] : 'false';

        return $instance;
    }
} // end Kopa_Widget_Flexslider

/**
 * Articles List Widget Class
 * @since FastNews 1.0
 */
class Kopa_Widget_Articles_List extends WP_Widget {
    function __construct() {
        $widget_ops = array('classname' => 'kp-article-list-widget clearfix', 'description' => __('Display Latest Articles Widget', kopa_get_domain()));
        $control_ops = array('width' => 'auto', 'height' => 'auto');
        parent::__construct('kopa_widget_articles_list', __('Kopa Articles List', kopa_get_domain()), $widget_ops, $control_ops);
    }

    function widget( $args, $instance ) {
        extract( $args );
        $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
        $query_args['categories'] = $instance['categories'];
        $query_args['relation'] = $instance['relation'];
        $query_args['tags'] = $instance['tags'];
        $query_args['posts_per_page'] = $instance['number_of_article'];
        $query_args['orderby'] = $instance['orderby'];

        echo $before_widget;

        if ( ! empty( $title ) ) {
            echo $before_title . $title . $after_title;
        }

        $posts = kopa_widget_posttype_build_query( $query_args );
        ?>

        <?php if ( $posts->have_posts() ) {
            $post_index = 1;
            
            while ( $posts->have_posts() ) { 
                $posts->the_post();
                ?>

                <?php if ( 1 == $post_index ) { ?>

                    <article class="entry-item clearfix">
                        <?php if ( 'gallery' == get_post_format() ) { 
                            $gallery_ids = kopa_content_get_gallery_attachment_ids( get_the_content() );

                            foreach ( $gallery_ids as $index => $id ) {
                                if ( ! wp_attachment_is_image( $id ) ) {
                                    unset( $gallery_ids[ $index ] );
                                }
                            }
                            ?>
                            <div class="entry-thumb">
                                <?php if ( ! empty( $gallery_ids ) ) { ?>
                                    <div class="news-slider flexslider loading">
                                        <ul class="slides">
                                            <?php foreach ( $gallery_ids as $id ) { 
                                                $full_attachment_src = wp_get_attachment_image_src( $id, 'full' );
                                                ?>
                                                <li><a rel="prettyPhoto[<?php echo $this->get_field_id( 'gallery' ) . '-' . get_the_ID(); ?>]" href="<?php echo $full_attachment_src[0]; ?>"><?php echo wp_get_attachment_image( $id, 'article-list-image-size' ); ?></a></li>
                                            <?php } // endforeach ?>
                                        </ul>
                                    </div>
                                <?php } elseif ( has_post_thumbnail() ) { ?>
                                    <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'article-list-image-size' ); ?></a>
                                <?php } // endif ?>
                            </div>
                        <?php } elseif ( has_post_thumbnail() ) { ?>
                            <div class="entry-thumb">
                                <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'article-list-image-size' ); ?></a>
                            </div>
                        <?php } ?>
                        <div class="entry-content">
                            <h4 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                            <span class="entry-date">&mdash; <?php the_time( get_option( 'date_format' ) ); ?></span>
                            <?php the_excerpt(); ?>
                        </div>
                    </article>

                    <?php if ( $posts->post_count > 1 ) {
                        echo '<ul class="older-post clearfix">';
                    } ?>

                <?php } else { ?>

                    <li class="clearfix">
                        <div class="entry-thumb">
                            <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'article-list-sm-image-size' ); ?></a>
                        </div>
                        <div class="entry-content">
                            <h4 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                            <span class="entry-date">&mdash; <?php the_time( get_option( 'date_format' ) ); ?></span>
                            <?php the_excerpt(); ?>
                        </div>
                    </li>

                    <?php if ( $post_index == $posts->post_count ) {
                        echo '</ul>';
                    } ?>

                <?php } // endif ?>

                <?php
                // increases post index by 1
                $post_index++;
                ?>

            <?php } // endwhile ?>

            
        <?php } // endif ?>

        <?php
        wp_reset_postdata();

        echo $after_widget;
    }

    function form($instance) {
        $defaults = array(
            'title'             => '',
            'categories'        => array(),
            'relation'          => 'OR',
            'tags'              => array(),
            'number_of_article' => 3,
            'orderby'           => 'latest',
        );
        $instance = wp_parse_args( (array) $instance, $defaults );
        $title = strip_tags( $instance['title'] );

        $form['categories'] = $instance['categories'];
        $form['relation'] = esc_attr($instance['relation']);
        $form['tags'] = $instance['tags'];
        $form['number_of_article'] = $instance['number_of_article'];
        $form['orderby'] = $instance['orderby'];

        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('categories'); ?>"><?php _e('Categories:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('categories'); ?>" name="<?php echo $this->get_field_name('categories'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                <?php
                $categories = get_categories();
                foreach ($categories as $category) {
                    printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $category->term_id, $category->name, $category->count, (in_array($category->term_id, $form['categories'])) ? 'selected="selected"' : '');
                }
                ?>
            </select>

        </p>
        <p>
            <label for="<?php echo $this->get_field_id('relation'); ?>"><?php _e('Relation:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('relation'); ?>" name="<?php echo $this->get_field_name('relation'); ?>" autocomplete="off">
                <?php
                $relation = array(
                    'AND' => __('And', kopa_get_domain()),
                    'OR' => __('Or', kopa_get_domain())
                );
                foreach ($relation as $value => $title) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['relation']) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('tags'); ?>"><?php _e('Tags:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('tags'); ?>" name="<?php echo $this->get_field_name('tags'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                <?php
                $tags = get_tags();
                foreach ($tags as $tag) {
                    printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $tag->term_id, $tag->name, $tag->count, (in_array($tag->term_id, $form['tags'])) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('number_of_article'); ?>"><?php _e('Number of article:', kopa_get_domain()); ?></label>                
            <input class="widefat" type="number" min="1" id="<?php echo $this->get_field_id('number_of_article'); ?>" name="<?php echo $this->get_field_name('number_of_article'); ?>" value="<?php echo esc_attr( $form['number_of_article'] ); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e('Orderby:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>" autocomplete="off">
                <?php
                $orderby = array(
                    'latest' => __('Latest', kopa_get_domain()),
                    'popular' => __('Popular by View Count', kopa_get_domain()),
                    'most_comment' => __('Popular by Comment Count', kopa_get_domain()),
                    'random' => __('Random', kopa_get_domain()),
                );
                foreach ($orderby as $value => $title) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['orderby']) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        
        <?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['categories'] = (empty($new_instance['categories'])) ? array() : array_filter($new_instance['categories']);
        $instance['relation'] = $new_instance['relation'];
        $instance['tags'] = (empty($new_instance['tags'])) ? array() : array_filter($new_instance['tags']);
        $instance['number_of_article'] = (int) $new_instance['number_of_article'];
        // validate number of article
        if ( 0 >= $instance['number_of_article'] ) {
            $instance['number_of_article'] = 3;
        }
        $instance['orderby'] = $new_instance['orderby'];

        return $instance;
    }
}

/**
 * Small Articles List Widget Class
 * @since FastNews 1.0
 */
class Kopa_Widget_Small_Articles_List extends WP_Widget {
    function __construct() {
        $widget_ops = array('classname' => 'kp-small-list-widget', 'description' => __('Display Latest Articles Widget', kopa_get_domain()));
        $control_ops = array('width' => 'auto', 'height' => 'auto');
        parent::__construct('kopa_widget_small_articles_list', __('Kopa Small Articles List', kopa_get_domain()), $widget_ops, $control_ops);
    }

    function widget( $args, $instance ) {
        extract( $args );
        $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
        $query_args['categories'] = $instance['categories'];
        $query_args['relation'] = $instance['relation'];
        $query_args['tags'] = $instance['tags'];
        $query_args['posts_per_page'] = $instance['number_of_article'];
        $query_args['orderby'] = $instance['orderby'];

        echo $before_widget;

        if ( ! empty( $title ) ) {
            echo $before_title . $title . $after_title;
        }

        $posts = kopa_widget_posttype_build_query( $query_args );
        ?>

        <?php if ( $posts->have_posts() ) {
            $post_index = 1; ?>

            <ul class="clearfix">
            
            <?php while ( $posts->have_posts() ) { 
                $posts->the_post();
                ?>

                <li>
                    <article class="entry-item">
                        <div class="entry-thumb"><a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'article-list-sm-image-size' ); ?></a></div>
                        <div class="entry-content">
                            <h4 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                            <span class="entry-date">&mdash; <?php the_time( get_option( 'date_format' ) ); ?></span>
                            <?php the_excerpt(); ?>
                        </div>
                    </article>
                </li>

                <?php
                if ( $post_index % 2 == 0 ) {
                    echo '<div class="clear"></div>';
                }

                // increases post index by 1
                $post_index++;
                ?>

            <?php } // endwhile ?>

            </ul>
            
        <?php } // endif ?>

        <?php
        wp_reset_postdata();

        echo $after_widget;
    }

    function form($instance) {
        $defaults = array(
            'title'             => '',
            'categories'        => array(),
            'relation'          => 'OR',
            'tags'              => array(),
            'number_of_article' => 3,
            'orderby'           => 'latest',
        );
        $instance = wp_parse_args( (array) $instance, $defaults );
        $title = strip_tags( $instance['title'] );

        $form['categories'] = $instance['categories'];
        $form['relation'] = esc_attr($instance['relation']);
        $form['tags'] = $instance['tags'];
        $form['number_of_article'] = $instance['number_of_article'];
        $form['orderby'] = $instance['orderby'];

        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('categories'); ?>"><?php _e('Categories:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('categories'); ?>" name="<?php echo $this->get_field_name('categories'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                <?php
                $categories = get_categories();
                foreach ($categories as $category) {
                    printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $category->term_id, $category->name, $category->count, (in_array($category->term_id, $form['categories'])) ? 'selected="selected"' : '');
                }
                ?>
            </select>

        </p>
        <p>
            <label for="<?php echo $this->get_field_id('relation'); ?>"><?php _e('Relation:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('relation'); ?>" name="<?php echo $this->get_field_name('relation'); ?>" autocomplete="off">
                <?php
                $relation = array(
                    'AND' => __('And', kopa_get_domain()),
                    'OR' => __('Or', kopa_get_domain())
                );
                foreach ($relation as $value => $title) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['relation']) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('tags'); ?>"><?php _e('Tags:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('tags'); ?>" name="<?php echo $this->get_field_name('tags'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                <?php
                $tags = get_tags();
                foreach ($tags as $tag) {
                    printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $tag->term_id, $tag->name, $tag->count, (in_array($tag->term_id, $form['tags'])) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('number_of_article'); ?>"><?php _e('Number of article:', kopa_get_domain()); ?></label>                
            <input class="widefat" type="number" min="1" id="<?php echo $this->get_field_id('number_of_article'); ?>" name="<?php echo $this->get_field_name('number_of_article'); ?>" value="<?php echo esc_attr( $form['number_of_article'] ); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e('Orderby:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>" autocomplete="off">
                <?php
                $orderby = array(
                    'latest' => __('Latest', kopa_get_domain()),
                    'popular' => __('Popular by View Count', kopa_get_domain()),
                    'most_comment' => __('Popular by Comment Count', kopa_get_domain()),
                    'random' => __('Random', kopa_get_domain()),
                );
                foreach ($orderby as $value => $title) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['orderby']) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        
        <?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['categories'] = (empty($new_instance['categories'])) ? array() : array_filter($new_instance['categories']);
        $instance['relation'] = $new_instance['relation'];
        $instance['tags'] = (empty($new_instance['tags'])) ? array() : array_filter($new_instance['tags']);
        $instance['number_of_article'] = (int) $new_instance['number_of_article'];
        // validate number of article
        if ( 0 >= $instance['number_of_article'] ) {
            $instance['number_of_article'] = 3;
        }
        $instance['orderby'] = $new_instance['orderby'];

        return $instance;
    }
}

/**
 * Articles Carousel Widget Class
 * @since FastNews 1.0
 */
class Kopa_Widget_Articles_Carousel extends WP_Widget {
    function __construct() {
        $widget_ops = array('classname' => 'kp-featured-news-widget', 'description' => __('Display Articles Carousel Widget', kopa_get_domain()));
        $control_ops = array('width' => '500', 'height' => 'auto');
        parent::__construct('kopa_widget_articles_carousel', __('Kopa Articles Carousel', kopa_get_domain()), $widget_ops, $control_ops);
    }

    function widget( $args, $instance ) {
        extract( $args );
        $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
        $query_args['categories'] = $instance['categories'];
        $query_args['relation'] = $instance['relation'];
        $query_args['tags'] = $instance['tags'];
        $query_args['posts_per_page'] = $instance['number_of_article'];
        $query_args['orderby'] = $instance['orderby'];

        echo $before_widget;
        if ( ! empty( $title ) ) {
            echo $before_title . $title . $after_title;
        }
        
        $posts = kopa_widget_posttype_build_query( $query_args );

        if ( $posts->have_posts() ) { ?>
            <div class="list-carousel responsive">
                <ul class="kopa-featured-news-carousel clearfix" data-next-id="#<?php echo $this->get_field_id( 'next-1' ); ?>" data-prev-id="#<?php echo $this->get_field_id( 'prev-1' ); ?>" data-scroll-items="<?php echo $instance['scroll_items']; ?>" data-columns="<?php echo $instance['columns']; ?>" data-autoplay="<?php echo $instance['autoplay']; ?>" data-duration="<?php echo $instance['duration']; ?>" data-timeout-duration="<?php echo $instance['timeout_duration']; ?>">
                <?php while ( $posts->have_posts() ) { 
                    $posts->the_post(); ?>
                    <li style="width: 160px;">
                        <article class="entry-item clearfix">
                            <div class="entry-thumb">
                                <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'article-carousel-image-size' ); ?></a>
                            </div>
                            <div class="entry-content">
                                <header>
                                    <h4 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                                    <span class="entry-date">&mdash; <?php the_time( get_option( 'date_format' ) ); ?></span>
                                </header>
                                <?php the_excerpt(); ?>
                            </div><!--entry-content-->
                        </article><!--entry-item-->
                    </li>
                <?php } // endwhile ?>  
                </ul><!--kopa-featured-news-carousel-->
                <div class="clearfix"></div>
                <div class="carousel-nav clearfix">
                    <a id="<?php echo $this->get_field_id( 'prev-1' ); ?>" class="carousel-prev" href="#" data-icon="&#xf053;"></a>
                    <a id="<?php echo $this->get_field_id( 'next-1' ); ?>" class="carousel-next" href="#" data-icon="&#xf054;"></a>
                </div>
            </div><!--list-carousel-->
            <?php
        } // endif $posts->have_posts()

        wp_reset_postdata();
        
        echo $after_widget;
    }

    function form($instance) {
        $defaults = array(
            'title'             => '',
            'categories'        => array(),
            'relation'          => 'OR',
            'tags'              => array(),
            'number_of_article' => 8,
            'orderby'           => 'latest',
            'scroll_items'      => 1,
            'columns'           => 3,
            'autoplay'          => 'false',
            'duration'          => 500,
            'timeout_duration'  => 2500,
        );
        $instance = wp_parse_args( (array) $instance, $defaults );
        $title = strip_tags( $instance['title'] );

        $form['categories'] = $instance['categories'];
        $form['relation'] = esc_attr($instance['relation']);
        $form['tags'] = $instance['tags'];
        $form['number_of_article'] = (int) $instance['number_of_article'];
        $form['orderby'] = $instance['orderby'];
        $form['scroll_items'] = $instance['scroll_items'];
        $form['columns'] = $instance['columns'];
        $form['autoplay'] = $instance['autoplay'];
        $form['duration'] = $instance['duration'];
        $form['timeout_duration'] = $instance['timeout_duration'];
        ?>
        <div class="kopa-one-half">
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('categories'); ?>"><?php _e('Categories:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('categories'); ?>" name="<?php echo $this->get_field_name('categories'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                <?php
                $categories = get_categories();
                foreach ($categories as $category) {
                    printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $category->term_id, $category->name, $category->count, (in_array($category->term_id, $form['categories'])) ? 'selected="selected"' : '');
                }
                ?>
            </select>

        </p>
        <p>
            <label for="<?php echo $this->get_field_id('relation'); ?>"><?php _e('Relation:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('relation'); ?>" name="<?php echo $this->get_field_name('relation'); ?>" autocomplete="off">
                <?php
                $relation = array(
                    'AND' => __('And', kopa_get_domain()),
                    'OR' => __('Or', kopa_get_domain())
                );
                foreach ($relation as $value => $title) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['relation']) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('tags'); ?>"><?php _e('Tags:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('tags'); ?>" name="<?php echo $this->get_field_name('tags'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                <?php
                $tags = get_tags();
                foreach ($tags as $tag) {
                    printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $tag->term_id, $tag->name, $tag->count, (in_array($tag->term_id, $form['tags'])) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('number_of_article'); ?>"><?php _e('Number of article:', kopa_get_domain()); ?></label>                
            <input class="widefat" type="number" min="2" id="<?php echo $this->get_field_id('number_of_article'); ?>" name="<?php echo $this->get_field_name('number_of_article'); ?>" value="<?php echo esc_attr( $form['number_of_article'] ); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e('Orderby:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>" autocomplete="off">
                <?php
                $orderby = array(
                    'latest' => __('Latest', kopa_get_domain()),
                    'popular' => __('Popular by View Count', kopa_get_domain()),
                    'most_comment' => __('Popular by Comment Count', kopa_get_domain()),
                    'random' => __('Random', kopa_get_domain())
                );
                foreach ($orderby as $value => $title) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['orderby']) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        </div>

        <div class="kopa-one-half last">
        <p>
            <label for="<?php echo $this->get_field_id('scroll_items'); ?>"><?php _e('Scroll Items:', kopa_get_domain()); ?></label>                
            <input class="widefat" type="number" min="1" id="<?php echo $this->get_field_id('scroll_items'); ?>" name="<?php echo $this->get_field_name('scroll_items'); ?>" value="<?php echo esc_attr( $form['scroll_items'] ); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('columns'); ?>"><?php _e('Number of Columns:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id( 'columns' ); ?>" name="<?php echo $this->get_field_name( 'columns' ); ?>">
                <?php $columns = array( 3, 4, 5, 6 );
                foreach ( $columns as $value ) { ?>
                    <option value="<?php echo $value; ?>" <?php selected( $form['columns'], $value ); ?>><?php echo $value; ?></option>
                <?php } ?>
            </select>
        </p>
        <p>
            <input class="" id="<?php echo $this->get_field_id('autoplay'); ?>" name="<?php echo $this->get_field_name('autoplay'); ?>" type="checkbox" value="true" <?php checked( $form['autoplay'], 'true' ); ?>>
            <label for="<?php echo $this->get_field_id('autoplay'); ?>"><?php _e('Auto Play', kopa_get_domain()); ?></label>                                
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('duration'); ?>"><?php _e('Duration of the transition (milliseconds):', kopa_get_domain()); ?></label>                
            <input class="widefat" type="number" min="100" step="100" id="<?php echo $this->get_field_id('duration'); ?>" name="<?php echo $this->get_field_name('duration'); ?>" value="<?php echo esc_attr( $form['duration'] ); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('timeout_duration'); ?>"><?php _e('The amount of milliseconds the carousel will pause:', kopa_get_domain()); ?></label>                
            <input class="widefat" type="number" min="100" step="100" id="<?php echo $this->get_field_id('timeout_duration'); ?>" name="<?php echo $this->get_field_name('timeout_duration'); ?>" value="<?php echo esc_attr( $form['timeout_duration'] ); ?>">
        </p>
        </div>
        <div class="kopa-clear"></div>
        <?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['categories'] = (empty($new_instance['categories'])) ? array() : array_filter($new_instance['categories']);
        $instance['relation'] = $new_instance['relation'];
        $instance['tags'] = (empty($new_instance['tags'])) ? array() : array_filter($new_instance['tags']);
        $instance['number_of_article'] = (int) $new_instance['number_of_article'];
        if ( 0 >= $instance['number_of_article'] ) {
            $instance['number_of_article'] = 8;
        }
        $instance['orderby'] = $new_instance['orderby'];
        $instance['scroll_items'] = (int) $new_instance['scroll_items'];
        if ( 0 >= $instance['scroll_items'] ) {
            $instance['scroll_items'] = 1;
        }

        $instance['columns'] = $new_instance['columns'];

        $instance['autoplay'] = isset( $new_instance['autoplay'] ) ? $new_instance['autoplay'] : 'false';

        $instance['duration'] = (int) $new_instance['duration'] ? (int) $new_instance['duration'] : 500;
        $instance['timeout_duration'] = (int) $new_instance['timeout_duration'] ? (int) $new_instance['timeout_duration'] : 2500;

        return $instance;
    }
}

/**
 * Entry List Widget Class
 * @since FastNews 1.0
 */
class Kopa_Widget_Entry_List extends WP_Widget {
    function __construct() {
        $widget_ops = array('classname' => 'kp-video-widget', 'description' => __('Display Featured Articles Widget', kopa_get_domain()));
        $control_ops = array('width' => 'auto', 'height' => 'auto');
        parent::__construct('kopa_widget_entry_list', __('Kopa Entry List', kopa_get_domain()), $widget_ops, $control_ops);
    }

    function widget( $args, $instance ) {
        extract( $args );
        $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
        $query_args['categories'] = $instance['categories'];
        $query_args['relation'] = $instance['relation'];
        $query_args['tags'] = $instance['tags'];
        $query_args['posts_per_page'] = $instance['number_of_article'];
        $query_args['orderby'] = $instance['orderby'];

        echo $before_widget;

        if ( ! empty( $title ) ) {
            echo $before_title . $title . $after_title;
        }

        $posts = kopa_widget_posttype_build_query( $query_args );
        
        if ( $posts->have_posts() ) {
            $post_index = 1;
            ?>
            
            <ul class="clearfix">

            <?php while ( $posts->have_posts() ) { 
                $posts->the_post(); ?>

                <li>
                    <article class="entry-item">
                        <header>
                            <h4 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                            <?php the_field('linha-fina'); ?>
                        </header>
                        <?php if ( has_post_thumbnail() ) { ?>
                            <div class="entry-thumb">
                                <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'article-list-image-size' ); ?></a>

                                <?php if ( 'video' == get_post_format() ) { 
                                    $video = kopa_content_get_video( get_the_content() );
                                    
                                    if ( isset( $video[0] ) ) {
                                        $video = $video[0];
                                    } else {
                                        $video = '';
                                    }

                                    if ( isset( $video['url'] ) ) { ?>
                                        <a rel="prettyPhoto" href="<?php echo esc_url( $video['url'] ); ?>" class="play-icon"></a>
                                    <?php } ?>
                                <?php } // endif ?>
                            </div>
                        <?php } // endif ?>
                    </article>
                </li>

            <?php } // endwhile ?>

            </ul>

        <?php } // endif ?>

        <?php
        wp_reset_postdata();

        echo $after_widget;
    }

    function form($instance) {
        $defaults = array(
            'title'             => '',
            'categories'        => array(),
            'relation'          => 'OR',
            'tags'              => array(),
            'number_of_article' => 4,
            'orderby'           => 'latest',
        );
        $instance = wp_parse_args( (array) $instance, $defaults );
        $title = strip_tags( $instance['title'] );

        $form['categories'] = $instance['categories'];
        $form['relation'] = esc_attr($instance['relation']);
        $form['tags'] = $instance['tags'];
        $form['number_of_article'] = $instance['number_of_article'];
        $form['orderby'] = $instance['orderby'];

        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('categories'); ?>"><?php _e('Categories:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('categories'); ?>" name="<?php echo $this->get_field_name('categories'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                <?php
                $categories = get_categories();
                foreach ($categories as $category) {
                    printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $category->term_id, $category->name, $category->count, (in_array($category->term_id, $form['categories'])) ? 'selected="selected"' : '');
                }
                ?>
            </select>

        </p>
        <p>
            <label for="<?php echo $this->get_field_id('relation'); ?>"><?php _e('Relation:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('relation'); ?>" name="<?php echo $this->get_field_name('relation'); ?>" autocomplete="off">
                <?php
                $relation = array(
                    'AND' => __('And', kopa_get_domain()),
                    'OR' => __('Or', kopa_get_domain())
                );
                foreach ($relation as $value => $title) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['relation']) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('tags'); ?>"><?php _e('Tags:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('tags'); ?>" name="<?php echo $this->get_field_name('tags'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                <?php
                $tags = get_tags();
                foreach ($tags as $tag) {
                    printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $tag->term_id, $tag->name, $tag->count, (in_array($tag->term_id, $form['tags'])) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('number_of_article'); ?>"><?php _e('Number of article:', kopa_get_domain()); ?></label>                
            <input class="widefat" type="number" min="1" id="<?php echo $this->get_field_id('number_of_article'); ?>" name="<?php echo $this->get_field_name('number_of_article'); ?>" value="<?php echo esc_attr( $form['number_of_article'] ); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e('Orderby:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>" autocomplete="off">
                <?php
                $orderby = array(
                    'latest' => __('Latest', kopa_get_domain()),
                    'popular' => __('Popular by View Count', kopa_get_domain()),
                    'most_comment' => __('Popular by Comment Count', kopa_get_domain()),
                    'random' => __('Random', kopa_get_domain()),
                );
                foreach ($orderby as $value => $title) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['orderby']) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        
        <?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['categories'] = (empty($new_instance['categories'])) ? array() : array_filter($new_instance['categories']);
        $instance['relation'] = $new_instance['relation'];
        $instance['tags'] = (empty($new_instance['tags'])) ? array() : array_filter($new_instance['tags']);
        $instance['number_of_article'] = (int) $new_instance['number_of_article'];
        // validate number of article
        if ( 0 >= $instance['number_of_article'] ) {
            $instance['number_of_article'] = 4;
        }
        $instance['orderby'] = $new_instance['orderby'];

        return $instance;
    }
}

/**
 * Entry List Widget Class
 * Display posts in widget area 5, 6, 7, 8, 9
 * @since FastNews 1.0
 */
class Kopa_Widget_Entry_List_2 extends WP_Widget {
    function __construct() {
        $widget_ops = array('classname' => 'kp-entry-list-widget', 'description' => __('Display Featured Articles Widget', kopa_get_domain()));
        $control_ops = array('width' => 'auto', 'height' => 'auto');
        parent::__construct('kopa_widget_entry_list_2', __('Kopa Entry List 2', kopa_get_domain()), $widget_ops, $control_ops);
    }

    function widget( $args, $instance ) {
        extract( $args );
        $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
        $query_args['categories'] = $instance['categories'];
        $query_args['relation'] = $instance['relation'];
        $query_args['tags'] = $instance['tags'];
        $query_args['posts_per_page'] = $instance['number_of_article'];
        $query_args['orderby'] = $instance['orderby'];

        echo $before_widget;

        if ( ! empty( $title ) ) {
            echo $before_title . $title . $after_title;
        }

        $posts = kopa_widget_posttype_build_query( $query_args );
        
        if ( $posts->have_posts() ) { ?>
            
            <?php while ( $posts->have_posts() ) { 
                $posts->the_post(); ?>

                <div class="widget kp-article-list-widget clearfix">
                    <article class="entry-item clearfix">
                        <div class="entry-thumb">
                            <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'article-list-image-size' ); ?></a>
                        </div>
                        <div class="entry-content">
                            <h4 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                            <span class="entry-date">&mdash; <?php the_time( get_option( 'date_format' ) ); ?></span>
                            <?php the_excerpt(); ?>
                        </div>
                    </article>
                </div>

            <?php } // endwhile ?>

        <?php } // endif ?>

        <?php
        wp_reset_postdata();

        echo $after_widget;
    }

    function form($instance) {
        $defaults = array(
            'title'             => '',
            'categories'        => array(),
            'relation'          => 'OR',
            'tags'              => array(),
            'number_of_article' => 1,
            'orderby'           => 'latest',
        );
        $instance = wp_parse_args( (array) $instance, $defaults );
        $title = strip_tags( $instance['title'] );

        $form['categories'] = $instance['categories'];
        $form['relation'] = esc_attr($instance['relation']);
        $form['tags'] = $instance['tags'];
        $form['number_of_article'] = $instance['number_of_article'];
        $form['orderby'] = $instance['orderby'];

        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('categories'); ?>"><?php _e('Categories:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('categories'); ?>" name="<?php echo $this->get_field_name('categories'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                <?php
                $categories = get_categories();
                foreach ($categories as $category) {
                    printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $category->term_id, $category->name, $category->count, (in_array($category->term_id, $form['categories'])) ? 'selected="selected"' : '');
                }
                ?>
            </select>

        </p>
        <p>
            <label for="<?php echo $this->get_field_id('relation'); ?>"><?php _e('Relation:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('relation'); ?>" name="<?php echo $this->get_field_name('relation'); ?>" autocomplete="off">
                <?php
                $relation = array(
                    'AND' => __('And', kopa_get_domain()),
                    'OR' => __('Or', kopa_get_domain())
                );
                foreach ($relation as $value => $title) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['relation']) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('tags'); ?>"><?php _e('Tags:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('tags'); ?>" name="<?php echo $this->get_field_name('tags'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                <?php
                $tags = get_tags();
                foreach ($tags as $tag) {
                    printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $tag->term_id, $tag->name, $tag->count, (in_array($tag->term_id, $form['tags'])) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('number_of_article'); ?>"><?php _e('Number of article:', kopa_get_domain()); ?></label>                
            <input class="widefat" type="number" min="1" id="<?php echo $this->get_field_id('number_of_article'); ?>" name="<?php echo $this->get_field_name('number_of_article'); ?>" value="<?php echo esc_attr( $form['number_of_article'] ); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e('Orderby:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>" autocomplete="off">
                <?php
                $orderby = array(
                    'latest' => __('Latest', kopa_get_domain()),
                    'popular' => __('Popular by View Count', kopa_get_domain()),
                    'most_comment' => __('Popular by Comment Count', kopa_get_domain()),
                    'random' => __('Random', kopa_get_domain()),
                );
                foreach ($orderby as $value => $title) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['orderby']) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        
        <?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['categories'] = (empty($new_instance['categories'])) ? array() : array_filter($new_instance['categories']);
        $instance['relation'] = $new_instance['relation'];
        $instance['tags'] = (empty($new_instance['tags'])) ? array() : array_filter($new_instance['tags']);
        $instance['number_of_article'] = (int) $new_instance['number_of_article'];
        // validate number of article
        if ( 0 >= $instance['number_of_article'] ) {
            $instance['number_of_article'] = 4;
        }
        $instance['orderby'] = $new_instance['orderby'];

        return $instance;
    }
}

/**
 * Advertising Widget Class
 * @since FastNews 1.0
 */
class Kopa_Widget_Advertising extends WP_Widget {
    function __construct() {
        $widget_ops = array('classname' => 'kp-adv-widget', 'description' => __('Display one advertising image', kopa_get_domain()));
        $control_ops = array('width' => 'auto', 'height' => 'auto');
        parent::__construct('kopa_widget_advertising', __('Kopa Advertising', kopa_get_domain()), $widget_ops, $control_ops);
    }

    function widget( $args, $instance ) {
        extract( $args );
        $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
        $caption = $instance['caption'];
        $image_src = $instance['image_src'];
        $image_url = $instance['image_url'];
        $style = $instance['style'];

        echo $before_widget;

        if ( ! empty( $title ) ) {
           echo $before_title . $title . $after_title;
        }
        ?>

            <?php if ( $image_url ) { ?>
                <a href="<?php echo esc_url($image_url) ?>"><img src="<?php echo esc_url($image_src); ?>" alt=""></a>
            <?php } else { ?>
                <img src="<?php echo esc_url($image_src); ?>" alt="<?php echo $title; ?>">
            <?php } ?>

            <?php if ( 'caption' == $style && ! empty( $caption ) ) { ?>
                <h6 class="adv-caption text-center">
                    <?php if ( ! empty( $image_url ) ) { ?>
                        <a href="<?php echo esc_url( $image_url ); ?>"><?php echo $caption; ?></a>
                    <?php } else {
                        echo $caption;
                    } ?>
                </h6>
            <?php } ?>

        <?php
        
        echo $after_widget;
    }

    function form($instance) {
        $defaults = array(
            'title'     => '',
            'caption'   => '',
            'image_src' => '',
            'image_url' => '',
            'style'     => 'normal',
        );
        $instance = wp_parse_args( (array) $instance, $defaults );
        $title = strip_tags( $instance['title'] );
        $form['caption'] = $instance['caption'];
        $form['image_src'] = $instance['image_src'];
        $form['image_url'] = $instance['image_url'];
        $form['style'] = $instance['style'];
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('caption'); ?>"><?php _e('Caption:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('caption'); ?>" name="<?php echo $this->get_field_name('caption'); ?>" type="text" value="<?php echo esc_attr($form['caption']); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('image_src'); ?>"><?php _e('Image Source:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('image_src'); ?>" name="<?php echo $this->get_field_name('image_src'); ?>" type="text" value="<?php echo esc_attr($form['image_src']); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('image_url'); ?>"><?php _e('Url:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('image_url'); ?>" name="<?php echo $this->get_field_name('image_url'); ?>" type="text" value="<?php echo esc_attr($form['image_url']); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('style'); ?>"><?php _e('Style:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('style'); ?>" name="<?php echo $this->get_field_name('style'); ?>" autocomplete="off">
                <?php
                $styles = array(
                    'normal'  => __('Normal', kopa_get_domain()),
                    'caption' => __('Caption', kopa_get_domain()),
                );
                foreach ($styles as $value => $title) { ?>
                    
                    <option value="<?php echo $value; ?>" <?php selected( $form['style'], $value ); ?>><?php echo $title; ?></option>

                <?php } // endforeach ?>
            </select>
        </p>
        <?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['caption'] = strip_tags($new_instance['caption']);
        $instance['image_src'] = $new_instance['image_src'];
        $instance['image_url'] = $new_instance['image_url'];
        $instance['style'] = $new_instance['style'];

        return $instance;
    }
}

/**
 * Kopa Gallery Widget Class
 * @since FastNews 1.0
 */
class Kopa_Widget_Gallery extends WP_Widget {
    function __construct() {
        $widget_ops = array('classname' => 'kopa-gallery-widget', 'description' => __('Display a slider of all images in one gallery format post', kopa_get_domain()));
        $control_ops = array('width' => 'auto', 'height' => 'auto');
        parent::__construct('kopa_widget_gallery', __('Kopa Gallery', kopa_get_domain()), $widget_ops, $control_ops);
    }

    function widget( $args, $instance ) {
        extract( $args );
        $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
        $post_id = $instance['post_id'];

        echo $before_widget;

        if ( ! empty( $title ) ) {
            echo $before_title . $title . $after_title;
        }

        $gallery_post = get_post( $post_id );

        $gallery_ids = kopa_content_get_gallery_attachment_ids( $gallery_post->post_content );

        if ( ! empty( $gallery_ids ) ) { 
        ?>
        
        <div class="gallery-slider flexslider">
            <ul class="slides">        
            
                <?php foreach ( $gallery_ids as $id ) { ?> 
                    <?php if ( wp_attachment_is_image( $id )  ) { 
                        $full_attachment_src = wp_get_attachment_image_src( $id, 'full' );
                        ?>

                        <li>
                            <a rel="prettyPhoto[<?php echo $this->get_field_id( 'gallery' ) . $gallery_post->ID; ?>]" href="<?php echo $full_attachment_src[0]; ?>"><?php echo wp_get_attachment_image( $id, 'large' ); ?></a>
                            <h4 class="text-center"><a href="<?php echo get_permalink( $gallery_post->ID ); ?>"><?php echo $gallery_post->post_title; ?></a></h4>
                        </li>
                        
                    <?php } // endif ?>
                <?php }  // endforeach ?>
            </ul>
        </div>

        <?php
        } // endif 
        echo $after_widget;
    }

    function form($instance) {
        $defaults = array(
            'title'        => '',
            'post_id'      => null,
        );
        $instance = wp_parse_args( (array) $instance, $defaults );
        $title = strip_tags( $instance['title'] );
        $form['post_id'] = $instance['post_id'];
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('post_id'); ?>"><?php _e('Select one gallery post:', kopa_get_domain()); ?></label>
            <select class="widefat" id="<?php echo $this->get_field_id( 'post_id' ); ?>" name="<?php echo $this->get_field_name( 'post_id' ); ?>">
                <?php 
                $gallery_posts = new WP_Query( array(
                    'tax_query' => array(
                        array(
                          'taxonomy' => 'post_format',
                          'field' => 'slug',
                          'terms' => 'post-format-gallery'
                        )
                    )
                ) );

                if ( $gallery_posts->have_posts() ) {
                    while ( $gallery_posts->have_posts() ) {
                        $gallery_posts->the_post();
                        ?>

                        <option value="<?php the_ID(); ?>" <?php selected( get_the_ID(), $form['post_id'] ); ?>><?php the_title(); ?></option>

                        <?php
                    }
                }

                wp_reset_postdata();
                ?>
            </select>
        </p>
        <?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['post_id'] = $new_instance['post_id'];

        return $instance;
    }
}

/**
 * Kopa Video Posts Slider Widget Class
 * @since FastNews 1.0
 */
class Kopa_Widget_Videos_Slider extends WP_Widget {
    function __construct() {
        $widget_ops = array('classname' => 'kopa-videos-slider-widget', 'description' => __('Display a slider of video post format posts', kopa_get_domain()));
        $control_ops = array('width' => 'auto', 'height' => 'auto');
        parent::__construct('kopa_widget_videos_slider', __('Kopa Video Posts Slider', kopa_get_domain()), $widget_ops, $control_ops);
    }

    function widget( $args, $instance ) {
        extract( $args );
        $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
        $query_args['categories'] = $instance['categories'];
        $query_args['orderby'] = $instance['orderby'];

        echo $before_widget;

        if ( ! empty( $title ) ) {
            echo $before_title . $title . $after_title;
        }

        // build query arguments array

        $video_args['posts_per_page'] = $instance['posts_per_page'];

        switch ( $query_args['orderby'] ) {
            case 'popular':
                $video_args['meta_key'] = 'kopa_' . kopa_get_domain() . '_total_view';
                $video_args['orderby'] = 'meta_value_num';
                break;
            case 'most_comment':
                $video_args['orderby'] = 'comment_count';
                break;
            case 'random':
                $video_args['orderby'] = 'rand';
                break;
            default:
                $video_args['orderby'] = 'date';
                break;
        }   

        $tax_query[] = array(
            'taxonomy' => 'post_format',
            'field'    => 'slug',
            'terms'    => array( 'post-format-video' )
        );

        if ( ! empty( $query_args['categories'] ) ) {
            $tax_query[] = array(
                'taxonomy' => 'category',
                'field' => 'id',
                'terms' => $query_args['categories']
            );
        }

        if ( count( $tax_query ) >= 2 )
            $tax_query['relation'] = 'AND';

        $video_args['tax_query'] = $tax_query;

        $video_posts = new WP_Query( $video_args );

        if ( $video_posts->have_posts() ) { ?>
            <div class="flexslider kp-gallery-slider">
                <ul class="slides">
                    <?php while ( $video_posts->have_posts() ) {
                        $video_posts->the_post(); 

                        $video = kopa_content_get_video( get_the_content() );

                        if ( isset( $video[0] ) ) {
                            $video = $video[0];
                        } else {
                            $video = '';
                        }

                        if ( isset( $video['url'] ) ) {
                    ?>
                        <li>
                            <div class="mask">
                                <a class="play-icon" href="<?php echo esc_url( $video['url'] ); ?>" rel="prettyPhoto[<?php echo $this->get_field_id( 'video' ); ?>]"></a>
                                <?php if ( has_post_thumbnail() ) {
                                    echo wp_get_attachment_image( get_post_thumbnail_id(), 'gallery-image-size' );
                                } elseif ( isset( $video['type'] ) && isset( $video['url'] ) ) {
                                    $thumbnail_image_src = kopa_get_video_thumbnails_url( $video['type'], $video['url'] );
                                    echo '<img src="'.$thumbnail_image_src.'" alt="'.get_the_title().'">';
                                } ?>
                            </div>
                            <?php if ( get_the_excerpt() ) { ?>
                                <div class="kp-gallery-caption"><?php the_excerpt(); ?></div>
                            <?php } ?>
                        </li>
                    <?php
                        } // endif
                    } // endwhile ?>
                </ul>
            </div>
            <div class="flexslider kp-gallery-carousel">
                <ul class="slides">
                    <?php while ( $video_posts->have_posts() ) { 
                        $video_posts->the_post(); 
                        
                        $video = kopa_content_get_video( get_the_content() );

                        if ( isset( $video[0] ) ) {
                            $video = $video[0];
                        } else {
                            $video = '';
                        }

                        if ( isset( $video['url'] ) ) {
                    ?>
                        <li>
                            <div class="mask"><span class="play-icon"></span>
                                <?php if ( has_post_thumbnail() ) {
                                    echo wp_get_attachment_image( get_post_thumbnail_id(), 'gallery-image-size' );
                                } elseif ( isset( $video['type'] ) && isset( $video['url'] ) ) {
                                    $thumbnail_image_src = kopa_get_video_thumbnails_url( $video['type'], $video['url'] );
                                    echo '<img src="'.$thumbnail_image_src.'" alt="'.get_the_title().'">';
                                } ?>
                            </div>
                        </li>
                    <?php
                        } // endif
                    } // endwhile ?>
                </ul>
            </div><!--kp-gallery-slider-->
        <?php
        } // endif $video_posts->have_posts()

        wp_reset_postdata();
        
        echo $after_widget;
    }

    function form($instance) {
        $defaults = array(
            'title'          => '',
            'categories'     => array(),
            'posts_per_page' => 8,
            'orderby'        => 'latest',
        );
        $instance = wp_parse_args( (array) $instance, $defaults );
        $title = strip_tags( $instance['title'] );

        $form['categories'] = $instance['categories'];
        $form['posts_per_page'] = $instance['posts_per_page'];
        $form['orderby'] = $instance['orderby'];
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('categories'); ?>"><?php _e('Categories:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('categories'); ?>" name="<?php echo $this->get_field_name('categories'); ?>[]" multiple="multiple" size="5" autocomplete="off">
                <option value=""><?php _e('-- None --', kopa_get_domain()); ?></option>
                <?php
                $categories = get_categories();
                foreach ($categories as $category) {
                    printf('<option value="%1$s" %4$s>%2$s (%3$s)</option>', $category->term_id, $category->name, $category->count, (in_array($category->term_id, $form['categories'])) ? 'selected="selected"' : '');
                }
                ?>
            </select>

        </p>
        <p>
            <label for="<?php echo $this->get_field_id('posts_per_page'); ?>"><?php _e('Number of posts:', kopa_get_domain()); ?></label>
            <input class="widefat" type="number" id="<?php echo $this->get_field_id('posts_per_page'); ?>" name="<?php echo $this->get_field_name('posts_per_page'); ?>" value="<?php echo esc_attr( $form['posts_per_page'] ); ?>">  
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e('Orderby:', kopa_get_domain()); ?></label>                
            <select class="widefat" id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>" autocomplete="off">
                <?php
                $orderby = array(
                    'latest' => __('Latest', kopa_get_domain()),
                    'popular' => __('Popular by View Count', kopa_get_domain()),
                    'most_comment' => __('Popular by Comment Count', kopa_get_domain()),
                    'random' => __('Random', kopa_get_domain()),
                );
                foreach ($orderby as $value => $title) {
                    printf('<option value="%1$s" %3$s>%2$s</option>', $value, $title, ($value === $form['orderby']) ? 'selected="selected"' : '');
                }
                ?>
            </select>
        </p>
        <?php
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['categories'] = (empty($new_instance['categories'])) ? array() : array_filter($new_instance['categories']);
        
        $instance['posts_per_page'] = (int) $new_instance['posts_per_page'];
        if ( $instance['posts_per_page'] <= 0 ) {
            $instance['posts_per_page'] = 8;
        }

        $instance['orderby'] = $new_instance['orderby'];

        return $instance;
    }
}

/**
 * Mailchimp Subscribe Widget Class
 * @since Forceful 1.0
 */
class Kopa_Widget_Mailchimp_Subscribe extends WP_Widget {
    function __construct() {
        $widget_ops = array('classname' => 'kopa-newsletter-widget', 'description' => __('Display mailchimp newsletter subscription form', kopa_get_domain()));
        $control_ops = array('width' => '400', 'height' => 'auto');
        parent::__construct('kopa_widget_mailchimp_subscribe', __('Kopa Mailchimp Subscribe', kopa_get_domain()), $widget_ops, $control_ops);
    }

    function widget( $args, $instance ) {
        extract( $args );
        $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
        $mailchimp_form_action = $instance['mailchimp_form_action'];
        $mailchimp_enable_popup = $instance['mailchimp_enable_popup'];
        $description = $instance['description'];

        echo $before_widget;

        if ( ! empty( $title ) ) {
            echo $before_title . $title . $after_title;
        }

        if ( ! empty( $mailchimp_form_action ) ) :

        ?>

        <form action="<?php echo esc_url( $mailchimp_form_action ); ?>" method="post" class="newsletter-form clearfix" <?php echo $mailchimp_enable_popup ? 'target="_blank"' : ''; ?>>
            <p class="input-email clearfix">
                <input type="text" onfocus="if(this.value==this.defaultValue)this.value='';" onblur="if(this.value=='')this.value=this.defaultValue;" name="EMAIL" value="<?php _e( 'Subscribe to newsletter...', kopa_get_domain() ); ?>" class="email" size="40">
                <input type="submit" value="<?php _e( 'Subscribe', kopa_get_domain() ); ?>" class="submit">
            </p>
        </form>
        <p><?php echo $description; ?></p>

        <?php
        endif;
        
        echo $after_widget;
    }

    function form( $instance ) {
        $defaults = array(
            'title'                  => '',
            'mailchimp_form_action'  => '',
            'mailchimp_enable_popup' => false,
            'description'            => ''
        );
        $instance = wp_parse_args( (array) $instance, $defaults );
        $title = strip_tags( $instance['title'] );
        $form['mailchimp_form_action'] = $instance['mailchimp_form_action'];
        $form['mailchimp_enable_popup'] = $instance['mailchimp_enable_popup'];
        $form['description'] = $instance['description'];
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('mailchimp_form_action'); ?>"><?php _e('Mailchimp Form Action:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('mailchimp_form_action'); ?>" name="<?php echo $this->get_field_name('mailchimp_form_action'); ?>" type="text" value="<?php echo esc_attr($form['mailchimp_form_action']); ?>">
        </p>
        <p>
            <input type="checkbox" value="true" id="<?php echo $this->get_field_id( 'mailchimp_enable_popup' ); ?>" name="<?php echo $this->get_field_name( 'mailchimp_enable_popup' ); ?>" <?php checked( true, $form['mailchimp_enable_popup'] ); ?>>
            <label for="<?php echo $this->get_field_id( 'mailchimp_enable_popup' ); ?>"><?php _e( 'Enable <strong>evil</strong> popup mode', kopa_get_domain() ); ?></label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'description' ); ?>"><?php _e( 'Description', kopa_get_domain() ); ?></label>
            <textarea class="widefat" name="<?php echo $this->get_field_name('description') ?>" id="<?php echo $this->get_field_id('description') ?>" rows="5"><?php echo esc_textarea( $form['description'] ); ?></textarea>
        </p>
        <?php
    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['mailchimp_form_action'] = $new_instance['mailchimp_form_action'];
        $instance['mailchimp_enable_popup'] = (bool) $new_instance['mailchimp_enable_popup'] ? true : false;
        $instance['description'] = strip_tags( $new_instance['description'] );

        return $instance;
    }
}

/**
 * FeedBurner Subscribe Widget Class
 * @since Forceful 1.0
 */
class Kopa_Widget_Feedburner_Subscribe extends WP_Widget {
    function __construct() {
        $widget_ops = array('classname' => 'kopa-newsletter-widget', 'description' => __('Display Feedburner subscription form', kopa_get_domain()));
        $control_ops = array('width' => '400', 'height' => 'auto');
        parent::__construct('kopa_widget_feedburner_subscribe', __('Kopa Feedburner Subscribe', kopa_get_domain()), $widget_ops, $control_ops);
    }

    function widget( $args, $instance ) {
        extract( $args );
        $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
        $feedburner_id = $instance['feedburner_id'];
        $description = $instance['description'];

        echo $before_widget;

        if ( ! empty( $title ) ) {
            echo $before_title . $title . $after_title;
        }

        if ( ! empty( $feedburner_id ) ) {

        ?>

        <form action="http://feedburner.google.com/fb/a/mailverify" method="post" class="newsletter-form clearfix" target="popupwindow" onsubmit="window.open('http://feedburner.google.com/fb/a/mailverify?uri=<?php echo esc_attr( $feedburner_id ); ?>', 'popupwindow', 'scrollbars=yes,width=550,height=520');return true">

            <input type="hidden" value="<?php echo esc_attr( $feedburner_id ); ?>" name="uri">

            <p class="input-email clearfix">
                <input type="text" onfocus="if(this.value==this.defaultValue)this.value='';" onblur="if(this.value=='')this.value=this.defaultValue;" name="email" value="<?php _e( 'Subscribe to newsletter...', kopa_get_domain() ); ?>" class="email" size="40">
                <input type="submit" value="<?php _e( 'Subscribe', kopa_get_domain() ); ?>" class="submit">
            </p>
        </form>

        <p><?php echo $description; ?></p>

        <?php
        } // endif
        
        echo $after_widget;
    }

    function form( $instance ) {
        $defaults = array(
            'title'         => '',
            'feedburner_id' => '',
            'description'   => ''
        );
        $instance = wp_parse_args( (array) $instance, $defaults );
        $title = strip_tags( $instance['title'] );
        $form['feedburner_id'] = $instance['feedburner_id'];
        $form['description'] = $instance['description'];
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('feedburner_id'); ?>"><?php _e('Feedburner ID (http://feeds.feedburner.com/<strong>wordpress/kopatheme</strong>):', kopa_get_domain()); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('feedburner_id'); ?>" name="<?php echo $this->get_field_name('feedburner_id'); ?>" type="text" value="<?php echo esc_attr($form['feedburner_id']); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'description' ); ?>"><?php _e( 'Description', kopa_get_domain() ); ?></label>
            <textarea class="widefat" name="<?php echo $this->get_field_name('description') ?>" id="<?php echo $this->get_field_id('description') ?>" rows="5"><?php echo esc_textarea( $form['description'] ); ?></textarea>
        </p>
        <?php
    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['feedburner_id'] = strip_tags( $new_instance['feedburner_id'] );
        $instance['description'] = strip_tags( $new_instance['description'] );

        return $instance;
    }
}