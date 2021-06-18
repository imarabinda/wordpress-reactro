<?php

class WR_PushService {





    /**
	 * The single instance of the class.
	 *
	 */
	protected static $instance = null;
    



    /***
     * Temporary store data
     */
    private $data = array();
    
    
    /***
     * return only single instance
     */
    public static function instance() {
		if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
		return self::$instance;
	}



    /***
     * Get tokens using conditions
     */
    public function send($data,$logging = false){

        if(empty($data) || !is_array($data) || !array_key_exists('conditions',$data) || empty($data['conditions']) || !is_array($data['conditions']) || !wr_required_columns(array('token','user_id','platform'),$data['conditions'])){
             return new WP_Error('invalid_notification_init_payload',__("Notification payloads are empty. Check again.",WR_TEXT_DOMAIN),array('status'=>400));
        }

        
        $conditions = $data['conditions'];
        
        if(array_key_exists('roles',$conditions)){
            if(!array_key_exists('user_id',$conditions)){
                $conditions['user_id']=array();
            }
            $conditions['user_id'] = array_merge($conditions['user_id'],$this->get_user_ids_from_roles($conditions['roles']));
        }

        $tokens = WR()->db->get(wr_get_table_name('push'),$conditions,array('token','user_id'));
        
        if(is_array($tokens) && !empty($tokens) && count($tokens) > 0){
            return $this->process_notification($tokens,$data,$logging);
        }else{
            return new WP_Error( 'no_tokens',__('No tokens found using this conditions',WR_TEXT_DOMAIN), array( 'status' => 400 ) );
        }
    }

   
   
   
   
    /***
     * Process tokens to send notification
     */
    public function process_notification($sql,$data,$logging = false){
        $server_key = WR()->get_option('serverKey');
        $message_sender_id  = WR()->get_option('messagingSenderId');
        
        if(empty($server_key) || empty($message_sender_id)){
            return new WP_Error( 'setup_error',__('Push Server Key and Message Sender id is not set.',WR_TEXT_DOMAIN), array( 'status' => 400 ) );
        }
        
        $this->data = $data;
        
        if(array_key_exists('conditions',$this->data)){
            unset($this->data['conditions']);
        }
        
        if(empty($sql) || !is_array($sql) || count($sql) < 1){
            return new WP_Error( 'invalid_params',__('Provide correct information..',WR_TEXT_DOMAIN), array( 'status' => 400 ) );
        }
        $user_col = array_column($sql,'user_id'); 
        $token_col = array_column($sql,'token');
        
        $registration_ids = $user_ids = $failedSubcribers = array();

        if(count($sql) > 300){
            $user_ids = array_chunk($user_col,400);
            $registration_ids = array_chunk($token_col,400);
        }else{
            $user_ids[]= $user_col;
            $registration_ids[] = $token_col;
        }

		$responses = $this->rest_send($registration_ids,$server_key,$message_sender_id);
		
        $multicast_ids=array();
        foreach($responses as $id=>$response){
            if($response['success'] > 0 || $response['failure'] > 0) {
                               
                if(array_key_exists('save',$this->data) && $this->data['save']){
                    if(!empty($user_ids[$id])){
                        $user_ids = array_unique($user_ids[$id]);
                        $this->save_notification($user_ids,$response['multicast_id']);
                    }
                }

                // $message_id[]=$subscribersSend['message_id'];
                if(!empty($response['results'])) {
                    foreach ($response['results'] as $key => $subscribersSend) {
                        if(isset($subscribersSend['error'])) {
                            $failedSubcribers[] = $registration_ids[$id][$key];
                        }
                    }
                }
                $multicast_ids[]=$response['multicast_id'];
            }
        }

        if($logging){
            $this->log_notification($multicast_ids,$data);
        }
        if(!empty($failedSubcribers)) {
            $this->remove_failed_subs($failedSubcribers);
        }

        $this->data = array();
        
        return $multicast_ids;

    }






    /***
     * Log notifications
     * Who which when!
     */
    private function log_notification($multicast_ids,$data){
        $rows=array();
        foreach ($multicast_ids as $multicast_id){
            $rows[]=array(get_current_user_id(),json_encode($data),$multicast_id);
        }
        return WR()->db->multi_insert(wr_get_table_name('logging'),array('send_by','data','multicast_id'),$rows);   
    }





    /****
     * Save notification to user accounts
     */
    private function save_notification($user_ids,$message_id){
        if(!is_array($user_ids) || empty($user_ids)){
            return false;
        }

        if(array_key_exists('priority',$this->data)){
            unset($this->data['priority']);
        }
        
        unset($this->data['save']);
        
        foreach($user_ids as $user_id){
            $rows[] =   array($user_id,$message_id,json_encode($this->data)); 
        }
        
        return WR()->db->multi_insert(wr_get_table_name('notification'),array('user_id','message_id','data'),$rows);
    }





    /*****************
     * Remove Failed subs 
     * 
     */
    private function remove_failed_subs($token){
        return WR()->db->delete(wr_get_table_name('push'),array('token'=>$token));
    }





