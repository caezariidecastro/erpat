<?php echo form_open(get_uri("bill_of_materials/delete"), array("id" => "delete-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $id?>" />
    <?php echo lang("delete_confirmation_message")?>
    
</div>

<div class="modal-footer">
    <button id="submit_button" type="submit" class="btn btn-danger"><span class="fa fa-trash"></span> <?php echo lang('delete'); ?></button>
    <button id="close_button" type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('cancel'); ?></button>
</div>

<?php echo form_close(); ?>


<script type="text/javascript">
    $(document).ready(function () {
        $("#delete-form").appForm({
            onSuccess: function (result) {
                $("#inventory-item-entries-table").appTable({reload: true});
            },
            onSubmit: function(){
                $("#submit_button").css({
                    "visibility": "hidden"
                });
                $("#close_button").css({
                    "visibility": "hidden"
                });
                $(".modal-footer").css({
                    "border-top": "1px solid #ffffff"
                });
            }
        });

        $("#ajaxModal .modal-dialog").css({
            "width": "400px"
        });
    });
</script>