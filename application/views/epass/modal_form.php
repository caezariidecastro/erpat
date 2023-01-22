<?php echo form_open(get_uri("EventPass/update"), array("id" => "epass-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <div class="form-group">
        <div class="col-md-12">
            <?php
            echo form_input(array(
                "id" => "title",
                "name" => "title",
                "value" => lang('reference_id').": ".strtoupper($model_info->uuid),
                "class" => "form-control notepad-title",
                "placeholder" => lang('title'),
                "disabled" => true
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12">
            <?php
            echo form_input(array(
                "id" => "event_name",
                "name" => "event_name",
                "value" => lang('event_name').": ".$model_info->event_name,
                "class" => "form-control notepad-title",
                "placeholder" => lang('event_name'),
                "autofocus" => true,
                "disabled" => true
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-8">
            <?php
            echo form_input(array(
                "id" => "full_name",
                "name" => "full_name",
                "value" => lang('fullname').": ".$model_info->full_name,
                "class" => "form-control notepad-title",
                "placeholder" => lang('full_name'),
                "disabled" => true
            ));
            ?>
        </div>
        <div class="col-md-4">
            <?php
            echo form_input(array(
                "id" => "seats",
                "name" => "seats",
                "value" => lang('seats_requested').": ".$model_info->seats,
                "class" => "form-control notepad-title",
                "placeholder" => lang('seats'),
                "disabled" => true
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-6">
            <?php
            echo form_input(array(
                "id" => "group_name",
                "name" => "group_name",
                "value" => lang('group_name').": ".strtoupper($model_info->group_name),
                "class" => "form-control notepad-title",
                "placeholder" => lang('group_name'),
                "disabled" => true
            ));
            ?>
        </div>
        <div class="col-md-6">
            <?php
            echo form_input(array(
                "id" => "vcode",
                "name" => "vcode",
                "value" => lang('virtual_id').": ".strtoupper($model_info->vcode),
                "class" => "form-control notepad-title",
                "placeholder" => lang('vcode'),
                "disabled" => true
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12">
            <div class="notepad">
                <?php
                echo form_textarea(array(
                    "id" => "description",
                    "name" => "description",
                    "value" => $model_info->remarks,
                    "class" => "form-control",
                    "placeholder" => lang('remarks') . "...",
                    "data-rich-text-editor" => true,
                    "disabled" => true
                ));
                ?>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <input id="epass_status_input" type="hidden" name="status" value="" />
    <?php if($model_info->status == "approved") {?>
        <button data-status="cancelled" type="submit" class="btn btn-danger update-epass-status"><span class="fa fa-times-circle"></span> <?php echo lang('cancel'); ?></button>
    <?php } ?>
    <?php if($model_info->status == "draft" || $model_info->status == "cancelled") {?>
        <button data-status="approved" type="submit" class="btn btn-success update-epass-status"><span class="fa fa-check-circle "></span> <?php echo lang('approve'); ?></button>
    <?php } ?>
</div>

<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $(".update-epass-status").click(function() {
            $("#epass_status_input").val($(this).attr("data-status"));
        });

        $("#epass-form").appForm({
            onSuccess: function (result) {
                $("#epass-table").appTable({newData: result.data, dataId: result.id});
            }
        });
        $("#title").focus();
    });
</script>    