    /***
     * Process Notification payload with data
     */
    private function process_notification_data($payload){
       
        if(!array_key_exists('title',$payload) || empty($payload['title'])){
            return new WP_Error('invalid_notification_payload',__("Notification data invalid format. Check again.",WR_TEXT_DOMAIN),array('status'=>400));
        }
        
        $notification = array();

		$payload['data']['type']='type';
        if(array_key_exists('data',$payload) && !empty($payload['data'])){
            $notification['data'] = $payload['data'];
			unset($payload['data']);
        }
        
        
        $notification['notification'] = $payload;
// 		unset($notification['notification']['click_action']);
	
        // $notification_keys = array(
        //     'title','body','image'
        // );        
        // foreach($notification_keys as $key){
        //     if(array_key_exists($key,$payload)){
        //         $notification['notification'][$key]= $payload[$key];
        //     }
        // }
        
        
		$notification['collapse_key'] = $payload['title'];


//         $notification['android']=array(
//             'ttl'=>1000,
//             'collapse_key'=> 'asa',
//             'notification'=> $payload,
//             'priority'=> $payload['priority'],
//         );

//         $notification['webpush']=array(
//             'headers'=>array(
//                 'ttl'=>1000
//             ),
//             'data'=>$payload['data'],
//             'notification'=>$payload,
//             'fcm_options'=>array(
//                 'link'=>$payload['click_action'],
//                 'analytics_label'=>$payload['title']
//             ),
//         );

//         $notification['apns']= array(
//             'headers'=>$payload['data'],
//             'payload'=>$payload,
//             'fcm_options'=>array(
//                 "analytics_label"=> $payload['title'],
//                 "image" => $payload['image'] 
//             )
//         );

        $notification['fcm_options']=array(
              "analytics_label"=> $payload['title'],
        );
        $notification['direct_boot_ok']=true;
        

        if(array_key_exists('priority',$payload)){
            $notification['priority'] = $payload['priority'];
        }

        return $notification;
    }



	/***
     * HTTP request to FCM server
     */
	private function rest_send($registration_ids,$server_key,$message_sender_id){
        
        $payload = $this->process_notification_data($this->data);
        
        
        if(is_wp_error( $payload )){
            return $payload;
        }

        $client = new GuzzleHttp\Client([
            'headers' => [
                'Authorization' => 'key='.$server_key,
                'project_id' => $message_sender_id,
            ]
        ]);
        

            
        $url = 'https://fcm.googleapis.com/fcm/send';
        // Create and send the request.
        $body=array();
        
            
        foreach ($registration_ids as $registration_id) {
            $payload['registration_ids'] = $registration_id;
            
            $response = $client->post($url, [
                'json' => $payload
            ]);
            
            // Decode the response body from json to a plain php array.
            $body[] = json_decode($response->getBody()->getContents(), true);
                if ($body === null || json_last_error() !== JSON_ERROR_NONE) {
                    throw new Exception('Failed to json decode response body: '.json_last_error_msg());
                }
        }
        return $body;
	}




    /***
     * Maybe i'm not wrong to update.
     */
    public function maybe_update($data){
        
        if(!array_key_exists('token',$data) || empty($data)){
            return null;
        }
        $rows = WR()->db->get(wr_get_table_name('push'),array('token'=>$data['token']));
        $db_data = $this->process_token_data($data);
        if(is_array($rows) && !empty($rows) && count($rows) > 0 && $data['status'] == 'guest' && $data['user_id'] != 0){
            $db =  WR()->db->update(wr_get_table_name('push'),$db_data,array('token'=>$db_data['token']));
            if(!$db){
                return new WP_Error( 'cannot_update',__('Can\'t update subscription.',WR_TEXT_DOMAIN), array( 'status' => 400 ) );
            }
        }else if(count($rows)==0 && $data['status'] == 'none' || count($rows) == 0 && $data['status'] == 'guest' || count($rows) == 0 && $data['status'] == 'auth'){
            $db=  WR()->db->insert(wr_get_table_name('push'),$db_data);
            if(!$db){
                return new WP_Error( 'cannot_create',__('Can\'t create subscription.',WR_TEXT_DOMAIN), array( 'status' => 400 ) );
            } 
        }
        return true;
    }
    
    
    
    
    
    /***
     * Process tokens payload
     */
    private function process_token_data($data){
        return array(
            'token'=>sanitize_text_field($data['token']),
            'platform'=>sanitize_text_field($data['platform'] ? $data['platform'] : 'web'),
            'user_id'=> sanitize_text_field($data['user_id'] ? $data['user_id'] : get_current_user_id()),
            'updated_at' => date("Y-m-d H:i:s", strtotime("now")),
        );
    }

        /***
         * Get guest tokens 0
         */
        public function get_guest_tokens(){
            return WR()->db->get(wr_get_table_name('push'),array('user_id'=>0));    
        }

        
        public function get_user_tokens($user_id){
            if(empty($user_id) || !is_numeric($user_id)){
                return null;
            }
            return WR()->db->get(wr_get_table_name('push'),array('user_id'=>$user_id));    
        }

}