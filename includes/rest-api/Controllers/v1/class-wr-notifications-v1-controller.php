<?php
class WR_REST_Notifications_V1_Controller extends REST_Base_v1 {


	/**
	 * Route base.
	 *
	 * @var string
	 */
	protected $rest_base = 'notification';


    public function register_routes(){

        register_rest_route( $this->namespace, '/' . $this->rest_base, array(
			
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_notification' ),
				'permission_callback' => function( WP_REST_Request $request ) {
                    if (wp_verify_nonce($request['_wpnonce'],'wp_rest') && is_user_logged_in()) {
                        return true;
                    } else {
                        return false;
                    }
                },
			),array(
				'methods'             => WP_REST_Server::DELETABLE,
				'callback'            => array( $this, 'delete_notification' ),
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
    /**
     * Delete notification
     */
    public function delete_notification($request){
        $check = $this->check_keys('message_id',$request->get_params());
        if($check && is_user_logged_in()){
            $deleted = wr_delete_notification(
                array(
                'message_id'=>$request->get_param('message_id'),
                'user_id'=>get_current_user_id()
                )
            );
            return $this->response($deleted);
        }else{
            $this->error('invalid_args',__('Can\'t delete notification.',WR_TEXT_DOMAIN),401);
        }
    }
	/**
     * Get Notification
     */
	public function get_notification($request){
		$notifications = wr_get_notifications();
		if(!is_wp_error($notifications)){
			return $this->response($notifications);
		}
		return $this->error($notifications->status,$notifications->message,$notifications->status_code);
	}
}