<?php
/*
Template Name: Videos – Feed
*/
?>

<?php
$kopa_setting = kopa_get_template_setting();
$sidebars = $kopa_setting['sidebars'];
?>

<?php get_header(); ?>

<div class="wrapper">

    <div class="main-col">


        <div class="widget-area-1">

            <h3 class="widget-title"><?php the_title(); ?></h3>

            <ul>
            <?php
            global $more;
            // set $more to 0 in order to only get the first part of the post
            $more = 0;
            $limit = get_option('posts_per_page');
            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
            query_posts('cat=10&showposts=' . $limit=9 . '&paged=' . $paged);
            $wp_query->is_category = true; $wp_query->is_home = false;
            ?>

            <?php while (have_posts()) : the_post(); ?>

            <li class="video-feed">
              <article class="entry-item">

                  <?php if ( has_post_thumbnail() ) { ?>
                      <div class="entry-thumb-small-video">
                          <div class="play-icon-feed"></div>
                          <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'article-list-image-size' ); ?></a>

                      </div>
                  <?php } // endif ?>

                  <header>
                      <h4 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                      <span class="entry-date">&mdash; <?php the_time( get_option( 'date_format' ) ); ?></span>
                      <?php the_field('linha-fina'); ?>
                  </header>

              </article>
          </li>



            <?php endwhile; ?>

            </ul>

             <div class="clear"></div>

        </div>
        <!-- widget-area-1 -->

        <!-- pagination -->
        <?php get_template_part('library/templates/template', 'pagination'); ?>

    </div>
    <!-- main-col -->

    <div class="abear-nos-jogos">
        <a href="http://www.agenciaabear.com.br/category/abear-nos-jogos/">
            <img src="http://www.agenciaabear.com.br/wp-content/uploads/2015/08/abear-nos-jogos_03.png" />
        </a>
    </div>
    <div class="sidebar">
        <?php if ( is_active_sidebar( $sidebars[0] ) ) {
            dynamic_sidebar( $sidebars[0] );
        } ?>

    </div>
    <!-- sidebar -->
    <div class="clear"></div>

</div>
<!-- wrapper -->

<?php if ( is_active_sidebar( $sidebars[1] ) || is_active_sidebar( $sidebars[2] ) || is_active_sidebar( $sidebars[3] ) || is_active_sidebar( $sidebars[4] ) || is_active_sidebar( $sidebars[5] ) ) { ?>
    <div class="widget-area-5">
        <ul class="wrapper clearfix">
            <li class="widget-area-6">
                <?php if ( is_active_sidebar( $sidebars[1] ) ) {
                    dynamic_sidebar( $sidebars[1] );
                } ?>
            </li>
            <!-- widget-area-6 -->
            <li class="widget-area-7">
                <?php if ( is_active_sidebar( $sidebars[2] ) ) {
                    dynamic_sidebar( $sidebars[2] );
                } ?>
            </li>
            <!-- widget-area-7 -->
            <li class="widget-area-8">
                <?php if ( is_active_sidebar( $sidebars[3] ) ) {
                    dynamic_sidebar( $sidebars[3] );
                } ?>
            </li>
            <!-- widget-area-8 -->
            <li class="widget-area-9">
                <?php if ( is_active_sidebar( $sidebars[4] ) ) {
                    dynamic_sidebar( $sidebars[4] );
                } ?>
            </li>
            <!-- widget-area-9 -->
            <li class="widget-area-10">
                <?php if ( is_active_sidebar( $sidebars[5] ) ) {
                    dynamic_sidebar( $sidebars[5] );
                } ?>
            </li>
            <!-- widget-area-10 -->
        </ul>
        <!-- wrapper -->
    </div>
    <!-- widget-area-5 -->
<?php } // endif ?>

<?php if ( is_active_sidebar( $sidebars[6] ) ) { ?>
    <div class="widget-area-11">
        <div class="wrapper">
            <?php dynamic_sidebar( $sidebars[6] ); ?>
        </div>
        <!-- wrapper -->
    </div>
    <!-- widget-area-11 -->
<?php } // endif ?>

<?php get_footer(); ?>
