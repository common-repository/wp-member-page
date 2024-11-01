<?php
require_once (dirname(__FILE__) . "/controller/IndexController.php");
global $_VIEW;

if($_GET["action"] == "edit" || $_GET["action"] == "update"):
    $messages = $_VIEW["messages"];
    $post_title = $_VIEW["post_title"];
    $id = $_VIEW["id"];
    $member_id = $_VIEW["member_id"];
    $password = $_VIEW["password"];
?>
<div class="wrap">
    <h2><?php _e('Edit Member','wp-member-page'); ?></h2>
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
        <input type="hidden" name="action" value="update" />
        <input type="hidden" name="id" value="<?php echo $id ?>" />
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row"><?php _e('Title','wp-member-page'); ?></th>
                    <td><?php echo $post_title ?></td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="member_id">ID</label></th>
                    <td><?php echo $member_id ?></td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="password"><?php _e('Password','wp-member-page'); ?></label></th>
                    <td>
                        <input name="password" type="text" id="password" value="<?php if(isset($password)):echo htmlspecialchars($password);endif; ?>" class="regular-text"></td>
                </tr>
            </tbody>
        </table>
        <p class="submit">
            <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Update','wp-member-page'); ?>" />
        </p>
    </form>
</div>
<?php
else:
    $first = $_VIEW["first"];
    $pages = $_VIEW["pages"];
    $members = $_VIEW["members"];
?>
<div class="wrap">
    <h2><?php _e('Member Pages','wp-member-page'); ?> <a href="<?php echo admin_url() . "admin.php?page=wp-member-page/member-new&post_id=" . $first; ?>" class="add-new-h2"><?php _e('Add New','wp-member-page'); ?></a></h2>
    <div class="tablenav top">
        <div class="alignleft actions bulkactions">
            <select id="operation" onchange="operationChange()">
                <option value="-1" selected="selected"><?php _e('Bulk Actions','wp-member-page'); ?></option>
                <option value="selectedDelete"><?php _e('Delete','wp-member-page'); ?></option>
            </select>
            <input type="button" name="" id="doaction" class="button action" value="<?php _e('Apply','wp-member-page'); ?>" onclick="operation();" />
        </div>
        <script>
            function operationChange() {
                var operation = document.getElementById("operation");
                var selIndex = operation.selectedIndex;
                document.getElementById("package").value = operation.options[selIndex].value;
            }
            function operation() {
                document.package.submit();
            }
        </script>
        <div class="alignleft actions">
            <form method="get">
                <input type="hidden" name="page" value="wp-member-page/wp-member-page.php" />
                <select onchange="submit(this.form)" name="post_id">
                    <?php
    $view_url = "";
    foreach($pages as $key => $value):
                    ?>
                    <option value="<?php echo $value -> ID; ?>" class="<?php echo $value -> post_name; ?>" <?php if($value -> ID == $first) : echo "selected";$view_url = $value -> guid; endif; ?>><?php echo $value -> post_title; ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="button" name="" id="doview" class="button action" value="<?php _e('View','wp-member-page'); ?>" onclick="window.open(<?php echo "'" . $view_url . "'"; ?>, '_blank');" />
            </form>
        </div>
    </div>
    <form name="package" method="get">
        <input id="package" type="hidden" name="action" value="" />
        <input type="hidden" name="page" value="wp-member-page/wp-member-page.php" />
        <input type="hidden" name="post_id" value="<?php echo $first; ?>" />
        <table class="wp-list-table widefat fixed posts">
            <thead>
                <tr>
                    <th scope="col" id="cb" class="manage-column column-cb check-column" style="">
                        <label class="screen-reader-text" for="cb-select-all-1"><?php _e('Select All','wp-member-page'); ?></label><input id="cb-select-all-1" type="checkbox"></th>
                    <th scope="col" id="title" class="manage-column" style="">ID</th>
                    <th scope="col" id="author" class="manage-column" style=""><?php _e('Password','wp-member-page'); ?></th>
                </tr>
            </thead>

            <tfoot>
                <tr>
                    <th scope="col" class="manage-column column-cb check-column" style="">
                        <label class="screen-reader-text" for="cb-select-all-2"><?php _e('Select All','wp-member-page'); ?></label><input id="cb-select-all-2" type="checkbox"></th>
                    <th scope="col" class="manage-column" style="">ID</th>
                    <th scope="col" class="manage-column" style=""><?php _e('Password','wp-member-page'); ?></th>
                </tr>
            </tfoot>

            <tbody id="the-list" class="ui-sortable">
                <?php
    for($i = 0;$i < $members -> size();$i++):
        $member = $members -> get($i);
                ?>
                <tr id="post-<?php echo $member -> getId(); ?>" class="post-<?php echo $member -> getId(); ?> type-post status-publish format-standard hentry category-1<?php if($i % 2 == 0): echo " alternate";endif; ?> iedit author-self level-0">
                    <th scope="row" class="check-column">
                        <label class="screen-reader-text" for="cb-select-<?php echo $member -> getMemberId(); ?>"><?php _e('Select','wp-member-page'); ?> <?php echo $member -> getId(); ?></label>
                        <input id="cb-select-<?php echo $member -> getId(); ?>" type="checkbox" name="id[]" value="<?php echo $member -> getId(); ?>">
                        <div class="locked-indicator"></div>
                    </th>
                    <td class="post-title page-title column-title">
                        <strong><a class="row-title" href="<?php echo admin_url() . "admin.php?page=wp-member-page/wp-member-page.php&id=" . $member -> getId(); ?>&action=edit" title="<?php _e('Edit','wp-member-page'); ?> &#8220;<?php echo $member -> getMemberId(); ?>&#8221;"><?php echo $member -> getMemberId(); ?></a></strong>
                        <div class="row-actions">
                            <span class='edit'><a href="<?php echo admin_url() . "admin.php?page=wp-member-page/wp-member-page.php&id=" . $member -> getId(); ?>&action=edit" title="<?php _e('Edit this item','wp-member-page'); ?>"><?php _e('Edit','wp-member-page'); ?></a> | </span>
                            <span class="trash"><a class="submitdelete" title="<?php _e('Delete this item','wp-member-page'); ?>" href="<?php echo admin_url() . "admin.php?page=wp-member-page/wp-member-page.php&post_id=" . $first . "&id=" . $member -> getId(); ?>&action=delete"><?php _e('Delete','wp-member-page'); ?></a></span>
                        </div>
                    </td>
                    <td class="author column-author"><?php echo $member -> getPassword(); ?></td>
                </tr>
                <?php endfor;?>
            </tbody>
        </table>
    </form>
</div>
<?php endif; ?>