<?php


class acfcb_block {


	public static function get_field_key($index){
		
		$handle 		= str_ireplace(array('_block', '_'), '', get_called_class());
		$index_length 	= strlen($index);
		$max_length 	= (13 - $index_length);

		if(strlen($handle) > $max_length)
			$handle = substr($handle, 0, $max_length);
		
		$diff = $max_length - strlen($handle.$index_length);
		if($diff >= 0)
			$handle = $handle.str_repeat('0', $diff+1);

		$key = substr($handle.$index, 0, 13);

		return $key;

	}


	public static function get_field_prefix(){

		return get_called_class().'_';

	}


}


?>