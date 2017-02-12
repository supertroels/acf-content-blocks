<?php


class acfcb_block_image extends acfcb_block {


	public static function register_block(){

		$block = array (
			'key' => self::get_field_key(1),
			'name' => 'acfcb_block_image',
			'label' => 'Image',
			'display' => 'block',
			'sub_fields' => array (
				array (
					'key' => 'field_'.self::get_field_key(2),
					'label' => 'Image',
					'name' => self::get_field_prefix().'file',
					'type' => 'image',
					'instructions' => '',
					'required' => '',
					'conditional_logic' => '',
					'wrapper' => array (
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'return_format' => '',
					'preview_size' => 'large',
					'library' => '',
					'min_width' => '',
					'min_height' => '',
					'min_size' => '',
					'max_width' => '',
					'max_height' => '',
					'max_size' => '',
					'mime_types' => '',
				),
			),
			'min' => '',
			'max' => '',
		);

		return apply_filters('acfcb_block/image', $block);

	}


}

?>