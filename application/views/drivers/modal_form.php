<?php echo form_open(get_uri("drivers/save"), array("id" => "driver-form", "class" => "general-form", "role" => "form")); ?>
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
        <label for="date_of_hire" class=" col-md-3"><?php echo lang('employment_date'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "date_of_hire",
                "name" => "date_of_hire",
                "value" => $model_info ? (isset($model_info->date_of_hire) ? $model_info->date_of_hire : "") : "",
                "class" => "form-control validate-hidden",
                "placeholder" => lang('date_of_hire'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="license_number" class=" col-md-3"><?php echo lang('license_number'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "license_number",
                "name" => "license_number",
                "value" => $model_info ? $model_info->license_number : "",
                "class" => "form-control validate-hidden",
                "placeholder" => lang('license_number'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="license_image" class=" col-md-3"><?php echo lang('license_image'); ?></label>
        <div class="col-md-9">
            <input id="license_image" class="form-control <?= $model_info->license_image ? 'hide' : ''?>" type="file" name="license_image"/>
            <?php
            $this->load->view("drivers/file_preview", array("file_name" => $model_info->license_image));
            ?>
        </div>
    </div>
    <div class="form-group" id="add_preview_wrapper">
        <label class=" col-md-3"></label>
        <div class="col-md-9">
            <img id="add_license_image_preview" style="width: 100%; height: auto;" src="" alt="">
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
        setDatePicker("#date_of_hire");

        $("#driver-form").appForm({
            onSuccess: function (result) {
                $("#driver-table").appTable({newData: result.data, dataId: result.id});
            }
        });

        $("#license_image").change(function() {
            readURL(this);
        });
    });

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
                $('#add_license_image_preview').attr('src', e.target.result);
                $("#edit_preview_wrapper").hide();
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>