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
			$file_name  = basename($json_path);
			$theme_path = ACFICOMOON_STYLESHEET_DIR;
			
			// only accept JSON file
			if(pathinfo($file_name, PATHINFO_EXTENSION) !== 'json'){
				return '';
			}
			
			// If the json_path is already absolute and exists
			if(file_exists($json_path)){
				return $json_path;
			}
			
			// If the json_path is relative, we need to find the full path
			$array_json_path     = explode('/', $json_path);
			$json_path_separator = viivue_array_key_exists(0, $array_json_path);
			if($json_path_separator){
				// for example [your-theme]/assets/fonts/selection.json or wp-content/themes/[your-theme]/assets/fonts/selection.json
				$position = strpos($theme_path, $json_path_separator);
				if($position !== false){
					$json_path = substr($theme_path, 0, $position) . $json_path;
					if(file_exists($json_path)){
						return $json_path;
					}
				}else{
					// for example assets/fonts/selection.json
					$json_path = $theme_path . '/' . $json_path;
					if(file_exists($json_path)){
						return $json_path;
					}
				}
			}
		}else{
			// If no json_path is provided, return the default selection.json path
			// from wp-content/themes/[your-theme]/assets/fonts/selection.json
			$json_path = viivue_acf_icomoon_default_json_option();
		}
		
		return $json_path;
	}
}

/**
 * Set default value for selection_json_path
 */

if(!function_exists('viivue_acf_icomoon_default_json_option')){
	function viivue_acf_icomoon_default_json_option(){
		$json_path = ACFICOMOON_STYLESHEET_DIR . '/assets/fonts/selection.json';
		
		return file_exists($json_path) ? $json_path : '';
	}
}