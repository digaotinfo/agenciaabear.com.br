<?php
$kopa_setting = kopa_get_template_setting();
$sidebars = $kopa_setting['sidebars'];
$total = count( $sidebars );
$footer_sidebar[0] = ($kopa_setting) ? $sidebars[$total - 5] : 'sidebar_11';
$footer_sidebar[1] = ($kopa_setting) ? $sidebars[$total - 4] : 'sidebar_12';
$footer_sidebar[2] = ($kopa_setting) ? $sidebars[$total - 3] : 'sidebar_13';
$footer_sidebar[3] = ($kopa_setting) ? $sidebars[$total - 2] : 'sidebar_14';
$footer_sidebar[4] = ($kopa_setting) ? $sidebars[$total - 1] : 'sidebar_15';

/* get options
 -------------------------*/

// footer logo
$kopa_footer_logo = get_option( 'kopa_theme_options_footer_logo' );

// footer copyright
$kopa_theme_options_copyright = get_option( 'kopa_theme_options_copyright', __( 'Copyright &copy; 2013 . All Rights Reserved. Designed by kopatheme.com.', kopa_get_domain() ) );
$kopa_theme_options_copyright = htmlspecialchars_decode( stripslashes( $kopa_theme_options_copyright ) );
$kopa_theme_options_copyright = apply_filters( 'the_content', $kopa_theme_options_copyright );

/* END get options
 -------------------------*/

?>

    <div id="bottom-sidebar">
        <div class="wrapper">
            <div class="t-bottom-sidebar clearfix">

                <?php if ( ! empty( $kopa_footer_logo ) ) { ?>
                <div class="footer-logo pull-left"><a href="<?php echo home_url(); ?>"><img src="<?php echo esc_url( $kopa_footer_logo ); ?>" alt="<?php bloginfo('name'); ?>"></a></div>
                <?php } ?>

                <?php
                if ( has_nav_menu( 'footer-nav' ) ) {
                    wp_nav_menu( array(
                        'theme_location' => 'footer-nav',
                        'container'      => '',
                        'menu_id'        => 'footer-menu',
                        'items_wrap'     => '<ul id="%1$s" class="clearfix">%3$s</ul>',
                        'depth'          => -1
                    ) );
                }
                ?>
            </div>
            <!-- t-bottom-sidebar -->
            <div class="b-bottom-sidebar clearfix">
                <div class="bottom-left-col">
                    <div class="row">
                        <div class="col-md-3 col-sm-3 col-xs-6 widget-area-12">
                            <?php if ( is_active_sidebar( $footer_sidebar[0] ) ) {
                                dynamic_sidebar( $footer_sidebar[0] );
                            } ?>
                        </div>
                        <!-- widget-area-12 -->
                        <div class="col-md-3 col-sm-3 col-xs-6 widget-area-13">
                            <?php if ( is_active_sidebar( $footer_sidebar[1] ) ) {
                                dynamic_sidebar( $footer_sidebar[1] );
                            } ?>
                        </div>
                        <!-- widget-area-6 -->
                        <div class="col-md-3 col-sm-3 col-xs-6 widget-area-14">
                            <?php if ( is_active_sidebar( $footer_sidebar[2] ) ) {
                                dynamic_sidebar( $footer_sidebar[2] );
                            } ?>
                        </div>
                        <!-- widget-area-7 -->
                        <div class="col-md-3 col-sm-3 col-xs-6 widget-area-15">
                            <?php if ( is_active_sidebar( $footer_sidebar[3] ) ) {
                                dynamic_sidebar( $footer_sidebar[3] );
                            } ?>
                        </div>
                        <!-- widget-area-8 -->
                    </div>
                    <!-- row -->
                </div>
                <!-- bottom-left-col -->
                <div class="bottom-right-col widget-area-16">
                    <?php if ( is_active_sidebar( $footer_sidebar[4] ) ) {
                        dynamic_sidebar( $footer_sidebar[4] );
                    } ?>
                </div>
                <!-- bottom-right-col -->
            </div>
            <!-- b-bottom-sidebar -->

            <div class="wrap-txt">
            <div id="txt-sobre">
              <div class="img-sobre"><a href="http://www.abear.com.br" target="_blank"><img src="<?php bloginfo( 'template_url' ); ?>/images/logo-footer.png" alt="" /></a></div>
              <div class="txt-sobre">A ABEAR foi criada em 2012 pelas cinco principais companhias aéreas brasileiras – AVIANCA, AZUL, GOL, TAM e TRIP, com a missão de estimular o hábito de voar no Brasil. A entidade tem ainda mais duas associadas: TAM Cargo, TAP e Bombardier. <a href="http://www.abear.com.br" target="_blank">Saiba mais</a></div>
            </div><!--end txt-sobre-->
            </div><!--end wrap-txt-->

            <div class="clear"></div>

            <div id="contatos">
              <ul id="lista-contatos">
                <li>Av. Ibirapuera, 2332 - Conj. 22 - Torre Ibirapuera I <br />Moema | 04028-002 | São Paulo | SP <br />+ 55 11 2369-6007</li>
                <li>Av. Marechal Câmara, 160, Ed. Orly, 12º andar - sl. 1210 | 20020-080 | Rio de Janeiro | RJ <br />+ 55 21 3578-1150 </li>
                <li>SAUS Quadra 1 - Bloco J , 10/20 - Edifício CNT - Sala 506 <br />70070-944 | Brasília | DF <br />+ 55 61 3225-5215</li>
              </ul>
            </div><!--end contatos-->





        </div>
        <!-- wrapper -->

    </div>
    <!-- bottom-sidebar -->
</div>
<!-- main-content -->


<footer id="kp-page-footer">
    <div class="wrapper text-center" id="copyright"><?php echo $kopa_theme_options_copyright; ?></div>
</footer>
<!-- kp-page-footer -->

<?php wp_footer(); ?>
</body>

</html>