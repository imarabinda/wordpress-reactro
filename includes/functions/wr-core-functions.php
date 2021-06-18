<?php

/****
 * Load functions
 */

require WR_ABSPATH . 'includes/functions/push/wr-push-functions.php';
require WR_ABSPATH . 'includes/functions/push/wr-notifications-functions.php';




if(!function_exists('wr_array_keys_exists')){
    function wr_array_keys_exists(array $keys, array $arr) {
        return !array_diff_key(array_flip($keys), $arr);
    }
}




if(!function_exists('wr_required_columns')){
    function wr_required_columns(array $columns=array(),array $where=array()){
        $available=false;
        foreach($columns as $column_name){
            if(array_key_exists($column_name,$where)){
                $available = true;
                break;
            }
        }
        return $available;
    }
}




if(!function_exists('wr_get_table_name')){
    function wr_get_table_name(string $table){
        $tables = array(
                'push'=>'wr_push_tokens',
                'notification'=>'wr_push_notifications',
                'logging'=>'wr_notification_logs',
            );

        return array_key_exists($table,$tables) ? $tables[$table] : null;
    }
}

if(!function_exists('wr_create_sections')){
function wr_create_sections($sections,$depth = false){
		$return = array();
		$fields=array();
		foreach($sections as $section_id => $keys){
			$args = array(
				'title'=> array_key_exists('title',$keys) ? $keys['title'] :preg_replace('/([^A-Z])([A-Z])/', "$1 $2", ucwords($section_id)) ,
				'subsection'=> array_key_exists('subsection',$keys) ? $keys['subsection'] : $depth ,
				'id'=> str_replace(" ","_",strtolower($section_id)),
			);
			

			if(array_key_exists('icon',$keys)){
				$args['icon']=$keys['icon'];
			}
			if(array_key_exists('desc',$keys)){
				$args['desc']=$keys['desc'];
			}
			if(array_key_exists('subtitle',$keys)){
				$args['subtitle']=$keys['subtitle'];
			}
			if(array_key_exists('sub_sections',$keys)){	
				$fields = wr_create_sections($keys['sub_sections'],true);
			}
			if(array_key_exists('exclude_fields',$keys) || array_key_exists('fields',$keys)){
				$args['fields'] = wr_create_notification_option_fields($section_id,$keys);
			}
			if(!empty($fields)){
					array_push($return,$args);
					if(!$depth){
						foreach($fields as $field){
							array_push($return,$field);
						} 
					}
			}else if($depth){
				$return[] = $args;
			}else{
				array_push($return,$args);
			}
		}
		return $return; 
	}
}


if(!function_exists('wr_create_notification_option_fields')){
	function wr_create_notification_option_fields($field_name,$keys){
		$objects = wr_notification_fields($field_name);
		$return = array();
		if(array_key_exists('exclude_fields',$keys) && !empty($keys)){
			foreach($keys['exclude_fields'] as $field){
				unset($objects[$field]); 
			}
			$return = array_values($objects);
		}elseif(array_key_exists('fields',$keys)){
			if(!is_array($keys['fields']) && $keys['fields'] == 'all'){
				$return = array_values($objects);
			}else if(!empty($keys)){
				foreach($keys['fields'] as $field){	
					$return[] = $objects[$field];
				}
			}
		}
		return $return;
	}
}





