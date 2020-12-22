<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
        <div class="page-title clearfix">
            <h1> <?php echo lang('holidays'); ?></h1>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("holidays/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_holiday'), array("class" => "btn btn-default", "title" => lang('add_holiday'))); ?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="holiday-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#holiday-table").appTable({
            source: '<?php echo_uri("holidays/list_data") ?>',
            order: [[0, 'desc']],
            columns: [
                {title: "<?php echo lang('title') ?> ", "class": "w20p"},
                {title: "<?php echo lang('description') ?>"},
                {title: "<?php echo lang('from') ?>", "class": "w100"},
                {title: "<?php echo lang('to') ?>", "class": "w100"},
                {title: "<?php echo lang('created_on') ?>",},
                {title: "<?php echo lang('created_by') ?>",},
                {title: "<i class='fa fa-bars'></i>", "class": "text-center option w100"}
            ],
            printColumns: [0, 1, 2, 3, 4, 5],
            xlsColumns: [0, 1, 2, 3, 4, 5]
        });
    });
</script>