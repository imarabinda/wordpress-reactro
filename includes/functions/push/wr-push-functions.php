<?php

if(!function_exists('wr_insert_token')){
    function wr_insert_token($data){
        if(empty($data) || !is_array($data) || !array_key_exists('token',$data)){
            return new WP_Error('token_missing',__('Token missing, provide token',WR_TEXT_DOMAIN),array('status'=>400));
        }
        try{
            
            $_callback = WR()->push_service->maybe_update($data);
            if(array_key_exists('user_id',$data) && intval($data['user_id']) !=0){
                    $status = 'auth';
                }else{
                    $status = 'guest';
                }
                if($data['status']='none' && in_array($data['platform'],WR()->get_option('enablePush'))  && in_array($data['platform'],WR()->get_option('enableWelcomeNotification'))){
                    wr_send_welcome_notification($data['platform'],$data);
                }
                return array('status'=>$status,'token'=>$data['token']);
            }

        catch(Exception $e){
            return $e;
        }
        
        // return $_callback;
    }
}
    
if(!function_exists('wr_send_welcome_notification')){
    function wr_send_welcome_notification($platform,$tokenDetails){
        $action = $platform.'WelcomeNotification';
        $data = wr_prepare_post_notify_data('', $action);
        return WR()->push_service->process_notification(array($tokenDetails),$data);
    }
}

if(!function_exists('wr_send_custom_notification')){
    function wr_send_custom_notification() {
            $action = 'customNotification';
            $data = wr_prepare_post_notify_data('', $action);
            $ret = wr_push_prepare_send($action,$data);
            
            if(!is_wp_error($ret)){
                
                return $ret;
                wr_clear_data(array(
                    $action.'Title',
                    $action.'Body',
                    $action.'Icon',
                    $action.'Image',
                    $action.'Devices',
                    $action.'UserStatus',
                    $action.'Rules',
                    $action.'Users',
                    $action.'Roles',
                    $action.'ClickAction',
                    $action.'ClickActionHybrid',
                    $action.'ClickActionURL',
                    $action.'ClickActionProduct',
                    $action.'ClickActionBlog',
                    $action.'Priority',
                    $action.'Save',
                ));
            }else{
                return $ret;
                WR_Helper::admin_notice('heu');
            }
            // wp_redirect(get_admin_url() . 'admin.php?page='.WR_OPTION_NAME);
            exit();
    }
}



if(!function_exists('wr_clear_data')){
    function wr_clear_data($data){
        $options = get_option('wordpress_reactro_options');
        foreach($data as $key){
            if(array_key_exists($key,$options)){
                unset($options[$key]);
            }
        }
        update_option('wordpress_reactro_options', $options);
        return true;
    }

}
   



if(!function_exists('wr_push_prepare_send')){
    function wr_push_prepare_send($action,$data){
            if(empty($action) || is_array($action)){
                return new WP_Error('not_valid_action',__("Provide action.",WR_TEXT_DOMAIN),array('status'=>400)); 
            }
            $conditions = wr_push_generate_condition($action);
            
            if(is_wp_error( $conditions)){
                return $conditions;
            }
            $data['conditions'] = $conditions;
            return WR()->push_service->send($data,true);    
        }
}
      




if(!function_exists('wr_push_generate_condition')){
        function wr_push_generate_condition($action){
            $push_enabled_for = WR()->get_option('enablePush');
            
            if(empty($push_enabled_for)){
                return new WP_Error('push_disabled',__("Push notifications not enabled.",WR_TEXT_DOMAIN),array('status'=>400)); 
            }
            
            $conditions = array();
            $devices = WR()->get_option($action . 'Devices');
            if(!empty($devices)){
                $conditions['platform']= array_intersect($push_enabled_for, $devices);;
            }else{
                return new WP_Error('no_platform',__("Select at least one platform.",WR_TEXT_DOMAIN),array('status'=>400));         
            }
            $status = WR()->get_option($action.'UserStatus');
            if(!empty($status)){
                $conditions['user_id']=array();
            }
            
            if(!empty($status) && in_array('guest',$status)){
                $conditions['user_id']=array_merge($conditions['user_id'],array('0'));
            }
            if(!empty($status) && in_array('logged',$status)){
                $select = WR()->get_option($action.'Rules');
                if($select == '1'){
                    $user_ids = WR()->get_option($action.'Users');
                    if(!empty($user_ids)){
                        $conditions['user_id']=array_merge($conditions['user_id'],$user_ids);
                    }else{
                        unset($conditions['user_id']);
                    }
                }else if($select == '2'){
                    $roles = WR()->get_option($action . 'UserRoles');
                    if(!empty($roles) && is_array($roles)){
                        $conditions['roles'] =$roles; 
                    }
                }
            }

            return $conditions;
        }


}
    
