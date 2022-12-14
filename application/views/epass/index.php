<div id="page-content" class="p20 clearfix">

    <div class="panel panel-default">
        <div class="page-title clearfix">
            <h1> <?php echo lang('epass'); ?></h1>
            <div class="title-button-group">
                <!-- Add button to manually add ticket. -->
                <?php //echo modal_anchor(get_uri("labels/modal_form"), "<i class='fa fa-tags'></i> " . lang('manage_labels'), array("class" => "btn btn-default", "title" => lang('manage_labels'), "data-post-type" => "to_do")); ?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="todo-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#todo-table").appTable({
            source: '<?php echo_uri("eventpass/list_data") ?>',
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
                {title: '<?php echo lang("seats_assignment"); ?>'},
                {title: '<?php echo lang("status"); ?>'},
                {title: '<?php echo lang("date"); ?>'},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ],
            checkBoxes: [
                {text: '<?php echo lang("draft") ?>', name: "status", value: "draft", isChecked: true},
                {text: '<?php echo lang("approved") ?>', name: "status", value: "approved", isChecked: false},
                {text: '<?php echo lang("cancelled") ?>', name: "status", value: "cancelled", isChecked: false}
            ],
            // printColumns: [2, 4],
            // xlsColumns: [2, 4],
        });
    });
</script>