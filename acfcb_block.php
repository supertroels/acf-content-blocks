<?php


class acfcb_block {

	public $label = '';
	public $block = false;

	public function __construct(){

		$this->block = array(
			'key' => $this->get_field_key(0),
			'name' => get_class($this),
			'label' => $this->label,
			'display' => 'block',
			'sub_fields' => array(),
			'min' => '',
			'max' => '',
		);

	}



	public function get_field_key($name){
		
		// $handle 		= hash('crc32', get_class($this));
		// $index_length 	= strlen($index);
		// $max_length 	= (13 - $index_length);

		// if(strlen($handle) > $max_length)
		// 	$handle = substr($handle, 0, $max_length);
		
		// $diff = $max_length - strlen($handle.$index_length);
		// if($diff >= 0)
		// 	$handle = $handle.str_repeat('0', $diff+1);

		//$key = substr($handle.$index, 0, 13);

		$key = 'acfcb'.hash('crc32', $this->get_field_prefix().$name);

		return $key;

	}


	public function get_field_prefix(){

		return get_class($this).'_';

	}


	public function add_field($name){

		require_once 'acfcb_field.php';
		$field = new acfcb_field($this->get_field_prefix().$name, $this->get_field_key($name));
		$this->block['sub_fields'][] = $field;

		return $field;

	}


	public function export(){

		if($this->block['sub_fields'])
			foreach($this->block['sub_fields'] as $key => $field){
				if(!is_array($field))
					$this->block['sub_fields'][$key] = $field->export();
			}

		return $this->block;

	}


}


?>