if(!function_exists('wr_prepare_post_notify_data')){
    function wr_prepare_post_notify_data($post,$action,$comment=array()){
            $data = array();
            $array=array();
            $tmp = array();
            if(is_array($post) || !empty($post)){
            
            $data['data']['post_type']=get_post_type($post->ID);
            
            $post_metas = get_post_meta($post->ID, '', true);
            
            foreach ($post_metas as $post_meta_key => $post_meta) {
                $tmp[$post_meta_key] = isset($post_meta[0]) ? $post_meta[0] : '';
            }
            $post_metas = $tmp;

            $array = array_merge($post_metas, (array) $post, (array) $comment);

            $array['post_author'] = get_userdata($array['post_author'])->user_nicename;
            $array['post_content'] = strip_tags( preg_replace("/\[[^\]]+\]/", '', $array['post_content']) );
            $array['post_excerpt'] = strip_tags( preg_replace("/\[[^\]]+\]/", '', $array['post_excerpt']) );

            if($action == "woocommerceNewOrder") {
                $array['post_url'] = admin_url() . 'post.php?post=' . $data['ID'] . '&action=edit';
            } else {
                $array['post_url'] = get_permalink($array['ID']);
            }
            
            foreach ($array as $key => $array) {

                $tmp['{' . $key . '}'] = $array;
            }
        }
        
        
        $data['title'] = strtr(WR()->get_option($action . 'Title'), $array);
        $data['body'] = strtr(WR()->get_option($action . 'Body'), $array);
       
        if(WR()->get_option($action . 'UseFeatureImageIcon') && has_post_thumbnail($array['ID'])) {
            $data['icon'] = get_the_post_thumbnail_url($array['ID']);
        } elseif(isset( WR()->get_option($action . 'Icon')['url']) && !empty(WR()->get_option($action . 'Icon')['url'])) {
            $data['icon'] = WR()->get_option($action . 'Icon')['url'];
        }


        if(WR()->get_option($action . 'UseFeatureImageImage') && has_post_thumbnail($array['ID'])) {
            $data['image'] = get_the_post_thumbnail_url($array['ID']);
        } elseif(isset( WR()->get_option($action . 'Image')['url']) && !empty(WR()->get_option($action . 'Image')['url'])) {
            $data['image'] = WR()->get_option($action . 'Image')['url'];
        }
        
        if(WR()->get_option($action.'Color')){
                $data['color']=WR()->get_option($action.'Color');
        }

        if(WR()->get_option($action.'Priority')){
            $data['priority']=WR()->get_option($action.'Priority');
        }


        //actions section
        $devices = WR()->get_option($action . 'Devices');
        
        if((in_array('android',$devices) || in_array('ios',$devices))){
            

             

            $data['data']['click_action'] = WR()->get_option($action . 'ClickActionHybrid');
            
            $param = 'default';
            
             
            if($data['data']['click_action']=='default' && !empty($comment) && count($comment) > 0){
                $data['data']['click_action'] = 'comment';
                $param = $post->ID;
            }
            
            if($data['data']['click_action']=='default' && !empty($post) && empty($comment)){
                $data['data']['click_action'] = 'post';
                $param= $post->ID;
            } 

            if($data['data']['click_action'] == 'link_extension' || $data['data']['click_action'] == 'link_webview'){
            $param = strtr(WR()->get_option($action . 'ClickActionURL'),$array);
            }

            if($data['data']['click_action'] == 'blog'){
            $param = WR()->get_option($action . 'ClickActionBlog');
            }

            if($data['data']['click_action'] == 'product'){
            $param = WR()->get_option($action . 'ClickActionProduct');
            }
            
            if($data['data']['click_action'] == 'custom'){
            $param = WR()->get_option($action . 'ClickActionCustom');
            }
            
            $data['data']['click_param']=$param;
        }
        
		
        if(WR()->get_option($action . 'ClickAction')){
            $data['data']['click_action_web'] = strtr(WR()->get_option($action . 'ClickAction'), $array);
        }else{
            $data['data']['click_action_web'] = site_url();
        }
        
        
        if(WR()->get_option($action.'Save')){
                $data['save']=true;
        }
        
        
        $data['data']['require_interaction'] = WR()->get_option($action.'RequireInteraction') ? true : false;
        
        $data['tag'] = current_time('timestamp');
        
        return $data;
    }
}


if(!function_exists('wr_prepare_order_notify_data')){
    function wr_prepare_order_notify_data($post_id,$action,$comment=array()){

     return $data;   
    }   
}



if(!function_exists('wr_push_action_buttons_validate')){
function wr_push_action_buttons_validate($field, $values, $existing_value){	
   
    $return['value'] = $values;
    
   $options =array();

   foreach($values as $value){

       $options[] = explode('|',$value);
       
   }
   $field['id']=$field['id'].'-0';
	       // $return['warning'] = $field;
			$field['msg']      = json_encode($field);
			$return['warning'] = $field;
	
		return $return;
}
}



if(!function_exists('wr_get_custom_posts')){
    function wr_get_custom_posts(){
        $args = array(
        'post_type'      => WR()->get_option('customNotificationClickActionPostType'),
        'posts_per_page' => 20,
        'orderby'        => 'title',
        'order'          => 'ASC',
    );

$posts = get_posts($args);

        return $posts;
    }
}

if(!function_exists('wr_push_get_available_devices')){
function wr_push_get_available_devices(){	
    $push = WR()->get_option('enablePush');
    $devices =array();
    if(in_array('web',$push)){
        $devices['web']='Web';
    }
    if(in_array('android',$push)){
        $devices['android']='Android';
    }
    if(in_array('ios',$push)){
        $devices['ios']='iOS';
	}

    return $devices;
}
}