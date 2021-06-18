<?php
if(!class_exists('WordPress_Reactro_Public')){
class WordPress_Reactro_Public
{

    public function __construct()
    {
    }

    
    public function enqueue_styles()
    {
        wp_enqueue_style(WR_NAME, plugin_dir_url(__FILE__).'css/wordpress-reactro-public.css', array(), WR_VERSION, 'all');
        
    }

   
    public function enqueue_scripts()
    {
        wp_enqueue_script('firebase-app', 'https://www.gstatic.com/firebasejs/8.0.2/firebase-app.js', array(), '8.0.2', true);
        wp_enqueue_script('firebase-messaging', 'https://www.gstatic.com/firebasejs/8.0.2/firebase-messaging.js', array(), '8.0.2', true);     
        wp_enqueue_script(WR_NAME.'-public', plugin_dir_url(__FILE__).'js/wordpress-reactro-public.js', array('jquery'), WR_VERSION, true);
        
        $forJS = array(
            'ajax_url' => admin_url('admin-ajax.php'),
            // Configs
            'projectId'=> WR()->get_option('projectId'),
            'apiKey'=>WR()->get_option('apiKey'),
            'appId'=>WR()->get_option('appId'),
            'messagingSenderId' => WR()->get_option('messagingSenderId'),
            'loggedIn'=>is_user_logged_in() ? 1 : 0,
            'nonce'=>wp_create_nonce('wp_rest'),
            // Blocked
            'deniedText' => __('Notifications are disabled by your browser. Please click on the info icon near your browser URL to adjust notification settings!', WR_TEXT_DOMAIN),

        );
        $forJS['swURL']=content_url().'/plugins/wordpress-reactro/src/public/js/firebase-messaging-sw.js?messagingSenderId='.$forJS['messagingSenderId'].'&appId='.$forJS['appId'].'&apiKey='.$forJS['apiKey'].'&projectId='.$forJS['projectId'];
        


        wp_localize_script(WR_NAME.'-public', 'wordpress_reactro_options', $forJS);
        return true;
       
    }

    public function add_manifest()
    {
            echo '<link rel="manifest" href="/manifest.json">';        
    }

    public function add_popup()
    {

        $popupEnable = WR()->get_option('popupEnable');
        if(!$popupEnable) {
            return false;
        }

        $popupText = WR()->get_option('popupText');
        $popupTextAgree = WR()->get_option('popupTextAgree');
        $popupTextDecline = WR()->get_option('popupTextDecline');


        $popupStyle = WR()->get_option('popupStyle');
        $popupPosition = WR()->get_option('popupPosition');
        $popupBackgroundColor = WR()->get_option('popupBackgroundColor');
        $popupTextColor = WR()->get_option('popupTextColor');
        $popupAgreeColor = WR()->get_option('popupAgreeColor');
        $popupAgreeBackgroundColor = WR()->get_option('popupAgreeBackgroundColor');
        $popupDeclineColor = WR()->get_option('popupDeclineColor');
        $popupDeclineBackgroundColor = WR()->get_option('popupDeclineBackgroundColor');
        $popupLinkColor = WR()->get_option('popupLinkColor');

        $popupCloseIcon = WR()->get_option('popupCloseIcon');
        $popupCloseIconColor = WR()->get_option('popupCloseIconColor');
        $popupCloseIconBackgroundColor = WR()->get_option('popupCloseIconBackgroundColor');

        $renderd = false;
        ?>
        <div class="wordpress-reactro-popup <?php echo $popupStyle . ' ' . $popupPosition ?>" 
            style="background-color: <?php echo $popupBackgroundColor ?>; color: <?php echo $popupTextColor ?>;">

            <div class="wordpress-reactro-popup-container">
                <a href="#" id="wordpress-reactro-popup-close" class="wordpress-reactro-popup-close" style="background-color: <?php echo $popupCloseIconBackgroundColor ?>;">
                    <i style="color: <?php echo $popupCloseIconColor ?>;" class="<?php echo $popupCloseIcon ?>"></i>
                </a>
                <div class="wordpress-reactro-popup-text"><?php echo wpautop($popupText) ?></div>
                <div class="wordpress-reactro-popup-actions">
                    <div class="wordpress-reactro-popup-actions-buttons">
                        <?php if(!empty($popupTextAgree)) { ?>
                            <a href="#" class="wordpress-reactro-popup-agree" style="background-color: <?php echo $popupAgreeBackgroundColor ?>; color: <?php echo $popupAgreeColor ?>;"><?php echo $popupTextAgree ?></a>
                        <?php } ?>
                    
                        <?php if(!empty($popupTextDecline)) { ?>
                            <a href="#" class="wordpress-reactro-popup-decline" style="background-color: <?php echo $popupDeclineBackgroundColor ?>; color: <?php echo $popupDeclineColor ?>;"><?php echo $popupTextDecline ?></a>
                        <?php } ?>
                        <div class="fire-push-clear"></div>
                    </div>
                    <div class="wordpress-reactro-popup-actions-links">
                        <?php if(!empty($popupTextPrivacyCenter) && !empty($privacyCenterPage)) { ?>
                            <a href="<?php echo get_permalink($privacyCenterPage) ?>" class="wordpress-reactro-popup-privacy-center" style="color: <?php echo $popupLinkColor ?>;"><?php echo $popupTextPrivacyCenter ?></a>
                        <?php } ?>

                        <?php if(!empty($popupTextPrivacySettings) && !empty($privacySettingsPopupEnable)) { ?>
                            <a href="#" class="wordpress-reactro-popup-privacy-settings-text wordpress-reactro-open-privacy-settings-modal" style="color: <?php echo $popupLinkColor ?>;"><?php echo $popupTextPrivacySettings ?></a>
                        <?php } ?>

                        <?php if(!empty($cookiePolicyPage) && !empty($popupTextCookiePolicy)) { ?>
                            <a href="<?php echo get_permalink($cookiePolicyPage) ?>" class="wordpress-reactro-popup-read-more" style="color: <?php echo $popupLinkColor ?>;"><?php echo $popupTextCookiePolicy ?></a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

}
}
