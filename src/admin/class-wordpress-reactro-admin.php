<?php

if(!class_exists('WordPress_Reactro_Admin')){
class WordPress_Reactro_Admin
{
   
    public function __construct()
    {
    }

    public static function enqueue_styles()
    {
        
    }

    public static function enqueue_scripts()
    {
       
    }

    public static function add_admin_js_vars()
    {
    
    }

   
    public static function load_framework()
    {

        $files=array(
            'init','app','version','store','push','miscellaneous',
        );

        foreach($files as $file){
            // Load the theme/plugin options
        if (file_exists(WR_ABSPATH.'src/admin/options/options-'.$file.'.php')) {
            include_once WR_ABSPATH.'src/admin/options/options-'.$file.'.php';
        }

        }
        
    }

}
}
