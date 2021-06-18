<?php

  //start Push
    $section_id='pushNotification';
    Redux::set_section( $opt_name, array(
		'title'  => __( 'Push Notification', WR_TEXT_DOMAIN ),
		'id'     => $section_id,
		'desc'   => __( 'Push Notification', WR_TEXT_DOMAIN ),
		'icon'   => 'el el-bell',
	) );

	//start of credentials
	$section_id='credentials';
	Redux::set_section($opt_name, array(
		'title'      => __('Credentials', WR_TEXT_DOMAIN),
		'id'         => $section_id,
		'desc'       => __('Please read our documentation how to get your Firebase API Credentials here.', WR_TEXT_DOMAIN),
		'subsection' => true,
	));

	Redux::set_fields( $opt_name, $section_id, array(
		array(
					'id'       => 'serverKey',
					'type'     => 'text',
					'title'    => __('Server Key', WR_TEXT_DOMAIN),
					'default'  => '',
				),
				array(
					'id'       => 'apiKey',
					'type'     => 'text',
					'title'    => __('Api Key', WR_TEXT_DOMAIN),
					'default'  => '',
					),
				array(
					'id'       => 'projectId',
					'type'     => 'text',
					'title'    => __('Project Id', WR_TEXT_DOMAIN),
					'default'  => '',
					),	
				array(
					'id'       => 'messagingSenderId',
					'type'     => 'text',
					'validate' => array(
									'numeric'
									),
					'title'    => __('Messaging Sender Id', WR_TEXT_DOMAIN),
					'default'  => '',
					),
				array(
					'id'       => 'appId',
					'type'     => 'text',
					'title'    => __('App Id', WR_TEXT_DOMAIN),
					'default'  => '',
					),
	) );
	//end of credentials

	$section_id = 'push-settings';
    Redux::set_section($opt_name, array(
		'title'      => __('General Settings', WR_TEXT_DOMAIN),
		'id'         => $section_id,
		'subsection' => true,
	));

	//push notification general settings
	Redux::set_fields($opt_name,$section_id,array(
		array(
				'id'       => 'enablePush',
				'type'     => 'select',
				'multi'=>true,
				'title'    => __('Enable Push Notifications ', WR_TEXT_DOMAIN),
				'options'=>array(
					'web'=>'Web',
					'android'=>'Android',
					'ios'=>'iOS'
				),
				'default'  => array('web','ios','android'),
			),
			
			
		));


	//welcome notification
	$section_id = 'welcome-notification';
    Redux::set_section($opt_name, array(
		'title'      => __('Welcome Notification', WR_TEXT_DOMAIN),
		'id'         => $section_id,
		'subsection' => true,
	));

	Redux::set_fields($opt_name,$section_id,array(
			 array(
				'id'       => 'enableWelcomeNotification',
                'type'     => 'select',
				'multi'=>true,
				'data'=>'callback',
				'args' => array('wr_push_get_available_devices'),
				'ajax'=>true,
				'title'    => __('Enable welcome notification', WR_TEXT_DOMAIN),
                'required'=> array('enablePush','!=',''),
			)
			
		));

	//welcome notification web
	Redux::set_fields($opt_name,$section_id,array(			
			array(
				'id' => 'webWelcomeNotification',
				'type' => 'info',
				'indent' => true,
				'style' => 'info',
				'title' => __( 'Web Push Section' , WR_TEXT_DOMAIN ),
				'required'=> array(array('enableWelcomeNotification','=','web'),array('enablePush','=','web')),
			), 
            array(
				'id'       => 'webWelcomeNotificationTitle',
				'type'     => 'text',
				'title'    => __('Web Welcome Notification Title.', WR_TEXT_DOMAIN),
				'default'  => __('It\'s now or NEVER!,',WR_TEXT_DOMAIN),
				'required'=> array(array('enableWelcomeNotification','=','web'),array('enablePush','=','web')),
            
            ),
            array(
				'id'       => 'webWelcomeNotificationBody',
				'type'     => 'text',
				'title'    => __('Web Welcome Notification Body.', WR_TEXT_DOMAIN),
				'default'  => __('We\'ve got your back. ,',WR_TEXT_DOMAIN),'required'=> array(array('enableWelcomeNotification','=','web'),array('enablePush','=','web')),
            
            ),
            array(
				'id'       => 'webWelcomeNotificationIcon',
				'type'     => 'media',
				'library_filter' => array(
        			'jpg','png'
				),
				'title'    => __('Web Welcome Notification Icon.', WR_TEXT_DOMAIN),
				'required'=> array(array('enableWelcomeNotification','=','web'),array('enablePush','=','web')),
            ),
            array(
				'id'       => 'webWelcomeNotificationImage',
				'type'     => 'media',
				'library_filter' => array(
        			'jpg','png'
				),
				'title'    => __('Web Welcome Notification Image.', WR_TEXT_DOMAIN),
				'required'=> array(array('enableWelcomeNotification','=','web'),array('enablePush','=','web')),
            ),
            array(
				'id'       => 'webWelcomeNotificationClickAction',
				'type'     => 'text',
				'validate' => array(
        							'url'
								),
				'title'    => __('Web Welcome Notification Click Action Url.', WR_TEXT_DOMAIN),
				'default'  => __(site_url(),WR_TEXT_DOMAIN),
				'required'=> array(array('enableWelcomeNotification','=','web'),array('enablePush','=','web')),
            
			),
		));

		//android welcome push notifications
		Redux::set_fields($opt_name,$section_id,array(			
			array(
				'id' => 'androidWelcomeNotification',
				'indent' => true,
				'style' => 'info',
				'type' => 'info',
				'title' => __( 'Android Push Section' , WR_TEXT_DOMAIN ),
				'required'=> array(array('enableWelcomeNotification','=','android'),array('enablePush','=','android')),
			), 
            array(
				'id'       => 'androidWelcomeNotificationTitle',
				'type'     => 'text',
				'title'    => __('Android Welcome Notification Title.', WR_TEXT_DOMAIN),
				'default'  => __('It\'s now or NEVER!,',WR_TEXT_DOMAIN),
                'required'=> array(array('enableWelcomeNotification','=','android'),array('enablePush','=','android')),
            
            ),
            array(
				'id'       => 'androidWelcomeNotificationBody',
				'type'     => 'text',
				'title'    => __('Android Welcome Notification Body.', WR_TEXT_DOMAIN),
				'default'  => __('We\'ve got your back. ,',WR_TEXT_DOMAIN),
                'required'=> array(array('enableWelcomeNotification','=','android'),array('enablePush','=','android')),
            
            ),
            array(
				'id'       => 'androidWelcomeNotificationIcon',
				'type'     => 'media',
				'library_filter' => array(
        			'jpg','png'
				),
				'title'    => __('Android Welcome Notification Icon.', WR_TEXT_DOMAIN),
				'required'=> array(array('enableWelcomeNotification','=','android'),array('enablePush','=','android')),
            ),
            array(
				'id'       => 'androidWelcomeNotificationImage',
				'type'     => 'media',
				'library_filter' => array(
        			'jpg','png'
				),
				'title'    => __('Android Welcome Notification Image.', WR_TEXT_DOMAIN),
				'required'=> array(array('enableWelcomeNotification','=','android'),array('enablePush','=','android')),
            
            ),
            array(
				'id'       => 'androidWelcomeNotificationClickAction',
				'type'     => 'text',
				'validate' => array(
        							'url'
								),
				'title'    => __('Android Welcome Notification Click Action Url.', WR_TEXT_DOMAIN),
				'default'  => __(site_url(),WR_TEXT_DOMAIN),
                'required'=> array(array('enableWelcomeNotification','=','android'),array('enablePush','=','android')),
            
			),array(
				'id'       => 'androidWelcomeNotificationClickActionURL',
				'type'     => 'text',
				'validate' => array(
        							'url'
								),
				'title'    => __('Provide Click Action Url.', WR_TEXT_DOMAIN),
				'default'  => __(site_url(),WR_TEXT_DOMAIN),
				'required'=> array(array('enableWelcomeNotification','=','android'),array('enablePush','=','android',array('androidWelcomeNotificationClickAction','=',array('link_extension','link_webview')))),
			)
		));
		
		//start of ios welcome notification
		Redux::set_fields($opt_name,$section_id,array(			
			array(
				'id' => 'iosWelcomeNotification',
				'type' => 'info',
				'indent' => true,
				'style' => 'info',
				'title' => __( 'iOs Push Section' , WR_TEXT_DOMAIN ),
				'required'=> array(array('enableWelcomeNotification','=','ios'),array('enablePush','=','ios')),
			), 
            array(
				'id'       => 'iosWelcomeNotificationTitle',
				'type'     => 'text',
				'title'    => __('iOS Welcome Notification Title.', WR_TEXT_DOMAIN),
				'default'  => __('It\'s now or NEVER!,',WR_TEXT_DOMAIN),
                'required'=> array(array('enableWelcomeNotification','=','ios'),array('enablePush','=','ios')),
            
            ),
            array(
				'id'       => 'iosWelcomeNotificationBody',
				'type'     => 'text',
				'title'    => __('iOS Welcome Notification Body.', WR_TEXT_DOMAIN),
				'default'  => __('We\'ve got your back. ,',WR_TEXT_DOMAIN),
                'required'=> array(array('enableWelcomeNotification','=','ios'),array('enablePush','=','ios')),
            
            ),
            array(
				'id'       => 'iosWelcomeNotificationIcon',
				'type'     => 'media',
				'library_filter' => array(
        			'jpg','png'
				),
				'title'    => __('iOS Welcome Notification Icon.', WR_TEXT_DOMAIN),
				'required'=> array(array('enableWelcomeNotification','=','ios'),array('enablePush','=','ios')),
            ),
            array(
				'id'       => 'iosWelcomeNotificationImage',
				'type'     => 'media',
				'library_filter' => array(
        			'jpg','png'
				),
				'title'    => __('iOS Welcome Notification Image.', WR_TEXT_DOMAIN),
				'required'=> array(array('enableWelcomeNotification','=','ios'),array('enablePush','=','ios')),
            
            ),
            array(
				'id'       => 'iosWelcomeNotificationClickAction',
				'type'     => 'text',
				'validate' => array(
        							'url'
								),
				'title'    => __('iOS Welcome Notification Click Action Url.', WR_TEXT_DOMAIN),
				'default'  => __(site_url(),WR_TEXT_DOMAIN),
                'required'=> array(array('enableWelcomeNotification','=','ios'),array('enablePush','=','ios')),
            
			),array(
				'id'       => 'iosWelcomeNotificationClickActionURL',
				'type'     => 'text',
				'validate' => array(
        							'url'
								),
				'title'    => __('Provide Click Action Url.', WR_TEXT_DOMAIN),
				'default'  => __(site_url(),WR_TEXT_DOMAIN),
				'required'=> array(array('enableWelcomeNotification','=','ios'),array('enablePush','=','ios',array('iosWelcomeNotificationClickAction','=',array('link_extension','link_webview')))),
			)
		));
		
	//end general settings

	$sections = array(
		'custom'=>
				array(
					'fields'=>'all',
					'title'=> 'Custom Notification',
					'subsection'=>true,
					'desc'=>'Description',
					),
		'wrWoocommerce'=>
				array(
					'sub_sections'=>
							array(
								'wrWoocommercePriceDrop'=>
									array(
										'exclude_fields'=>
											array(
												'send'
												),
										),
								'wrWoocommerceNewOrder'=>
									array(
										'exclude_fields'=>
											array(
												'send'
											),
									),
								'wrWoocommerceNewProduct'=>
									array(
										'exclude_fields'=>
											array(
												'send'
											),
									)		
								),
					// 'icon'=>'icon',
					'title'=> 'WooCommerce Notifications',
					'subsection'=>false,
					'desc'=>'Description',
			),
			'wrCustom'=>array(		
				'sub_sections'=> array(),
					// 'icon'=>'icon',
					'title'=> 'Custom Notifications',
					'subsection'=>false,
					'desc'=>'Description',
			)
		
		);

		$push = array();
		for($i=1;$i<5;$i++){
			$action = 'wrCustom'.$i;
			 $push[$action] = 
			 		array(
						'exclude_fields'=>
								array(
									'send'
								),
						'title'=>'Custom Notification '.$i
							);				
		}

	$sections['wrCustom']['sub_sections'] = $push;
		
	Redux::set_sections($opt_name, wr_create_sections($sections));


	
	function wr_notification_fields($field_name){
		return array(
		'title' => array(
				'id'       => $field_name.'NotificationTitle',
				'type'     => 'text',
				'title'    => __('Notification Title', WR_TEXT_DOMAIN),
				'default'  => '', 
				'required'=> array('enablePush','!=',''),
			),
		'body'=> array(
				'id'       => $field_name.'NotificationBody',
				'type'     => 'text',
				'title'    => __('Notification Body', WR_TEXT_DOMAIN),
				'default'  => '',
				'required'=> array('enablePush','!=',''),
			),
			
			'feature_image_icon' =>array(
				'id'       => $field_name.'NotificationUseFeatureImageIcon',
				'type'     => 'checkbox',
				'title'    => __('Use Featured image as icon?', WR_TEXT_DOMAIN),
				'required'=> array('enablePush','!=',''),
			),
		'icon'=>	array(
				'id'       => $field_name.'NotificationIcon',
				'type'     => 'media',
				'title'    => __('Notification Icon', WR_TEXT_DOMAIN),
				'library_filter' => array(
        			'jpg','png'
				),
				'required'=> array(array('enablePush','!=',''),array($field_name.'NotificationUseFeatureImageIcon','=','false'))
			),
			
		'feature_image'=>array(
				'id'       => $field_name.'NotificationUseFeatureImageImage',
				'type'     => 'checkbox',
				'title'    => __('Use Featured image?', WR_TEXT_DOMAIN),
				'required'=> array('enablePush','!=',''),
			),
		'image'=>array(
				'id'       => $field_name.'NotificationImage',
				'type'     => 'media',
				'title'    => __('Notification Image', WR_TEXT_DOMAIN),
				'library_filter' => array(
        			'jpg','png'
				),
				'required'=> array(array('enablePush','!=',''),array($field_name.'NotificationUseFeatureImageImage','=','false'))
			),
		'color'=>array(
				'id'       => $field_name.'NotificationColor',
				'type'     => 'color',
				'title'    => __('Notification Color', WR_TEXT_DOMAIN),
				'validate' => array(
        				'color'
    			),
				'required'=> array('enablePush','!=',''),
			),
	
		'devices'=>array(
                'id'       => $field_name.'NotificationDevices',
				'type'     => 'select',
				'multi'=>true,
				'data'=>'callback',
				'args' => array('wr_push_get_available_devices'),
				'ajax'=>true,
                'title'    => __('Devices', WR_TEXT_DOMAIN),
                'subtitle' => __('Select targeting devices. Without devices notification can\'t be send.', WR_TEXT_DOMAIN),
				'required'=> array('enablePush','!=',''),
			),

		'user_status'=>array(
                'id'       => $field_name.'NotificationUserStatus',
				'type'     => 'select',
				'multi'=>true,
                'title'    => __('User Status', WR_TEXT_DOMAIN),
                'subtitle' => __('Select targeting user status.Empty Means all devices.', WR_TEXT_DOMAIN),
                'options'  => array(
                    'logged' => 'Logged In',
                    'guest' => 'Guest',
					),
				'required'=> array('enablePush','!=',''),
			),
			
        'rules'=>array(array(
                'id'       => $field_name.'NotificationRules',
                'type'     => 'radio',
                'title'    => __('Which Rule?', WR_TEXT_DOMAIN),
                'subtitle' => __('Select type of user to target.', WR_TEXT_DOMAIN),
				'options'  => array(
                    '1' => 'Send using custom user selection',
                    '2' => 'Send using role based selection',
                    ),
				'default' => '1',
				'required'=>array($field_name.'NotificationUserStatus','=','logged'),	
			),
			
			array(
                'id'       => $field_name.'NotificationUsers',
				'type'     => 'select',
                'title'    => __('Select users', WR_TEXT_DOMAIN),
                'subtitle' => __('Select targeting users. Empty Means all users.', WR_TEXT_DOMAIN),
				'data' => 'users',
				'multi' => true,
    			'ajax' => true,
				'required'=>array($field_name.'NotificationRules','=','1'),
			),
			
            array(
                'id'       => $field_name.'NotificationRoles',
				'type'     => 'select',
				'multi'=>true,
                'title'    => __('Select roles', WR_TEXT_DOMAIN),
                'subtitle' => __('Select targeting roles. Empty Means all roles.', WR_TEXT_DOMAIN),
				'data' => 'roles',
    			'ajax' => true,
				'required'=>array($field_name.'NotificationRules','=','2'),
			)),
		'click_action'=>array(
				'id'       => $field_name.'NotificationClickAction',
				'type'     => 'text',
				'title'    => __('Notification Click Action URL (WEB)', WR_TEXT_DOMAIN),
				'default'  => site_url(),
				'validate' => array(
									'url'
								),
				'required'=>array(array(
					$field_name.'NotificationDevices','=','web'
				))
			),
			
		'click_action_hybrid'=>array(array(
				'id'       => $field_name.'NotificationClickActionHybrid',
				'type'     => 'select',
				'multi'=>false,
				'title'    => __('Notification Click Action (IOS and Android)', WR_TEXT_DOMAIN),
				'options'=>array(
					'link_extension'=>__('Open in Browser (URL)',WR_TEXT_DOMAIN),
					'link_webview'=>__('Open in App Browser (URL)',WR_TEXT_DOMAIN),
					// 'category' => __('Category',WR_TEXT_DOMAIN),
                    'blog' => __('Blog',WR_TEXT_DOMAIN),
                    'product' => __('Product',WR_TEXT_DOMAIN),
                    // 'referral'=>__('Refer and Earn',WR_TEXT_DOMAIN),
                    'default'=>__('Default App Screen',WR_TEXT_DOMAIN),
                    'custom' => __('Enter Custom Route ID',WR_TEXT_DOMAIN),
				),
				'default'  => 'default',
				'required'=>array(array(
					$field_name.'NotificationDevices','=',array('ios','android')
				))
			),

			array(
				'id'       => $field_name.'NotificationClickActionURL',
				'type'     => 'text',
				'title'    => __('Notification Click Action URL (Android & iOS)', WR_TEXT_DOMAIN),
				'default'  => '',
				'validate' => array(
									'url'
								),
				'required'=>array(array(
					$field_name.'NotificationClickActionHybrid','=',array('link_extension','link_webview')
				))
			),
			
			array(
				'id'       => $field_name.'NotificationClickActionProduct',
				'type'     => 'text',
				'title'    => __('Notification Click Action Provide Product ID (Android & iOS)', WR_TEXT_DOMAIN),
				'data'  => 'posts',
				'args'=>array(
					'post_type'=>'product'
				),
				'ajax'=>true,
				'validate' => array(
        							'numeric'
								),
				'required'=>array(array(
					$field_name.'NotificationClickActionHybrid','=',array('product')
				))
			),
			array(
				'id'       => $field_name.'NotificationClickActionBlog',
				'type'     => 'select',
				'title'    => __('Notification Click Action Select Which blog (Android & iOS)', WR_TEXT_DOMAIN),
				'default'  => '',
				'data' => 'posts',
				'required'=>array(array(
					$field_name.'NotificationClickActionHybrid','=',array('blog')
				))
			),
			
			array(
				'id'       => $field_name.'NotificationClickActionCustom',
				'type'     => 'text',
				'title'    => __('Enter Notification Click Action Route ID (Android & iOS)', WR_TEXT_DOMAIN),
				'required'=>array(array(
					$field_name.'NotificationClickActionHybrid','=',array('custom')
					))
				)
			),
		'priority'=>array(
				'id'       => $field_name.'NotificationPriority',
				'type'     => 'select',
				'title'    => __('Notification Priority', WR_TEXT_DOMAIN),
				'options'=>array(
					'high'=>__('HIGH',WR_TEXT_DOMAIN),
					'normal'=>__('MEDIUM',WR_TEXT_DOMAIN),
					),
				'default'  => 'high',
				'required'=> array('enablePush','!=',''),
			),

// 			array(
// 				'id'       => $field_name.'NotificationActions',
// 				'type' => 'multi_text',
// 				'title'    => __('Notification Action Buttons', WR_TEXT_DOMAIN),
// 				'add_text'=>__('Add Action',WR_TEXT_DOMAIN),
// 				'required'=> array('enablePush','!=',''),
// 				'validate'=>'not_empty',
// 				'validate_callback'=>'wr_push_action_buttons_validate',
// 			),

		'renotify'=>array(
				'id'       => $field_name.'NotificationRenotify',
				'type'     => 'switch',
				'title'    => __('Enable Renotify.', WR_TEXT_DOMAIN),
				'default'  => false,
				'required'=> array('enablePush','!=',''),
			),
		'interaction'=>array(
				'id'       => $field_name.'NotificationRequireInteraction',
				'type'     => 'switch',
				'title'    => __('Require USER Interaction.', WR_TEXT_DOMAIN),
				'default'  => false,
				'required'=> array('enablePush','!=',''),
			),

		'save'=>array(
				'id'       => $field_name.'NotificationSave',
				'type'     => 'switch',
				'title'    => __('Save Notification.', WR_TEXT_DOMAIN),
				'default'  => true,
				'required'=> array('enablePush','!=',''),
			),

		'send' =>array(
				'id' => $field_name.'NotificationSend',
				'type' => 'raw',
				'markdown' => true,
				'desc'=>__('Save changes before sending notification.',WR_TEXT_DOMAIN),
				'content' => '<div style="text-align:center;">
								<a href="' . site_url() . '/wp-json/reactro/v1/push?_wpnonce='.wp_create_nonce( 'wp_rest' ).'" class="button button-success">' . __('Send', WR_TEXT_DOMAIN) . '</a>
								</div>',
				'required'=> array('enablePush','!=',''),
			),
		'warning'=>array(
				'id' => $field_name.'NotificationEnablePush',
				'type' => 'info',
				'style'=>'warning',
				'desc' => '<div style="text-align:center;">
				<span style="font-weight:600">To send notification enable push for atleast one platform.</span>				
				</div>',
				'required'=> array('enablePush','=',''),
			)
	);
	}
	