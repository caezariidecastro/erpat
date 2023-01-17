<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
        <div class="page-title clearfix">
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("EventPass/modal_form_add"), "<i class='fa fa-plus'></i> " . lang('add_epass'), array("class" => "btn btn-default", "title" => lang('add_epass'))); ?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="epass-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        
        $("#epass-table").appTable({
            source: '<?php echo_uri("EventPass/list_data") ?>',
            order: [[1, 'desc']],
            columns: [
                {visible: false, searchable: false},
                // {visible: false, searchable: false},
                //{title: '', "class": "w25"},
                {title: '<?php echo lang("reference_id"); ?>'},
                {title: '<?php echo lang("event_name"); ?>'},
                {title: '<?php echo lang("full_name"); ?>'},
                {title: '<?php echo lang("group_name"); ?>'},
                {title: '<?php echo lang("virtual_id"); ?>'},
                {title: '<?php echo lang("remarks"); ?>'},
                {title: '<?php echo lang("seats_requested"); ?>'},
                {title: '<?php echo lang("seats_assignment"); ?>', "class": "w200"},
                {title: '<?php echo lang("status"); ?>'},
                {title: '<?php echo lang("date"); ?>'},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ],
            radioButtons: [
                {text: '<?php echo lang("draft") ?>', name: "status", value: "draft", isChecked: true},
                {text: '<?php echo lang("approved") ?>', name: "status", value: "approved", isChecked: false},
                {text: '<?php echo lang("cancelled") ?>', name: "status", value: "cancelled", isChecked: false}
            ],
            tableRefreshButton: true,
            printColumns: [1,2,3,4,5,6,7,8,9],
            xlsColumns: [1,2,3,4,5,6,7,8,9],
        });
    });
</script>