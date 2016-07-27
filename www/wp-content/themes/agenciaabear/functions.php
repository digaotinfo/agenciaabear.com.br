<?php

define('KOPA_THEME_NAME', 'FastNews');
define('KOPA_DOMAIN', 'fastnews');
define('KOPA_CPANEL_IMAGE_DIR', get_template_directory_uri() . '/library/images/layout/');
define('KOPA_UPDATE_TIMEOUT', 21600);
define('KOPA_UPDATE_URL', 'http://kopatheme.com/notifier/fastnews.xml');

require trailingslashit(get_template_directory()) . '/library/kopa.php';
require trailingslashit(get_template_directory()) . '/library/ini.php';
require trailingslashit(get_template_directory()) . '/library/includes/google-fonts.php';
require trailingslashit(get_template_directory()) . '/library/includes/ajax.php';
require trailingslashit(get_template_directory()) . '/library/includes/metabox/post.php';
require trailingslashit(get_template_directory()) . '/library/includes/metabox/category.php';
require trailingslashit(get_template_directory()) . '/library/includes/metabox/page.php';
require trailingslashit(get_template_directory()) . '/library/front.php';

/**
 * Include the TGM_Plugin_Activation class.
 */
require_once get_template_directory() . '/library/includes/class-tgm-plugin-activation.php';

add_action( 'tgmpa_register', 'kopa_register_required_plugins' );


/**
 * Excluir categorias
 */
function exclude_widget_categories($args){
$exclude = "1,10,26,27,28,29,30,31,32,33,68"; // The IDs of the excluding categories
$args["exclude"] = $exclude;
return $args;
}
add_filter("widget_categories_args","exclude_widget_categories");


// remove parentheses from category list and add span class to post count
function categories_postcount_filter ($variable) {
$variable = str_replace('(', '<span class="post-count"> ', $variable);
$variable = str_replace(')', ' </span>', $variable);
   return $variable;
}
add_filter('wp_list_categories','categories_postcount_filter');





function kopa_register_required_plugins() {
	$plugins = array(
		array(
			'name'     				=> 'Kopa Shortcodes', // The plugin name
			'slug'     				=> 'kopa-shortcodes', // The plugin slug (typically the folder name)
			'source'   				=> get_stylesheet_directory() . '/plugins/kopa-shortcodes.zip', // The plugin source
			'required' 				=> true, // If false, the plugin is only 'recommended' instead of required
			'version' 				=> '1.0', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
			'force_activation' 		=> false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
			'force_deactivation' 	=> true, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
			'external_url' 			=> '', // If set, overrides default API URL and points to an external URL
		)
	);	
	$theme_text_domain = KOPA_DOMAIN;	
	$config = array(
		'domain'       		=> $theme_text_domain,         	// Text domain - likely want to be the same as your theme.
		'default_path' 		=> '',                         	// Default absolute path to pre-packaged plugins
		'parent_menu_slug' 	=> 'themes.php', 				// Default parent menu slug
		'parent_url_slug' 	=> 'themes.php', 				// Default parent URL slug
		'menu'         		=> 'install-required-plugins', 	// Menu slug
		'has_notices'      	=> true,                       	// Show admin notices or not
		'is_automatic'    	=> true,					   	// Automatically activate plugins after installation or not
		'message' 			=> '',							// Message to output right before the plugins table
		'strings'      		=> array(
			'page_title'                       			=> __( 'Install Required Plugins', $theme_text_domain ),
			'menu_title'                       			=> __( 'Install Plugins', $theme_text_domain ),
			'installing'                       			=> __( 'Installing Plugin: %s', $theme_text_domain ), // %1$s = plugin name
			'oops'                             			=> __( 'Something went wrong with the plugin API.', $theme_text_domain ),
			'notice_can_install_required'     			=> _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.' ), // %1$s = plugin name(s)
			'notice_can_install_recommended'			=> _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.' ), // %1$s = plugin name(s)
			'notice_cannot_install'  					=> _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.' ), // %1$s = plugin name(s)
			'notice_can_activate_required'    			=> _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s)
			'notice_can_activate_recommended'			=> _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s)
			'notice_cannot_activate' 					=> _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.' ), // %1$s = plugin name(s)
			'notice_ask_to_update' 						=> _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.' ), // %1$s = plugin name(s)
			'notice_cannot_update' 						=> _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.' ), // %1$s = plugin name(s)
			'install_link' 					  			=> _n_noop( 'Begin installing plugin', 'Begin installing plugins' ),
			'activate_link' 				  			=> _n_noop( 'Activate installed plugin', 'Activate installed plugins' ),
			'return'                           			=> __( 'Return to Required Plugins Installer', $theme_text_domain ),
			'plugin_activated'                 			=> __( 'Plugin activated successfully.', $theme_text_domain ),
			'complete' 									=> __( 'All plugins installed and activated successfully. %s', $theme_text_domain ), // %1$s = dashboard link
			'nag_type'									=> 'updated' // Determines admin notice type - can only be 'updated' or 'error'
		)
	);
	tgmpa( $plugins, $config );
}


function codex_custom_init() {
	$args = array(
			'public' => true,
			'label'  => 'Enderecos',
			'menu_position' => 5,
			'supports' => array('title', 'editor'),
	);
	register_post_type( 'enderecos', $args );
	
	$args = array(
			'public' => true,
			'label'  => 'Sobre',
			'menu_position' => 5,
			'supports' => array('title', 'editor', 'thumbnail'),
	);
	register_post_type( 'sobre', $args );

}
add_action( 'init', 'codex_custom_init' );