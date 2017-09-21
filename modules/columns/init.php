<?php

class acfcb_module_columns {


	public static $path 		= '';
	public static $url 			= '';
	public static $last_parent 	= '';


	public static function init(){

		self::$path = acf_content_blocks::$path.'/modules/columns';
		self::$url 	= acf_content_blocks::$url.'/modules/columns';

		add_action('acf/input/admin_enqueue_scripts', 'acfcb_module_columns::add_admin_assets');
		add_filter('acfcb/block', 'acfcb_module_columns::add_block_layout_fields', 10, 2);
		add_filter('acf/prepare_field', 'acfcb_module_columns::load_block_col_info', 10, 1);
		add_action('acf/render_field', 'acfcb_module_columns::add_mode_picker', 10, 1);


		add_filter('acfcb/block/columns/modes', function($modes){
			return [
					'xs' => [
						'cols' 	=> 1,
						'min'	=> 1,
						'max'	=> 1,
						'label'	=> 'X Small'
					], 

					'sm' => [
						'cols' 	=> 2,
						'min'	=> 1,
						'max'	=> 2,
						'label'	=> 'Small'
					], 

					'md' => [
						'cols' 	=> 6,
						'min'	=> 1,
						'max'	=> 6,
						'label'	=> 'Medium'
					], 

					'lg' => [
						'cols' 	=> 12,
						'min'	=> 1,
						'max'	=> 12,
						'label'	=> 'Large'
					], 

					];
		}, 1, 1);


	}



	public static function load_block_col_info($field){

		if(!preg_match('~_block_cols_info$~i', $field['_name']))
			return $field;

		$modes = apply_filters('acfcb/block/columns/modes', []);
		
		$field['value'] = json_encode($modes);

		return $field;

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

		$modes = apply_filters('acfcb/block/columns/modes', []);

		$block->add_field('block_cols_info')
		->set('type', 'text')
		->set('disabled', true)
		->default_value(json_encode($modes))
		->wrapper('class', 'block-cols-data')
		;

		foreach($modes as $mode => $info){

			$block->add_field('block_'.$mode.'_cols')
			->set('type', 'number')
			->set('min', $info['min'])
			->set('max', $info['max'])
			->set('step', 1)
			->set('label', $mode)
			->set('append', 'columns')
			->default_value($info['max'])
			->wrapper('class', 'block-cols-input')
			;


			$block->add_field('block_'.$mode.'_left_offset')
			->set('type', 'number')
			->set('min', 0)
			->set('max', $cols)
			->set('step', 1)
			->set('label', $mode)
			->set('append', 'columns')
			->default_value(0)
			->wrapper('class', 'block-cols-offset-input left')
			;


			$block->add_field('block_'.$mode.'_right_offset')
			->set('type', 'number')
			->set('min', 0)
			->set('max', $info['cols'])
			->set('step', 1)
			->set('label', $mode)
			->set('append', 'columns')
			->default_value(0)
			->wrapper('class', 'block-cols-offset-input right')
			;

		}

		return $block;


	}



	public static function add_mode_picker($field){


		if($field['_name'] != 'content_blocks')
			return;

		$modes = apply_filters('acfcb/block/columns/modes', []);

		$choices = [];
		foreach($modes as $mode => $info)
			$choices[$mode] = $info['label'];

		echo '<label>Mode</label>';
		acf_render_field(array(
			'type'		=> 'select',
			'name'		=> 'block-cols-mode',
			'choices'	=> $choices,
		));


	}



}


?>