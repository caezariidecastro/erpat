<?php echo form_open(get_uri("lds/pallets/prepare_export"), array("id" => "export-pallet-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div class="form-group">
        <label for="zone_id" class="col-md-3"><?php echo lang('zone'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("zone_id", $zone_dropdown, "", "class='select2 validate-hidden' ");
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="label_id" class="col-md-3"><?php echo lang('labels'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("label_id", $label_dropdown, "", "class='select2 validate-hidden' ");
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
                "placeholder" => lang('status')
            ));
            ?>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="submit" class="btn btn-primary"><span class="fa fa-download"></span> <?php echo lang('export'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#export-pallet-form").appForm({
            onSuccess: function (result) {
                if(result.success) {
                    for(var i=1; i<= result.pages; i++) {
                        var win = window.open('<?= get_uri("lds/pallets/export_barcode/") ?>'+i+result.data, '_blank');
                        if (win) {
                            win.focus();
                        } else {
                            alert('Please allow popups for this website');
                        }
                    }
                } else {
                    alert('Something went wrong!');
                }
            }
        });

        $('.select2').select2();
        $('#label_id').on("change", function (e) { console.log("change"); });
        $("#status").select2({data: <?= json_encode(array_replace($status_select2, array(0 => array("id" => "", "text"  => "-"))))?> });
        $('#zone_id').on("change", function (e) { console.log("change"); });
    });
</script>