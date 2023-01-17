<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
        <div class="table-responsive">
            <table id="job_view_data-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#job_view_data-table").appTable({
            source: '<?php echo_uri("hrs/team_members/job_view_data") ?>',
            order: [[1, "asc"]],
            filterDropdown: [
                {name: "status", class: "text-center w100", options: <?php echo $usertype_dropdown; ?>},
                {name: "label_id", class: "text-center w100", options: <?php echo $users_labels_dropdown; ?>},
                {id: "schedule_select2_filter", name: "schedule_select2_filter", class: "w150", options: <?php echo json_encode($schedule_select2); ?>},
                {id: "department_select2_filter", name: "department_select2_filter", class: "w150", options: <?php echo json_encode($department_select2); ?>}
            ],
            columns: [
                {visible: false, searchable: false},
                {title: "<?php echo lang("id") ?>", "class": "w10p"},
                {title: "<?php echo lang("first_name") ?>", "class": "w15p"},
                {title: "<?php echo lang("last_name") ?>", "class": "w10p"},

                {title: "<?php echo lang("job_title") ?>", "class": "w10p"},
                {title: "<?php echo lang("date_of_hire") ?>", "class": "w10p"},
                {title: "<?php echo lang("signature") ?>", "class": "w10p"},
            ],
            printColumns: [0, 1, 2, 3, 4],
            xlsColumns: [0, 1, 2, 3, 4],
            tableRefreshButton: true,
        });
    });
</script>    
