<?php 
$audio = kopa_content_get_audio( get_the_content() );
if ( isset( $audio[0] ) ) {
    $audio = $audio[0];
} else {
    $audio = '';
}
?>
<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        
    <?php if ( isset( $audio['shortcode'] ) ) { ?>                
        <div class="entry-thumb">
            <?php echo do_shortcode( $audio['shortcode'] ); ?>               
        </div>
    <?php } // endif ?>
    <header>
        <h4 class="entry-title"><?php the_title(); ?></h4>
        <span class="entry-date">&mdash; <?php the_time( get_option( 'date_format' ) ); ?></span>
    </header>

    <div class="elements-box">
        <?php $content = get_the_content(); 
        $content = preg_replace( '/\[audio.*].*\[\/audio]/', '', $content );
        $content = preg_replace( '/\[soundcloud].*\[\/soundcloud]/', '', $content );
        $content = apply_filters( 'the_content', $content );
        $content = str_replace(']]>', ']]&gt;', $content);
        echo $content;
        ?>
    </div>

    <div class="kopa-pagelink clearfix">
        <?php wp_link_pages( array(
            'before'   => '<p>',
            'after'    => '</p>',
            'pagelink' => __( 'Page %', kopa_get_domain() )
        ) ); ?>
    </div> <!-- .wp-link-pages -->
    
    <?php kopa_social_sharing_links(); ?>
    
    <footer class="clearfix">
        <?php get_template_part( 'library/templates/template', 'post-navigation' ); ?>
    </footer>
    
</div><!--entry-box-->