<?php 
$video = kopa_content_get_video( get_the_content() );
if ( isset( $video[0] ) ) {
    $video = $video[0];
} else {
    $video = '';
}
?>
<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        
    <?php if ( isset( $video['shortcode'] ) ) { ?>                
        <div class="entry-thumb">
            <?php echo do_shortcode( $video['shortcode'] ); ?>               
        </div>
    <?php } // endif ?>
    <header>
        <h4 class="entry-title"><?php the_title(); ?></h4>
        <span class="entry-date">&mdash; <?php the_time( get_option( 'date_format' ) ); ?></span>
    </header>

    <div class="elements-box">
        <?php $content = get_the_content();
        // strips all youtube and vimeo shortcode
        $content = preg_replace("/\[youtube].*\[\/youtube]/", "", $content);
        $content = preg_replace('/\[vimeo].*\[\/vimeo]/', '', $content);
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