<?php

/*
Plugin Name: Content Blocks for ACF Pro
*/


class acf_content_blocks {


	public static $registered_blocks = array();
	public static $fallback_templates = array();
	public static $updating = false;
	public static $path = '';
	public static $url = '';
	public static function init(){

		if(!self::check_dependencies())
			return null;

		self::$path = dirname(__FILE__);
		self::$url 	= plugin_dir_url('acf-content-blocks/acf-content-blocks.php');

		include 'acfcb_block.php';

		add_filter('acfcb/register_blocks', 'acf_content_blocks::register_default_blocks', 1, 1);

		add_action('acf/init', 'acf_content_blocks::register_blocks');
		add_action('acf/init', 'acf_content_blocks::register_content_blocks_field');

		add_filter('the_content', 'acf_content_blocks::do_blocks', 1, 1);
		add_filter('save_post', 'acf_content_blocks::update_fallback_content', 10, 1 );

		add_action('after_setup_theme', 'acf_content_blocks::remove_autop');

		spl_autoload_register('acf_content_blocks::register_autoloader');

		add_action('after_setup_theme', 'acf_content_blocks::load_modules');

	}


	public static function load_modules(){

		$modules = apply_filters('acfcb/modules', array());

		foreach($modules as $module => $enable){
			
			if(!$enable)
				continue;

			$module = strtolower($module);
			include self::$path.'/modules/'.$module.'/init.php';
			$class = 'acfcb_module_'.$module;
			call_user_func(array($class, 'init'));

		}

	}


	public static function remove_autop(){
		
		remove_filter('the_content', 'wpautop');
		remove_filter('the_excerpt', 'wpautop');

	}


	public static function register_autoloader($class){

			if(stristr($class, 'acfcb_')){
				$file = self::$path.'/'.$class.'.php';
				if(file_exists($file))
					require_once($file);
			}

	}


	public static function register_blocks(){
		self::$registered_blocks = apply_filters('acfcb/register_blocks', array());
	}


	public static function register_content_blocks_field(){

		$blocks = array();

		if(self::$registered_blocks){
			foreach(self::$registered_blocks as $name => $dir){

				// Main file
				$file = $dir.'/acfcb_block_'.$name.'.php';
				if(file_exists($file)){
					include $file;
					$class = 'acfcb_block_'.$name;
					$block = new $class();

					$block = apply_filters('acfcb/block', $block, $name);
					$block = apply_filters('acfcb/block/'.$name, $block, $name);

					if(method_exists($block, 'export'))
						$blocks[$name] = $block->export();
					else
						unset($blocks[$name]);

				}

				// Fallback template
				$file = $dir.'/fallback.php';
				if(file_exists($file)){
					self::$fallback_templates[$name] = $file;
				}

			}
		}


		$group = array(
			'key' 		=> 'group_acfcb'.hash('crc32', 'content_blocks_group_key'),
			'title' 	=> 'Content blocks',
			'fields' 	=> array(
				array(
					'key' => 'field_acfcb'.hash('crc32', 'content_blocks_field_key'),
					'label' => '',
					'name' => 'content_blocks',
					'type' => 'flexible_content',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array (
						'width' => '',
						'class' => '',
						'id' => 'acfcb-content-blocks',
					),
					'button_label' => 'Add block',
					'min' => '',
					'max' => '',
					'layouts' => $blocks
				)
			),
			'location' 	=> apply_filters('acfcb/location', array(
				array(
					array(
						'param' => 'post_type',
						'operator' => '==',
						'value' => 'post',
					),
				),
				array(
					array(
						'param' => 'post_type',
						'operator' => '==',
						'value' => 'page',
					)
				),
			)),
			'menu_order' 			=> 0,
			'position' 				=> 'normal',
			'style' 				=> 'default',
			'label_placement' 		=> 'top',
			'instruction_placement' => 'label',
			'hide_on_screen' 		=> array (
				0 => 'the_content',
			),
			'active' 				=> 1,
			'description' 			=> '',
		);

		$group = apply_filters('acfcb/field_group', $group);

		acf_add_local_field_group($group);


	}



	public static function register_default_blocks($blocks){

		$blocks_dir = dirname(__FILE__).'/blocks';

		// Text block
		$blocks['text'] 	= $blocks_dir.'/text';
		$blocks['image'] 	= $blocks_dir.'/image';
		$blocks['video'] 	= $blocks_dir.'/video';

		return $blocks;

	}




	public static function check_dependencies(){

		include_once(ABSPATH.'wp-admin/includes/plugin.php');
		return is_plugin_active('advanced-custom-fields-pro/acf.php');

	}


	public static function do_blocks($content){

		if(have_rows('content_blocks')):
	     	ob_start();
	         // loop through the rows of data
	        while ( have_rows('content_blocks') ) : the_row();

	        	$row_layout = get_row_layout();
				$name = str_ireplace('acfcb_block_', '', $row_layout);
				$block = new $row_layout();
				do_action('acfcb/before_block', $name, $block);
	            ?>
	            <div class="block block-<?php echo $name ?> <?php echo implode(' ', apply_filters('acfcb/block/classes', array(), $name, $block)) ?>" <?php self::print_attributes($name, $block) ?>>
	            <?php
	            do_action('acfcb/begin_block', $name, $block);
	            $path = apply_filters('acfcb/template_path', 'blocks/', $name, $block);
	            include locate_template($path.$name.'.php');
	           	do_action('acfcb/end_block', $name, $block);
	            ?>
	            </div>
	            <?php
	           	do_action('acfcb/after_block', $name, $block);
	        endwhile;
	        $content = ob_get_clean();
	    endif;

		return $content;

	}


	public static function print_attributes($name, $block){

		$attr = apply_filters('acfcb/block/attributes', array(), $name, $block);

		if(!$attr or !is_array($attr))
			return;

		$output = '';

		foreach($attr as $key => $value)
			$output = " ".$key."='".$value."'";

		echo $output;

	}


	public static function update_fallback_content($post_id){

		if(self::$updating) // Prevent infinite update loops
			return false;

		if(!have_rows('content_blocks'))
			return false;

		$content = '';

		 // loop through the rows of data
		while(have_rows('content_blocks')){

			the_row();
			
			$layout = get_row_layout();
			$name = str_ireplace('acfcb_block_', '', $layout);
			
			if($fallback_template = self::$fallback_templates[$name] and file_exists($fallback_template)){
				$block = new $layout();
				ob_start();
				include $fallback_template;
				$output = ob_get_clean();
				$content .= $output;

			}

		}


		self::$updating = true;
		$updated = wp_update_post(array(
			'ID' => $post_id,
			'post_content' => $content
		));
		self::$updating = false;


	}


}

acf_content_blocks::init();

?>
