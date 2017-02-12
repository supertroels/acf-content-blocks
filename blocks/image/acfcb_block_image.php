<?php

class acfcb_block_image extends acfcb_block {


	public function init(){

		$this->label = 'Image';

		parent::__init();

		$this->add_field('file')
		->set('label', 'Image')
		->set('type', 'image');

	}


}

?>