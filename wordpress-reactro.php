<?php

/**
 * The plugin bootstrap file
 *
 * @wordpress-plugin
 * Plugin Name:       WordPress Reactro
 * Plugin URI:        https://mrlazyfox.com/
 * Description:       The All in One WordPress React Solution
 * Version:           1.2.2
 * Author:            Arabinda
 * Author URI:        https:/mrlazyfox.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wordpress-reactro
 * Domain Path:       /languages
 */


defined( 'ABSPATH' ) || exit;

if ( ! defined( 'WR_PLUGIN_FILE' ) ) {
	define( 'WR_PLUGIN_FILE', __FILE__ );
}

require dirname( WR_PLUGIN_FILE ) . '/src/PluginCheck.php';
if(! \Mrlazyfox\Reactro\PluginCheck::init()){
	return;
}

if ( ! class_exists( 'WordpressReactro', false ) ) {
	include_once dirname( WR_PLUGIN_FILE ) . '/includes/class-wordpress-reactro.php';
}

if ( !function_exists( 'WR' ) ) {
		function WR() {
			return WordpressReactro::instance();
		}
		$GLOBALS['reactro']=WR();
}





