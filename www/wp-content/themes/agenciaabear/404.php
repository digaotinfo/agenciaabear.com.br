<?php 
$kopa_setting = kopa_get_template_setting();
$sidebars = $kopa_setting['sidebars'];
?>

<?php get_header(); ?>

<div class="wrapper">
        
    <?php kopa_breadcrumb(); ?>
    
    <section class="error-404 clearfix">
        <div class="left-col">
            <p><?php _e( '404', kopa_get_domain() ); ?></p>
        </div><!--left-col-->
        <div class="right-col">
            <h1><?php _e( 'Page not found...', kopa_get_domain() ); ?></h1>
            <p><?php _e( "We're sorry, but we can't find the page you were looking for. It's probably some thing we've done wrong but now we know about it we'll try to fix it. In the meantime, try one of this options:", kopa_get_domain() ); ?></p>
            <ul class="arrow-list">
                <?php if ( isset( $_SERVER['HTTP_REFERER'] ) ) { ?>
                    <li><a href="<?php echo $_SERVER['HTTP_REFERER']; ?>"><?php _e( 'Go back to previous page', kopa_get_domain() ); ?></a></li>
                <?php } ?>
                <li><a href="<?php echo home_url(); ?>"><?php _e( 'Go to homepage', kopa_get_domain() ); ?></a></li>
            </ul>
        </div><!--right-col-->
    </section><!--error-404-->

</div>
<!-- wrapper -->

<?php if ( is_active_sidebar( $sidebars[0] ) || is_active_sidebar( $sidebars[1] ) || is_active_sidebar( $sidebars[2] ) || is_active_sidebar( $sidebars[3] ) || is_active_sidebar( $sidebars[4] ) ) { ?>
    <div class="widget-area-5">
        <ul class="wrapper clearfix">
            <li class="widget-area-6">
                <?php if ( is_active_sidebar( $sidebars[0] ) ) {
                    dynamic_sidebar( $sidebars[0] );
                } ?>
            </li>
            <!-- widget-area-6 -->
            <li class="widget-area-7">
                <?php if ( is_active_sidebar( $sidebars[1] ) ) {
                    dynamic_sidebar( $sidebars[1] );
                } ?>
            </li>
            <!-- widget-area-7 -->
            <li class="widget-area-8">
                <?php if ( is_active_sidebar( $sidebars[2] ) ) {
                    dynamic_sidebar( $sidebars[2] );
                } ?>
            </li>
            <!-- widget-area-8 -->
            <li class="widget-area-9">
                <?php if ( is_active_sidebar( $sidebars[3] ) ) {
                    dynamic_sidebar( $sidebars[3] );
                } ?>
            </li>
            <!-- widget-area-9 -->
            <li class="widget-area-10">
                <?php if ( is_active_sidebar( $sidebars[4] ) ) {
                    dynamic_sidebar( $sidebars[4] );
                } ?>
            </li>
            <!-- widget-area-10 -->
        </ul>
        <!-- wrapper -->
    </div>
    <!-- widget-area-5 -->
<?php } // endif ?>

<?php if ( is_active_sidebar( $sidebars[5] ) ) { ?>
    <div class="widget-area-11">
        <div class="wrapper">
            <?php dynamic_sidebar( $sidebars[5] ); ?>
        </div>
        <!-- wrapper -->
    </div>
    <!-- widget-area-11 -->
<?php } // endif ?>

<?php get_footer(); ?>