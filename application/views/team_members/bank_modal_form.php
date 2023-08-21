<?php echo form_open(get_uri("hrs/team_members/update_bank_detail"), array("id" => "update-bank-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" id="user_id" name="user_id" value="<?= $model_info->user_id ?>"/>
    <div role="tabpanel" class="tab-pane">
        <div class="form-group">
            <label for="bank_name" class=" col-md-4"><?php echo lang('bank_name'); ?></label>
            <div class=" col-md-8">
                <?php
                echo form_input(array(
                    "id" => "bank_name",
                    "name" => "bank_name",
                    "class" => "form-control",
                    "value" => $model_info->bank_name,
                    "placeholder" => lang('bank_name'),
                ));
                ?>
            </div>
        </div>
        <div class="form-group">
            <label for="bank_account" class=" col-md-4"><?php echo lang('bank_account'); ?></label>
            <div class=" col-md-8">
                <?php
                echo form_input(array(
                    "id" => "bank_account",
                    "name" => "bank_account",
                    "class" => "form-control",
                    "value" => $model_info->bank_account,
                    "placeholder" => lang('bank_account'),
                ));
                ?>
            </div>
        </div>
        <div class="form-group">
            <label for="bank_number" class=" col-md-4"><?php echo lang('bank_number'); ?></label>
            <div class=" col-md-8">
                <?php
                echo form_input(array(
                    "id" => "bank_number",
                    "name" => "bank_number",
                    "type" => "number",
                    "class" => "form-control",
                    "value" => $model_info->bank_number,
                    "placeholder" => lang('bank_number'),
                ));
                ?>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">

    $(document).ready(function () {
        $("#update-bank-form").appForm({
            onSuccess: function (result) {
                $(".dataTable:visible").appTable({newData: result.data, dataId: result.id});
            }
        });

        $("#ajaxModalTitle").text("Update Bank Details");
    });

</script>