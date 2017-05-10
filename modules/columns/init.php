<?php

class acfcb_module_columns {


	public static $path = '';
	public static $url 	= '';


	public static function init(){

		self::$path = acf_content_blocks::$path.'/modules/columns';
		self::$url 	= acf_content_blocks::$url.'/modules/columns';

		add_action('acf/input/admin_enqueue_scripts', 'acfcb_module_columns::add_admin_assets');
		add_filter('acfcb/block', 'acfcb_module_columns::add_block_layout_fields', 10, 2);
		add_filter('acfcb/block/attributes', 'acfcb_module_columns::add_block_attributes', 10, 3);


	}



	public static function add_admin_assets(){

		// register style
	    wp_register_style('acfcb-columns-admin-css', self::$url.'/assets/css/admin.css');
	    wp_enqueue_style('acfcb-columns-admin-css');
	    
	    
	    // register script
	    wp_register_script('acfcb-columns-admin-js', self::$url.'/assets/js/admin.js');
	    wp_enqueue_script('acfcb-columns-admin-js');

	}

	

	public static function add_block_layout_fields($block, $name){

		$cols = apply_filters('acfcb/block/coloumns', 12, $name, $block);

		$block->add_field('block_width')
		->set('type', 'number')
		->set('min', 1)
		->set('max', $cols)
		->set('step', 1)
		->set('append', 'columns')
		->default_value($cols)
		;

		// $block->add_field('block_handle')
		// ->set('type', 'text')
		// ;

		return $block;

	}



	public static function add_block_attributes($attrs, $name, $block){


		$width = $block->get_field('block_width');
		$attrs['data-block-cols'] = $width;

		return $attrs;


	}



}

?>