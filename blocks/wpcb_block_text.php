<?php

class wpcb_block_text {


	public static function register_block(){


		$block = array(
			'key' => 'wpcbtext00001',
			'name' => 'wpcb_block_text',
			'label' => 'Text',
			'display' => 'block',
			'sub_fields' => array (
				array (
					'key' => 'field_wpcbtext00002',
					'label' => 'Content',
					'name' => 'block_text_content',
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

		return $block;

	}

}

?>