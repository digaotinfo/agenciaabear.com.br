<?php
/*
Plugin Name: Kopa Shortcodes
Plugin URI: http://kopatheme.com
Description: A plugin to generate shortcodes in the WordPress visual editor. Specific shortcodes plugin uses in FastNews theme.
Version: 1.0
Author: Kopatheme
Author URI: http://kopatheme.com
License: GPLv2 or later
Note: Specific plugin uses in FastNews theme
*/

add_action('admin_head', 'kopa_shortcodes_globals_js');
function kopa_shortcodes_globals_js(){
    ?>
    <script type="text/javascript">
        var kopa_shortcodes_globals = {
            "pluginUrl": "<?php echo plugins_url( '' , __FILE__ ); ?>"
        };
    </script>
    <?php
}


function kopa_plugin_init() {
    load_plugin_textdomain( 'kopa-shortcodes', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' ); 
}
add_action('plugins_loaded', 'kopa_plugin_init');


/**
 * Shortcodes Definition
 * @since FastNews 1.0
 */

remove_shortcode('gallery', 'gallery_shortcode');
add_shortcode('gallery', 'kopa_gallery_shortcode');

function kopa_gallery_shortcode($atts, $content = null) {
    extract(shortcode_atts(array("ids" => '', "display_type" => 0), $atts));
    $output = '';

    if ( isset( $atts['ids'] ) ) {
        $ids = explode(',', $atts['ids']);
        if ($ids) { 
            ob_start();
            ?>
            
            <div class="flexslider kp-gallery-slider">
                <ul class="slides">
                    <?php foreach ( $ids as $id ) { 
                        $full_attachment_src = wp_get_attachment_image_src( $id, 'full' );
                        ?>
                    <li>
                        <div class="mask">
                            <a href="<?php echo $full_attachment_src[0]; ?>" rel="prettyPhoto[kp-gallery]"><?php echo wp_get_attachment_image( $id, 'gallery-image-size' ); ?></a>
                        </div>
                        <?php if ( get_post_field('post_excerpt', $id) ) { ?>
                            <div class="kp-gallery-caption"><?php echo get_post_field('post_excerpt', $id); ?></div>
                        <?php } ?>
                    </li>
                    <?php } ?>
                </ul>
            </div>
            <div class="flexslider kp-gallery-carousel">
                <ul class="slides">
                    <?php foreach ( $ids as $id ) { ?>
                        <li><?php echo wp_get_attachment_image( $id, 'gallery-image-size' ); ?></li>
                    <?php } ?>
                </ul>
            </div><!--kp-gallery-slider-->

        <?php }
    }

    $output = ob_get_clean();
    return $output;
}

/* SHORTCODE : ONE_HALF */

add_shortcode('one_half', 'kopa_shortcode_one_half');

function kopa_shortcode_one_half($atts, $content = null) {
    $atts = shortcode_atts(
            array(
        'last' => 'no',
            ), $atts);

    if ($atts['last'] == 'yes') {
        return '<div class="kopa-one-two last">' . do_shortcode($content) . '</div><div class="clear"></div>';
    } else {
        return '<div class="kopa-one-two">' . do_shortcode($content) . '</div>';
    }
}

/* SHORTCODE : ONE_THIRD */

add_shortcode('one_third', 'kopa_shortcode_one_third');

function kopa_shortcode_one_third($atts, $content = null) {
    $atts = shortcode_atts(
            array(
        'last' => 'no',
            ), $atts);

    if ($atts['last'] == 'yes') {
        return '<div class="kopa-one-third last">' . do_shortcode($content) . '</div><div class="clear"></div>';
    } else {
        return '<div class="kopa-one-third">' . do_shortcode($content) . '</div>';
    }
}

add_shortcode('two_third', 'kopa_shortcode_two_third');

function kopa_shortcode_two_third($atts, $content = null) {
    $atts = shortcode_atts(
            array(
        'last' => 'no',
            ), $atts);

    if ($atts['last'] == 'yes') {
        return '<div class="kopa-two-third last">' . do_shortcode($content) . '</div><div class="clear"></div>';
    } else {
        return '<div class="kopa-two-third">' . do_shortcode($content) . '</div>';
    }
}

/* SHORTCODE : ONE_FOURTH */

add_shortcode('one_fourth', 'kopa_shortcode_one_fourth');

function kopa_shortcode_one_fourth($atts, $content = null) {
    $atts = shortcode_atts(
            array(
        'last' => 'no',
            ), $atts);

    if ($atts['last'] == 'yes') {
        return '<div class="kopa-one-fourth last">' . do_shortcode($content) . '</div><div class="clear"></div>';
    } else {
        return '<div class="kopa-one-fourth">' . do_shortcode($content) . '</div>';
    }
}

/* SHORTCODE : THREE_FOURTH */

add_shortcode('three_fourth', 'kopa_shortcode_three_fourth');

function kopa_shortcode_three_fourth($atts, $content = null) {
    $atts = shortcode_atts(
            array(
        'last' => 'no',
            ), $atts);

    if ($atts['last'] == 'yes') {
        return '<div class="kopa-three-fourth last">' . do_shortcode($content) . '</div><div class="clear"></div>';
    } else {
        return '<div class="kopa-three-fourth">' . do_shortcode($content) . '</div>';
    }
}

/* SHORTCODE : TABS */
/**
 * @since Forceful 1.0
 */

add_shortcode('tabs', 'kopa_shortcode_tabs');

function kopa_shortcode_tabs($atts, $content = null) {
    extract(shortcode_atts(array(), $atts));

    $out = '<div class="list-container-3">';
    $out .= '<ul class="tabs-3 clearfix">';
    foreach ($atts as $key => $tab) {
        $out .= '<li><a href="#' . $key . '">' . $tab . '</a></li>';
    }
    $out .= '</ul>';
    $out .= '</div>';

    $out .= '<div class="tab-container-3">';
    $out .= do_shortcode($content);
    $out .= '</div>';

    return $out;
}

/* SHORTCODE : TAB */
/**
 * @since Forceful 1.0
 */
add_shortcode('tab', 'kopa_shortcode_tab');

function kopa_shortcode_tab($atts, $content = null) {
    extract(shortcode_atts(array(), $atts));
    return '<div id="' . $atts['id'] . '" class="tab-content-3" style="display:none;">' . do_shortcode($content) . '</div>';
}


/* SHORTCODE : ACCORDIONS */

add_shortcode('accordions', 'kopa_shortcode_accordions');

function kopa_shortcode_accordions($atts, $content = null) {
    extract(shortcode_atts(array(), $atts));
    return '<div class="acc-wrapper">' . do_shortcode($content) . '</div>';
}

/* SHORTCODE : ACCORDION */

add_shortcode('accordion', 'kopa_shortcode_accordion');

function kopa_shortcode_accordion($atts, $content = null) {
    extract(shortcode_atts(array(
        'title' => 'Accordion Title'
                    ), $atts));
    return '<div class="accordion-title">
                <h3><a href="#">' . $atts['title'] . '</a></h3>
                    <span>+</span>
            </div>
            <div class="accordion-container" style="display:none;">' . do_shortcode($content) . '</div>';
}

/* SHORTCODE : TOGGLE */


add_shortcode('toggles', 'kopa_shortcode_toggles');

function kopa_shortcode_toggles($atts = array(), $content = NULL) {
    extract(shortcode_atts(array(), $atts));

    $out = '<ul id="toggle-view">';
    $out.= do_shortcode($content);
    $out.= '</ul>';

    return apply_filters('kopa_shortcode_toggles', $out);
}

add_shortcode('toggle', 'kopa_shortcode_toggle');

function kopa_shortcode_toggle($atts = array(), $content = NULL) {
    extract(shortcode_atts(array('title' => ''), $atts));

    $out = '<li class="clearfix">';
    $out.='<span>+</span>';
    $out.='<h3>' . $atts['title'] . '</h3>';
    $out.='<div class="clear"></div>';
    $out.='<div class="panel clearfix">';
    $out.= '<p>'.do_shortcode($content).'</p>';
    $out.='</div>';
    $out.='</li>';

    return apply_filters('kopa_shortcode_toggle', $out);
}

/* SHORTCODE : DROPCAPS */

add_shortcode('dropcaps', 'kopa_shortcode_dropcaps');

function kopa_shortcode_dropcaps($atts, $content = null) {
    $atts = shortcode_atts(array('round' => 'no'), $atts);
    return '<span class="kp-dropcap '.($atts['round'] === 'yes' ? 'color' : '').'">' . do_shortcode($content) . '</span>';
}

/** 
 * SHORTCODE : BUTTON 
 * @since gammar 1.0   
 */

add_shortcode('button', 'kopa_shortcode_button');

function kopa_shortcode_button($atts, $content = null) {
    $atts = shortcode_atts( array(
        'style' => 'solid',
        'size' => 'small',
        'link' => '',
        'target' => '_self',
        ), $atts);

    if ( ! in_array( $atts['style'], array( 'solid', 'border' ) ) ) {
        $atts['style'] = 'solid';
    }

    if ( $atts['style'] == 'solid' ) {
        $atts['style'] = 'navy';
    }

    if (!in_array($atts['size'], array('small', 'medium', 'big'))) {
        $atts['size'] = 'small';
    }
    $out = sprintf('<a href="%1$s" class="kopa-button %2$s-button %3$s-button" target="%4$s">%5$s</a>', $atts['link'], $atts['style'], $atts['size'], $atts['target'], do_shortcode($content));
    return apply_filters('kopa_shortcode_button', $out);
}

/**
 * SHORTCODE : ALERT 
 * @package FastNews
 * @since FastNews 1.0
 */

add_shortcode('alert', 'kopa_shortcode_alert');

function kopa_shortcode_alert($atts, $content = null) {
    $atts = shortcode_atts(
            array(
        'type' => 'info',
        'title' => ''
            ), $atts);

    $class = '';

    if (!in_array($atts['type'], array('warning', 'danger', 'success', 'info'))) {
        $atts['type'] = 'info';
    }

    $out = "<div class='alert alert-{$atts['type']}'>";
    $out .= "<h6>{$atts['title']}</h6>";
    $out .= '<p>' . do_shortcode($content) . '</p>';
    $out .= "</div >";

    return $out;
}

/** 
 * SHORTCODE : CONTACT 
 * @since Gammar 1.0
 */
add_shortcode('contact_form', 'kopa_shortcode_contact_form');

function kopa_shortcode_contact_form($atts, $content = null) {
    $atts = shortcode_atts( array(
        'caption' => ''
        ), $atts );

    $out = '<div id="contact-box">';

    if ($atts['caption']) {
        $out .= "<h3><span class='title-line'></span><span class='title-text'>{$atts['caption']}</span></h3>";
    }

    $out .= '<form id="contact-form" class ="clearfix" action="' . admin_url('admin-ajax.php') . '" method="post">';

    $out .= '<div class="contact-left">';
    $out .= '<p class="input-block clearfix">';
    $out .= '<label class="required" for="contact_name">' . __('Name <span>(required):</span>', 'kopa-shortcodes') . '</label>';
    $out .= '<input class="valid" type="text" name="name" id="contact_name" value="">';
    $out .= '</p>';

    $out .= '<p class="input-block clearfix">';
    $out .= '<label class="required" for="contact_email">' . __('Email <span>(required):</span>', 'kopa-shortcodes') . '</label>';
    $out .= '<input type="email" class="valid" name="email" id="contact_email" value="">';
    $out .= '</p>';

    $out .= '<p class="input-block clearfix">';
    $out .= '<label class="required" for="contact_url">' . __('Website:', 'kopa-shortcodes') . '</label>';
    $out .= '<input type="url" class="valid" name="url" id="contact_url" value="">';
    $out .= '</p>';
    $out .= '</div>';

    $out .= '<div class="contact-right">';
    $out .= '<p class="textarea-block">';
    $out .= '<label class="required" for="contact_message">' . __('Message <span>(required):</span>', 'kopa-shortcodes') . '</label>';
    $out .= '<textarea id="contact_message" name="message"></textarea>';
    $out .= '</p>';
    $out .= '</div>';

    $out .= '<div class="clear"></div>';
    $out .= '<p class="comment-button clearfix">';
    $out .= '<input type="submit" id="submit-contact" value="' . __('Submit', 'kopa-shortcodes') . '">';
    $out .= '</p>';

    $out .= '<input type="hidden" name="action" value="kopa_send_contact">';
    $out .= wp_nonce_field('kopa_send_contact_nicole_kidman', 'kopa_send_contact_nonce', true, false);

    $out .= '</form>';

    $out .= '<div id="response"></div>';

    $out.= '</div><!--contact-box-->';

    return $out;
}

/* SHORTCODE : POST */
add_shortcode('posts', 'kopa_shortcode_posts');

function kopa_shortcode_posts($atts, $content = null) {
    $atts = shortcode_atts(array(
        'title'      => __( 'Latest Posts', 'kopa-shortcodes' ),
        'cats'       => '',
        'tags'       => '',
        'relation'   => 'OR',
        'count'      => 10,
        'orderby'    => 'lastest', //lastest, popular, most_like, most_comment, random
        'max_length' => 0
            ), $atts);

    $args = array(
        'post_type' => array('post'),
        'posts_per_page' => (int) $atts['count'],
        'ignore_sticky_posts' => 1,
    );

    $tax_query = array();
    if ($atts['cats']) {
        $tax_query[] = array(
            'taxonomy' => 'category',
            'field' => 'id',
            'terms' => explode(', ', $atts['cats'])
        );
    }
    if ($atts['tags']) {
        $tax_query[] = array(
            'taxonomy' => 'post_tag',
            'field' => 'id',
            'terms' => explode(', ', $atts['tags'])
        );
    }
    if ($atts['relation'] && count($tax_query) == 2) {
        $tax_query[] = ('OR' == $atts['relation']) ? 'OR' : 'AND';
    }

    if ($tax_query) {
        $args['tax_query'] = $tax_query;
    }

    switch ($atts['orderby']) {
        case 'popular':
            $domain = wp_get_theme();
            $domain = strtolower( $domain->name );
            $domain = str_replace(' ', '', $domain);
            $args['meta_key'] = 'kopa_' . $domain . '_total_view';
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

    $posts = new WP_Query($args);

    $out = '';
    $max_length = ($atts['max_length']) ? (int) $atts['max_length'] : 0;

    ob_start();

    if ( $posts->have_posts() ) { 
        $post_index = 1;
        ?>
        <div class="widget kopa-video-widget clearfix">
            <?php if ( ! empty( $atts['title'] ) ) { ?>
                <h3 class="widget-title"><?php echo $atts['title']; ?></h3>
            <?php } ?>
            
            <ul class="clearfix">
            <?php while ( $posts->have_posts() ) {
                $posts->the_post();

                $terms = get_the_terms( get_the_ID(), 'category' );
                $first_term = array_shift( $terms );
                ?>
            <li>
                <article class="entry-item clearfix">
                <?php if ( has_post_thumbnail() ) { ?>
                    <div class="entry-thumb">
                        <?php the_post_thumbnail( 'large' ); ?>
                        <div class="mask"><a href="<?php the_permalink(); ?>" data-icon="&#xe125;"></a></div>
                    </div>
                <?php } // endif ?>
                    <div class="entry-content">
                        <header class="clearfix">
                            <h5 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
                            <span class="entry-date">&mdash; <?php the_time( get_option( 'date_format' ) ); ?></span>
                            <span class="entry-categories"><?php _e( 'in:', 'kopa-shortcodes' ); ?> <a href="<?php echo get_term_link( $first_term ); ?>"><?php echo $first_term->name; ?></a></span>
                            <?php if ( comments_open() ) { ?>
                            <span class="entry-comments"><?php comments_popup_link( '0', '1', '%' ); ?><span></span></span>
                            <?php } ?>
                        </header>
                        <?php the_excerpt(); ?>
                    </div>
                    <!-- entry-content -->
                </article>
            </li>
                <?php 
                if ( $post_index % 4 == 0 ) {
                    echo '<div class="clear"></div>';
                } 
                $post_index++;
                ?>
            <?php } // endwhile ?>
            </ul><!--kopa-featured-news-carousel-->
                
        </div><!--kopa-video-widget-->
    <?php } // endif

    wp_reset_postdata();

    $out = ob_get_clean();

    return $out;
}

add_shortcode('youtube', 'kopa_shortcode_youtube');

function kopa_shortcode_youtube($atts, $content = null) {
    $atts = shortcode_atts(array(), $atts);
    $out = '';
    if ($content) {
        $matches = array();
        preg_match('#(\.be/|/embed/|/v/|/watch\?v=)([A-Za-z0-9_-]{5,11})#', $content, $matches);
        if (isset($matches[2]) && $matches[2] != '') {
            $out .= '<div class="video-wrapper"><iframe src="http://www.youtube.com/embed/' . $matches[2] . '" width="560" height="315" frameborder="0" allowfullscreen></iframe></div>';
        }
    }

    return $out;
}

add_shortcode('vimeo', 'kopa_shortcode_vimeo');

function kopa_shortcode_vimeo($atts, $content = null) {
    $atts = shortcode_atts(array(), $atts);
    $out = '';
    if ($content) {
        $matches = array();
        preg_match('/(\d+)/', $content, $matches);
        if (isset($matches[0]) && $matches[0] != '') {
            $out .= '<div class="video-wrapper"><iframe src="http://player.vimeo.com/video/' . $matches[0] . '" width="560" height="315" frameborder="0" allowfullscreen></iframe></div>';
        }
    }
    return $out;
}

add_shortcode('google_map', 'kopa_shortcode_google_map');

function kopa_shortcode_google_map($atts, $content = null) {
    $atts = shortcode_atts(array(
        'caption' => ''
            ), $atts);

    $out = '<div class="kp-map">';
    if ($atts['caption'])
        $out .= '<h4 class="widget-title">' . $atts['caption'] . '</h4>';
    $out .= $content . '</div>';

    return $out;
}

add_shortcode('audio', 'kopa_shortcode_audio');

function kopa_shortcode_audio($atts, $content = null) {
    $atts = shortcode_atts(array(
        'wav_url' => '',
        'mp3_url' => '',
    ), $atts);
    $out = '';

    if ( ! empty( $atts['wav_url'] ) || ! empty( $atts['mp3_url'] ) ) {
        $out = '<audio controls>
              <source src="'.$atts['mp3_url'].'" type="audio/mpeg">  
              <source src="'.$atts['wav_url'].'" type="audio/wav">'.
              __('Your browser does not support the audio tag.', kopa_get_domain()) .
            '</audio>';
    } elseif ($content) {
        $out = '<audio controls>
            <source src="' . $content . '" type="audio/mpeg"></audio>';
    }
    return $out;
}

add_shortcode('soundcloud', 'kopa_shortcode_soundcloud');

function kopa_shortcode_soundcloud($atts, $content = null) {
    $atts = shortcode_atts(array(), $atts);
    $out = '';

    if ($content) {
        $out = '<iframe width="100%" height="166" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url='.esc_attr( urlencode( $content ) ).'"></iframe>';
    }
    return $out;
}

add_action('init', 'kopa_shortcode_add_button');

function kopa_shortcode_add_button() {
    if (current_user_can('edit_posts') && current_user_can('edit_pages')) {
        add_filter('mce_external_plugins', 'kopa_add_plugin');
        add_filter('mce_buttons_3', 'kopa_register_button');
    }
}

function kopa_add_plugin($plugin_array) {
    $plugin_array['one_half'] = plugins_url( 'js/shortcodes', __FILE__ ) . '/one_half.js';
    $plugin_array['one_third'] = plugins_url( 'js/shortcodes', __FILE__ ) . '/one_third.js';
    $plugin_array['two_third'] = plugins_url( 'js/shortcodes', __FILE__ ) . '/two_third.js';
    $plugin_array['one_fourth'] = plugins_url( 'js/shortcodes', __FILE__ ) . '/one_fourth.js';
    $plugin_array['three_fourth'] = plugins_url( 'js/shortcodes', __FILE__ ) . '/three_fourth.js';
    $plugin_array['tabs'] = plugins_url( 'js/shortcodes', __FILE__ ) . '/tabs.js';
    $plugin_array['accordions'] = plugins_url( 'js/shortcodes', __FILE__ ) . '/accordions.js';
    $plugin_array['toggle'] = plugins_url( 'js/shortcodes', __FILE__ ) . '/toggle.js';
    $plugin_array['dropcaps'] = plugins_url( 'js/shortcodes', __FILE__ ) . '/dropcaps.js';
    $plugin_array['button'] = plugins_url( 'js/shortcodes', __FILE__ ) . '/button.js';
    $plugin_array['alert'] = plugins_url( 'js/shortcodes', __FILE__ ) . '/alert.js';
    $plugin_array['posts_lastest'] = plugins_url( 'js/shortcodes', __FILE__ ) . '/posts_lastest.js';
    $plugin_array['posts_popular'] = plugins_url( 'js/shortcodes', __FILE__ ) . '/posts_popular.js';
    $plugin_array['posts_most_comment'] = plugins_url( 'js/shortcodes', __FILE__ ) . '/posts_most_comment.js';
    $plugin_array['posts_random'] = plugins_url( 'js/shortcodes', __FILE__ ) . '/posts_random.js';
    $plugin_array['contact_form'] = plugins_url( 'js/shortcodes', __FILE__ ) . '/contact_form.js';
    $plugin_array['google_map'] = plugins_url( 'js/shortcodes', __FILE__ ) . '/google_map.js';
    $plugin_array['youtube'] = plugins_url( 'js/shortcodes', __FILE__ ) . '/youtube.js';
    $plugin_array['vimeo'] = plugins_url( 'js/shortcodes', __FILE__ ) . '/vimeo.js';
    $plugin_array['audio'] = plugins_url( 'js/shortcodes', __FILE__ ) . '/audio.js';

    return $plugin_array;
}

function kopa_register_button($buttons) {
    array_push($buttons, 'one_half');
    array_push($buttons, 'one_third');
    array_push($buttons, 'two_third');
    array_push($buttons, 'one_fourth');
    array_push($buttons, 'three_fourth');
    array_push($buttons, 'dropcaps');
    array_push($buttons, 'button');
    array_push($buttons, 'alert');
    array_push($buttons, 'tabs');
    array_push($buttons, 'accordions');
    array_push($buttons, 'toggle');
    array_push($buttons, 'posts_lastest');
    array_push($buttons, 'posts_popular');
    array_push($buttons, 'posts_most_comment');
    array_push($buttons, 'posts_random');
    array_push($buttons, 'contact_form');
    array_push($buttons, 'google_map');
    array_push($buttons, 'youtube');
    array_push($buttons, 'vimeo');
    array_push($buttons, 'audio');

    return $buttons;
}