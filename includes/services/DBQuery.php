<?php

class WR_DBQuery {

    
    /**
	 * The single instance of the class.
	 *
	 */
	protected static $instance = null;
    
    public static function instance() {
		if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
		return self::$instance;
    }
    
    public function multi_insert($table,$columns,$rows){
        global $wpdb;
	    $table_name= $wpdb->prefix.$table;       
        
        $columnList = '`' . implode('`, `', $columns) . '`';
        $placeholders = array();
        foreach ($rows as $row) {
            $rowPlaceholders = array();
            foreach ($row as $key => $value) {
                if(is_array($value)){
                    $value=json_encode($value);
                }
                $rowPlaceholders[] = "'$value'";
            }
            $placeholders[] = '(' . implode(', ', $rowPlaceholders) . ')';
        }
        $sql = "INSERT INTO `$table_name` ($columnList) VALUES ";
        $sql .= implode(",", $placeholders);
        return $wpdb->query($sql);
    }
    


    public function get_user_ids_from_roles($roles){
           $query = array(
               'role__in'=>$roles
           );
           return get_users($query);
    }

    public function get($table,$args, $columns=array()){
        if(empty($table)){
            return null;
        }

        global $wpdb;
        $table_name= $wpdb->prefix.$table;
        if(!empty($columns) && is_array($columns)){
            $columnList = '`' . implode('`, `', $columns) . '`';
        }else{
            $columnList='*';
        }

        
        $limitQ = $conditions = '';

        if(!empty($args) && is_array($args) && count($args) > 0){
        

        if(array_key_exists('limit',$args)){
            $page = $args['paged'] ? $args['paged'] : 1;
            $limit = $args['limit'] > 0 ? $args['limit'] : 10; 
            
            $lastItem = 0;
                if($page > 1){
                    $lastItem=$page*$limit;
                }
            $limitQ = "LIMIT $lastItem, $limit";
        }

        foreach($args as $key=>$value){
            if(array_key_exists($key.'_relation',$args)){
               $rel =  $args[$key.'_relation'];
            }
			if(is_array($value)){
                if(!array_key_exists('relation',$value)){
                    $value['relation'] = 'IN';
                }
                $s = $this->relation_builder($value);
                if(!empty($s)){
                    $conditions .= "$key $s $rel ";
                }
			}else{
        	    $conditions .= $key." = '".$value."' ".$rel." "; 
            }
        }

        $query = $wpdb->get_results(
            "SELECT $columnList FROM  $table_name  WHERE $conditions $limitQ"
            ); 
        }else{
           $query = $wpdb->get_results(
            "SELECT $columnList FROM  $table_name $limitQ"
            );  
        }
        return $query;    
    }


    private function relation_builder($values){
        $relation = $operator = $value ='';
        $relation=$values['relation'];
        
        unset($values['relation']);
        
        if(array_key_exists('value',$values)){
            $value = $values['value'];
        }else{
            $value = $values;
        }

        if(empty($value)){
            return null;
        }

        switch($relation){
            case '<':
            case 'less':
            case 'is_smaller':
                $operator = '<';
                if(is_array($value)){
                    $value = $value[0];
                }
                break;
            case '<=':
            case 'less_equal':
            case 'is_smaller_equal':
                $operator = '<=';
                
                if(is_array($value)){
                    $value = $value[0];
                }
                break;
            case '>':
            case 'greater':
            case 'is_larger':
                $operator = '>';
                if(is_array($value)){
                    $value = $value[0];
                }
                break;
            case '>=':
            case 'greater_equal':
            case 'is_greater_equal':
                $operator = '>=';
                if(is_array($value)){
                    $value = $value[0];
                }
                break;
            case '=':
            case 'equals':
            case 'is_equal':
                $operator = '=';
                if(is_array($value)){
                    $value = $value[0];
                }
                break;
            case '!=':
            case 'not':
            case 'is_not':
                $operator = '!==';
                if(is_array($value)){
                    $value = $value[0];
                }
                break;
            case 'in':
            case 'IN':
                $operator = 'IN';
                if(is_array($value)){
                    $value =  array_map(function($value) {
                            return '\''.$value.'\'';
                            }, $value);
                    
                    $value = implode(',',$value);
                    $value = "(".$value.")";   
                }
                break;
            case 'between':
            case 'BETWEEN':
                $operator = 'BETWEEN';
                if(is_array($value)){
                    $value = implode(' AND ',$value);   
                }
                break;
            case 'not_between':
            case 'NOT_BETWEEN':
                $operator = 'NOT BETWEEN';
                if(is_array($value)){
                    $value = implode(' AND ',$value);
                 }
                break;
            case 'not_in':
            case 'NOT_IN':
                $operator = 'NOT IN';
                if(is_array($value)){
                    $value =  array_map(function($value) {
                            return '\''.$value.'\'';
                            }, $value);
                    $value = implode(',',$value);
                   $value = "(".$value.")";
                 }
                break;
            case 'like':
            case 'LIKE':
            case '===':
                $operator = 'LIKE';
                if(is_array($value)){
                    $value = $value[0];
                }
                break;
            case 'is_null':
            case 'IS_NULL':
                $operator = 'IS NULL';
                if(is_array($value)){
                    $value = $value[0];
                }
                break;
            case 'is_not_null':
            case 'IS_NOT_NULL':
            case 'is_not_null':
                $operator = 'IS NOT NULL';
                if(is_array($value)){
                    $value = $value[0];
                }
                break;
        }
        return "$operator $value";
    }
    
        public function update($table,$update_data,$where){
            if(!is_array($update_data) || empty($where) || !is_array($where) || empty($update_data) || empty($table)){
                return null;
            }
            global $wpdb;
            $table_name= $wpdb->prefix.$table;
            return $wpdb->update($table_name, $update_data, $where);
            }


        public function insert($table,$data){
            if(!is_array($data) || empty($table)){
                return null;
            }
            global $wpdb;
            $table_name= $wpdb->prefix.$table;
            $format =array();
            foreach($data as $value){
                $format[]=is_numeric($value) ? '%d':'%s';
            }
            return $wpdb->insert($table_name, $data, $format);
            
        }
    
    public function delete($table,$where_conditions){
		if(!is_array($where_conditions) || empty($where_conditions) || empty($table)){
			return null;
		}
        global $wpdb;
        $table_name= $wpdb->prefix.$table;
        foreach($where_conditions as $column =>$value){
			if(is_array($value)){
               $value =  array_map(function($value) {
                        return '\''.$value.'\'';
                        }, $value);

                $value = implode(',',$value);
                $condi[]= $column." IN (".$value.")"; 
			}else{
        	    $condi[]= $column." = '".$value."'"; 
            }
        }
        $conditions = implode(' AND ',$condi);
        


return $wpdb->query( "DELETE FROM $table_name WHERE $conditions" );
    }

}