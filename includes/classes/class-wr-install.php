<?php 

class WR_Install {




    public static function install(){
        if ( ! is_blog_installed() ) {
			return;
		}

		// Check if we are not already running this routine.
		if ( 'yes' === get_transient( 'wr_installing' ) ) {
			return;
        }
        set_transient( 'wr_installing', 'yes', MINUTE_IN_SECONDS * 10 );
        self::create_tables();
    
		delete_transient( 'wr_installing' );
        do_action('wr_installed');
    }


    private function create_tables(){
        global $wpdb;
		$wpdb->hide_errors();
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( self::get_schema() );
    }


    
	/**
	 * Get Table schema.
	 *
	 * A note on indexes; Indexes have a maximum size of 767 bytes. Historically, we haven't need to be concerned about that.
	 * As of WordPress 4.2, however, we moved to utf8mb4, which uses 4 bytes per character. This means that an index which
	 * used to have room for floor(767/3) = 255 characters, now only has room for floor(767/4) = 191 characters.
	 *
	 * Changing indexes may cause duplicate index notices in logs due to https://core.trac.wordpress.org/ticket/34870 but dropping
	 * indexes first causes too much load on some servers/larger DB.
	 *
	 * When adding or removing a table, make sure to update the list of tables in WC_Install::get_tables().
	 *
	 * @return string
	 */
	private static function get_schema() {
		global $wpdb;

		$collate = '';

		if ( $wpdb->has_cap( 'collation' ) ) {
			$collate = $wpdb->get_charset_collate();
		}

		/*
		 * Indexes have a maximum size of 767 bytes. Historically, we haven't need to be concerned about that.
		 * As of WP 4.2, however, they moved to utf8mb4, which uses 4 bytes per character. This means that an index which
		 * used to have room for floor(767/3) = 255 characters, now only has room for floor(767/4) = 191 characters.
		 */
		$max_index_length = 191;

		$tables = "
    CREATE TABLE {$wpdb->prefix}wr_push_tokens (
                id BIGINT UNSIGNED NOT null auto_increment,
	            user_id BIGINT UNSIGNED NOT null,
		        token TEXT NOT null,
	            platform TEXT NOT null,
		        create_time datetime DEFAULT CURRENT_TIMESTAMP NOT null,
		        updated_at datetime DEFAULT CURRENT_TIMESTAMP NOT null,
		        PRIMARY KEY( id )	
        ) $collate;
    CREATE TABLE {$wpdb->prefix}wr_push_notifications (
                id BIGINT UNSIGNED NOT null auto_increment,
	            user_id BIGINT UNSIGNED NOT null,
		        message_id TEXT NOT null,
	            data LONGTEXT NOT null,
		        create_time datetime DEFAULT CURRENT_TIMESTAMP NOT null,
		        PRIMARY KEY( id )	
        ) $collate;
    CREATE TABLE {$wpdb->prefix}wr_notification_logs (
                id BIGINT UNSIGNED NOT null auto_increment,
	            send_by BIGINT UNSIGNED NOT null,
		        data TEXT NOT null,
	            multicast_id TEXT NOT null,
	            notification_clicks BIGINT null,
		        create_time datetime DEFAULT CURRENT_TIMESTAMP NOT null,
		        updated_at datetime DEFAULT CURRENT_TIMESTAMP NOT null,
		        PRIMARY KEY( id )	
        ) $collate;
                ";

		return $tables;
	}


    
	/**
	 * Drop WooCommerce tables.
	 *
	 * @return void
	 */
	public static function drop_tables() {
		global $wpdb;

		$tables = self::get_tables();

		foreach ( $tables as $table ) {
			$wpdb->query( "DROP TABLE IF EXISTS {$table}" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		}
    }
    

    
    public static function uninstall(){
        self::drop_tables();
    }


    /**
	 * Return a list of WooCommerce tables. Used to make sure all WC tables are dropped when uninstalling the plugin
	 * in a single site or multi site environment.
	 *
	 * @return array WC tables.
	 */
	public static function get_tables() {
		global $wpdb;

		$tables = array(
			"{$wpdb->prefix}wr_push_tokens",
			"{$wpdb->prefix}wr_push_notifications",
                    );
                    
    }
}