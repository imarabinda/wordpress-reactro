<?php


  //start Miscellaneous
    $section_id='miscellaneous';
    Redux::set_section( $opt_name, array(
		'title'  => __( 'Miscellaneous', WR_TEXT_DOMAIN ),
		'id'     => $section_id,
		'desc'   => __( 'Version Controller for app..', WR_TEXT_DOMAIN ),
		'icon'   => 'el el-cog',
	) );


	$section_id='miscellaneous-settings';
    Redux::set_section($opt_name, array(
		'title'      => __('Miscellaneous Settings', WR_TEXT_DOMAIN),
		'id'         => $section_id,
		'subsection' => true
	));
	
	Redux::set_fields($opt_name,$section_id,array(
			array(
				'id'       => 'maintenanceMode',
				'type'     => 'switch',
				'title'    => __('Enable Maintenance Mode for all app users.', WR_TEXT_DOMAIN),
				'default'  => false,
            ),
            array(
				'id'       => 'maintenanceModeText',
				'type'     => 'text',
				'title'    => __('Maintenance Mode text.', WR_TEXT_DOMAIN),
                'default'  => __('Cleaning the ovens.. Hold on.'),
                'required'=> array('maintenanceMode','=',true),
            ),
            
		));
//end miscellaneous