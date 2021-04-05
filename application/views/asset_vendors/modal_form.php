<?php echo form_open(get_uri("ams/vendors/save"), array("id" => "asset-vendors-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info ? $model_info->id : "" ?>" />
    <div class="form-group">
        <label for="name" class="col-md-3"><?php echo lang('name'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "name",
                "name" => "name",
                "value" => $model_info ? $model_info->name : "",
                "class" => "form-control",
                "placeholder" => lang('name'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="address" class="col-md-3"><?php echo lang('address'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_textarea(array(
                "id" => "address",
                "name" => "address",
                "value" => $model_info ? $model_info->address : "",
                "class" => "form-control",
                "placeholder" => lang('address'),
                "data-rich-text-editor" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="city" class="col-md-3"><?php echo lang('city'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "city",
                "name" => "city",
                "value" => $model_info ? $model_info->city : "",
                "class" => "form-control",
                "placeholder" => lang('city'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="state" class="col-md-3"><?php echo lang('state'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "state",
                "name" => "state",
                "value" => $model_info ? $model_info->state : "",
                "class" => "form-control",
                "placeholder" => lang('state'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="zip" class="col-md-3"><?php echo lang('zip'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "zip",
                "name" => "zip",
                "value" => $model_info ? $model_info->zip : "",
                "class" => "form-control",
                "placeholder" => lang('zip'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="country" class="col-md-3"><?php echo lang('country'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "country",
                "name" => "country",
                "value" => $model_info ? $model_info->country : "",
                "class" => "form-control",
                "placeholder" => lang('country'),
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <?php if($model_info->id){?>
    <div class="form-group">
        <label for="status" class=" col-md-3"><?php echo lang("status")?></label>
        <div class="col-md-9" id="invoice_client_selection_wrapper">
            <?php
            echo form_input(array(
                "id" => "status",
                "name" => "status",
                "value" => $model_info ? $model_info->status : "",
                "class" => "form-control validate-hidden",
                "data-rule-required" => "true",
                "data-msg-required" => lang('field_required'),
                "placeholder" => lang('status')
            ));
            ?>
        </div>
    </div>
    <?php }else{?>
    <input type="hidden" name="status" value="active"/>
    <?php }?>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        let isUpdate = "<?php echo $model_info->id?>";

        if(isUpdate){
            $("#status").select2({data: <?= json_encode(array_replace($status_select2, array(0 => array("id" => "", "text"  => "-"))))?> });
        }

        $("#asset-vendors-form").appForm({
            onSuccess: function (result) {
                $("#asset-vendors-table").appTable({newData: result.data, dataId: result.id});
            }
        });
    });
    
</script>