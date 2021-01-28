<?php echo form_open(get_uri("productions/save"), array("id" => "production-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info ? $model_info->id : "" ?>" />
    <?php
        if(!$model_info->id){
    ?>
    <div class="form-group">
        <label for="bill_of_material_id" class="col-md-3"><?php echo lang('bill_of_material'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("bill_of_material_id", $bill_of_material_dropdown, "", "class='select2 validate-hidden' id='bill_of_material_id' data-rule-required='true' data-msg-required='".lang("field_required")."'");
            ?>
        </div>

        <script>
            $(document).ready(function () {
                $('#bill_of_material_id').select2();
            });
        </script>
    </div>
    <?php
        }
    ?>
    <?php
        if($model_info->id){
    ?>
    <div class="form-group">
        <input type="hidden" name="bill_of_material_id" value="<?php echo $model_info ? $model_info->bill_of_material_id : "" ?>" />
        <label for="status" class="col-md-3"><?php echo lang('status'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("status", $statuses_dropdown,  $model_info ? $model_info->status : "", "class='select2 validate-hidden' id='status' data-rule-required='true' data-msg-required='".lang("field_required")."'");
            ?>
        </div>

        <script>
            $(document).ready(function () {
                $('#status').select2();
            });
        </script>
    </div>
    <?php
        }
    ?>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#production-form").appForm({
            onSuccess: function (result) {
                $("#production-table").appTable({newData: result.data, dataId: result.id});
            }
        });
    });
</script>