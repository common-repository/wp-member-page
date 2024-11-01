<?php
require_once (dirname(__FILE__) . "/controller/MemberNewController.php");
require_once (dirname(__FILE__) . "/entity/WpMembersMessages.php");
global $_VIEW;
$first = $_VIEW["first"];
$pages = $_VIEW["pages"];
$messages = $_VIEW["messages"];
?>
<div class="wrap">
    <h2><?php _e('Add New Member','wp-member-page'); ?></h2>
    <?php if(0 < $messages -> size()):
              foreach($messages -> getList() as $value):?>
    <div id="setting-error-invalid_siteurl" class="error settings-error">
        <p><strong><?php echo $value; ?></strong></p>
    </div>
    <?php
              endforeach;
          endif;
    ?>
    <form method="post">
        <input type="hidden" name="action" value="add" />
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row"><?php _e('Title','wp-member-page'); ?></th>
                    <td><select name="post_id">
                    <?php
                    foreach($pages as $key => $value):
                    ?>
                    <option value="<?php echo $value -> ID; ?>" class="<?php echo $value -> post_name; ?>" <?php if($value -> ID == $first) : echo "selected"; endif; ?>><?php echo $value -> post_title; ?></option>
                    <?php endforeach; ?>
                </select></td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="member_id">ID</label></th>
                    <td>
                        <input name="member_id" type="text" id="member_id" value="<?php if(isset($_POST["member_id"])):echo htmlspecialchars($_POST["member_id"]);endif; ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="password"><?php _e('Password','wp-member-page'); ?></label></th>
                    <td>
                        <input name="password" type="text" id="password" value="<?php if(isset($_POST["password"])):echo htmlspecialchars($_POST["password"]);endif; ?>" class="regular-text"></td>
                </tr>
            </tbody>
        </table>
        <p class="submit">
            <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Add','wp-member-page'); ?>" />
        </p>
    </form>
</div>
