<?php echo form_open(get_uri("EventPass/save_assigned_user"), array("id" => "add-user-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <div class="form-group">
        <div class="col-md-12">
              <input type="text" value="<?php echo $model_info->user_id; ?>" name="user_assigned" id="user_assigned" class="w100p validate-hidden"  placeholder="<?php echo lang('select_users'); ?>"  />    
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="submit" class="btn btn-success"><span class="fa fa-check-circle "></span> <?php echo lang('save'); ?></button>
</div>

<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#add-user-form").appForm({
            onSuccess: function (result) {
                $("#epass-table").appTable({newData: result.data, dataId: result.id});
            }
        });

        $("#user_assigned").select2({
            data: <?php echo json_encode($users_dropdown); ?>
        });
        $("#add-user-form .select2").select2();
    });
</script>    