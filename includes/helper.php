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
 * Get icomoon JSON path
 * Return valid JSON path start from wp-content/themes/your-theme
 */

if(!function_exists('viivue_get_icomoon_json_path')){
	function viivue_get_icomoon_json_path($json_path = ''){
		if(!empty($json_path)){
			$file_name = basename($json_path);
			
			// only accept JSON file
			if(pathinfo($file_name, PATHINFO_EXTENSION) !== 'json'){
				return '';
			}
			
			// return the valid path
			$theme_path      = get_stylesheet_directory();
			$json_path_array = array_unique(array_merge(explode('/', $theme_path), explode('/', $json_path)));
			$json_path       = implode('/', $json_path_array);
		}else{
			$json_path = ACFICOMOON_STYLESHEET_DIR . '/assets/fonts/selection.json';
			if(!file_exists($json_path)){
				return '';
			}
		}
		
		return $json_path;
	}
}

/**
 * Set default value for selection_json_path
 */

if(!function_exists('viivue_acf_icomoon_default_value_selection_json')){
	function viivue_acf_icomoon_default_value_selection_json(){
		$json_path = ACFICOMOON_STYLESHEET_DIR . '/assets/fonts/selection.json';
		
		return file_exists($json_path) ? $json_path : '';
	}
}