<?php


 //start store
 $section_id='store';
    Redux::set_section( $opt_name, array(
		'title'  => __( 'Store', WR_TEXT_DOMAIN ),
		'id'     => $section_id,
		'desc'   => __( 'Store Control', WR_TEXT_DOMAIN ),
        'icon'   => 'el el-shopping-cart-sign',
        
    ) );
    
    $section_id='store-settings';
	Redux::set_section($opt_name, array(
		'title'      => __('Store Settings', WR_TEXT_DOMAIN),
		'id'         => $section_id,
		'subsection' => true,
    ));

    Redux::set_fields($opt_name,$section_id,array(
			array(
				'id'       => 'disableOnlineOrders',
				'type'     => 'switch',
                'title'    => __('Enable/Disable online orders.', WR_TEXT_DOMAIN),
                'subtitle'  => __('No orders will be made', WR_TEXT_DOMAIN),
				'default'  => false,
			),array(
				'id'       => 'disableOnlineOrdersText',
				'type'     => 'text',
                'title'    => __('Disable Online order text', WR_TEXT_DOMAIN),
                'subtitle'  => __('This will be shown in the user screen', WR_TEXT_DOMAIN),
                'default'  => __('Sorry for the inconvenience , orders are not accepted for now.',WR_TEXT_DOMAIN),
                'validate' => 'not_empty',
                'required'=>array(array('disableOnlineOrders','equals',true)),
            ),
            array(
				'id'       => 'enableStoreOpenAndCloseHours',
				'type'     => 'switch',
                'title'    => __('Enable store open and closing hours ?', WR_TEXT_DOMAIN),
                'subtitle'  => __('Store will open in between times.', WR_TEXT_DOMAIN),
                'default'  => true,
                'required'=>array(array('disableOnlineOrders','equals', false)),
			),
            array(
				'id'       => 'storeOpenFrom',
				'type'     => 'text',
                'title'    => __('Store open from!', WR_TEXT_DOMAIN),
                'subtitle'  => __('Store will open in between times.in format "HH:MM:SS"', WR_TEXT_DOMAIN),
                'placeholder'=>__('HH:MM:SS',WR_TEXT_DOMAIN),
                'default'=>__('06:00:00',WR_TEXT_DOMAIN),
                'validate' => 'not_empty',
                'required'=>array(array('disableOnlineOrders','equals',false),array('enableStoreOpenAndCloseHours','=',true)),
			),array(
				'id'       => 'storeOpenTo',
				'type'     => 'text',
                'title'    => __('Store open to!', WR_TEXT_DOMAIN),
                'subtitle'  => __('Store will open in between times.in format "HH:MM:SS"', WR_TEXT_DOMAIN),
                'placeholder'=>__('HH:MM:SS',WR_TEXT_DOMAIN),
                'default'=>__('23:00:00',WR_TEXT_DOMAIN),
                'validate' => 'not_empty',
                'required'=>array(array('disableOnlineOrders','equals',false),array('enableStoreOpenAndCloseHours','=',true)),
			),array(
				'id'       => 'storeDeliveryUpto',
				'type'     => 'text',
                'title'    => __('Radius of delivery in KM', WR_TEXT_DOMAIN),
                'subtitle'  => __('Store deliverable radius', WR_TEXT_DOMAIN),
                'placeholder'=>__('7 KM',WR_TEXT_DOMAIN),
                'default'=>__('7',WR_TEXT_DOMAIN),
                'validate' => 'not_empty',
                'required'=>array(array('disableOnlineOrders','equals',false)),
			)
        ));

    //end store