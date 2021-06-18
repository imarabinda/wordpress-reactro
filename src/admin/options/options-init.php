<?php

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Redux' ) || !defined('WR_OPTION_NAME')) {
	return;
}

$opt_name = WR_OPTION_NAME; 

/*
 * ---> BEGIN 
 */


$args = array(
	'opt_name'                  => $opt_name,

	'display_name'              => WR_NAME,

	'display_version'           => WR_VERSION,

	'menu_type'                 => 'menu',

	'allow_sub_menu'            => true,

	'menu_title'                => esc_html__( WR_NAME, WR_TEXT_DOMAIN ),

	'page_title'                => esc_html__( WR_NAME, WR_TEXT_DOMAIN ),

	'async_typography'          => false,

	'disable_google_fonts_link' => false,

	'admin_bar'                 => true,

	'admin_bar_icon'            => 'dashicons-portfolio',

	'admin_bar_priority'        => 50,

	'global_variable'           => '',

	'dev_mode'                  => false,

	'customizer'                => true,

	'open_expanded'             => false,

	'disable_save_warn'         => false,

	'page_priority'             => null,

	'page_parent'               => 'themes.php',

	'page_permissions'          => 'manage_options',

	'menu_icon'                 => '',

	'last_tab'                  => '',

	'page_icon'                 => 'icon-themes',

	'page_slug'                 => $opt_name,

	'save_defaults'             => true,

	'default_show'              => false,

	'default_mark'              => '*',

	'show_import_export'        => true,

	'transient_time'            => 60 * MINUTE_IN_SECONDS,

	'output'                    => true,

	'output_tag'                => true,

	'footer_credit'             => 'Made with love by '.WR_CREATOR,

	'use_cdn'                   => true,
	'cdn_check_time' 			=> 1440,

	'admin_theme'               => 'wp',

	'hints'                     => array(
		'icon'          => 'el el-question-sign',
		'icon_position' => 'right',
		'icon_color'    => 'lightgray',
		'icon_size'     => 'normal',
		'tip_style'     => array(
			'color'   => 'red',
			'shadow'  => true,
			'rounded' => false,
			'style'   => '',
		),
		'tip_position'  => array(
			'my' => 'top left',
			'at' => 'bottom right',
		),
		'tip_effect'    => array(
			'show' => array(
				'effect'   => 'slide',
				'duration' => '500',
				'event'    => 'mouseover',
			),
			'hide' => array(
				'effect'   => 'slide',
				'duration' => '500',
				'event'    => 'click mouseleave',
			),
		),
	),

	'database'                  => '',
	'network_admin'             => true,
);


$args['admin_bar_links'][] = array(
	'id'    => 'redux-docs',
	'href'  => '//docs.redux.io/',
	'title' => __( 'Documentation', WR_TEXT_DOMAIN ),
);

$args['admin_bar_links'][] = array(
	'id'    => 'redux-support',
	'href'  => '//github.com/ReduxFramework/redux-framework/issues',
	'title' => __( 'Support', WR_TEXT_DOMAIN ),
);

$args['admin_bar_links'][] = array(
	'id'    => 'redux-extensions',
	'href'  => 'redux.io/extensions',
	'title' => __( 'Extensions', WR_TEXT_DOMAIN ),
);

$args['share_icons'][] = array(
	'url'   => '//github.com/ReduxFramework/ReduxFramework',
	'title' => 'Visit us on GitHub',
	'icon'  => 'el el-github',
);
$args['share_icons'][] = array(
	'url'   => '//www.facebook.com/pages/Redux-Framework/243141545850368',
	'title' => 'Like us on Facebook',
	'icon'  => 'el el-facebook',
);
$args['share_icons'][] = array(
	'url'   => '//twitter.com/reduxframework',
	'title' => 'Follow us on Twitter',
	'icon'  => 'el el-twitter',
);
$args['share_icons'][] = array(
	'url'   => '//www.linkedin.com/company/redux-framework',
	'title' => 'Find us on LinkedIn',
	'icon'  => 'el el-linkedin',
);

$args['footer_text'] = '<p>' . esc_html__( 'Hey, Feel free to contact us. MrLazyFox 2020', WR_TEXT_DOMAIN ) . '</p>';

Redux::set_args( $opt_name, $args );


$tabs = array(
	array(
		'id'      => 'help-tab',
		'title'   => __('Information', WR_TEXT_DOMAIN),
		'content' => __('<p>Need support? Please use the comment function on <a href="//mrlazyfox.com">mrlazyfox</a>.</p>', WR_TEXT_DOMAIN)
	),
);


// Redux::set_help_tab($opt_name, $tabs);
$content = '<p>' . esc_html__( 'Hey.', WR_TEXT_DOMAIN ) . '</p>';

// Redux::set_help_sidebar( $opt_name, $content );


Redux::disable_demo();

/*
* <--- END HELP TABS--->