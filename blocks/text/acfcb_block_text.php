<?php

class acfcb_block_text extends acfcb_block {


	public function __construct(){

		$this->label = 'Text';

		parent::__construct();

		$this->add_field('content')
		->set('label', '')
		->set('type', 'wysiwyg')
		->set('tabs', 'all')
		->set('toolbar', 'full')
		->set('media_upload', 1)
		;

	}


}

?>