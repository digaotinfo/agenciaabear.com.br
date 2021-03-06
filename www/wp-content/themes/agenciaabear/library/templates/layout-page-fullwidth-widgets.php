<?php 
$kopa_setting = kopa_get_template_setting(); 
$sidebars = $kopa_setting['sidebars'];
get_header(); ?>

<div class="wrapper">
    <?php kopa_breadcrumb(); ?>

    <div class="row">
        <div class="col-md-12">
            
            <?php if ( is_active_sidebar( $sidebars[0] ) ) {
                dynamic_sidebar( $sidebars[0] );
            } ?>

        </div><!--col-md-12-->
    </div>
    <!-- row -->

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