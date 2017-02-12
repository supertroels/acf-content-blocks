<?php

/*
*********************************************
*
*	ACFC Field
*	v0.1
*	
*	This class is to be used for creating new
*	fields when building field groups with the
*	ACFC extension for ACF
*
*********************************************
*/

class acfcb_field {
	
	/*
	*****************************
	*	
	*	Properties
	*	
	*****************************
	*/
	
	public $key		= '';

	
	/**
	 * Runs on class contruction and sets
	 * up the field object for manipulation
	 * 
	 * @param $name string - the name of the field
	 * @param $key string - the unique and never-to-be-changed key of this field
	 * @return void
	 **/
	public function __construct($name, $key){

		$this->key 						= 'field_'.$key;
		$this->label 					= '';
		$this->name 					= $name;
		$this->type						= '';
		$this->instructions				= '';
		$this->required					= 0;
		$this->conditional_logic		= array();
		
		$this->wrapper					= array(
								        	'width' => '',
								        	'class' => '',
								        	'id' 	=> '',
								      	);
		$this->default_value			= '';
		$this->disabled					= 0;
		$this->readonly					= 0;
	}
	/**
	 * Sets a value on the field object
	 * 
	 * @param $key string - the key
	 * @param $value mixed - the value
	 * @return object - $this for chainability
	 **/
	public function set($key, $value){
		$this->{$key} = $value;
		return $this;
	}
	/**
	 * Sets a value on the wrapper array of
	 * the field object
	 * 
	 * @param $key string - the key
	 * @param $value mixed - the value
	 * @return object - $this for chainability
	 **/
	public function wrapper($key, $value){
		$this->wrapper[$key] = $value;
		return $this;
	}
	/**
	 * Sets the default value of the field
	 * and formats it according to the field
	 * type
	 * 
	 * @param $default string - the default value
	 * @return object - $this for chainability
	 **/
	public function default_value($default){
		
		if($this->is_selectable() and is_string($default))
			$default = array($default, $default);
		$this->set('default_value', $default);
		return $this;
	}
	/**
	 * Determines wether the field is a of a
	 * type that is "selectable"
	 * 
	 * @param $default string - the default value
	 * @return object - $this for chainability
	 **/
	public function is_selectable(){
		$selectables = apply_filters('acfc/selectables', array('select', 'checkbox', 'radio'));
		return in_array($this->type, $selectables);
	}
	/**
	 * Sets the default value of the field
	 * and formats it according to the field
	 * type
	 * 
	 * @param $default string - the default value
	 * @return object - $this for chainability
	 **/
	
	public function add_choice($key, $value){
		
		if(!self::is_selectable()){
			error_log('Tried to add a choice on a non-selectable');
			return $this;
		}
		if(!isset($this->choices) or !is_array($this->choices))
			$this->choices = array();
		$this->choices[$key] = $value;
		return $this;
	}
	/**
	 * Will add a sub field to this field if
	 * it is a repeater
	 * 
	 * @param $field_object object - the field object to add
	 * @return object - $this for chainability
	 **/
	
	public function add_sub_field($field_object){
		if($this->type !== 'repeater' or !$this->_is_layout){
			error_log('Tried to set a sub-field on a non-repeater field');
			return $this;
		}
		if(!isset($this->sub_fields) or !is_array($this->sub_fields))
			$this->sub_fields = array();
		$this->sub_fields[] = $field_object->export();
		return $this;
	}
	/**
	 * Will add a layout to this field if
	 * it is a flexible content field
	 * 
	 * @param $title - the title of the field
	 * @param $field_object object - the field object to add
	 * @return object - $this for chainability
	 **/
	
	public function add_layout($field_group){
		if($this->type !== 'flexible_content'){
			error_log('Tried to set a layout on a non-flexible-content field');
			return $this;
		}
		if(!isset($this->layouts) or !is_array($this->layouts)){
			$this->layouts			= array();
		}
		$this->layouts[] = $field_group->export();
		return $this;
	}
	/**
	 * Applies an acfc_ruleset object to the conditional
	 * logic for this field
	 * 
	 * @param $rulese object - the acfc_ruleset object to add
	 * @return object - $this for chainability
	 **/
	public function add_conditional_logic($ruleset){
		$this->conditional_logic[] = $ruleset->export();
		return $this;
	}
	/**
	 * Wrapper for add_conditional_logic
	 * 
	 * @param $ruleset object - the acfc_ruleset object to add
	 * @return object - $this for chainability
	 **/
	public function add_condition($ruleset){
		return $this->add_conditional_logic($ruleset);
	}
	/**
	 * Exports the field. Used primarily when included
	 * but can also be used in conjunction with acf_json_encode
	 * to export fields and groups to JSON files
	 * 
	 * @return array - the exported field
	 **/
	public function export(){
		return (array)$this;
	}
}
?>