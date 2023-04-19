<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
        <div class="page-title clearfix">
            <h4> <?php echo lang('schedule'); ?></h4>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("hrs/schedule/modal_form"), "<i class='fa fa-plus-circle'></i> " 
                    . lang('add_sched'), array("class" => "btn btn-default", "title" => lang('add_sched'))); ?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="units-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $("#units-table").appTable({
            source: '<?php echo_uri("hrs/schedule/list") ?>',
            columns: [
                {title: "<?php echo lang("title"); ?>"},
                {title: "<?php echo lang("assigned_to"); ?>"},
                {title: "<?php echo lang("description"); ?>"},
                {title: "<?php echo lang("monday"); ?>"},
                {title: "<?php echo lang("tuesday"); ?>"},
                {title: "<?php echo lang("wednesday"); ?>"},
                {title: "<?php echo lang("thursday"); ?>"},
                {title: "<?php echo lang("friday"); ?>"},
                {title: "<?php echo lang("saturday"); ?>"},
                {title: "<?php echo lang("sunday"); ?>"},
                {title: "<?php echo lang("created_on"); ?>"},
                {title: "<?php echo lang("created_by"); ?>"},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ],
            xlsColumns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
            printColumns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
            tableRefreshButton: true,
        });
    });
</script>