<?php 



if(!function_exists('wr_get_notifications')){
    function wr_get_notifications($user_id = null){
        $user_id = $user_id ? $user_id : get_current_user_id();
        
        if($user_id == 0 || !$user_id){
            return new WP_Error('require_log_in',__('User ID not found.',WR_TEXT_DOMAIN),array('status'=>400));
        }
        return WR()->db->get(wr_get_table_name('notifications'),array('user_id'=>$user_id),array('data','message_id'));
    }
}



if(!function_exists('wr_delete_notification')){   
    function wr_delete_notification(array $conditions){
        if(!wr_array_keys_exists(array('message_id','user_id'),$conditions)){
            return new WP_Error('missing_condition',__('Provide message_id and user_id',WR_TEXT_DOMAIN),array('status'=>400));
        }
        $conditions['user_id']=$conditions['user_id'] ? $conditions['user_id'] : get_current_user_id();
        return WR()->db->delete(wr_get_table_name('notifications'),$conditions);
    }
}