<?php echo form_open(get_uri("hrs/team_members/update_user_form/".$model_info->id), array("id" => "edit-team_member-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div role="tabpanel" class="tab-pane">
        <div class="form-group">
            <label for="name" class=" col-md-3"><?php echo lang('first_name'); ?></label>
            <div class=" col-md-9">
                <?php
                echo form_input(array(
                    "id" => "first_name",
                    "name" => "first_name",
                    "value" => $model_info->first_name,
                    "class" => "form-control",
                    "placeholder" => lang('first_name'),
                    "autofocus" => true,
                    "data-rule-required" => true,
                    "data-msg-required" => lang("field_required"),
                ));
                ?>
            </div>
        </div>
        <div class="form-group">
            <label for="last_name" class=" col-md-3"><?php echo lang('last_name'); ?></label>
            <div class=" col-md-9">
                <?php
                echo form_input(array(
                    "id" => "last_name",
                    "name" => "last_name",
                    "value" => $model_info->last_name,
                    "class" => "form-control",
                    "placeholder" => lang('last_name'),
                    "data-rule-required" => true,
                    "data-msg-required" => lang("field_required"),
                ));
                ?>
            </div>
        </div>
        <div class="form-group">
            <label for="job_title" class=" col-md-3"><?php echo lang('job_title'); ?></label>
            <div class=" col-md-9">
                <?php
                echo form_input(array(
                    "id" => "job_title",
                    "name" => "job_title",
                    "value" => $model_info->job_title,
                    "class" => "form-control",
                    "placeholder" => lang('job_title'),
                ));
                ?>
            </div>
        </div>

        <div class="form-group">
            <label for="role" class="col-md-3"><?php echo lang('role'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_dropdown("role", $role_dropdown, array($model_info->role_id), "class='select2 validate-hidden' id='user-role'");
                ?>
                <div id="user-role-help-block" class="help-block ml10 <?php echo $model_info->role_id === "admin" ? "" : "hide" ?>"><i class="fa fa-warning text-warning"></i> <?php echo lang("admin_user_has_all_power"); ?></div>
            </div>
        </div>
        <div class="form-group">
            <label for="status_dropdown" class="col-md-3"><?php echo lang('status'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_dropdown("status_dropdown", $status_dropdown, array($model_info->user_status), "class='select2 validate-hidden' id='user-status'");
                ?>
            </div>
        </div>
        <div class="form-group">
            <label for="labels" class=" col-md-3"><?php echo lang('labels'); ?></label>
            <div class="col-md-9">
                <?php
                echo form_input(array(
                    "id" => "labels",
                    "name" => "labels",
                    "value" => $model_info ? $model_info->labels : "",
                    "class" => "form-control",
                    "placeholder" => lang('labels'),
                    "autofocus" => true,
                ));
                ?>
            </div>
        </div>
        <div class="form-group">
            <label for="disable_login" class="col-md-2"><?php echo lang('disable_login'); ?></label>
            <input id="jq_login" name="login" type="hidden" value="<?= $model_info->disable_login ?>"/>
            <div class="col-md-10">
                <?php
                echo form_checkbox("disable_login", "1", $model_info->disable_login ? true : false, "id='disable_login' class='ml15'");
                ?>
                <span id="disable-login-help-block" class="ml10 <?php echo $model_info->disable_login ? "" : "hide" ?>"><i class="fa fa-warning text-warning"></i> <?php echo lang("disable_login_help_message"); ?></span>
            </div>
        </div>
        <div class="form-group">
            <label for="user_status" class="col-md-2"><?php echo lang('mark_as_inactive'); ?></label>
            <input id="jq_status" name="status" type="hidden" value="<?= $model_info->status ?>"/>
            <div class="col-md-10">
                <?php
                echo form_checkbox("user_status", "inactive", $model_info->status === "inactive" ? true : false, "id='user_status' class='ml15'");
                ?>
                <span id="user-status-help-block" class="ml10 <?php echo $model_info->status === "inactive" ? "" : "hide" ?>"><i class="fa fa-warning text-warning"></i> <?php echo lang("mark_as_inactive_help_message"); ?></span>
            </div>
        </div>

        <?php //print_r( $model_info ); //$this->load->view("custom_fields/form/prepare_context_fields", array("custom_fields" => $custom_fields, "label_column" => "col-md-3", "field_column" => " col-md-9")); ?> 

    </div>
</div>

<div class="modal-footer">
    <button class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
    <button id="form-submit" type="button" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#edit-team_member-form").appForm({
            onSuccess: function (result) {
                if (result.success) {
                    var radio = $('input[name=status]:checked').val();
                    var update = $('#jq_status').val();
                    //console.log($('input[name=status]:checked').val() + ' = ' + $('#jq_status').val());
                    if( (radio == "active" && update == "active") || (radio == "inactive" && update == "inactive") ) {
                        $("#team_member-table").appTable({newData: result.data, dataId: result.id});
                    } else {
                        //TODO: Remove the specific row in this datatables as not found in this radio status. Use: result.id
                        location.reload();
                    }
                }
            },
            onSubmit: function () {
                //Do something
            },
            onAjaxSuccess: function () {
                //Do something
            }
        });

        $('#user_status').change(function() {
            $('#jq_status').val(this.checked ? 'inactive' : 'active');
        });

        $('#disable_login').change(function() {
            $('#jq_login').val(this.checked ? '1' : '0');
        });

        $("#edit-team_member-form input").keydown(function (e) {
            if (e.keyCode === 13) {
                e.preventDefault();
                if ($('#form-submit').hasClass('hide')) {
                    $("#form-next").trigger('click');
                } else {
                    $("#edit-team_member-form").trigger('submit');
                }
            }
        });
        $("#first_name").focus();
        $("#edit-team_member-form .select2").select2();

        $("#form-submit").click(function () {
            $("#edit-team_member-form").trigger('submit');
        });

        $("#user-role").change(function () {
            if ($(this).val() === "admin") {
                $("#user-role-help-block").removeClass("hide");
            } else {
                $("#user-role-help-block").addClass("hide");
            }
        });

        $("#labels").select2({multiple: true, data: <?php echo json_encode($label_suggestions); ?>});

        $("#ajaxModalTitle").text("Edit user");
    });
</script>