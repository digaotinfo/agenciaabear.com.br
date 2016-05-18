<?php if ( have_posts() ) { 
    while ( have_posts() ) {
        the_post(); 

        if ( '' == get_post_format() ) {
            get_template_part( 'library/templates/format-single', 'standard' );
        } else {
            get_template_part( 'library/templates/format-single', get_post_format() );
        }
?>
    <?php if ( get_the_terms( get_the_ID(), 'post_tag' ) ) { ?>
        <div class="tag-box">
            <span><?php _e( 'Tagged with:', kopa_get_domain() ); ?></span>
            <?php the_tags( '', ' ', '' ); ?>
        </div><!--tag-box-->
    <?php } // endif ?>

    <?php kopa_get_related_articles(); ?>

    

<?php } // endwhile
} // endif


