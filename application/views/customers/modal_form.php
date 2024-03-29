<?php echo form_open(get_uri("sales/Customers/save"), array("id" => "customer-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info ? $model_info->id : "" ?>" />

    <div class="form-group">
        <label for="first_name" class=" col-md-3"><?php echo lang('first_name'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "first_name",
                "name" => "first_name",
                "value" => $model_info ? $model_info->first_name : "",
                "class" => "form-control validate-hidden",
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
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "last_name",
                "name" => "last_name",
                "value" => $model_info ? $model_info->last_name : "",
                "class" => "form-control validate-hidden",
                "placeholder" => lang('last_name'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="email" class=" col-md-3"><?php echo lang('email'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "email",
                "name" => "email",
                "value" => $model_info ? $model_info->email : "",
                "class" => "form-control validate-hidden",
                "placeholder" => lang('email'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="phone" class=" col-md-3"><?php echo lang('phone'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "phone",
                "name" => "phone",
                "value" => $model_info ? $model_info->phone : "",
                "class" => "form-control validate-hidden",
                "placeholder" => lang('phone'),
                "autofocus" => true,
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="street" class=" col-md-3"><?php echo lang('street'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "street",
                "name" => "street",
                "value" => $model_info ? $model_info->street : "",
                "class" => "form-control validate-hidden",
                "placeholder" => lang('street'),
                "autofocus" => true,
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="city" class=" col-md-3"><?php echo lang('city'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "city",
                "name" => "city",
                "value" => $model_info ? $model_info->city : "",
                "class" => "form-control validate-hidden",
                "placeholder" => lang('city'),
                "autofocus" => true,
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="state" class=" col-md-3"><?php echo lang('state'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "state",
                "name" => "state",
                "value" => $model_info ? $model_info->state : "",
                "class" => "form-control validate-hidden",
                "placeholder" => lang('state'),
                "autofocus" => true,
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="zip" class=" col-md-3"><?php echo lang('zip'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "zip",
                "name" => "zip",
                "value" => $model_info ? $model_info->zip : "",
                "class" => "form-control validate-hidden",
                "placeholder" => lang('zip'),
                "autofocus" => true,
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="country" class=" col-md-3"><?php echo lang('country'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "country",
                "name" => "country",
                "value" => $model_info ? $model_info->country : "",
                "class" => "form-control validate-hidden",
                "placeholder" => lang('country'),
                "autofocus" => true,
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
        $("#customer-form").appForm({
            onSuccess: function (result) {
                $("#customer-table").appTable({newData: result.data, dataId: result.id});
            }
        });
    });
</script>