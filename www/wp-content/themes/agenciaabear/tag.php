<?php
$kopa_setting = kopa_get_template_setting();
$sidebars = $kopa_setting['sidebars'];
?>

<?php get_header(); ?>

<div class="wrapper">

    <div class="main-col">

        <?php kopa_breadcrumb(); ?>

        <div class="widget-area-1">

            <h3 class="widget-title"><?php single_tag_title(); ?></h3>

            <ul class="categorias isotop-item clearfix">

            <?php if (have_posts()) : ?>
            <?php while (have_posts()) : ?>
            <?php the_post(); ?>
            <li class="element">
            <article id="post-<?php the_ID(); ?>" <?php post_class( 'entry-item clearfix' ); ?>>
                <?php if ( has_post_thumbnail() ) { ?>
                    <div class="entry-thumb-small">
                        <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'medium' ); ?></a>
                    </div>
                    <!-- entry-thumb -->
                <?php } // endif ?>
                <div class="entry-content">
                    <header>
                        <h4 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                        <span class="entry-date">&mdash; <?php the_time( get_option( 'date_format' ) ); ?></span>
                    </header>
                    <?php the_field('linha-fina'); ?>

                </div>
                <!-- entry-content -->
            </article>
            </li>
            <?php endwhile; ?>

              <?php else : ?>

              <?php endif; ?>


              </ul>

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
