<?php

abstract class Controller {
	
    public $title;
    
	public function __construct() {
        
        $action = "";
        if(isset($_POST["action"])){
            $action = $_POST["action"];
        }elseif(isset($_GET["action"])){
            $action = $_GET["action"];
        }
        
		if($action != ""){
			$this -> $action();
		}else{
			$this -> initialize();
		}
	}
	
}


?>