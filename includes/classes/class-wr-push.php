<?php 

namespace Mrlazyfox\Reactro\Push;

class WR_Push {


    public function __construct(){

        add_action("wp_ajax_push_update", array($this,'ajax_insert_token'));
        add_action("wp_ajax_nopriv_push_update", array($this,'ajax_insert_token'));

        if(!is_array(WR()->get_option('enablePush')) || count(WR()->get_option('enablePush')) < 1){
            return;
        }

        add_action( 'save_post', array($this, 'notifyOnPublishOrUpdate'), 10, 3);
        add_action( 'comment_post', array($this, 'notifyOnComment'), 10, 2);


        if(WR()->get_option('woocommerceNewProduct')) {
            add_action( 'save_post', array($this, 'notifyNewProduct'), 10, 3);
        }

        if(WR()->get_option('woocommercePriceDrop')) {
            add_action( 'update_post_meta', array($this, 'notifyPriceDrop'), 10, 4);
        }

        if(WR()->get_option('woocommerceNewOrder')) {
            add_action('woocommerce_new_order', array($this, 'notifyOnNewOrder'));
        }

        if(WR()->get_option('woocommerceLowStock')) {
            add_action( 'woocommerce_low_stock', array($this, 'notifyLowStock') );
            add_action( 'woocommerce_no_stock',  array($this, 'notifyLowStock') );
        }
        if(WR()->get_option('onCompleteOrder')) {
            add_action( 'woocommerce_order_status_completed', array($this,'wpOnCompleteOrder'));
        }
        if(WR()->get_option('onOrderPending')) {
            add_action( 'woocommerce_order_status_pending', array($this,'wpOnOrderPending'));
        }
        if(WR()->get_option('onOrderFailed')) {
            add_action( 'woocommerce_order_status_failed', array($this,'wpOnOrderFailed'));
        }
        if(WR()->get_option('onOrderHold')) {
            add_action( 'woocommerce_order_status_on-hold', array($this,'wpOnOrderHold'));
        }
        if(WR()->get_option('onOrderProcessing')) {
            add_action( 'woocommerce_order_status_processing', array($this,'wpOnOrderProcessing'));
        }
        if(WR()->get_option('onOrderRefund')) {
            add_action( 'woocommerce_order_status_refunded', array($this,'wpOnOrderRefund'));
        }
        if(WR()->get_option('onOrderCancelled')) {
            add_action( 'woocommerce_order_status_cancelled', array($this,'wpOnOrderCancelled'));
        }
        if(WR()->get_option('onOrderPaymentComplete')) {
            add_action( 'woocommerce_payment_complete', array($this,'wpOnOrderPaymentComplete'));
        }

    }



    
	public function ajax_insert_token(){
        
        if (!defined('DOING_AJAX') || !DOING_AJAX || wp_verify_nonce('wp_rest',$_POST['nonce'])) {
            wp_send_json_error(array(
                'message'=>__('Sorry, you are not allowed to do that.',WR_TEXT_DOMAIN)
                ),
                401);
        }
        $data = [ 'token' => sanitize_text_field($_POST['token']),'status'=>sanitize_text_field($_POST['status']),'platform'=>'web','user_id'=>get_current_user_id()] ;
        $_callback = wr_insert_token($data);
        if(!is_wp_error($_callback)){
            wp_send_json_success($_callback);
        }else{
            wp_send_json_error($_callback);   
        }
    }

    
    public function notifyOnPublishOrUpdate($post_id, $post, $update)
    {
        if($post->post_type == 'revision') {
            return false;
        }

        if($post->post_status == 'draft' || $post->post_status == 'auto-draft' || $post->post_status == 'trash') {
            return false;
        }

        if ( !get_post_meta( $post_id, 'firstpublish', true ) ) {
            update_post_meta( $post_id, 'firstpublish', true );
            $update = false;
        }

        for ($i=1; $i <= 4; $i++) {

            $action = 'wrCustomNotification' . $i;

            if(!WR()->get_option($action . 'Enabled')) {
                continue;
            }

            $onUpdate = WR()->get_option($action . 'OnUpdate');
            if(!$onUpdate && $update) {
                continue;
            }

            $onPublish = WR()->get_option($action . 'OnPublish');
            if(!$onPublish && !$update) {
                continue;
            }

            $allowedPostTypes = WR()->get_option($action . 'PostTypes');
            if(is_array($allowedPostTypes) && !in_array($post->post_type, $allowedPostTypes)) {
                continue;
            }

            $data = wr_prepare_post_notify_data($post, $action);
            if(!$data && !is_array($data)) {
                continue;
            }
            return wr_push_prepare_send($action,$data);
        }
    }


    
    public function notifyOnComment($comment_ID, $comment_approved)
    {
        if( 1 !== $comment_approved ){
            return false;
        }

        for ($i=0; $i <= 4; $i++) {

            $action = 'wrCustom'.$i.'Notification';

            if(!WR()->get_option($action . 'Enabled')) {
                continue;
            }

            if(!WR()->get_option($action . 'OnNewComment')) {
                continue;
            }
            $comment = get_comment($comment_ID, ARRAY_A);
            $post = get_post($comment['comment_post_ID']);
            $data = wr_prepare_post_notify_data($post, $action, $comment);

            if(!$data && !is_array($data)) {
                continue;
            };

            return wr_push_prepare_send($action,$data);    
        }
    }
    
}
new WR_Push();