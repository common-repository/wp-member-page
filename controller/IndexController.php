<?php
require_once (dirname(__FILE__) . "/Controller.php");
require_once (dirname(__FILE__) . "/../entity/WpMembersMessages.php");
require_once (dirname(__FILE__) . "/../entity/WpMembersPage.php");
require_once (dirname(__FILE__) . "/../dao/WpMemberPageDao.php");
require_once (dirname(__FILE__) . "/../util/WpMembersValidate.php");

class IndexController extends Controller {
    
	public function initialize() {
        $pages = get_pages();
        $first = "";
        global $_VIEW;
        if(isset($_GET["post_id"]) && $_GET["post_id"] != ""){
            $first = $_GET["post_id"];
        }else{
            $first = reset($pages) -> ID;
        }
        $_VIEW["first"] = $first;
        $_VIEW["pages"] = $pages;
        
        $dao = new WpMemberPageDao();
        $_VIEW["members"] = $dao ->search(array("post_id" => $first));
        
	}
    
    public function edit(){
        global $_VIEW;
        
        $id = "";
        if(isset($_GET["id"]) && $_GET["id"] != ""){
            $id = $_GET["id"];
        }else{
            echo "<p><marquee scrolldelay=\"280\" scrollamount=\"428\" width=\"428\">".__('Please wait.','wp-member-page')."</marquee></p><script>location.href='admin.php?page=wp-member-page/wp-member-page.php'</script>";
            exit;
        }
        
        $dao = new WpMemberPageDao();
        $members = $dao ->search(array("id" => $id));
        if($members -> size() < 1){
            echo "<p><marquee scrolldelay=\"280\" scrollamount=\"428\" width=\"428\">".__('Please wait.','wp-member-page')."</marquee></p><script>location.href='admin.php?page=wp-member-page/wp-member-page.php'</script>";
            exit;
        }
        
        $member = $members -> get(0);
        $_VIEW["post_title"] = get_post($member -> getPostId()) -> post_title;
        $_VIEW["id"] = $member -> getId();
        $_VIEW["member_id"] = $member -> getMemberId();
        $_VIEW["password"] = $member -> getPassword();
        $messages = new WpMembersMessages();
        $_VIEW["messages"] = $messages;
    }
    
    public function update(){
        $messages = new WpMembersMessages();
        
        $dao = new WpMemberPageDao();
        $id = "";
        if(isset($_REQUEST["id"]) && $_REQUEST["id"] != ""){
            $id = $_REQUEST["id"];
        }else{
            echo "<p><marquee scrolldelay=\"280\" scrollamount=\"428\" width=\"428\">".__('Please wait.','wp-member-page')."</marquee></p><script>location.href='admin.php?page=wp-member-page/wp-member-page.php'</script>";
            exit;
        }
        
        $members = $dao ->search(array("id" => $id));
        if($members -> size() < 1){
            echo "<p><marquee scrolldelay=\"280\" scrollamount=\"428\" width=\"428\">".__('Please wait.','wp-member-page')."</marquee></p><script>location.href='admin.php?page=wp-member-page/wp-member-page.php'</script>";
            exit;
        }
        $member = $members -> get(0);
        
        $password = $_POST["password"];
        if(isset($password) && WpMembersValidate::isNotSet($password)){
            $messages -> set("password",__('Please enter the correct password.','wp-member-page'));
        }else{
            if(WpMembersValidate::isNotHankakuAlphabetNumeric($password)){
                $messages -> set("member_id",__('Please enter alphanumeric password.','wp-member-page'));
            }
        }
        
        if($messages ->size() <= 0){

            $member -> setPassword($password);
            $dao ->update($member);
            $post_id = $member -> getPostId();
            echo "<p><marquee scrolldelay=\"280\" scrollamount=\"428\" width=\"428\">".__('Please wait.','wp-member-page')."</marquee></p><script>location.href='admin.php?page=wp-member-page/wp-member-page.php&post_id=${post_id}'</script>";
            exit;
        }
        global $_VIEW;
        $_VIEW["post_title"] = get_post($member -> getPostId()) -> post_title;
        $_VIEW["id"] = $member -> getId();
        $_VIEW["member_id"] = $member -> getMemberId();
        $_VIEW["password"] = $password;
        $_VIEW["messages"] = $messages;
    }
    
    public function delete(){
        $id = $_GET["id"];
        $dao = new WpMemberPageDao();
        $dao -> delete($id);
        $this -> initialize();
    }
    
    public function selectedDelete(){
        $id = $_GET["id"];
        if(!isset($id)){
            $this -> initialize();
            return;
        }
        $dao = new WpMemberPageDao();
        foreach($id as $value){
            $dao -> delete($value);
        }
        $this -> initialize();
    }
    
}

new IndexController();
?>