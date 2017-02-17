<?php

class acfcb_block_video extends acfcb_block {


	public function __construct(){

		$this->label = 'Video';

		parent::__construct();

		$this->add_field('url')
		->set('label', 'Video')
		->set('type', 'oembed');

	}


}

?>