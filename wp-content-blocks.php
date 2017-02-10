<?php

/*
Plugin Name: WP Content Blocks
*/


class wp_content_blocks {


	public static $registered_blocks = array();
	public static $updating = false;


	public static function init(){

		if(!self::check_dependencies())
			return null;

		add_filter('wp_content_blocks/register_blocks', 'wp_content_blocks::register_default_blocks', 1, 1);

		add_action('acf/init', 'wp_content_blocks::register_blocks');
		add_action('acf/init', 'wp_content_blocks::register_content_blocks_field');

		// add_filter('the_content', 'wp_content_blocks::do_blocks', 1, 1);
		// add_filter('save_post', 'wp_content_blocks::update_fallback_content', 10, 1 );

	}


	public static function register_blocks(){
		self::$registered_blocks = apply_filters('wp_content_blocks/register_blocks', array());
	}


	public static function register_content_blocks_field(){


		$blocks = array();
		if(self::$registered_blocks){
			foreach(self::$registered_blocks as $block_class)
				if(method_exists($block_class, 'register_block'))
					$blocks[] = call_user_func(array($block_class, 'register_block'));
		}


		$group = array(
			'key' 		=> 'group_wpcb000000001',
			'title' 	=> 'Content blocks',
			'fields' 	=> array(
				array(
					'key' => 'field_wpcb000000002',
					'label' => '',
					'name' => 'blocks',
					'type' => 'flexible_content',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array (
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'button_label' => 'Add block',
					'min' => '',
					'max' => '',
					'layouts' => $blocks
				)
			),
			'location' 	=> apply_filters('wp_content_blocks/location', array(
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

		acf_add_local_field_group($group);


	}


	public static function register_default_blocks($blocks){

		$blocks_dir = dirname(__FILE__).'/blocks/';
		if($files = glob($blocks_dir.'wpcb_block_*.php')){
			foreach($files as $file){
				$classname = str_ireplace(array($blocks_dir,'.php'), '', $file);
				// error_log(var_export($file, true));
				// error_log(var_export($classname, true));
				include $file;
				$blocks[] = $classname;
			}
		}

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

	            ?>
	            <div class="block block-<?php echo $row_layout ?>">
	            <?php
	            include locate_template('content_blocks/'.$row_layout.'.php');
	            ?>
	            </div>
	            <?php
	        endwhile;
	        $content = ob_get_clean();
	    endif;

		return $content;

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
			ob_start();
			include 'templates/content_block_fallbacks/'.get_row_layout('acf_fc_layout').'.php';
			$output = ob_get_clean();
			$content .= $output;

		}


		self::$updating = true;
		$updated = wp_update_post(array(
			'ID' => $post_id,
			'post_content' => $content
		));
		self::$updating = false;


	}


}

wp_content_blocks::init();

?>
