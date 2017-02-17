<?php


class acfcb_block {

	public $label = '';
	public $block = false;

	public function __construct(){

		$class = get_class($this);

		$this->block = array(
			'key' => $this->get_field_key($class),
			'name' => $class,
			'label' => $this->label,
			'display' => 'block',
			'sub_fields' => array(),
			'min' => '',
			'max' => '',
		);

	}



	public function get_field_key($name){

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


	// Content methods

	public function get_field($name){
		$field = $this->get_field_prefix().$name;
        return get_sub_field($field);
	}


	public function the_field($name){
		echo $this->get_field($name);
	}


}


?>