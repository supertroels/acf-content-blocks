<?php

class acfcb_block_text extends acfcb_block {


	public static function register_block(){

		$block = array(
			'key' => self::get_field_key(123),
			'name' => 'acfcb_block_text',
			'label' => 'Text',
			'display' => 'block',
			'sub_fields' => array (
				array (
					'key' => 'field_'.self::get_field_key(2),
					'label' => 'Content',
					'name' => self::get_field_prefix().'content',
					'type' => 'wysiwyg',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array (
						'width' => '',
						'class' => '',
						'id' => '',
						),
					'default_value' => '',
					'tabs' => 'all',
					'toolbar' => 'full',
					'media_upload' => 1,
					)
				),
			'min' => '',
			'max' => '',
			);

		return apply_filters('acfcb_block/text', $block);

	}


}

?>