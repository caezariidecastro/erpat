<div id="page-content" class="p20 clearfix winners-modal">
    <div class="page-title clearfix">
        <h1> <?php echo lang("raffle").": ".$model_info->title; ?></h1>
    </div>
    <div class="panel panel-default">
        <div class="table-responsive">
            <table id="winner_list-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
    <div class="page-title clearfix">
        <div class="title-button-group" style="display: flex; margin-bottom: 10px;">
            <?php echo form_open(get_uri("Raffle_draw/play/".$model_info->id), array("id" => "single_draw-form", "class" => "general-form", "role" => "form")); ?>
                <button type="submit" class="btn btn-warning"><span class="fa fa-rotate-right "></span>  <?php echo lang('test_draw'); ?></button>
            <?php echo form_close(); ?>
            <?php echo form_open(get_uri("Raffle_draw/clear_winners/".$model_info->id), array("id" => "clear_winners-form", "class" => "general-form", "role" => "form")); ?>
                <button type="submit" class="btn btn-danger"><span class="fa fa-trash "></span>  <?php echo lang('clear_winners'); ?></button>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#winner_list-table").appTable({
            source: '<?php echo_uri("Raffle_draw/list_winners/".$model_info->id) ?>',
            //order: [[1, 'desc']],
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

        $("#single_draw-form").appForm({
            closeModalOnSuccess: false,
            onSuccess: function (result) {
                if(result.success) {
                    for(var i=0; i<result.data.length; i++) {
                        $("#winner_list-table").appTable({newData: result.data[i]});
                    }
                }
            },
            beforeAjaxSubmit: function(data, self, options) {
                appLoader.show({container: ".winners-modal", css: "left:0; top: 50%;"});
            },
            onAjaxSuccess: function (result) {
                if(!result.success) {
                    alert(result.message);
                }
                appLoader.hide();
            }
        });

        $("#clear_winners-form").appForm({
            closeModalOnSuccess: false,
            onSuccess: function (result) {
                if(result.success) {
                    console.log('Cleared');
                    $('#winner_list-table').DataTable().clear().draw();
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