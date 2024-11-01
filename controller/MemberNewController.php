<?php
require_once (dirname(__FILE__) . "/Controller.php");
require_once (dirname(__FILE__) . "/../entity/WpMembersMessages.php");
require_once (dirname(__FILE__) . "/../entity/WpMembersPage.php");
require_once (dirname(__FILE__) . "/../util/WpMembersValidate.php");
require_once (dirname(__FILE__) . "/../dao/WpMemberPageDao.php");

class MemberNewController extends Controller {
    
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
        
        $messages = new WpMembersMessages();
        $_VIEW["messages"] = $messages;
	}
    
    public function add(){
        $messages = new WpMembersMessages();
        
        $post_id = $_POST["post_id"];
        
        $member_id = $_POST["member_id"];
        if(isset($member_id) && WpMembersValidate::isNotSet($member_id)){
            $messages -> set("member_id",__('Please enter the correct ID.','wp-member-page'));
        }else{
            if(WpMembersValidate::isNotHankakuAlphabetNumeric($member_id)){
                $messages -> set("member_id",__('Please enter alphanumeric ID.','wp-member-page'));
            }
            $dao = new WpMemberPageDao();
            $entity_list = $dao -> search(array("post_id" => $post_id,"member_id" => $member_id));
            if(0 < $entity_list -> size()){
                $messages -> set("member_id",__('ID you entered is already in use.','wp-member-page'));
            }
        }
        
        $password = $_POST["password"];
        if(isset($password) && WpMembersValidate::isNotSet($password)){
            $messages -> set("password",__('Please enter the correct password.','wp-member-page'));
        }else{
            if(WpMembersValidate::isNotHankakuAlphabetNumeric($password)){
                $messages -> set("member_id",__('Please enter alphanumeric password.','wp-member-page'));
            }
        }
        
        if($messages ->size() <= 0){
            $dao = new WpMemberPageDao();
            $entity = new WpMembersPage();
            $entity -> setMemberId($member_id);
            $entity -> setPassword($password);
            $entity -> setPostId($post_id);
            $dao ->insert($entity);
            echo "<p><marquee scrolldelay=\"280\" scrollamount=\"428\" width=\"428\">".__('Please wait.','wp-member-page')."</marquee></p><script>location.href='admin.php?page=wp-member-page/wp-member-page.php&post_id=${post_id}'</script>";
            exit;
        }
        global $_VIEW;
        
        $_VIEW["post"] = get_post($post_id);
        $pages = get_pages();
        $first = "";
        if(isset($_POST["post_id"]) && $_POST["post_id"] != ""){
            $first = $_POST["post_id"];
        }
        $_VIEW["first"] = $first;
        $_VIEW["pages"] = $pages;
        $_VIEW["messages"] = $messages;
    }
    
}

new MemberNewController();
?>