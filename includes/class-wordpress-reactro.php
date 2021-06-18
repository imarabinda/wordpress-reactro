<?php


class WordpressReactro
{
   
    
    const plugin_name= 'WordPress Reactro';
    
    const version='1.2.2';
    
    const creator= 'Arabinda';
    
    protected static $_instance;

    protected $options;

    protected $loader;
    
    public $db;

    public $push_service;

    public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
    }
    

    public function __construct()
    {
        $this->define_constants();
        $this->includes();
        $this->init_hooks();
        $this->define_admin_hooks();
        $this->define_public_hooks();
        $this->hooks();
        $this->loader->run();
    }



    private function hooks(){
        //wr notification hooks
        $this->loader->add_action("wp_ajax_get_notifications", 'WR_Notification','ajax_get_notifications');
        $this->loader->add_action("wp_ajax_nopriv_get_notifications", 'WR_Notification','ajax_get_notifications');
    }

    private function init_hooks(){
        register_activation_hook( WR_PLUGIN_FILE, array( 'WR_Install', 'install' ) );
        $this->loader->add_action( 'activated_plugin', $this, 'activated_plugin'  );
        $this->loader->add_action( 'deactivated_plugin',  $this, 'deactivated_plugin'  );
		$this->loader->add_action( 'init', $this, 'load_rest_api'  );
    }
    
    
    /**
	 * Ran when any plugin is activated.
	 *
	 * @since 3.6.0
	 * @param string $filename The filename of the activated plugin.
	 */
	public function activated_plugin( $filename ) {

	}

	/**
	 * Ran when any plugin is deactivated.
	 *
	 * @since 3.6.0
	 * @param string $filename The filename of the deactivated plugin.
	 */
	public function deactivated_plugin( $filename ) {
    
    }
    


	/**
	 * Define WC Constants.
	 */
	private function define_constants() {
		$this->define( 'WR_ABSPATH', dirname( WR_PLUGIN_FILE ) . '/' );
		$this->define( 'WR_PLUGIN_BASENAME', plugin_basename( WR_PLUGIN_FILE ) );
		$this->define( 'WR_VERSION', self::version );
        $this->define( 'WR_NAME', self::plugin_name );
        $this->define('WR_CREATOR',self::creator);
        $this->define('WR_OPTION_NAME','wordpress_reactro_options');
		$this->define('WR_TEXT_DOMAIN','wordpress-reactro');
	}

        /**
	 * Define constant if not already set.
	 *
	 * @param string      $name  Constant name.
	 * @param string|bool $value Constant value.
	 */
	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

    private function includes()
    {
        //helper classes
        include_once WR_ABSPATH . 'includes/classes/class-wr-helper.php';
        include_once WR_ABSPATH . 'includes/classes/class-wr-loader.php';
        
        include_once WR_ABSPATH . 'src/Autoloader.php';
        if(!\Mrlazyfox\Reactro\Autoloader::init()){
            return;
        }
        
        //admin classes
        include_once WR_ABSPATH . 'src/admin/class-wordpress-reactro-admin.php';   
        include_once WR_ABSPATH . 'src/public/class-wordpress-reactro-public.php';   
        
        //core functions
        include_once WR_ABSPATH . 'includes/functions/wr-core-functions.php';
        
        //core classes        
        include_once WR_ABSPATH . 'includes/classes/class-wr-install.php';
        include_once WR_ABSPATH . 'includes/classes/class-wr-push.php';
        
        //services 
        include_once WR_ABSPATH . 'includes/services/DBQuery.php';
        include_once WR_ABSPATH . 'includes/services/PushService.php';
        $this->loader = new WR_Loader();
        $this->db = WR_DBQuery::instance();
        $this->push_service = WR_PushService::instance();
    }
  
    /**
	 * Load REST API.
	 */
	public function load_rest_api() {
		\Mrlazyfox\Reactro\RestApi\Server::instance()->init();
    }
    

/***
 * All admin init  and action
 */
    private function define_admin_hooks()
    {
        $this->loader->add_action('init',$this,'load_options');
        $this->loader->add_action('plugins_loaded','WordPress_Reactro_Admin', 'load_framework');
    }

    public function load_options(){
        global $wordpress_reactro_options;        
        $this->options = $wordpress_reactro_options;
    }

/***
 * public section
 * Responsible for all public function
 */
    private function define_public_hooks()
    {
    
    $this->loader->add_action('wp_enqueue_scripts','WordPress_Reactro_Public','enqueue_styles');
    $this->loader->add_action('wp_enqueue_scripts','WordPress_Reactro_Public','enqueue_scripts');
    $this->loader->add_action('admin_enqueue_scripts','WordPress_Reactro_Public','enqueue_scripts');
    $this->loader->add_action('admin_enqueue_scripts','WordPress_Reactro_Public','enqueue_styles');
    $this->loader->add_action('wp_footer','WordPress_Reactro_Public','add_popup');
    $this->loader->add_action('admin_footer','WordPress_Reactro_Public','add_popup');
    }


    //public
    public function get_version(){
        return self::version;
    }

    public function get_option($option)
    {
        if(!isset($this->options)) {
            return false;
        }

        if (!is_array($this->options)) {
            return false;
        }

        if (!array_key_exists($option, $this->options)) {
            return false;
        }
        return $this->options[$option] ? $this->options[$option] : false;
    }


}
