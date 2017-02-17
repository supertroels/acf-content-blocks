<?php 

class acfcb_condition {


	/*
	*****************************
	*	
	*	Properties
	*	
	*****************************
	*/

	protected $rules = array();
	


	/**
	 * Adds a rule to the ruleset
	 * 
	 * @param $parameter mixed - a string for a location rule and an object for a conditional logic rule
	 * @param $operator mixed - the operator to use when testing the rule
	 * @param $value mixed - the value to test against
	 * @return object - $this for chainability
	 **/

	public function add_rule($parameter, $operator, $value){

		$param_key 	= 'param';
		if(is_object($parameter) and get_class($parameter) == 'acfcb_field'){
			$parameter 	= $parameter->key;
			$param_key 	= 'field';
		}

		$this->rules[] = array(
			$param_key 	=> $parameter,
			'operator' 	=> $operator,
			'value'		=> $value
			);

		return $this;

	}



	/**
	 * Exports the ruleset as an array
	 * 
	 * @return array - the exported field
	 **/

	public function export(){
		return (array)$this->rules;
	}




}


?>