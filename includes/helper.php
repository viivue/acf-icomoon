<?php
/**
 * Get specific item from array with callback default value
 */

if(!function_exists('viivue_array_key_exists')){
	function viivue_array_key_exists($key, $array, $default = ''){
		if($array && is_array($array)){
			if(array_key_exists($key, $array)){
				return !empty($array[$key]) ? $array[$key] : $default;
			}
		}
		
		return $default;
	}
}