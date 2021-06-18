<?php

class REST_Base_v1{
    

    /**
	 * Endpoint namespace.
	 *
	 * @var string
	 */
    protected $namespace = 'reactro/v1';
    

    public function response($data){
        return new WP_REST_Response($data, 200);
        
    }


    public function process_data($keys,$request){
        if(empty($keys)){
            return null;
        }
        if(!is_array($keys)){
            $keys =array($keys);
        }
        
        $data=array();
		$req = $request->get_params();
        foreach($keys as $key){
			if(!array_key_exists($key,$req)){
                continue;
			}
			$data[$key]=$req[$key];
		}
		return $data;
    }


    public function error($code='invalid_request',$message='No response form server.',$status=400){
        return new WP_Error( $code,__($message), array( 'status' => $status ) );
    }
    
}