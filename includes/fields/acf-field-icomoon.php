<?php
// exit if accessed directly
if(!defined('ABSPATH')){
	exit;
}

if(!class_exists('ViiVue_ACF_Field_Icomoon')){
	class ViiVue_ACF_Field_Icomoon extends acf_field{
		/**
		 * Set up the field type data
		 */
		function __construct(){
			$this->name     = 'viivue_acf_icomoon';
			$this->label    = __('Icomoon', 'acf-icomoon');
			$this->category = 'content';
			$this->defaults = array(
				'selection_json_path' => ACFICOMOON_STYLESHEET_DIR . '/assets/fonts/selection.json',
			);
			parent::__construct();
		}
		
		
		/**
		 * Create settings for this fields in ACF
		 */
		function render_field_settings($field){
			// Admin field: Selection.json
			acf_render_field_setting($field, array(
				'label'        => __('Selection.json', 'acf-icomoon'),
				'instructions' => __('The path to the selection.json file generated by IcoMoon. Default value will be assets/fonts/selection.json in the current theme folder.', 'acf-icomoon'),
				'type'         => 'text',
				'name'         => 'selection_json_path'
			));
			
			// Admin field: Return value type
			acf_render_field_setting($field, array(
				'label'        => __('Return value', 'acf-icomoon'),
				'instructions' => __('Specify the returned value on front end', 'acf-icomoon'),
				'type'         => 'radio',
				'name'         => 'display_type',
				'choices'      => array(
					'icon'       => 'Icon element (HTML)',
					'icon_class' => 'Icon class',
					'svg'        => 'SVG element'
				)
			));
		}
		
		
		/**
		 *  Render HTML interface for ACF
		 */
		function render_field($field){
			// list of icons
			$choices = $this->viivue_get_icomoon_json(viivue_array_key_exists('selection_json_path', $field));
			
			// selected value
			$value = esc_attr(viivue_array_key_exists('value', $field));
			$name  = esc_attr(viivue_array_key_exists('name', $field));
			
			// ACF input field display in backend interface
			$input_html = '<input type="hidden" name="' . $name . '" value="' . $value . '"/>';
			
			// output HTML
			echo $this->viivue_icomoon_select_html($choices, $value, $input_html);
		}
		
		
		/**
		 * HTML for ACF and VC
		 */
		function viivue_icomoon_select_html($icon_array, $value, $input_html, $vc_element = false): string{
			$id    = uniqid();
			$class = $vc_element ? 'vc-element' : 'acf';
			
			// find active icon
			$active_icon = array();
			foreach($icon_array as $icon){
				$return_value = $vc_element ? "icon_class" : "name";
				$is_selected  = $icon[$return_value] == $value;
				if($is_selected){
					$active_icon = $icon;
				}
			}
			$active_icon_svg  = viivue_array_key_exists('icon_svg', $active_icon);
			$active_icon_name = viivue_array_key_exists('name', $active_icon);
			
			// wrapper
			$html = '<div data-icomoon-app class="vii-icomoon popup-top ' . $class . '" id="' . $id . '">';
			
			$html .= "<div style='display:none' data-icomoon-icons='" . json_encode($icon_array) . "'></div>";
			
			// hidden input
			$html .= '<div class="vii-icomoon__hidden-input">' . $input_html . '</div>';
			
			// custom field
			$html .= '<div class="vii-icomoon__custom-field">';
			
			$html .= '<div class="vii-icomoon__custom-field-col left">';
			$html .= '<div class="vii-icomoon__custom-field-result" data-icomoon="popup-trigger">';
			$html .= '<span class="icon-svg">' . $active_icon_svg . '</span>';
			$html .= '<span class="icon-name">' . $active_icon_name . '</span>';
			$html .= '</div>';
			$html .= '<a class="vii-icomoon__custom-field-remove" data-icomoon="remove-value" href="#">⨉</a>';
			$html .= '</div>';
			
			$html .= '<div class="vii-icomoon__custom-field-col right">';
			$html .= '<div class="vii-icomoon__custom-field-button">';
			$html .= '<button class="button" type="button" data-icomoon-popup-trigger>';
			$html .= '<span class="select">Select icon</span>';
			$html .= '<span class="cancel">Cancel</span>';
			$html .= '</button>';
			$html .= '</div>';
			$html .= '</div>';
			
			$html .= '</div>'; // end custom field
			
			// popup
			$html .= '<vii-icomoon-popup :icons="icons" :id="id"/>';
			
			$html .= '</div>'; // end wrapper
			
			return $html;
		}
		
		
		/**
		 * Get popup HTML
		 *
		 * @param $icons_array
		 * @param $selected_value
		 * @param $vc_element
		 *
		 * @return string
		 */
		function viivue_get_popup_html($icons_array, $selected_value, $vc_element): string{
			$html       = '';
			$count_text = '<span data-icomoon="count">' . count($icons_array) . '</span> icons';
			
			// icons HTML
			$items = '';
			foreach($icons_array as $icon){
				$return_value  = $vc_element ? "icon_class" : "name";
				$svg           = viivue_array_key_exists('icon_svg', $icon);
				$name          = viivue_array_key_exists('name', $icon);
				$is_selected   = $icon[$return_value] == $selected_value;
				$icomoon_value = viivue_array_key_exists($return_value, $icon);
				
				// attributes
				$attr = 'data-icomoon-value="' . $icomoon_value . '"';
				$attr .= 'class="' . ($is_selected ? 'active' : '') . '"';
				$attr .= 'title="' . $name . '"';
				
				// HTML
				$items .= '<li>';
				$items .= '<a data-icomoon="select" href="#" ' . $attr . '>' . $svg . '</a>';
				$items .= '</li>';
			}
			
			// HTML
			$html .= '<div class="vii-icomoon__popup" data-icomoon-popup>';
			$html .= '<div class="vii-icomoon__popup-inner">';
			
			$html .= '<div class="vii-icomoon__popup-head">';
			$html .= '<div class="vii-icomoon__search"><input data-icomoon="search" type="search" placeholder="Search icon..."></div>';
			$html .= '<span class="vii-icomoon__count-text">' . $count_text . '</span>';
			$html .= '</div>';
			
			$html .= '<div class="vii-icomoon__popup-body">';
			$html .= '<ul class="vii-icomoon__icons">';
			$html .= $items;
			$html .= '</ul>';
			$html .= '</div>';
			
			$html .= '</div>';
			$html .= '</div>';
			
			return $html;
		}
		
		
		/**
		 * Get array of icons from json
		 */
		function viivue_get_icomoon_json($json_path = ''): array{
			$icon_size  = 32;
			$icon_array = array();
			if(empty($json_path) || !file_exists($json_path)){
				$json_path = ACFICOMOON_STYLESHEET_DIR . '/assets/fonts/selection.json';
			}
			
			if($json_path && file_exists($json_path)){
				$icomoon_json = json_decode(file_get_contents($json_path), true);
				if($icomoon_json){
					$prefix = $icomoon_json['preferences']['fontPref']['prefix'];
					$icons  = viivue_array_key_exists('icons', $icomoon_json);
					if($icons){
						foreach($icons as $icon){
							$icon_obj   = viivue_array_key_exists('icon', $icon);
							$icon_paths = viivue_array_key_exists('paths', $icon_obj);
							$icon_props = viivue_array_key_exists('properties', $icon);
							$icon_name  = viivue_array_key_exists('name', $icon_props);
							
							// get svg (Format the SVG path)
							$attr = 'width="' . $icon_size . '"';
							$attr .= 'height="' . $icon_size . '"';
							$attr .= 'viewBox="0 0 1024 1024"';
							$attr .= 'preserveAspectRatio="xMidYMid meet"';
							
							$icon_svg = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" ' . $attr . '>';
							$icon_svg .= '<title>' . $icon_name . '</title>';
							foreach($icon_paths as $path){
								$icon_svg .= '<path fill="#444" d="' . $path . '"></path>';
							}
							$icon_svg .= '</svg>';
							
							$icon_array[] = array(
								'name'       => $icon_name,
								'icon_class' => $prefix . $icon_name,
								'svg'        => $icon_svg,
							);
						}
					}
				}
			}
			
			return $icon_array;
		}
		
		
		/**
		 *  Add assets, inherit from acf_field class
		 */
		function input_admin_enqueue_scripts(){
			wp_enqueue_script('viivue-acf-field-icomoon', ACFICOMOON_ASSETS_URL . "js/acf-icomoon.js", false, ACFICOMOON_VERSION);
			
			// easy popup
			wp_register_style('viivue-acf-field-icomoon-easy-popup', ACFICOMOON_ASSETS_URL . "css/easy-popup.min.css", false, '0.0.2');
			wp_register_script('viivue-acf-field-icomoon-easy-popup', ACFICOMOON_ASSETS_URL . "js/easy-popup.min.js", false, '0.0.2');
			
			// vue
			wp_register_script('viivue-acf-field-icomoon-helper', ACFICOMOON_ASSETS_URL . "js/helper.js", false, ACFICOMOON_VERSION);
			wp_register_script('viivue-acf-field-icomoon-vue', ACFICOMOON_ASSETS_URL . "js/vue.global.min.js", false, '3.2.31');
			wp_register_script('viivue-acf-field-icomoon-vue-dom', ACFICOMOON_ASSETS_URL . "js/class-acf-icomoon-dom.js", false, ACFICOMOON_VERSION);
			wp_register_script('viivue-acf-field-icomoon-vue-app', ACFICOMOON_ASSETS_URL . "js/acf-icomoon-app.js", array(
				'viivue-acf-field-icomoon-helper',
				'viivue-acf-field-icomoon-easy-popup',
				'viivue-acf-field-icomoon-vue',
				'viivue-acf-field-icomoon-vue-dom'
			), ACFICOMOON_VERSION);
			
			wp_enqueue_script('viivue-acf-field-icomoon-vue-app');
			
			
			// CSS
			wp_register_style('viivue-acf-field-icomoon', ACFICOMOON_ASSETS_URL . "css/acf-icomoon.css", array(
				'acf-input',
				'viivue-acf-field-icomoon-easy-popup'
			), ACFICOMOON_VERSION);
			
			wp_enqueue_style('viivue-acf-field-icomoon');
		}
		
		
		/**
		 *  This filter is applied to the $value after it is loaded from the db before it is returned to the template
		 */
		function format_value($value, $post_id, $field){
			if(empty($value)){
				return $value;
			}
			
			$path         = $field['selection_json_path'];
			$choices      = $this->viivue_get_icomoon_json($path);
			$display_type = viivue_array_key_exists('display_type', $field);
			
			if(!array_key_exists($value, $choices)){
				return '';
			}
			
			$value = viivue_array_key_exists($value, $choices);
			
			// return icon class
			if($display_type == 'icon_class'){
				return viivue_array_key_exists('icon_class', $value);
			}
			
			// return svg
			if($display_type == 'svg'){
				return viivue_array_key_exists('icon_svg', $value);
			}
			
			// return icon html
			return '<i class="' . viivue_array_key_exists('icon_class', $value) . '"></i>';
		}
		
	}
	
	// initialize
	new ViiVue_ACF_Field_Icomoon();
}