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
			// for example [your-theme]/assets/fonts/selection.json or wp-content/themes/[your-theme]/assets/fonts/selection.json
			$array_json_path     = explode('/', $json_path);
			$json_path_separator = viivue_array_key_exists(0, $array_json_path);
			if($json_path_separator){
				$position = strpos($theme_path, $json_path_separator);
				if($position !== false){
					$json_path = substr($theme_path, 0, $position) . $json_path;
					if(file_exists($json_path)){
						return $json_path;
					}
				}
			}
			
			// Fallback to search and return any selection.json from theme
			$folder_path   = viivue_array_key_exists('dirname', pathinfo(get_template_directory()));
			$recursive_dir = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($folder_path));
			$filename      = viivue_array_key_exists('basename', pathinfo($json_path));
			foreach($recursive_dir as $file){
				if($file->getBasename() == $filename){
					return $file->getPathname();
				}
			}
		}
		
		return viivue_acf_icomoon_default_json_option();
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