<?php if ( have_posts() ) {
    while ( have_posts() ) {
        the_post(); ?>

    <div id="page-<?php the_ID(); ?>" <?php post_class( 'elements-box' ); ?>>
        <?php the_content(); ?>
    
        <div class="kopa-pagelink clearfix">
            <?php wp_link_pages( array(
                'before'   => '<p>',
                'after'    => '</p>',
                'pagelink' => __( 'Page %', kopa_get_domain() )
            ) ); ?>
        </div> <!-- .wp-link-pages -->

     <!-- pagination -->
     <?php get_template_part('library/templates/template', 'pagination'); ?>
        
    </div>

<?php } // endwhile
} // endif
?>