<?php echo form_open(get_uri("hrs/leave_credits/save"), array("id" => "leave-credit-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <div class="form-group">
        <label for="user_id" class=" col-md-3"><?php echo lang('employee'); ?></label>
        <div class=" col-md-9">
            <?php
            if (isset($team_members_info)) {
                $image_url = get_avatar($team_members_info->image);
                echo "<span class='avatar avatar-xs mr10'><img src='$image_url' alt=''></span>" . $team_members_info->first_name . " " . $team_members_info->last_name;
                ?>
                <input type="hidden" name="user_id" value="<?php echo $team_members_info->id; ?>" />
                <?php
            } else {
                echo form_dropdown("user_id", $team_members_dropdown, "", "class='select2 validate-hidden' id='user_id' data-rule-required='true', data-msg-required='" . lang('field_required') . "'");
            }
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="leave_type" class=" col-md-3"><?php echo lang('leave_type'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_dropdown("leave_type_id", $leave_types_dropdown, "", "class='select2 validate-hidden' id='leave_type_id' data-rule-required='true', data-msg-required='" . lang('field_required') . "'");
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="title" class=" col-md-3"><?php echo lang('credits')." (".lang('days').")"; ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "counts",
                "name" => "counts",
                "value" => $model_info->counts,
                "type" => "number",
                "min" => "0",
                "class" => "form-control",
                "placeholder" => lang('number_of_days'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="remarks" class=" col-md-3"><?php echo lang('remarks'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_textarea(array(
                "id" => "remarks",
                "name" => "remarks",
                "value" => $model_info->remarks,
                "class" => "form-control",
                "placeholder" => lang('remarks'),
                "data-rich-text-editor" => true
            ));
            ?>
        </div>
    </div>
</div>

<div class="modal-footer">
    <input type="hidden" name="action" value="<?php echo $credit_form_action; ?>" />
    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function() {
        $("#leave-credit-form").appForm({
            onSuccess: function(result) {
                $("#leave-credit-table").dataTable()._fnAjaxUpdate();
                //$("#leave-credit-table").appTable({newData: result.data, dataId: result.id});
            }
        });
        $("#user_id").focus();

        if ($("#user_id").length) {
            $("#user_id").select2();
        }
        // setDatePicker("#in_date, #out_date");
        // setTimePicker("#in_time, #out_time");
        $("#leave_type_id").select2();
    });
</script>    