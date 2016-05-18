<?php 
$kopa_logo_url = get_option('kopa_theme_options_logo_url');
$kopa_top_banner_code = get_option( 'kopa_theme_options_top_banner_code' );
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">                   
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php kopa_print_page_title(); ?></title>     
    <link rel="profile" href="http://gmpg.org/xfn/11">           
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
    
    <?php if ( get_option('kopa_theme_options_favicon_url') ) { ?>       
        <link rel="shortcut icon" type="image/x-icon"  href="<?php echo get_option('kopa_theme_options_favicon_url'); ?>">
    <?php } ?>
    
    <?php if ( get_option('kopa_theme_options_apple_iphone_icon_url') ) { ?>
        <link rel="apple-touch-icon" sizes="57x57" href="<?php echo get_option('kopa_theme_options_apple_iphone_icon_url'); ?>">
    <?php } ?>

    <?php if ( get_option('kopa_theme_options_apple_ipad_icon_url') ) { ?>
    <link rel="apple-touch-icon" sizes="72x72" href="<?php echo get_option('kopa_theme_options_apple_ipad_icon_url'); ?>">
    <?php } ?>

    <?php if ( get_option('kopa_theme_options_apple_iphone_retina_icon_url') ) { ?>
    <link rel="apple-touch-icon" sizes="114x114" href="<?php echo get_option('kopa_theme_options_apple_iphone_retina_icon_url'); ?>">
    <?php } ?>

    <?php if ( get_option('kopa_theme_options_apple_ipad_retina_icon_url') ) { ?>
        <link rel="apple-touch-icon" sizes="144x144" href="<?php echo get_option('kopa_theme_options_apple_ipad_retina_icon_url'); ?>">        
    <?php } ?>

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->

    <!--[if lt IE 9]>
        <script src="<?php echo get_template_directory_uri(); ?>/js/html5shiv.js"></script>
        <script src="<?php echo get_template_directory_uri(); ?>/js/respond.min.js"></script>
        <script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
        <script src="<?php echo get_template_directory_uri(); ?>/js/PIE_IE678.js"></script>
    <![endif]-->
    
    <?php wp_head(); ?>
</head>
    
<body <?php body_class(); ?>>

<div class="kp-page-header">
    <div id="header-top">
        <div class="wrapper clearfix">
            <nav id="main-nav" class="pull-left">
                <?php 
                if ( has_nav_menu( 'main-nav' ) ) {
                    wp_nav_menu( array(
                        'theme_location' => 'main-nav',
                        'container'      => '',
                        'menu_id'        => 'main-menu',
                        'items_wrap'     => '<ul id="%1$s" class="%2$s clearfix">%3$s</ul>'
                    ) );

                    $mobile_menu_walker = new kopa_mobile_menu();
                    wp_nav_menu( array(
                        'theme_location' => 'main-nav',
                        'container'      => 'div',
                        'container_id'   => 'mobile-menu',
                        'menu_id'        => 'toggle-view-menu',
                        'items_wrap'     => '<span>'.__( 'Menu', kopa_get_domain() ).'</span><ul id="%1$s">%3$s</ul>',
                        'walker'         => $mobile_menu_walker
                    ) );
                } ?>
            </nav>
            <!-- main-nav -->
            
            <div class="search-box pull-right clearfix">
                <form action="<?php echo home_url(); ?>" class="search-form clearfix" method="get">
                    <input type="text" onblur="if (this.value == '') this.value = this.defaultValue;" onfocus="if (this.value == this.defaultValue) this.value = '';" value="<?php _e( 'Enter keyworks', kopa_get_domain() ); ?>" name="s" class="search-text">
                    <input type="submit" value="<?php _e( 'Search', kopa_get_domain() ); ?>" class="search-submit">
                </form>
            </div>
            <!--search-box-->
        </div>
        <!-- wrapper -->
    </div>
    <!-- header-top -->
    <div id="header-middle">
        <div class="wrapper clearfix">
            <div class="logo-image pull-left">
                <?php if ( !empty( $kopa_logo_url ) ) { ?> 
                <a href="<?php echo home_url(); ?>"><img src="<?php echo esc_url( $kopa_logo_url ); ?>" alt="<?php bloginfo('name'); ?>"></a>
                <?php } else { ?>
                <h1 class="site-title"><a href="<?php echo home_url(); ?>"><?php bloginfo('name'); ?></a></h1>
                <?php } ?>
            </div>
            <div class="top-banner pull-right">
                <?php echo htmlspecialchars_decode( stripslashes( $kopa_top_banner_code ) ); ?>
            </div>
        </div>
        <!-- wrapper -->
    </div>
    <!-- header-middle -->
    <div id="header-bottom">
        <div class="wrapper clearfix">
            <div class="kp-headline-wrapper pull-left">
                <?php kopa_header_ticker(); ?>
                <span class="sobre">Informações oficiais do setor aéreo; conteúdo pode ser reproduzido livremente pela mídia</span>                    
            </div>
            <!-- kp-headline-wrapper -->
            
            <?php kopa_header_social_links(); ?>
            <!-- social-link -->
        </div>
        <!-- wrapper -->
    </div>
    <!-- header-bottom -->
</div>
<!-- kp-page-header -->

<div id="main-content" class="clearfix">