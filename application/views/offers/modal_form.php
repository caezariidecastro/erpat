<?php echo form_open(get_uri("offers/save"), array("id" => "offers-form", "class" => "general-form", "role" => "form")); ?>
<div id="offers-dropzone" class="post-dropzone">
    <div class="modal-body clearfix">
        <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
        <div class="form-group">
            <div class="col-md-12">
                <?php
                echo form_input(array(
                    "id" => "title",
                    "name" => "title",
                    "value" => $model_info->title,
                    "class" => "form-control notepad-title",
                    "placeholder" => lang('title'),
                    "autofocus" => true,
                    "data-rule-required" => true,
                    "data-msg-required" => lang("field_required"),
                ));
                ?>
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-6">
                <?php
                echo form_dropdown(
                        "type", array(
                            "percent" => lang("percent_amount"),
                            "fixed" => lang("fixed_amount"),
                        ), $model_info->type, "class='select2 mini'"
                );
                ?>
            </div>
            <div class="col-md-6">
                <?php
                echo form_input(array(
                    "id" => "off",
                    "name" => "off",
                    "type" => "number",
                    "value" => $model_info->off,
                    "class" => "form-control",
                    "placeholder" => lang('off'),
                    "autofocus" => true,
                    "data-rule-required" => true,
                    "data-msg-required" => lang("field_required"),
                ));
                ?>
            </div>
        </div>
        <div class="form-group">
            
            <div class="col-md-6">
                <?php
                echo form_input(array(
                    "id" => "min",
                    "name" => "min",
                    "type" => "number",
                    "value" => $model_info->min,
                    "class" => "form-control",
                    "placeholder" => lang('min'),
                    "autofocus" => true,
                    "data-rule-required" => true,
                    "data-msg-required" => lang("field_required"),
                ));
                ?>
            </div>
            <div class="col-md-6">
                <?php
                echo form_input(array(
                    "id" => "upto",
                    "name" => "upto",
                    "type" => "number",
                    "value" => $model_info->upto,
                    "class" => "form-control",
                    "placeholder" => lang('upto'),
                    "autofocus" => true,
                    "data-rule-required" => true,
                    "data-msg-required" => lang("field_required"),
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
                        "value" => $model_info->description,
                        "class" => "form-control",
                        "placeholder" => lang('description') . "...",
                        "data-rich-text-editor" => true
                    ));
                    ?>
                </div>
            </div>
        </div>
        <!-- <div class="form-group">
            <div class="col-md-12">
                <div class="notepad">
                    <?php /*
                    echo form_input(array(
                        "id" => "note_labels",
                        "name" => "labels",
                        "value" => $model_info->labels,
                        "class" => "form-control",
                        "placeholder" => lang('labels')
                    ));
                    */ ?>
                </div>
            </div>
        </div> -->

        <div class="form-group">
            <div class="col-md-12">
                <?php
                $this->load->view("includes/file_list", array("files" => $model_info->files));
                ?>
            </div>
        </div>

        <?php $this->load->view("includes/dropzone_preview"); ?>
    </div>

    <div class="modal-footer">
        <button class="btn btn-default upload-file-button pull-left btn-sm round" type="button" style="color:#7988a2"><i class="fa fa-camera"></i> <?php echo lang("upload_file"); ?></button>
        <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
        <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
    </div>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#offers-form .select2").select2();

        var uploadUrl = "<?php echo get_uri("offers/upload_file"); ?>";
        var validationUri = "<?php echo get_uri("offers/validate_offers_file"); ?>";

        var dropzone = attachDropzoneWithForm("#offers-dropzone", uploadUrl, validationUri);

        $("#offers-form").appForm({
            onSuccess: function (result) {
                $("#offers-table").appTable({newData: result.data, dataId: result.id});
            }
        });

        $("#title").focus();

        //$("#note_labels").select2({multiple: true, data: <?php echo json_encode($label_suggestions); ?>});
    });
</script>    