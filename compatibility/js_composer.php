<?php
/**
 * VC Icomoon Param
 */

vc_add_shortcode_param('icomoon_class', 'viivue_js_composer_icomoon');
function viivue_js_composer_icomoon($settings, $value){
	$acf_icomoon = new ViiVue_ACF_Field_Icomoon();
	
	$json_path  = ACFICOMOON_STYLESHEET_DIR . "/assets/fonts/selection.json";
	$json_path  = apply_filters('viivue_vc_icomoon_json_path', $json_path, $settings);
	$icon_array = $acf_icomoon->viivue_get_icomoon_json($json_path);
	$type       = viivue_array_key_exists("type", $settings);
	$param_name = viivue_array_key_exists("param_name", $settings);
	$value      = esc_attr($value);
	$param_name = esc_attr($param_name);
	$input_html = '<input name="' . $param_name . '" class="hidden wpb_vc_param_value wpb-textinput ' . $param_name . ' ' . esc_attr($type) . '_field" type="text" v-model="selected.icon_class" data-icomoon-input/>';
	
	$html = $acf_icomoon->viivue_icomoon_select_html($icon_array, $value, $input_html, true);
	$html .= '<script>jsComposerAfterElementRender(document.querySelector(".vc_ui-panel.vc_active"));</script>';
	
	return $html;
}