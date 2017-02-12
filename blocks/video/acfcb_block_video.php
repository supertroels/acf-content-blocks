<?php

class acfcb_block_video extends acfcb_block {


	public function init(){

		$this->label = 'Video';

		parent::__init();

		$this->add_field('url')
		->set('label', 'Video')
		->set('type', 'oembed');

	}


}

?>