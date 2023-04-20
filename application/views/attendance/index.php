<div id="page-content" class="p20 clearfix">

    <div class="panel panel-default">
        <ul id="attendance-tabs" data-toggle="ajax-tab" class="nav nav-tabs bg-white title" role="tablist">
            <li class="title-tab"><h4 class="pl15 pt10 pr15"><?php echo lang("attendance"); ?></h4></li>

            <li><a role="presentation" class="active" href="javascript:;" data-target="#daily-attendance"><?php echo lang("daily"); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("hrs/attendance/custom/"); ?>" data-target="#custom-attendance"><?php echo lang('custom'); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("hrs/attendance/summary/"); ?>" data-target="#summary-attendance"><?php echo lang('summary'); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("hrs/attendance/export/"); ?>" data-target="#export-attendance"><?php echo lang('export'); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("hrs/attendance/members_clocked_in/"); ?>" data-target="#members-clocked-in"><?php echo lang('clocked_in'); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("hrs/attendance/clock_in_out"); ?>" data-target="#clock-in-out"><?php echo lang('override'); ?></a></li>

            <div class="tab-title clearfix no-border">
                <div class="title-button-group">
                    <?php echo modal_anchor(get_uri("hrs/attendance/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_attendance'), array("class" => "btn btn-default", "title" => lang('add_attendance'))); ?>
                </div>
            </div>
        </ul>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade" id="daily-attendance">
                <div class="table-responsive">
                    <table id="attendance-table" class="display" cellspacing="0" width="100%">            
                    </table>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="custom-attendance"></div>
            <div role="tabpanel" class="tab-pane fade" id="summary-attendance"></div>
            <div role="tabpanel" class="tab-pane fade" id="export-attendance"></div>
            <div role="tabpanel" class="tab-pane fade" id="members-clocked-in"></div>
            <div role="tabpanel" class="tab-pane fade" id="clock-in-out"></div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#attendance-table").appTable({
            source: '<?php echo_uri("hrs/attendance/list_data/"); ?>',
            order: [[2, "desc"]],
            filterDropdown: [
                {name: "department_id", class: "w200", options: <?= json_encode($department_select2) ?>},
                {name: "user_id", class: "w200", options: <?php echo $team_members_dropdown; ?>}
            ],
            dateRangeType: "daily",
            columns: [
                {title: "<?php echo lang("employee"); ?>", "class": "w20p"},
                {title: "<?php echo lang("department"); ?>", "class": "w15p text-center"},
                {visible: false, searchable: false},
                {title: "<?php echo lang("in_date"); ?>", "class": "text-center w10p", iDataSort: 2},
                {title: "<?php echo lang("in_time"); ?>", "class": "text-center w10p"},
                <?php if(get_setting('breaktime_tracking')) { ?>
                {title: "<?php echo lang("break_1st_start"); ?>", "class": "text-center w10p"},
                {title: "<?php echo lang("break_1st_end"); ?>", "class": "text-center w10p"},
                {title: "<?php echo lang("break_lunch_start"); ?>", "class": "text-center w10p"},
                {title: "<?php echo lang("break_lunch_end"); ?>", "class": "text-center w10p"},
                {title: "<?php echo lang("break_2nd_start"); ?>", "class": "text-center w10p"},
                {title: "<?php echo lang("break_2nd_end"); ?>", "class": "text-center w10p"},
                <?php } ?>
                {visible: false, searchable: false},
                {title: "<?php echo lang("out_date"); ?>", "class": "text-center w10p", iDataSort: 5},
                {title: "<?php echo lang("out_time"); ?>", "class": "text-center w10p"},
                {title: "<?php echo lang("duration"); ?>", "class": "w5p text-right"},
                {title: "<?php echo lang("worked"); ?>", "class": "w5p text-right"},
                {title: "<?php echo lang("overtime"); ?>", "class": "w5p text-right"},
                {title: "<?php echo lang("bonus"); ?>", "class": "w5p text-right"},
                {title: "<?php echo lang("night"); ?>", "class": "w5p text-right"},
                {title: "<?php echo lang("lates"); ?>", "class": "w5p text-right"},
                {title: "<?php echo lang("overbreak"); ?>", "class": "w5p text-right"},
                {title: "<?php echo lang("undertime"); ?>", "class": "w5p text-right"},
                {title: "<?php echo lang("info"); ?>", "class": "text-center w50"},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ],
            printColumns: [0, 1, 3, 4, 5, 6, 7, 8, 9, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21],
            xlsColumns: [0, 1, 3, 4, 5, 6, 7, 8, 9, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21],
            <?php if(get_setting('breaktime_tracking')) { ?>
                summation: [
                    {column: 14, dataType: 'time'},
                    {column: 15, dataType: 'number'},
                    {column: 16, dataType: 'number'},
                    {column: 17, dataType: 'number'},
                    {column: 18, dataType: 'number'},
                    {column: 19, dataType: 'number'},
                    {column: 20, dataType: 'number'},
                    {column: 21, dataType: 'number'}            
                ],
            <?php } else { ?>
                summation: [
                    {column: 8, dataType: 'time'},
                    {column: 9, dataType: 'number'},
                    {column: 10, dataType: 'number'},
                    {column: 11, dataType: 'number'},
                    {column: 12, dataType: 'number'},
                    {column: 13, dataType: 'number'},
                    {column: 14, dataType: 'number'},
                    {column: 15, dataType: 'number'},
                ],
            <?php } ?>
            tableRefreshButton: true,
        });
    });
</script>    
