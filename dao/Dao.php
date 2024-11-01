<?php

class Dao{

	protected function sendQuery($sql) {
        global $wpdb;
		return $wpdb->query($sql);
	}
    
	protected function getQuery($sql) {
        global $wpdb;
		return $wpdb->get_results($sql);
	}
    
    protected function quote_smart($value)
    {
        if (get_magic_quotes_gpc()) 
        {
            $value = stripslashes($value);
        }
        if (!is_numeric($value)) 
        {
            $value = mysql_real_escape_string($value);
        }
        return $value;
    }

}