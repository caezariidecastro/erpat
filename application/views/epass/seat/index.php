<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
        <div class="page-title clearfix">
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("Epass_Seat/modal_form"), "<i class='fa fa-plus'></i> " . lang('add_seat'), array("class" => "btn btn-default", "title" => lang('add_seat'))); ?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="seat-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#seat-table").appTable({
            source: '<?php echo_uri("Epass_Seat/list_data") ?>',
            order: [[1, 'desc']],
            columns: [
                {visible: false, searchable: false},
                {title: '<?php echo lang("event_name"); ?>'},
                {title: '<?php echo lang("area_name"); ?>'},
                {title: '<?php echo lang("block_name"); ?>'},
                {title: '<?php echo lang("seat_name"); ?>'},
                {title: '<?php echo lang("sort"); ?>'},
                {title: '<?php echo lang("remarks"); ?>'},
                {title: '<?php echo lang("date"); ?>'},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ],
            tableRefreshButton: true,
            // printColumns: [2, 4],
            // xlsColumns: [2, 4],
        });
    });
</script>