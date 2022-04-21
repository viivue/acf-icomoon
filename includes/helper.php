<?php
/**
 * Get specific item from array with callback default value
 */

if(!function_exists('viivue_array_key_exists')){
	function viivue_array_key_exists($key, $array, $default = ''){
		if($array && is_array($array)){
			if(array_key_exists($key, $array)){
				if(!empty($array[$key])){
					return $array[$key];
				}else{
					return $default;
				}
			}
		}
		
		return $default;
	}
}