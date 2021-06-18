<?php

class WR_REST_Push_V1_Controller extends REST_Base_v1 {


	/**
	 * Route base.
	 *
	 * @var string
	 */
	protected $rest_base = 'push';


    public function register_routes(){

        register_rest_route( $this->namespace, '/' . $this->rest_base, array(
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'insert_token' ),
				'permission_callback' => function( WP_REST_Request $request ) {
                    if (wp_verify_nonce($request['_wpnonce'],'wp_rest')) {
                        return true;
                    } else {
                        return false;
                    }
                },
			),
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'send_custom_notification' ),
				'permission_callback' => function( WP_REST_Request $request ) {
                    if (wp_verify_nonce($request['_wpnonce'],'wp_rest') && is_user_logged_in()) {
                        return true;
                    } else {
                        return false;
                    }
                },
			),
		) );
	}
	
	/***
	 * Send custom notification using REST API
	 */
	public function send_custom_notification($request){
		$query = wr_send_custom_notification();
		if(!is_wp_error($query)){
			return $this->response($query);
		}
		return $query;
	}

	public function insert_token($request){
		$data = $this->process_data(array('token','platform','status','user_id'),$request);
		$data['user_id']=$data['user_id'] ? $data['user_id'] : get_current_user_id();
		$_callback = wr_insert_token($data);
		if(!is_wp_error($_callback)){
			return $this->response($_callback);
		}
		return $this->error('push_error','Can\'t subscribe to push notifications.');
	}
}