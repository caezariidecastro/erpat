<div id="page-content" class="p20 clearfix">
    <div class="page-title clearfix">
        <h1> <?php echo lang("raffle").": ".$model_info->title; ?></h1>
    </div>
    <div class="panel panel-default">
        <div class="table-responsive">
            <table id="participants_list-table" class="display" cellspacing="0" width="100%">            
            </table>
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
    });
</script>