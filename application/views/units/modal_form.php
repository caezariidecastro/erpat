<?php echo form_open(get_uri("units/save"), array("id" => "units-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info ? $model_info->id : "" ?>" />

    <div class="form-group">
        <label for="title" class=" col-md-3"><?php echo lang('title'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "title",
                "name" => "title",
                "value" => $model_info ? $model_info->title : "",
                "class" => "form-control validate-hidden",
                "placeholder" => lang('title'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="value" class=" col-md-3"><?php echo lang('value'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "value",
                "name" => "value",
                "value" => $model_info ? $model_info->value : "",
                "class" => "form-control",
                "placeholder" => lang('value'),
                "type" => "number",
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div id="base_unit_wrapper" class="<?= $model_info->base_unit ? "" : "hide"?>">
        <?php 
            if($model_info->base_unit){
                $this->load->view("units/base_unit");
            }
        ?>
    </div>
    <div class="form-group">
        <label for="is_base_unit" class="col-md-3"><?php echo lang('is_base_unit'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_checkbox("is_base_unit", "1", $model_info->id ? ($model_info->base_unit ? false : true) : true, "id='is_base_unit' class='ml15'");
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
        $("#units-form").appForm({
            onSuccess: function (result) {
                $("#units-table").appTable({newData: result.data, dataId: result.id});
            }
        });

        $("#is_base_unit").change(function(){
            if($(this).prop("checked")){
                $('#base_unit_wrapper').html("");
                $("#base_unit_wrapper").addClass("hide");
            }
            else{
                appLoader.show();

                $.ajax({
                    url: "<?php echo get_uri("units/base_unit/") . $model_info->id ."/". $model_info->base_unit; ?>",
                    success: function (result) {
                        $('#base_unit_wrapper').html(result);
                        $("#base_unit_wrapper").removeClass("hide");
                        appLoader.hide();
                    }
                });
            }
        });

        $("#base_unit").select2();
    });
</script>