<?php
$kopa_setting = kopa_get_template_setting();
$sidebars = $kopa_setting['sidebars'];
?>

<?php get_header(); ?>

<div class="wrapper">
    <div class="col-a widget-area-1">

        <?php get_template_part( 'library/templates/contents' ); ?>

    </div>
    <!-- col-a -->

    <div class="col-b">

        <div class="widget-area-4">
            <?php if ( is_active_sidebar( $sidebars[0] ) ) {
                dynamic_sidebar( $sidebars[0] );
            } ?>
        </div>
        <!-- widget-area-4 -->

        <div class="abear-nos-jogos">
            <a href="http://www.agenciaabear.com.br/category/abear-nos-jogos/">
                <img src="http://www.agenciaabear.com.br/wp-content/uploads/2015/08/abear-nos-jogos_03.png" />
            </a>
        </div>

        <div class="widget-area-3">
            <?php if ( is_active_sidebar( $sidebars[1] ) ) {
                dynamic_sidebar( $sidebars[1] );
            } ?>
        </div>
        <!-- widget-area-3 -->

        <div class="clear"></div>
    </div>
    <!-- col-b -->
    <div class="clear"></div>
</div>
<!-- wrapper -->

<?php if ( is_active_sidebar( $sidebars[2] ) || is_active_sidebar( $sidebars[3] ) || is_active_sidebar( $sidebars[4] ) || is_active_sidebar( $sidebars[5] ) || is_active_sidebar( $sidebars[6] ) ) { ?>
    <div class="widget-area-5">
        <ul class="wrapper clearfix">
            <li class="widget-area-6">
                <?php if ( is_active_sidebar( $sidebars[2] ) ) {
                    dynamic_sidebar( $sidebars[2] );
                } ?>
            </li>
            <!-- widget-area-6 -->
            <li class="widget-area-7">
                <?php if ( is_active_sidebar( $sidebars[3] ) ) {
                    dynamic_sidebar( $sidebars[3] );
                } ?>
            </li>
            <!-- widget-area-7 -->
            <li class="widget-area-8">
                <?php if ( is_active_sidebar( $sidebars[4] ) ) {
                    dynamic_sidebar( $sidebars[4] );
                } ?>
            </li>
            <!-- widget-area-8 -->
            <li class="widget-area-9">
                <?php if ( is_active_sidebar( $sidebars[5] ) ) {
                    dynamic_sidebar( $sidebars[5] );
                } ?>
            </li>
            <!-- widget-area-9 -->
            <li class="widget-area-10">
                <?php if ( is_active_sidebar( $sidebars[6] ) ) {
                    dynamic_sidebar( $sidebars[6] );
                } ?>
            </li>
            <!-- widget-area-10 -->
        </ul>
        <!-- wrapper -->
    </div>
    <!-- widget-area-5 -->
<?php } // endif ?>

<?php if ( is_active_sidebar( $sidebars[7] ) ) { ?>
    <div class="widget-area-11">
        <div class="wrapper">
            <?php dynamic_sidebar( $sidebars[7] ); ?>
        </div>
        <!-- wrapper -->
    </div>
    <!-- widget-area-11 -->
<?php } // endif ?>

<?php get_footer(); ?>
