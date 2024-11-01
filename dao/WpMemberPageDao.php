<?php
require_once (dirname(__FILE__) . "/Dao.php");
require_once (dirname(__FILE__) . "/../entity/EntityList.php");

class WpMemberPageDao extends Dao{
    
    private $table;
    
    public function __construct(){
        global $wpdb;
        $this->table = $wpdb->prefix."member_page";
    }
    
    public function search($array){
        $sql = "select * from {$this->table} where ";
        $index = 0;
        foreach($array as $key => $value){
            $sql .= $index != 0 ? " and " : "";
            $value = $this -> quote_smart($value);
            if($this -> haveComparisonOperators($key)){
                $sql .= "${key} '${value}'";
            }else{
                $sql .= "${key} = '${value}'";
            }
            $index++;
        }
        $result = $this -> getEntityList($sql);
        return $result;
    }
    
    private function haveComparisonOperators($value){
        $array = array("=","<",">","!");
        foreach($array as $co_value){
            if(mb_strpos($value,$co_value)) return true;
        }
        return false;
    }
    
    public function update($entity){
        $id = $this -> quote_smart($entity -> getId());
        $post_id = $this -> quote_smart($entity -> getPostId());
        $member_id = $this -> quote_smart($entity -> getMemberId());
        $password = $this -> quote_smart($this -> encryption($entity -> getPassword()));
        global $wpdb;
        $sql = $wpdb->prepare("update {$this->table} set id = '%s',
                              post_id = '%s',
                            member_id = '%s',
                             password = '%s'
                             where id = '%s'",$id,$post_id,$member_id,$password,$id);
        $result = $this -> sendQuery($sql);
        return $result;
    }
    
    public function insert($entity){
        $post_id = $this -> quote_smart($entity -> getPostId());
        $member_id = $this -> quote_smart($entity -> getMemberId());
        $password = $this -> quote_smart($this -> encryption($entity -> getPassword()));
        global $wpdb;
        $sql = $wpdb->prepare("insert into {$this->table}(post_id,
                                         member_id,
                                          password)
                                            values
                                    ('%s',
                                     '%s',
                                     '%s')",$post_id,$member_id,$password);
        return $this -> sendQuery($sql);
    }
    
    public function delete($id){
        $id = $this -> quote_smart($id);
        global $wpdb;
        $sql = $wpdb->prepare("delete from {$this->table} where id = %s",$id);
        return $this -> sendQuery($sql);
    }
    
	private function getEntityList($sql) {
        $result = $this -> getQuery($sql);
        $entity_list = new EntityList();
        
        foreach($result as $value){
            $wp_member_page = new WpMembersPage();
            $wp_member_page -> setId(trim($value -> id));
            $wp_member_page -> setPostId(trim($value -> post_id));
            $wp_member_page -> setMemberId(trim($value -> member_id));
            $wp_member_page -> setPassword(trim($this -> decryption($value -> password)));
            $entity_list -> put($wp_member_page);
        }
        
		return $entity_list;
	}
    
    private function encryption($plain_text){

        $key = md5(WP_MEMBER_ENCRYPTION);

        $td  = mcrypt_module_open('des', '', 'ecb', '');
        $key = substr($key, 0, mcrypt_enc_get_key_size($td));
        $iv  = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);

        if (mcrypt_generic_init($td, $key, $iv) < 0) {
            exit('error.');
        }

        $crypt_text = base64_encode(mcrypt_generic($td, $plain_text));

        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        
        return $crypt_text;
    }
    
    private function decryption($crypt_text){

        $key = md5(WP_MEMBER_ENCRYPTION);

        $td  = mcrypt_module_open('des', '', 'ecb', '');
        $key = substr($key, 0, mcrypt_enc_get_key_size($td));
        $iv  = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);

        if (mcrypt_generic_init($td, $key, $iv) < 0) {
            exit('error.');
        }

        $plain_text = mdecrypt_generic($td, base64_decode($crypt_text));

        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        
        return $plain_text;
    }
    
}
?>