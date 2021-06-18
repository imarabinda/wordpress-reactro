<?php
class WR_Notification {
    public function ajax_get_notifications(){
        if($_POST['nonce'] !== wp_verify_nonce( 'wp_rest',$_POST['nonce'])){
            return null;
        }

       $notifications =  wr_get_notifications();
       if(is_wp_error($notifications)){
           wp_send_json_error(array('message'=>__('No notifications found.',WR_TEXT_DOMAIN),404));
       }
       wp_send_json_success( $notifications);
    }
    

    public function ajax_delete_notifications(){
        if($_POST['nonce'] !== wp_verify_nonce( 'wp_rest',$_POST['nonce'])){
            return null;
        }
        if(!isset($_POST['message_id']) || !is_user_logged_in()){
           return null;
        }

       $notifications =  wr_delete_notifications(array('message_id'=>$_POST['message_id'],'user_id'=>get_current_user_id()));
       
       if(is_wp_error( $notifications )){
           wp_send_json_error(array(
                'message'=>__('Can\'t delete notification.',WR_TEXT_DOMAIN)
                ),
                401);
       }

       wp_send_json_success( $notifications);
    }
}