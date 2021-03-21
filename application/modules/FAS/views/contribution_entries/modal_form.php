<?php echo form_open(get_uri("fas/contribution_entries/save"), array("id" => "contribution-entries-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info ? $model_info->id : "" ?>" />
    <input type="hidden" name="expense_id" value="<?php echo $model_info ? $model_info->expense_id : "" ?>" />
    <div class="form-group">
        <label for="account_id" class="col-md-3"><?php echo lang('account'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("account_id", $account_dropdown, $model_info ? $model_info->account_id : "", "class='select2 validate-hidden' id='account_id' data-rule-required='true' data-msg-required='".lang("field_required")."'");
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
        <label for="amount" class="col-md-3"><?php echo lang('amount'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "amount",
                "name" => "amount",
                "value" => $model_info ? $model_info->amount : "",
                "class" => "form-control",
                "placeholder" => lang('amount'),
                "type" => "number",
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="category" class="col-md-3"><?php echo lang('category'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("category", $category_dropdown, $model_info ? $model_info->category : "", "class='select2 validate-hidden' id='category' data-rule-required='true' data-msg-required='".lang("field_required")."'");
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
        setDatePicker("#date_from");
        setDatePicker("#date_to");

        $("#contribution-entries-form").appForm({
            onSuccess: function (result) {
                
                $("#contribution-entries-table").appTable({newData: result.data, dataId: result.id});
            }
        });

        $('.select2').select2();
    });
    
</script>