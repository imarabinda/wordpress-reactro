<?php

//start app
	$section_id='app';
     Redux::set_section( $opt_name, array(
		'title'  => __( 'App', WR_TEXT_DOMAIN ),
		'id'     => $section_id,
		'desc'   => __( 'App General', WR_TEXT_DOMAIN ),
        'icon'   => 'el el-idea',
        
	) );
	$section_id = 'app-settings';
    Redux::set_section($opt_name, array(
		'title'      => __('App Settings', WR_TEXT_DOMAIN),
		'id'         => $section_id,
		'subsection' => true,
	));
	
	Redux::set_fields($opt_name,$section_id,array(	
			array(
				'id'       => 'loginRequired',
				'type'     => 'switch',
				'title'    => __('Login required ?', WR_TEXT_DOMAIN),
				'default'  => false,
            ),array(
				'id'       => 'socialLogin',
				'type'     => 'switch',
				'title'    => __('Enable social login ?', WR_TEXT_DOMAIN),
				'default'  => false,
            ),
			array(
				'id'       => 'copyRight',
				'type'     => 'text',
				'title'    => __('Copy right section', WR_TEXT_DOMAIN),
				'default'  => __('&copy; '.get_bloginfo()),
            ),
			array(
				'id'       => 'aboutUsUrl',
				'type'     => 'select',
				'data'=>'pages',
				'title'    => __('Select About US page', WR_TEXT_DOMAIN),
				
            ),
			array(
				'id'       => 'termsUrl',
				'type'     => 'select',
				'data'=>'pages',
				'title'    => __('Select T&C page', WR_TEXT_DOMAIN),
				
            ),
			array(
				'id'       => 'privacyUrl',
				'type'     => 'select',
				'data'=>'pages',
				'title'    => __('Select Privacy Policy page', WR_TEXT_DOMAIN),
				
            ),
            
		));
    //end app