<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
        <div class="page-title clearfix">
            <h1> <?php echo lang('consumers'); ?></h1>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("consumers/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_consumer'), array("class" => "btn btn-default", "title" => lang('add_consumer'))); ?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="consumer-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#consumer-table").appTable({
            source: '<?php echo_uri("consumers/list_data") ?>',
            order: [[0, 'desc']],
            columns: [
                {title: "<?php echo lang('first_name') ?> "},
                {title: "<?php echo lang('last_name') ?>"},
                {title: "<?php echo lang('email') ?>"},
                {title: "<?php echo lang('contact') ?>"},
                {title: "<?php echo lang('street') ?>"},
                {title: "<?php echo lang('city') ?>"},
                {title: "<?php echo lang('state') ?>"},
                {title: "<?php echo lang('zip') ?>"},
                {title: "<?php echo lang('country') ?>"},
                {title: "<?php echo lang('created_on') ?>"},
                {title: "<?php echo lang('created_by') ?>"},
                {title: "<i class='fa fa-bars'></i>", "class": "text-center option w100"}
            ],
            printColumns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11],
            xlsColumns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
        });
    });
</script>