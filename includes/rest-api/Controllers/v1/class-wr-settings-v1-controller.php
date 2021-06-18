<?php

class WR_REST_Settings_V1_Controller extends REST_Base_v1 {


	/**
	 * Route base.
	 *
	 * @var string
	 */
	protected $rest_base = 'settings';


    public function register_routes(){
        register_rest_route( $this->namespace, '/' . $this->rest_base, array(
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_settings' )
				)
			) 
		);
	}
	

	public function get_settings($request){
		$updated =WR()->get_option('REDUX_LAST_SAVE');
		$last_load_init=$request->get_param('last_load');
		if($last_load_init && !empty($last_load_init) && strlen($last_load_init) > 5 && intval($last_load_init) != $updated ){
		$settings=array(
			'requireLogin'			=> WR()->get_option('loginRequired'),
			'timezone_string'       => wp_timezone_string(),
            'date_format'           => get_option( 'date_format' ),
            'time_format'           => get_option( 'time_format' ),
			'maintenanceActive'		=>WR()->get_option('maintenanceMode'),
  			'maintenanceText'		=>WR()->get_option('maintenanceModeText'),
			  'minimumAppVersion'	=>array(
				  'ios'				=>WR()->get_option('minimumAppVersionIOS'),
				  'android'			=>WR()->get_option('minimumAppVersionAndroid'),
				),
				'updateAbleAppVersion'=> array(
				'isNewVersionMandatory'=>WR()->get_option('isNewVersionMandatory'),  
				'ios'				=> WR()->get_option('updateAvailableVersionIOS'),
				'android'			=> WR()->get_option('updateAvailableVersionAndroid'),
				'downloadLinkEnabled'=>WR()->get_option('downloadLinkEnabled'),
				'downloadLinkAndroid'=>WR()->get_option('downloadLinkAndroid'),
				),
			'changeLog'=>array(
				'changeLogEnabled'=>WR()->get_option('changeLogShow'),
				'changeLogTexts'=>WR()->get_option('changeLogTexts'),
			),
			'store'=>array(
				'enableStoreOpenAndCloseHours'=>WR()->get_option('enableStoreOpenAndCloseHours'),
				'storeOpenForm'=>WR()->get_option('storeOpenFrom'),
				'storeOpenTo'=>WR()->get_option('storeOpenTo'),
				'storeClosed'=>$this->isBetween(WR()->get_option('storeOpenFrom'),WR()->get_option('storeOpenTo')),
				'disableOnlineOrders'=>WR()->get_option('disableOnlineOrders'),
				'disableOnlineOrdersText'=>WR()->get_option('disableOnlineOrdersText'),
			),
			'copyrightText'=>WR()->get_option('copyRight'),
			'privacyLink'=>WR()->get_option('privacyUrl'),
			'termsLink'=>WR()->get_option('termsUrl'),
			'aboutUsLink'=>WR()->get_option('aboutUsUrl'),
			'socialLogin'=>WR()->get_option('socialLogin'),
			'nonce'=>wp_create_nonce('wp_rest'),
			'last_updated'=>$updated,
			);
		}else{
			$settings = array(
				'last_updated'=>$updated,
				'nonce'=>wp_create_nonce('wp_rest'),
			);
		}
		return $this->response($settings);
	}

	    private function isBetween($from, $till) {
        $f = DateTime::createFromFormat('!H:i:s', $from);
        $t = DateTime::createFromFormat('!H:i:s', $till);
        $i = DateTime::createFromFormat('!H:i:s', date( 'H:i:s', current_time( 'timestamp', 0 ) ));
        if ($f > $t) $t->modify('+1 day');
        return ($f <= $i && $i <= $t) || ($f <= $i->modify('+1 day') && $i <= $t);
    }

	
}