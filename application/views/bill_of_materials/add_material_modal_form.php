<div class="modal-body clearfix">
    <?php echo form_open(get_uri("bill_of_materials/add_material"), array("id" => "bill-of-materials-materials-form", "class" => "general-form", "role" => "form")); ?>
    <input type="hidden" name="id" value="<?php echo $model_info ? $model_info->id : "" ?>" />

    <div class="form-group">
        <label for="material_id" class="col-md-3"><?php echo lang('material'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("material_id", $material_dropdown, "", "class='select2 validate-hidden' id='material_id' data-rule-required='true' data-msg-required='".lang("field_required")."'");
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="quantity" class=" col-md-3"><?php echo lang('quantity'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "quantity",
                "name" => "quantity",
                "value" => "",
                "class" => "form-control validate-hidden",
                "placeholder" => lang('quantity'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-12">
            <button id="form-submit" type="button" class="btn btn-primary pull-right"><span class="fa fa-check-circle"></span> <?php echo lang('add'); ?></button>
        </div>
    </div>
    <?php echo form_close(); ?>
    <hr>
    <div class="form-group">
        <div class="col-md-12">
            <div class="table-responsive">
                <table id="bill-of-materials-materials-table" class="display" cellspacing="0" width="100%">            
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
</div>

<style type="text/css">
    .datatable-tools:first-child {
        display:  none;
    }
</style>

<script type="text/javascript">
    let bill_of_materials_materials_table;

    $(document).ready(function () {
        $("#bill-of-materials-materials-form").appForm({
            closeModalOnSuccess: false,
            onSuccess: function (result) {
                $("#bill-of-materials-materials-table").appTable({newData: result.data, dataId: result.id});
                $(".modal-mask").html("<div class='circle-done'><i class='fa fa-check'></i></div>");
                setTimeout(function () {
                    $(".modal-mask").find('.circle-done').addClass('ok');
                    setTimeout(function(){
                        $('.modal-body').removeClass('hide');
                        $(".modal-mask").remove();
                    }, 500);
                }, 300);

                $('#material_id').select2("val", "");
                $("#quantity").val("");
            }
        });

        $("#bill-of-materials-materials-table").appTable({
            source: '<?php echo_uri("bill_of_materials/material_list_data?id=".$model_info->id) ?>',
            order: [[0, 'desc']],
            columns: [
                {visible: false, searchable: false},
                {title: "<?php echo lang('material') ?> "},
                {title: "<?php echo lang('quantity') ?>"},
                {title: "<i class='fa fa-bars'></i>", "class": "text-center option w100"}
            ],
        });

        bill_of_materials_materials_table = $("#bill-of-materials-materials-table").DataTable();
        bill_of_materials_materials_table.columns(0).visible(false);

        $('#material_id').select2();

        $("#form-submit").click(function () {
            submit();
        });
    });

    function submit(){
        if(is_material_already_at_the_table()){
            if(confirm("Material is already at the table. Are you sure you want to add existing material?")){
                $("#bill-of-materials-materials-form").trigger('submit');
            }
            else{
                $('#material_id').select2("val", "");
                $("#quantity").val("");
            }
        }
        else{
            $("#bill-of-materials-materials-form").trigger('submit');
        }
    }

    function is_material_already_at_the_table(){
        let checker = false;

        bill_of_materials_materials_table.rows().every(function (){
            let data = this.data();

            if(data[0] == $('#material_id').select2("val")){
                checker = true;
            }
        });

        return checker;
    }
</script>