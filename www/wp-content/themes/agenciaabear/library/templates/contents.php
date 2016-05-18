<?php 
$kopa_setting = kopa_get_template_setting();

if ( is_home() || is_archive() || is_search() ) {
    if ( 'blog' == $kopa_setting['layout_id'] ) {
        get_template_part( 'library/templates/loop', 'blog-1' );
    } else {
        get_template_part( 'library/templates/loop', 'blog' );
    }
} elseif ( is_page() ) {
    get_template_part( 'library/templates/loop', 'page' );
} elseif ( is_page() ) {
    get_template_part( 'library/templates/loop', 'noticias' );
} elseif ( is_single() ) {
    get_template_part( 'library/templates/loop', 'single' );
} else {
    get_template_part( 'library/templates/loop', 'blog' );
}