<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <?php if (has_permission('custom_links', '', 'create') || (has_permission('custom_links', '', 'edit') && isset($link))) { ?>
                <div class="col-md-6">
                    <div class="panel_s">
                        <div class="panel-body">
                            <h4 class="no-margin inline-block">
                                <?php
                                if (isset($link))
                                    echo html_escape(_l('mcl_edit_custom_links', $link['title']));
                                else
                                    echo html_escape(_l('mcl_add_custom_links'));
                                ?>
                            </h4>
                            <?php $value_main_setup = isset($link) ? $link['main_setup'] : '0'; ?>
                            <span class="switch-link-type">
                                <a href="#" onclick="setMenuType(this, '0'); return false;" class="mright5 link-headings <?php if($value_main_setup == "0") echo 'active'; ?>"><?php echo _l('mcl_main_menu'); ?></a>
                                | <a href="#" onclick="setMenuType(this, '1'); return false;" class="mleft5 mright5 link-headings <?php if($value_main_setup == "1") echo 'active'; ?>"><?php echo _l('mcl_setup_menu'); ?></a>
                                | <a href="#" onclick="setMenuType(this, '2'); return false;" class="mleft5 text-primary link-headings <?php if($value_main_setup == "2") echo 'active'; ?>"><?php echo _l('mcl_client_menu'); ?></a>
                            </span>

                            <?php echo form_open('', ["method" => "post", "id" => "custom_links_form"]); ?>
                            <?php
                            if (isset($link)) {
                                echo form_hidden('id', $link['id']);
                            }
                            $http_protocol = "0";
                            if(isset($link)){
                                $http_protocol = $link['http_protocol'];
                            }
                            ?>
                            <input type="hidden" name="main_setup" id="main_setup" value="<?php echo $value_main_setup; ?>">
                            <input type="hidden" name="http_protocol" id="http_protocol" value="<?php echo $http_protocol; ?>">

                            <div class="horizontal-scrollable-tabs">
                                <div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
                                <div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>
                                <div class="horizontal-tabs">
                                    <ul class="nav nav-tabs nav-tabs-horizontal" role="tablist">
                                        <li role="presentation" class="active">
                                            <a href="#general" aria-controls="general" role="tab" data-toggle="tab"><?php echo _l('mcl_tab_general'); ?></a>
                                        </li>
                                        <li role="presentation">
                                            <a href="#other_options" aria-controls="invoice" role="tab" data-toggle="tab"><?php echo _l('mcl_tab_options'); ?></a>
                                        </li>
                                        <li role="presentation">
                                            <a href="#restriction" aria-controls="credit_notes" role="tab" data-toggle="tab"><?php echo _l('mcl_tab_restriction'); ?></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane active" id="general">
                                    <?php
                                    $value = isset($link) ? $link['title'] : '';
                                    echo render_input('title', _l('mcl_link_title'), $value, 'text', [], [], "", "input-lg");
                                    ?>

                                    <?php
                                    $value = isset($link) ? $link['parent_id'] : '';
                                    ?>

                                    <div class="form-group main_menu_items hide" app-field-wrapper="parent_id">
                                        <label for="main_parent_id" class="control-label"><?php echo _l('mcl_parent_menu'); ?></label>
                                        <select id="main_parent_id" name="parent_id" class="selectpicker" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" data-live-search="true" tabindex="-98" disabled>
                                            <option value=""></option>
                                            <?php foreach($main_menu_items as $menu_item){ ?>
                                                <option value="<?php echo html_escape($menu_item['slug']); ?>" <?php if($value == $menu_item['slug']) echo 'selected'; ?>><?php echo html_escape($menu_item['name']); ?></option>
                                            <?php } ?>
                                        </select>
                                        <div class="help-block text-warning hide convert_hash_warning"><?php echo _l('mcl_will_convert_to_hash'); ?></div>
                                    </div>

                                    <div class="form-group setup_menu_items hide" app-field-wrapper="parent_id">
                                        <label for="setup_parent_id" class="control-label"><?php echo _l('mcl_parent_menu'); ?></label>
                                        <select id="setup_parent_id" name="parent_id" class="selectpicker" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" data-live-search="true" tabindex="-98" disabled>
                                            <option value=""></option>
                                            <?php foreach($setup_menu_items as $menu_item){ ?>
                                                <option value="<?php echo html_escape($menu_item['slug']); ?>" <?php if($value == $menu_item['slug']) echo 'selected'; ?>><?php echo html_escape($menu_item['name']); ?></option>
                                            <?php } ?>
                                        </select>
                                        <div class="help-block text-warning hide convert_hash_warning"><?php echo _l('mcl_will_convert_to_hash'); ?></div>
                                    </div>

                                    <div class="form-group">
                                        <label for="external_internal0"><?php echo _l('mcl_external_internal'); ?></label><br/>
                                        <div class="radio radio-inline radio-primary">
                                            <input type="radio" name="external_internal" id="external_internal0"
                                                   value="0" <?php if (isset($link) && $link['external_internal'] == "0" || !isset($link)) {
                                                echo 'checked';
                                            } ?>>
                                            <label for="external_internal0"><?php echo _l('mcl_internal_link'); ?></label>
                                        </div>

                                        <div class="radio radio-inline radio-primary">
                                            <input type="radio" name="external_internal" id="external_internal1"
                                                   value="1" <?php if (isset($link) && $link['external_internal'] == "1") {
                                                echo 'checked';
                                            } ?>>
                                            <label for="external_internal1"><?php echo _l('mcl_external_link'); ?></label>
                                        </div>

                                        <div class="radio radio-inline radio-primary">
                                            <input type="radio" name="external_internal" id="external_internal2"
                                                   value="2" <?php if (isset($link) && $link['external_internal'] == "2") {
                                                echo 'checked';
                                            } ?>>
                                            <label for="external_internal2"><?php echo _l('mcl_hash_link'); ?></label>
                                        </div>
                                    </div>

                                    <?php
                                    $value = isset($link) ? $link['href'] : '';
                                    ?>
                                    <div class="form-group form_link hide" app-field-wrapper="href">
                                        <label for="href"><?php echo _l('mcl_link'); ?></label>
                                        <div class="input-group">
                                            <span class="input-group-addon" id="internal_link_prefix">
                                                <span><?php echo html_escape(site_url()); ?></span>
                                            </span>
                                            <div class="input-group-btn hide" id="external_link_prefix">
                                                <button type="button" class="btn btn-default dropdown-toggle mcl_http" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">http:// <span class="caret"></span></button>
                                                <ul class="dropdown-menu">
                                                    <li><a href="#" onclick="setHttp('0'); return false;" id="http_protocol_0">http://</a></li>
                                                    <li><a href="#" onclick="setHttp('1'); return false;" id="http_protocol_1">https://</a></li>
                                                </ul>
                                            </div>
                                            <input type="text" name="href" class="form-control" id="href"
                                                   value="<?php echo html_escape($value); ?>">
                                        </div>
                                    </div>
                                    <?php
                                    $value = isset($link) ? $link['position'] : '';
                                    echo render_input('position', _l('mcl_position'), $value);
                                    ?>
                                </div>
                                <!-- OTHER OPTIONS -->
                                <div role="tabpanel" class="tab-pane" id="other_options">
                                    <div class="form-group">
                                        <label for="show_in0"><?php echo _l('mcl_show_in'); ?></label><br/>
                                        <div class="radio radio-inline radio-primary">
                                            <input type="radio" name="show_in" id="show_in0"
                                                   value="0" <?php if (isset($link) && $link['show_in'] == "0" || !isset($link)) {
                                                echo 'checked';
                                            } ?>>
                                            <label for="show_in0"><?php echo _l('mcl_same_tab'); ?></label>
                                        </div>

                                        <div class="radio radio-inline radio-primary">
                                            <input type="radio" name="show_in" id="show_in1"
                                                   value="1" <?php if (isset($link) && $link['show_in'] == "1") {
                                                echo 'checked';
                                            } ?>>
                                            <label for="show_in1"><?php echo _l('mcl_open_in_new_window'); ?></label>
                                        </div>

                                        <div class="radio radio-inline radio-primary">
                                            <input type="radio" name="show_in" id="show_in2"
                                                   value="2" <?php if (isset($link) && $link['show_in'] == "2") {
                                                echo 'checked';
                                            } ?>>
                                            <label for="show_in2"><?php echo _l('mcl_show_in_iframe'); ?></label>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-md-6">
                                            <?php
                                            $value = isset($link) ? $link['badge'] : '';
                                            echo render_input('badge', _l('mcl_badge'), $value, 'text', ["maxlength" => "63"], [], ' form-field-badge hide');
                                            ?>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-field-badge hide">
                                                <?php
                                                $value = isset($link) ? $link['badge_color'] : '';
                                                echo render_color_picker('badge_color', _l('mcl_badge_color'), $value);
                                                ?>
                                            </div>
                                        </div>
                                    </div>


                                    <?php
                                    $value = isset($link) ? $link['icon'] : '';
                                    ?>
                                    <div class="form-group form-icon hide">
                                        <label for="icon-new"><?php echo _l('mcl_icon'); ?></label>
                                        <div class="input-group">
                                            <input type="text" name="icon" value="<?php echo html_escape($value); ?>"
                                                   class="form-control icon-picker" id="icon-new">
                                            <span class="input-group-addon"><i class="fa"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="restriction">

                                    <div class="form-group form-require-login hide">
                                        <label for="require_login0"><?php echo _l('mcl_require_client_login'); ?></label><br/>
                                        <div class="radio radio-inline radio-primary">
                                            <input type="radio" name="require_login" id="require_login1"
                                                   value="1" <?php if (isset($link) && $link['require_login'] == "1") {
                                                echo 'checked';
                                            } ?>>
                                            <label for="require_login1"><?php echo _l('settings_yes'); ?></label>
                                        </div>

                                        <div class="radio radio-inline radio-primary">
                                            <input type="radio" name="require_login" id="require_login0"
                                                   value="0" <?php if (isset($link) && $link['require_login'] == "0" || !isset($link)) {
                                                echo 'checked';
                                            } ?>>
                                            <label for="require_login0"><?php echo _l('settings_no'); ?></label>
                                        </div>
                                    </div>
                                    <?php
                                    $value = isset($link) ? explode(",", $link['clients']) : '';
                                    $class = '';
                                    if ($client_ajax)
                                        $class = 'ajax-search';
                                    echo render_select('clients[]', $clients, ["userid", "company"], _l('mcl_restrict_clients'), $value, ["multiple" => true, "data-none-selected-text" => _l("mcl_show_to_all_clients")], [], 'form-field-clients hide', 'clients '.$class, false)
                                    ?>

                                    <?php
                                    $value = isset($link) ? explode(",", $link['roles']) : '';
                                    echo render_select('roles[]', $staff_roles, ["roleid", "name"], _l('mcl_restrict_roles'), $value, ["multiple" => true, "data-none-selected-text" => _l("mcl_show_to_all_roles")], [], 'form-field-roles hide', 'roles', false)
                                    ?>
                                    <?php
                                    $value = isset($link) ? explode(",", $link['users']) : '';
                                    $class = '';
                                    if ($staff_ajax)
                                        $class = 'ajax-search';
                                    echo render_select('users[]', $staff, ["staffid", ["firstname", 'lastname']], _l('mcl_restrict_staff'), $value, ["multiple" => true, "data-none-selected-text" => _l("mcl_show_to_all_staff")], [], 'form-field-users hide', $class, false)
                                    ?>
                                </div>
                            </div>

                            <hr class="hr-panel-heading"/>
                            <?php if (isset($link)) { ?>
                                <a href="<?php echo html_escape(admin_url('custom_links')); ?>"
                                   class="btn btn-primary pull-left mright5"><?php echo _l('add_new'); ?></a>
                                <a href="<?php echo html_escape(admin_url('custom_links')); ?>"
                                   class="btn btn-primary pull-right mright5"><?php echo _l('cancel'); ?></a>
                            <?php } ?>
                            <button type="submit"
                                    class="btn btn-primary pull-right mright5"><?php echo _l('save'); ?></button>
                            <?php echo form_close(); ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <div class="col-md-6">
                <div class="panel_s" id="custom-links-list">
                    <div class="panel-body">
                        <h4 class="no-margin inline-block">
                            <?php echo html_escape($title); ?>
                        </h4>
                        <hr class="hr-panel-heading"/>
                        <?php if (isset($main_links) && count($main_links) > 0) { ?>
                            <p class="mtop15 text-primary link-headings"><?php echo _l('mcl_main_menu'); ?></p>
                            <div class="dd active">
                                <ol class="dd-list" id="main-links-list">
                                    <?php foreach ($main_links as $link) { ?>
                                        <li class="dd-item dd3-item main"
                                            data-id="<?php echo html_escape($link['id']); ?>">
                                            <div class="dd3-content">
                                                <span class="dd-icon"><?php if (!empty($link['icon'])) echo '<i class="fa ' . $link['icon'] . '"></i>'; ?></span>
                                                <?php echo _l($link['title'], '', false); ?>
                                                <?php if (has_permission('custom_links', '', 'delete')) { ?>
                                                    <a href="<?php echo admin_url('custom_links/delete/' . $link['id']); ?>"
                                                       onclick="return confirm('<?php echo _l('mcl_confirm_delete_link'); ?>');"
                                                       class="text-muted pull-right text-danger mleft10"><i
                                                                class="fa fa-trash"></i></a>
                                                <?php } ?>
                                                <?php if (has_permission('custom_links', '', 'edit')) { ?>
                                                    <a href="<?php echo admin_url('custom_links/link/' . $link['id']); ?>"
                                                       class="text-muted pull-right"><i class="fa fa-pencil"></i></a>
                                                <?php } ?>
                                            </div>
                                        </li>
                                        <?php if(isset($link['children'])){ ?>
                                        <ol class="dd-list">
                                            <?php foreach ($link['children'] as $child){ ?>
                                                <li class="dd-item dd3-item main"
                                                    data-id="<?php echo html_escape($child['id']); ?>">
                                                    <div class="dd3-content">
                                                        <span class="dd-icon"><?php if (!empty($child['icon'])) echo '<i class="fa ' . $child['icon'] . '"></i>'; ?></span>
                                                        <?php echo _l($child['title'], '', false); ?>
                                                        <?php if (has_permission('custom_links', '', 'delete')) { ?>
                                                            <a href="<?php echo admin_url('custom_links/delete/' . $child['id']); ?>"
                                                               onclick="return confirm('<?php echo _l('mcl_confirm_delete_link'); ?>');"
                                                               class="text-muted pull-right text-danger mleft10"><i
                                                                        class="fa fa-trash"></i></a>
                                                        <?php } ?>
                                                        <?php if (has_permission('custom_links', '', 'edit')) { ?>
                                                            <a href="<?php echo admin_url('custom_links/link/' . $child['id']); ?>"
                                                               class="text-muted pull-right"><i class="fa fa-pencil"></i></a>
                                                        <?php } ?>
                                                    </div>
                                                </li>
                                            <?php } ?>
                                        </ol>
                                        <?php } ?>
                                    <?php } ?>
                                </ol>
                            </div>
                        <?php }
                        if (isset($setup_links) && count($setup_links) > 0) { ?>
                            <div class="clearfix"></div>
                            <p class="mtop15 text-primary link-headings"><?php echo _l('mcl_setup_menu'); ?></p>
                            <div class="dd active">
                                <ol class="dd-list" id="setup-links-list">
                                    <?php foreach ($setup_links as $link) { ?>
                                        <li class="dd-item dd3-item main"
                                            data-id="<?php echo html_escape($link['id']); ?>">
                                            <div class="dd3-content">
                                                <span class="dd-icon"><?php if (!empty($link['icon'])) echo '<i class="fa ' . $link['icon'] . '"></i>'; ?></span>
                                                <?php echo _l($link['title'], '', false); ?>
                                                <?php if (has_permission('custom_links', '', 'delete')) { ?>
                                                    <a href="<?php echo admin_url('custom_links/delete/' . $link['id']); ?>"
                                                       onclick="return confirm('<?php echo _l('mcl_confirm_delete_link'); ?>');"
                                                       class="text-muted pull-right text-danger mleft10"><i
                                                                class="fa fa-trash"></i></a>
                                                <?php } ?>
                                                <?php if (has_permission('custom_links', '', 'edit')) { ?>
                                                    <a href="<?php echo admin_url('custom_links/link/' . $link['id']); ?>"
                                                       class="text-muted pull-right"><i class="fa fa-pencil"></i></a>
                                                <?php } ?>
                                            </div>
                                        </li>

                                        <?php if(isset($link['children'])){ ?>
                                            <ol class="dd-list">
                                                <?php foreach ($link['children'] as $child){ ?>
                                                    <li class="dd-item dd3-item main"
                                                        data-id="<?php echo html_escape($child['id']); ?>">
                                                        <div class="dd3-content">
                                                            <span class="dd-icon"><?php if (!empty($child['icon'])) echo '<i class="fa ' . $child['icon'] . '"></i>'; ?></span>
                                                            <?php echo _l($child['title'], '', false); ?>
                                                            <?php if (has_permission('custom_links', '', 'delete')) { ?>
                                                                <a href="<?php echo admin_url('custom_links/delete/' . $child['id']); ?>"
                                                                   onclick="return confirm('<?php echo _l('mcl_confirm_delete_link'); ?>');"
                                                                   class="text-muted pull-right text-danger mleft10"><i
                                                                            class="fa fa-trash"></i></a>
                                                            <?php } ?>
                                                            <?php if (has_permission('custom_links', '', 'edit')) { ?>
                                                                <a href="<?php echo admin_url('custom_links/link/' . $child['id']); ?>"
                                                                   class="text-muted pull-right"><i class="fa fa-pencil"></i></a>
                                                            <?php } ?>
                                                        </div>
                                                    </li>
                                                <?php } ?>
                                            </ol>
                                        <?php } ?>
                                    <?php } ?>
                                </ol>
                            </div>
                        <?php }
                        if (isset($client_links) && count($client_links) > 0) { ?>
                            <div class="clearfix"></div>
                            <p class="mtop15 text-primary link-headings"><?php echo _l('mcl_client_menu'); ?></p>
                            <div class="dd active">
                                <ol class="dd-list" id="setup-links-list">
                                    <?php foreach ($client_links as $link) { ?>
                                        <li class="dd-item dd3-item main"
                                            data-id="<?php echo html_escape($link['id']); ?>">
                                            <div class="dd3-content">
                                                <span class="dd-icon"><?php if (!empty($link['icon'])) echo '<i class="fa ' . $link['icon'] . '"></i>'; ?></span>
                                                <?php echo _l($link['title'], '', false); ?>
                                                <?php if (has_permission('custom_links', '', 'delete')) { ?>
                                                    <a href="<?php echo admin_url('custom_links/delete/' . $link['id']); ?>"
                                                       onclick="return confirm('<?php echo _l('mcl_confirm_delete_link'); ?>');"
                                                       class="text-muted pull-right text-danger mleft10"><i
                                                                class="fa fa-trash"></i></a>
                                                <?php } ?>
                                                <?php if (has_permission('custom_links', '', 'edit')) { ?>
                                                    <a href="<?php echo admin_url('custom_links/link/' . $link['id']); ?>"
                                                       class="text-muted pull-right"><i class="fa fa-pencil"></i></a>
                                                <?php } ?>
                                            </div>
                                        </li>
                                    <?php } ?>
                                </ol>
                            </div>
                        <?php }
                        if (
                            (!isset($main_links) || count($main_links) == 0)
                            && (!isset($setup_links) || count($setup_links) == 0)
                            && (!isset($client_links) || count($client_links) == 0)
                        ) { ?>
                            <div class="alert alert-warning"><?php echo _l('mcl_no_ling_msg'); ?></div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>
<?php if (isset($link) && !empty($link['users'])) { ?>
    <script>
        $(document).ready(function () {
            $("[name='users[]']").selectpicker("val", <?php echo json_encode(explode(",", $link['users'])); ?>);
        })
    </script>
<?php } ?>
</body>
</html>
