<?php echo form_open(get_uri("lds/pallets/save_bulk"), array("id" => "pallet-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div class="form-group">
        <label for="total" class=" col-md-3"><?php echo lang('total'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "total",
                "name" => "total",
                "value" => "",
                "type" => "number",
                "class" => "form-control validate-hidden",
                "placeholder" => lang('total'),
                "autofocus" => true,
                "data-rule-required" => "true",
                "data-msg-required" => lang('field_required'),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="zone_id" class="col-md-3"><?php echo lang('zone'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("zone_id", $zone_dropdown, "", "class='select2 validate-hidden' ");
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="remarks" class=" col-md-3"><?php echo lang('remarks'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "remarks",
                "name" => "remarks",
                "value" => "",
                "class" => "form-control validate-hidden",
                "placeholder" => lang('remarks'),
                "autofocus" => true,
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="labels" class=" col-md-3"><?php echo lang('labels'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "labels",
                "name" => "labels",
                "value" => "",
                "class" => "form-control",
                "placeholder" => lang('labels'),
                "autofocus" => true,
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="status" class=" col-md-3"><?php echo lang("status")?></label>
        <div class="col-md-9" id="invoice_client_selection_wrapper">
            <?php
            echo form_input(array(
                "id" => "status",
                "name" => "status",
                "value" => "",
                "class" => "form-control validate-hidden",
                "data-rule-required" => "true",
                "data-msg-required" => lang('field_required'),
                "placeholder" => lang('status')
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
        $("#pallet-form").appForm({
            onSuccess: function (result) {
                location.reload();
            }
        });

        $('.select2').select2();
        $("#labels").select2({multiple: true, data: <?php echo json_encode($label_suggestions); ?>});

        $("#status").select2({data: <?= json_encode(array_replace($status_select2, array(0 => array("id" => "", "text"  => "-"))))?> });

        $('#zone_id').on("change", function (e) { console.log("change"); });
    });
</script>