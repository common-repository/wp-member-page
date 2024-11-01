<?php
class WpMembersPage{
    
	private $id;
	private $post_id;
    private $member_id;
    private $password;
    
	public function setId($id) {
		$this -> id = $id;
	}

	public function getId() {
		return $this -> id;
	}
    
	public function setPostId($post_id) {
		$this -> post_id = $post_id;
	}

	public function getPostId() {
		return $this -> post_id;
	}
    
	public function setMemberId($member_id) {
		$this -> member_id = $member_id;
	}

	public function getMemberId() {
		return $this -> member_id;
	}
    
	public function setPassword($password) {
		$this -> password = $password;
	}

	public function getPassword() {
		return $this -> password;
	}
    
}
?>