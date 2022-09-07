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
			$this->prefix   = 'icon-';
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
				'instructions' => __('Specify the returned value on front end.', 'acf-icomoon'),
				'type'         => 'radio',
				'name'         => 'display_type',
				'choices'      => array(
					'icon'       => 'Icon element (HTML, Multicolor)',
					'icon_class' => 'Icon class',
					'svg'        => 'SVG element (Multicolor)',
					'array'      => 'Array'
				)
			));
		}
		
		
		/**
		 *  Render HTML interface for ACF
		 */
		function render_field($field){
			// list of icons
			$icon_array = $this->viivue_get_icomoon_json(viivue_array_key_exists('selection_json_path', $field));
			
			// selected value
			$value = esc_attr(viivue_array_key_exists('value', $field));
			$name  = esc_attr(viivue_array_key_exists('name', $field));
			
			$input_html = '<input name="' . $name . '" v-model="selected.icon_class" data-icomoon-input type="hidden">';
			
			// output HTML
			echo $this->viivue_icomoon_select_html($icon_array, $value, $input_html);
		}
		
		
		/**
		 * HTML for ACF and VC
		 */
		function viivue_icomoon_select_html($icon_array, $value, $input_html, $vc_element = false): string{
			$class = $vc_element ? 'vc-element' : 'acf';
			$html  = '';
			
			// value must begin with prefix (support legacy version)
			if(substr($value, 0, strlen($this->prefix)) !== $this->prefix){
				$value = $this->prefix . $value;
			}
			
			// wrapper
			$html .= '<div data-icomoon-app class="vii-icomoon ' . $class . ' unmounted" data-icomoon-selected="' . $value . '">';
			
			// json data
			$html .= "<div style='display:none' data-icomoon-icons='" . json_encode($icon_array) . "'></div>";
			
			// hidden input
			$html .= '<div class="vii-icomoon__hidden-input">';
			$html .= $input_html;
			$html .= '</div>';
			
			// custom field
			$html .= '<div class="vii-icomoon__custom-field empty">';
			$html .= '<div class="vii-icomoon__custom-field-inner">';
			
			$html .= '<div class="vii-icomoon__custom-field-result" data-icomoon-popup-trigger>';
			$html .= '<span class="vii-icomoon__icon-svg" v-if="selected.svg" v-html="selected.svg"></span>';
			$html .= '<span class="vii-icomoon__icon-name" v-if="selected.icon_class">{{selected.icon_class}}</span>';
			$html .= '<span class="vii-icomoon__icon-name empty" v-if="!selected.icon_class">Click to select icon</span>';
			$html .= '</div>';
			
			$html .= '<button class="vii-icomoon__custom-field-remove" v-if="selected.icon_class" @click="clearSelection">⨉</button>';
			
			$html .= '</div>';
			$html .= '</div>'; // end custom field
			
			// popup
			$html .= '<vii-icomoon-popup :icons="icons" :id="id" :selected="selected" @select-icon="selectIcon"/>';
			
			$html .= '</div>'; // end wrapper
			
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
					$prefix       = $icomoon_json['preferences']['fontPref']['prefix'];
					$this->prefix = $prefix;
					$icons        = viivue_array_key_exists('icons', $icomoon_json);
					if($icons){
						foreach($icons as $icon){
							$icon_obj      = viivue_array_key_exists('icon', $icon);
							$icon_paths    = viivue_array_key_exists('paths', $icon_obj);
							$icon_attrs    = viivue_array_key_exists('attrs', $icon_obj);
							$is_multicolor = viivue_array_key_exists('isMulticolor', $icon_obj);
							$icon_props    = viivue_array_key_exists('properties', $icon);
							$icon_name     = viivue_array_key_exists('name', $icon_props);
							$icon_class    = $prefix . $icon_name;
							
							// loop icon data
							$icon_html_inner = '';
							$icon_svg_inner  = '';
							if($icon_paths){
								foreach($icon_paths as $index => $path){
									// svg
									$icon_attr      = viivue_array_key_exists($index, $icon_attrs, array());
									$fill           = viivue_array_key_exists('fill', $icon_attr);
									$icon_svg_inner .= '<path fill="' . $fill . '" d="' . $path . '"></path>';
									
									// html
									if($fill && $is_multicolor){
										// only output span if there are more than 1 path
										$icon_html_inner .= '<span class="path' . ($index + 1) . '"></span>';
									}
								}
							}
							
							// get svg (Format the SVG path)
							$attr = 'width="' . $icon_size . '"';
							$attr .= 'height="' . $icon_size . '"';
							$attr .= 'viewBox="0 0 1024 1024"';
							$attr .= 'preserveAspectRatio="xMidYMid meet"';
							
							$icon_svg = '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" ' . $attr . '>';
							$icon_svg .= '<title>' . $icon_name . '</title>';
							$icon_svg .= $icon_svg_inner;
							$icon_svg .= '</svg>';
							
							// get icon HTML
							$icon_html = '<i class="' . $icon_class . '">';
							$icon_html .= $icon_html_inner;
							$icon_html .= '</i>';
							
							// return
							$icon_array[] = array(
								'name'       => $icon_name,
								'icon_class' => $icon_class,
								'svg'        => $icon_svg,
								'html'       => $icon_html,
								'data'       => $icon
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
			
			$icon = array();
			foreach($choices as $object){
				if(viivue_array_key_exists('icon_class', $object) === $value){
					$icon = $object;
					break;
				}
			}
			
			// return icon class
			if($display_type == 'icon_class'){
				return viivue_array_key_exists('icon_class', $icon);
			}
			
			// return svg
			if($display_type == 'svg'){
				return viivue_array_key_exists('svg', $icon);
			}
			
			// return array
			if($display_type == 'array'){
				return viivue_array_key_exists('data', $icon);
			}
			
			// return icon html
			return viivue_array_key_exists('html', $icon);
		}
		
	}
	
	// initialize
	new ViiVue_ACF_Field_Icomoon();
}