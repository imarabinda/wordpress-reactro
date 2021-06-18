<?php

 
/***
 * Version Control
 */

	$section_id='version';
	Redux::set_section( $opt_name, array(
		'title'  => __( 'Version Control', WR_TEXT_DOMAIN ),
		'id'     => 'version',
		'desc'   => __( 'Version Controller for app..', WR_TEXT_DOMAIN ),
		'icon'   => 'el el-fork',
	) );

	$section_id='version-settings';
	Redux::set_section($opt_name, array(
		'title'      => __('Version Settings', WR_TEXT_DOMAIN),
		'id'         => $section_id,
		'subsection' => true,
	));
	
	Redux::set_fields($opt_name,$section_id,array(
			array(
				'id'       => 'minimumAppVersionIOS',
				'type'     => 'text',
                'title'    => __('Minimum iOS App Version', WR_TEXT_DOMAIN),
                'subtitle'  => __('This is the base version off app , older version of this version will be deprecated', WR_TEXT_DOMAIN),
                'default'  => '1.0.0',
                'validate' => 'not_empty',
			),
			array(
				'id'       => 'minimumAppVersionAndroid',
				'type'     => 'text',
                'title'    => __('Minimum Android App Version', WR_TEXT_DOMAIN),
                'subtitle'  => __('This is the base version off app , older version of this version will be deprecated', WR_TEXT_DOMAIN),
                'default'  => '1.0.0',
                'validate' => 'not_empty',
			),
			array(
				'id'       => 'updateAvailableVersionIOS',
				'type'     => 'text',
				'title'    => __('New iOS version available for download.', WR_TEXT_DOMAIN),
				'subtitle'  => __('', WR_TEXT_DOMAIN),
                'default'  => '1.0.0',
                'validate' => 'not_empty',
			),
			array(
				'id'       => 'updateAvailableVersionAndroid',
				'type'     => 'text',
				'title'    => __('New Android version available for download.', WR_TEXT_DOMAIN),
				'subtitle'  => __('', WR_TEXT_DOMAIN),
                'default'  => '1.0.0',
                'validate' => 'not_empty',
			),
			array(
				'id'       => 'isNewVersionMandatory',
				'type'     => 'switch',
				'title'    => __('is new Version mandatory update for everyone?', WR_TEXT_DOMAIN),
				'subtitle'  => __('Without updating the app user can not use other features.', WR_TEXT_DOMAIN),
				'default'  => false,
            ),
            array(
				'id'       => 'changeLogShow',
				'type'     => 'switch',
				'title'    => __('want show change logs?', WR_TEXT_DOMAIN),
				'subtitle'  => __('This will add change logs.', WR_TEXT_DOMAIN),
				'default'  => false,
            ),
            array(
                'id'       => 'changeLogTexts',
                'type'     => 'multi_text',
                'title'    => __( 'Add Change Log', WR_TEXT_DOMAIN ),
                'subtitle' => __( 'Change log to show user what have updated.', WR_TEXT_DOMAIN ),
                'desc'     => __( 'Change Log', WR_TEXT_DOMAIN ),
                'default'=>__(array('Fixed bugs.','Perfomance improvements.'), WR_TEXT_DOMAIN),
                'validate' => 'not_empty',
                'required'=>array('changeLogShow','=', true)
			),
			array(
				'id'       => 'downloadLinkEnabled',
				'type'     => 'switch',
                'title'    => __('Enable manually download link android', WR_TEXT_DOMAIN),
                'subtitle'  => __('If playstore update not available this link will be used.', WR_TEXT_DOMAIN),
                'default'  => false,
			),
			array(
				'id'       => 'downloadLinkAndroid',
				'type'     => 'text',
                'title'    => __('Manually download link android', WR_TEXT_DOMAIN),
                'subtitle'  => __('Manually Download link', WR_TEXT_DOMAIN),
                'default'  => 'https://downloads.link.com/',
				'validate' => array('url','not_empty'),
				'required'=>array('downloadLinkEnabled','equals',true)
			),
		));

    
  //end version

