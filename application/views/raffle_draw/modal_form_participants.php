<div id="page-content" class="p20 clearfix participants-modal">
    <div class="page-title clearfix">
        <h1> <?php echo lang("raffle").": ".$model_info->title; ?></h1>
    </div>
    <div class="panel panel-default">
        <div class="table-responsive">
            <table id="participants_list-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
    <div class="page-title clearfix">
        <div class="title-button-group" style="display: flex; margin-bottom: 10px;">
            <?php echo form_open(get_uri("Raffle_draw/anonymous_participants/".$model_info->id), array("id" => "anonymous_participants-form", "class" => "general-form", "role" => "form")); ?>
                <button type="submit" class="btn btn-info"><span class="fa fa-recycle "></span>  <?php echo lang('ananymous_subscribers'); ?></button>
            <?php echo form_close(); ?>
            <?php echo form_open(get_uri("Raffle_draw/export_qrcode/".$model_info->id), array("id" => "export_qrcode-form", "class" => "general-form", "role" => "form")); ?>
                <button type="submit" class="btn btn-success"><span class="fa fa-print "></span>  <?php echo lang('export_qrcode'); ?></button>
            <?php echo form_close(); ?>
            <?php echo form_open(get_uri("Raffle_draw/join_subscribers/".$model_info->id), array("id" => "join_subscribers-form", "class" => "general-form", "role" => "form")); ?>
                <button type="submit" class="btn btn-warning"><span class="fa fa-plus "></span>  <?php echo lang('join_subscribers'); ?></button>
            <?php echo form_close(); ?>
            <?php echo form_open(get_uri("Raffle_draw/clear_participants/".$model_info->id), array("id" => "clear_participants-form", "class" => "general-form", "role" => "form")); ?>
                <button type="submit" class="btn btn-danger"><span class="fa fa-trash "></span>  <?php echo lang('clear_participants'); ?></button>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#participants_list-table").appTable({
            source: '<?php echo_uri("Raffle_draw/list_participants/".$model_info->id) ?>',
            order: [[1, 'desc']],
            columns: [
                {visible: false, searchable: false},
                {title: '<?php echo lang("uuid"); ?>'},
                {title: '<?php echo lang("participants"); ?>'},
                {title: '<?php echo lang("remarks"); ?>'},
                {title: '<?php echo lang("updated_at"); ?>'},
                //{title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ],
            tableRefreshButton: true,
            printColumns: [1,2,3,4],
            xlsColumns: [1,2,3,4],
        });

        $("#anonymous_participants-form").appForm({
            closeModalOnSuccess: false,
            onSuccess: function (result) {
                if(result.success) {
                    $('#participants_list-table').DataTable().ajax.reload();
                }
            },
            beforeAjaxSubmit: function(data, self, options) {
                appLoader.show({container: ".participants-modal", css: "left:0; top: 50%;"});
            },
            onAjaxSuccess: function (result) {
                if(!result.success) {
                    alert(result.message);
                }
                appLoader.hide();
            }
        });

        $("#join_subscribers-form").appForm({
            closeModalOnSuccess: false,
            onSuccess: function (result) {
                if(result.success) {
                    $('#participants_list-table').DataTable().ajax.reload();
                }
            },
            beforeAjaxSubmit: function(data, self, options) {
                appLoader.show({container: ".participants-modal", css: "left:0; top: 50%;"});
            },
            onAjaxSuccess: function (result) {
                if(!result.success) {
                    alert(result.message);
                }
                appLoader.hide();
            }
        });

        $("#clear_participants-form").appForm({
            closeModalOnSuccess: false,
            onSuccess: function (result) {
                if(result.success) {
                    console.log('Cleared');
                    $('#participants_list-table').DataTable().clear().draw();
                }
            },
            onAjaxSuccess: function (result) {
                if(!result.success) {
                    alert(result.message);
                }
            }
        });
    });
</script>