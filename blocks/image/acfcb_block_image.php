<?php

class acfcb_block_image extends acfcb_block {


	public function __construct(){

		$this->label = 'Image';

		parent::__construct();

		$this->add_field('file')
		->set('label', 'Image')
		->set('type', 'image');

		$this->add_field('link')
		->set('label', 'Link')
		->set('type', 'url');

	}


}

?>