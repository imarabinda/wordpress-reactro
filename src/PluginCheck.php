<?php


namespace Mrlazyfox\Reactro;

class PluginCheck{


	private function __construct() {}
	protected static $missing_plugin_name;

public static function init(){
		  $all_clear = true;
		  return true;
	$names= array();
	if ( !class_exists('Redux')){
		$names[]= __('Redux Framework',WR_TEXT_DOMAIN);
        $all_clear = false;
	}
	
	if(!function_exists('digits_addon_digrestapi')){
		$names[] = __('Digits Rest Api Addon',WR_TEXT_DOMAIN);
		$all_clear = false;
	}

	if(!function_exists('digits_version')){
		$names[] = __('Digits',WR_TEXT_DOMAIN);
		$all_clear = false;
	}

	if($all_clear){
		return true;
	}else{
		self::$missing_plugin_name = $names;
		self::missing_plugin(); 
		return false;
	}
}


public static function missing_plugin() {
	$plugin_names=self::$missing_plugin_name;
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			error_log( 
				esc_html__( 'WordPress Reactro requires the following plugins'.implode(",", $plugin_names).', Please install or activate it before!', WR_TEXT_DOMAIN )
				);
		}
		add_action(
			'admin_notices',
			function() {
				?>
				<div class="notice notice-error">
					<p>
						<?php
					echo __( 'WordPress Reactro requires the following plugins '.implode(', ',self::$missing_plugin_name).'. Please install or activate it before!', WR_TEXT_DOMAIN );
				
						?>
					</p>
				</div>
				<?php
			}
		);
	}

}