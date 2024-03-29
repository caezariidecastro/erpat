<?php echo form_open(get_uri("production/BillOfMaterials/save"), array("id" => "bill-of-materials-form", "class" => "general-form", "role" => "form")); ?>
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
        <label for="description" class="col-md-3"><?php echo lang('description'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_textarea(array(
                "id" => "description",
                "name" => "description",
                "value" => $model_info ? $model_info->description : "",
                "class" => "form-control",
                "placeholder" => lang('description'),
                "data-rich-text-editor" => true
            ));
            ?>
        </div>
    </div>
    <?php 
        if($item_id){
    ?>
    <input type="hidden" name="item_id" value="<?php echo $item_id?>" />
    <script>
        $("#bill-of-materials-form").appForm({
            onSuccess: function (result) {
                $("#inventory-item-entries-table").appTable({reload: true});
            }
        });
    </script>
    <?php 
        }
        else{
    ?>
    <div class="form-group">
        <label for="item_id" class="col-md-3"><?php echo lang('product'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_dropdown("item_id", $product_dropdown, $model_info ? $model_info->item_id : "", "class='select2 validate-hidden' id='item_id' data-rule-required='true' data-msg-required='".lang("field_required")."'");
            ?>
        </div>
    </div>
    <?php 
        }
    ?>
    
    <div class="form-group">
        <label for="quantity" class=" col-md-3"><?php echo lang('quantity'); ?></label>
        <div class="col-md-6">
            <?php
            echo form_input(array(
                "id" => "quantity",
                "name" => "quantity",
                "value" => $model_info ? $model_info->quantity : "",
                "class" => "form-control validate-hidden",
                "placeholder" => lang('quantity'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
        <div class="col-md-3">
            <?php
            echo form_input(array(
                "id" => "unit_type",
                "name" => "unit_type",
                "value" => $unit_abbreviation,
                "class" => "form-control validate-hidden",
                "placeholder" => lang('unit_type'),
                "autofocus" => true,
                "disabled" => "disabled",
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
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
        $("#bill-of-materials-form").appForm({
            onSuccess: function (result) {
                $("#bill-of-materials-table").appTable({newData: result.data, dataId: result.id});
            }
        });

        $('.select2').select2();
        
        // $("#item_id").select2().change(function(){
        //     $.ajax({
        //         url: "<?php //echo base_url()?>sales/ProductEntries/inventory",
        //         method: "POST",
        //         data: {
        //             id: $(this).val(),
        //             json: true
        //         },
        //         dataType: "json",
        //         success: function(response){
        //             if(response.success){
        //                 $("#unit_type").val(response.data.unit_abbreviation);
        //             }
        //         }
        //     })
        // });

        $("#ajaxModal .modal-dialog").removeAttr("style");
    });
</script>