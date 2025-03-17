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

/**
 * Get icomoon json path
 */

if(!function_exists('viivue_get_icomoon_json_path')){
	function viivue_get_icomoon_json_path($json_path = ''){
		if(!empty($json_path)){
			$file_name = basename($json_path);
			if(pathinfo($file_name, PATHINFO_EXTENSION) !== 'json'){
				return '';
			}
			$theme_path      = get_stylesheet_directory();
			$json_path_array = array_unique(array_merge(explode('/', $theme_path), explode('/', $json_path)));
			$json_path       = implode('/', $json_path_array);
		}
		
		return $json_path;
	}
}