<?php

class EntityList{
    
	protected $entity_list = array();

	public function put($value) {
		array_push($this -> entity_list, $value);
	}
    
	public function get($value) {
        $entity_list = NULL;
        if(isset($this -> entity_list[$value])){
            $entity_list = $this -> entity_list[$value];
        }
		return $entity_list;
	}

	public function size() {
		return count($this -> entity_list);
	}

}

?>