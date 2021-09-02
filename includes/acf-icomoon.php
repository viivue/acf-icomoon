<?php
// exit if accessed directly
if(!defined('ABSPATH')){
	exit;
}

if(!class_exists('ViiVue_ACF_Icomoon')){
	class ViiVue_ACF_Icomoon{
		function __construct(){
			add_action('acf/include_field_types', array($this, 'include_field_types'));
		}
		
		/*
		*  Create new field type
		*/
		
		function include_field_types(){
			include_once(ACFICOMOON_DIR . 'includes/fields/acf-field-icomoon.php');
		}
		
	}
	
	// initialize
	new ViiVue_ACF_Icomoon();
}