<?php
/*
Plugin Name: Wp Member Page
Plugin URI: http://www.elegants.biz/Products/WpMemberPage
Description: Plug-in to create a membership page with basic authentication.
Version: 1.3
Author: momen2009
Author URI: http://www.elegants.biz/
License: GPLv2 or later
 */

/*  Copyright 2014 木綿の優雅な一日 (email : momen.yutaka@gmail.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

load_plugin_textdomain("wp-member-page", false, dirname( plugin_basename( __FILE__ ) ) . '/languages');
define("WP_MEMBER_ENCRYPTION","BQIGLAOBIHA");

require_once (dirname(__FILE__) . "/entity/WpMembersPage.php");
require_once (dirname(__FILE__) . "/dao/WpMemberPageDao.php");
add_action('admin_menu', 'my_menu');

global $afb;
$afb = new WpMemberPage();
register_activation_hook(__FILE__, array($afb, "activate"));

class WpMemberPage{
    
    var $table;
    var $version = 0.1;
    var $db_version;
    
    function activate(){
        $data = '# BEGIN WP-MEMBER-PAGE'."\n".'<IfModule mod_rewrite.c>'."\n".'RewriteEngine On'."\n".'RewriteCond %{HTTP:Authorization} ^(.*)'."\n".'RewriteRule ^(.*) - [E=HTTP_AUTHORIZATION:%1]'."\n".'</IfModule>'."\n".'# END WP-MEMBER-PAGE'."\n";
        $fp;
        if(!file_exists(ABSPATH.'.htaccess')){
            $fp = fopen(ABSPATH.'.htaccess', 'w');
        }else{
            $fp = fopen(ABSPATH.'.htaccess', 'r');
        }
        $contents = stream_get_contents($fp);
        if(!preg_match("/# BEGIN WP-MEMBER-PAGE/",$contents)){
            fclose($fp);
            $fp = fopen(ABSPATH.'.htaccess', 'w');
            if ($fp){
                if (flock($fp, LOCK_EX)){
                    fwrite($fp,$data.$contents);
                    flock($fp, LOCK_UN);
                }
            }
            fclose($fp);
        }
        
        global $wpdb;
        
        $is_db_exists = $wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", $this->table));
        if($is_db_exists){
            if($this->db_version >= $this->version){
                return;
            }
        }

        require_once ABSPATH."wp-admin/includes/upgrade.php";
        dbDelta($this->sql());
        update_option("afb_db_version", $this->version);
        
    }
    
    function sql(){
        $char = defined("DB_CHARSET") ? DB_CHARSET : "utf8";
        return <<<EOS
               CREATE TABLE {$this->table} (
                    `id` INT(11) NOT NULL AUTO_INCREMENT,
                    `post_id` VARCHAR(255) NOT NULL,
                    `member_id` VARCHAR(255) NOT NULL,
                    `password` VARCHAR(255) NOT NULL,
                    UNIQUE(`id`)
               ) ENGINE = MyISAM DEFAULT CHARSET = {$char} ;
EOS;
    }
    
    function __construct(){
        global $wpdb;
        $this->table = $wpdb->prefix."member_page";
        $this->db_version = get_option('afb_db_version', 0);
    }
}

function my_menu() {
    add_menu_page(__('Member Pages','wp-member-page'),__('Member Pages','wp-member-page'), 8, __FILE__, 'wp_menber_page');
    add_submenu_page(__FILE__,__('Add New','wp-member-page'),__('Add New','wp-member-page'), 8, 'wp-member-page/member-new', 'member_new');
}
function wp_menber_page(){
    require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'index.php';
}

function member_new(){
    require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'member-new.php';
}

function my_the_content_filter() {
    $dao = new WpMemberPageDao();
    global $post;
    global $wp_query;
    
    $entity_list = $dao -> search(array("post_id" => $wp_query->post->ID));
    
    if ($entity_list->size() < 1 && $post->post_parent != 0){
        $the_post_id = $post->post_parent;
    }else{
        $the_post_id = $wp_query->post->ID;
    }
    
    $entity_list = $dao -> search(array("post_id" => $the_post_id));
    if(0 < $entity_list -> size()){
        if(!isset($_SERVER["PHP_AUTH_USER"])) {
            authenticateFailed();
        }
        else {
            if(AuthenticateUser($the_post_id,$_SERVER["PHP_AUTH_USER"],$_SERVER["PHP_AUTH_PW"])){
                return;
            } else {
                authenticateFailed();
            }
        }
    }
}

function AuthenticateUser($the_post_id,$user,$pwd){
    $dao = new WpMemberPageDao();
    $entity_list = $dao -> search(array("post_id" => $the_post_id,"member_id" => $user));
    if($entity_list->size() < 1) return false;
    $entity = $entity_list -> get(0);
    if($entity -> getPassword() == $pwd) return true;
    return false;
}

function authenticateFailed(){
    header("WWW-Authenticate: Basic realm=\"Please Enter Your Password\"");
    header("HTTP/1.0 401 Unauthorized");
    echo "<!DOCTYPE HTML PUBLIC \"-//IETF//DTD HTML 2.0//EN\"><html><head><title>401 Authorization Required</title></head><body><h1>Authorization Required</h1><p>This server could not verify that you are authorized to access the document requested.  Either you supplied the wrong credentials (e.g., bad password), or your browser doesn't understand how to supply the credentials required.</p></body></html>";
    exit;
}

add_filter( 'template_redirect', 'my_the_content_filter' );
?>