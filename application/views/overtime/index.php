<div id="page-content" class="p20 clearfix">

    <div class="panel panel-default">
        <ul id="overtime-tabs" data-toggle="ajax-tab" class="nav nav-tabs bg-white title" role="tablist">
            <li class="title-tab"><h4 class="pl15 pt10 pr15"><?php echo lang("overtime"); ?></h4></li>

            <li><a role="presentation" class="active" href="javascript:;" data-target="#daily-overtime"><?php echo lang("daily"); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("hrs/overtime/custom/"); ?>" data-target="#custom-overtime"><?php echo lang('custom'); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("hrs/overtime/summary/"); ?>" data-target="#summary-overtime"><?php echo lang('summary'); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("hrs/overtime/export/"); ?>" data-target="#export-overtime"><?php echo lang('export'); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("hrs/overtime/members_clocked_in/"); ?>" data-target="#members-clocked-in"><?php echo lang('clocked_in'); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("hrs/overtime/clock_in_out"); ?>" data-target="#clock-in-out"><?php echo lang('override'); ?></a></li>

            <div class="tab-title clearfix no-border">
                <div class="title-button-group">
                    <?php echo modal_anchor(get_uri("hrs/overtime/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_overtime'), array("class" => "btn btn-default", "title" => lang('add_overtime'))); ?>
                </div>
            </div>
        </ul>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade" id="daily-overtime">
                <div class="table-responsive">
                    <table id="overtime-table" class="display" cellspacing="0" width="100%">            
                    </table>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="custom-overtime"></div>
            <div role="tabpanel" class="tab-pane fade" id="summary-overtime"></div>
            <div role="tabpanel" class="tab-pane fade" id="export-overtime"></div>
            <div role="tabpanel" class="tab-pane fade" id="members-clocked-in"></div>
            <div role="tabpanel" class="tab-pane fade" id="clock-in-out"></div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#overtime-table").appTable({
            source: '<?php echo_uri("hrs/overtime/list_data/"); ?>',
            order: [[2, "desc"]],
            filterDropdown: [
                {name: "user_id", class: "w200", options: <?php echo $team_members_dropdown; ?>}
            ],
            dateRangeType: "daily",
            columns: [
                {title: "<?php echo lang("employee"); ?>", "class": "w20p"},
                {visible: false, searchable: false},
                {title: "<?php echo lang("in_date"); ?>", "class": "text-center w10p", iDataSort: 1},
                {title: "<?php echo lang("in_time"); ?>", "class": "text-center w10p"},
                {visible: false, searchable: false},
                {title: "<?php echo lang("out_date"); ?>", "class": "text-center w10p", iDataSort: 4},
                {title: "<?php echo lang("out_time"); ?>", "class": "text-center w10p"},
                {title: "<?php echo lang("duration"); ?>", "class": "w10p text-right"},
                {title: "<?php echo lang("hours"); ?>", "class": "w10p text-right"},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ],
            printColumns: [0, 2, 3, 5, 6, 7],
            xlsColumns: [0, 2, 3, 5, 6, 7],
            summation: [
                {column: 7, dataType: 'time'},
                {column: 8, dataType: 'number'}
            ]
        });
    });
</script>    
