<?php

class acfcb_module_block_titles {


	public static $path 		= '';
	public static $url 			= '';
	public static $last_parent 	= '';


	public static function init(){

		self::$path = acf_content_blocks::$path.'/modules/block_titles';
		self::$url 	= acf_content_blocks::$url.'/modules/block_titles';

		add_action('acf/input/admin_enqueue_scripts', 'acfcb_module_block_titles::add_admin_assets');
		add_filter('acfcb/block', 'acfcb_module_block_titles::add_block_layout_fields', 10, 2);


	}


	public static function add_admin_assets(){
	    
	    
	    // register script
	    wp_register_script('acfcb-block_titles-admin-js', self::$url.'/assets/js/admin.js');
	    wp_enqueue_script('acfcb-block_titles-admin-js');

	}

	

	public static function add_block_layout_fields($block, $name){

		$block->add_field('block_title')
		->set('label', 'Block title')
		->set('type', 'text')
		->set('instructions', 'An optional short and descriptive title of this blocks contents.')
		->wrapper('width', 50);

		$block->add_field('block_id')
		->set('label', 'Block ID')
		->set('type', 'text')
		->set('instructions', 'An optional block ID to use when referencing this block on the frontend.')
		->wrapper('width', 50);

		return $block;


	}


}


?>