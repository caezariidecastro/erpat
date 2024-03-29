
<div class="page-title clearfix">
    <div class="title-button-group">
        <?php echo modal_anchor(get_uri("hrs/leaves/apply_leave_modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('apply_leave'), array("class" => "btn btn-default", "title" => lang('apply_leave'))); ?>
    </div>
</div>
<div class="table-responsive">
    <table id="pending-approval-table" class="display" cellspacing="0" width="100%">            
    </table>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $("#pending-approval-table").appTable({
            source: '<?php echo_uri("hrs/leaves/pending_approval_list_data") ?>',
            filterDropdown: [
                {name: "leave_type_id", class: "w200", options: <?= json_encode($leave_types_dropdown) ?> },
            ],
            dateRangeType: "yearly",
            columns: [
                {title: '<?php echo lang("applicant") ?>', "class": "w20p"},
                {title: '<?php echo lang("leave_type") ?>'},
                {title: '<?php echo lang("date") ?>'},
                {title: '<?php echo lang("duration") ?>'},
                {title: '<?php echo lang("status") ?>'},
                {title: '<?php echo lang("created_by") ?>'},
                {title: '<?php echo lang("date_created") ?>'},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ],
            printColumns: [0, 1, 2, 3, 4, 5, 6],
            xlsColumns: [0, 1, 2, 3, 4, 5, 6],
            tableRefreshButton: true
        });
    });
</script>

