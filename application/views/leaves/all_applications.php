
<div class="page-title clearfix">
    <div class="title-button-group">
        <?php echo modal_anchor(get_uri("hrs/leaves/assign_leave_modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('assign_leave'), array("class" => "btn btn-default", "title" => lang('assign_leave'))); ?>
    </div>
</div>
<div class="table-responsive">
    <table id="all-application-table" class="display" cellspacing="0" width="100%">            
    </table>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $("#all-application-table").appTable({
            source: '<?php echo_uri("hrs/leaves/all_application_list_data") ?>',
            dateRangeType: "monthly",
            filterDropdown: [
                {name: "leave_type_id", class: "w200", options: <?= json_encode($leave_types_dropdown) ?> },
                {name: "status", class: "w200", options: <?= json_encode($status_dropdown) ?> },
            ],
            columns: [
                {title: '<?php echo lang("applicant") ?>', "class": "w20p"},
                {title: '<?php echo lang("leave_type") ?>'},
                {title: '<?php echo lang("date") ?>', "class": "w20p"},
                {title: '<?php echo lang("duration") ?>', "class": "w20p"},
                {title: '<?php echo lang("status") ?>', "class": "w15p"},
                {title: '<?php echo lang("created_by") ?>', "class": "w15p"},
                {title: '<?php echo lang("date_created") ?>', "class": "w15p"},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ],
            printColumns: [0, 1, 2, 3, 4, 5, 6],
            xlsColumns: [0, 1, 2, 3, 4, 5, 6]
        });
    });
</script>

