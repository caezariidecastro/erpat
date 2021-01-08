<?php echo form_open(get_uri("discipline_entries/save"), array("id" => "discipline-entries-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info ? $model_info->id : "" ?>" />
    <div class="form-group">
        <label for="category" class="col-md-3"><?php echo lang('category'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("category", $category_dropdown, $model_info ? $model_info->category : "", "class='select2 validate-hidden' id='category' data-rule-required='true' data-msg-required='".lang("field_required")."'");
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="date_occurred" class=" col-md-3"><?php echo lang('date_occurred'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "date_occurred",
                "name" => "date_occurred",
                "value" => $model_info ? $model_info->date_occurred : "",
                "class" => "form-control validate-hidden",
                "placeholder" => lang('date_occurred'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="user" class="col-md-3"><?php echo lang('user'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("user", $user_dropdown, $model_info ? $model_info->user : "", "class='select2 validate-hidden' id='user' data-rule-required='true' data-msg-required='".lang("field_required")."'");
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="witness" class="col-md-3"><?php echo lang('witness'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("witness", $user_dropdown, $model_info ? $model_info->witness : "", "class='select2 validate-hidden' id='witness' data-rule-required='true' data-msg-required='".lang("field_required")."'");
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="description" class="col-md-3"><?php echo lang('description'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_textarea(array(
                "id" => "description",
                "name" => "description",
                "value" => $model_info ? $model_info->description : "",
                "class" => "form-control",
                "placeholder" => lang('description'),
                "data-rich-text-editor" => true
            ));
            ?>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        setDatePicker('#date_occurred');
    
        $("#discipline-entries-form").appForm({
            onSuccess: function (result) {
                $("#discipline-entries-table").appTable({newData: result.data, dataId: result.id});
            }
        });

        $('.select2').select2();
    });
</script>