<?php 
if ( is_home() ) {
    get_template_part( 'library/templates/template', 'blog-slider' );
}
?>

<?php if ( have_posts() ) {

    /* for first post
    ----------------------------------------
    */
    $post_index = 1;
    while ( have_posts() ) {
        the_post(); ?>

        <?php if ( 1 == $post_index ) { ?>
            <div class="latest-entry-item">
                <article id="post-<?php the_ID(); ?>" class="entry-item clearfix">
                    <?php if ( has_post_thumbnail() ) { ?>
                        <div class="entry-thumb">
                            <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'medium' ); ?></a>
                        </div>
                        <!-- entry-thumb -->
                    <?php } // endif ?>
                    <!-- entry-thumb -->
                    <div class="entry-content">
                        <header>
                            <h4 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                            <span class="entry-date">&mdash; <?php the_time( get_option( 'date_format' ) ); ?></span>
                        </header>
                        <?php the_excerpt(); ?>
                        <a href="<?php the_permalink(); ?>" class="more-link"><?php _e( 'Continue Reading ...', kopa_get_domain() ); ?></a>
                    </div>
                    <!-- entry-content -->
                </article>
                <!-- entry-item -->
            </div>
            <!-- latest-entry-item-->
        <?php } ?>

    <?php $post_index++;
    } // endwhile



    /* for even posts
    ----------------------------------------
    */
    $post_index = 1;
    echo '<ul class="entry-list l-entry-list clearfix">';
    while ( have_posts() ) {
        the_post(); ?>

        <?php if ( $post_index != 1 && $post_index % 2 == 0 ) { ?>
            <li class="element">
                <article id="post-<?php the_ID(); ?>" <?php post_class( 'entry-item clearfix' ); ?>>
                    <?php if ( has_post_thumbnail() ) { ?>
                        <div class="entry-thumb">
                            <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'medium' ); ?></a>
                        </div>
                        <!-- entry-thumb -->
                    <?php } // endif ?>
                    <div class="entry-content">
                        <header>
                            <h4 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                            <span class="entry-date">&mdash; <?php the_time( get_option( 'date_format' ) ); ?></span>
                        </header>
                        <?php the_excerpt(); ?>
                        <a href="<?php the_permalink(); ?>" class="more-link"><?php _e( 'Continue Reading ...', kopa_get_domain() ); ?></a>
                    </div>
                    <!-- entry-content -->
                </article>
                <!-- entry-item -->
            </li>
        <?php } ?>

    <?php $post_index++;
    } // endwhile
    echo '</ul> <!-- l-entry-list -->';



    /* for odd posts
    ----------------------------------------
    */
    $post_index = 1;
    echo '<ul class="entry-list r-entry-list clearfix">';
    while ( have_posts() ) {
        the_post(); ?>

        <?php if ( $post_index != 1 && $post_index % 2 != 0 ) { ?>
            <li class="element">
                <article id="post-<?php the_ID(); ?>" <?php post_class( 'entry-item clearfix' ); ?>>
                    <?php if ( has_post_thumbnail() ) { ?>
                        <div class="entry-thumb">
                            <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'medium' ); ?></a>
                        </div>
                        <!-- entry-thumb -->
                    <?php } // endif ?>
                    <div class="entry-content">
                        <header>
                            <h4 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                            <span class="entry-date">&mdash; <?php the_time( get_option( 'date_format' ) ); ?></span>
                        </header>
                        <?php the_excerpt(); ?>
                        <a href="<?php the_permalink(); ?>" class="more-link"><?php _e( 'Continue Reading ...', kopa_get_domain() ); ?></a>
                    </div>
                    <!-- entry-content -->
                </article>
                <!-- entry-item -->
            </li>
        <?php } ?>

    <?php $post_index++;
    } // endwhile
    echo '</ul> <!-- r-entry-list -->';
    echo '<div class="clear"></div>';
} // endif ?>

<!-- pagination -->
<?php get_template_part('library/templates/template', 'pagination'); ?>