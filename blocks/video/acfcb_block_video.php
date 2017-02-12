<?php

class acfcb_block_video extends acfcb_block {


	public static function register_block(){

		$block = array (
			'key' => self::get_field_key(1),
			'name' => 'acfcb_block_video',
			'label' => 'Video',
			'display' => 'block',
			'sub_fields' => array (
				array (
					'key' => 'field_'.self::get_field_key(2),
					'label' => 'video',
					'name' => self::get_field_prefix().'url',
					'type' => 'oembed',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array (
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'width' => '',
					'height' => '',
				),
			),
			'min' => '',
			'max' => '',
		);

		return apply_filters('acfcb_block/video', $block);

	}


}

?>