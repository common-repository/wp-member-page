<?php

class WpMembersMessages{
	
	private $list = array();
	
	function set($key,$value){
		if(isset($this->list[$key])){
			$this->list[$key] = $this->list[$key].$value;
		}else{
			$this->list[$key] = $value;
		}
	}
	
	function getList(){
		return $this->list;
	}
	
	function get($key){
        if(isset($this->list[$key])){
            return $this->list[$key];
        }
	}
	
	function size(){
		return count($this->list);
	}
	
}

?>