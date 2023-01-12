<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
        <div class="page-title clearfix">
            <h1> <?php echo lang('raffle_draw'); ?></h1>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("Raffle_draw/modal_form"), "<i class='fa fa-plus'></i> " . lang('create_new_draw'), array("class" => "btn btn-default", "title" => lang('create_new_draw'))); ?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="raffle_draw-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#raffle_draw-table").appTable({
            source: '<?php echo_uri("Raffle_draw/list_data") ?>',
            order: [[1, 'desc']],
            columns: [
                {visible: false, searchable: false},
                {title: '<?php echo lang("uuid"); ?>'},
                {title: '<?php echo lang("event_name"); ?>'},
                {title: '<?php echo lang("title"); ?>'},
                {title: '<?php echo lang("description"); ?>'},
                {title: '<?php echo lang("number_of_winners"); ?>'},
                {title: '<?php echo lang("labels"); ?>'},
                {title: '<?php echo lang("remarks"); ?>'},
                {title: '<?php echo lang("ranking"); ?>'},
                {title: '<?php echo lang("draw_date"); ?>'},
                {title: '<?php echo lang("status"); ?>'},
                {title: '<?php echo lang("created_by"); ?>'},
                {title: '<?php echo lang("date_created"); ?>'},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ],
            tableRefreshButton: true,
            printColumns: [1,2,3,4,5,6,7,8,9,10,11,12],
            xlsColumns: [1,2,3,4,5,6,7,8,9,10,11,12],
        });
    });
</script>