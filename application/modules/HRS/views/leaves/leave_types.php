<div class="page-title clearfix">
    <div class="title-button-group">
        <?php echo modal_anchor(get_uri("hrs/leaves/modal_form_type"), "<i class='fa fa-plus-circle'></i> " . lang('add_leave_type'), array("class" => "btn btn-default", "title" => lang('add_leave_type'))); ?>
    </div>
</div>
<div class="table-responsive">
    <table id="leave-type-table" class="display" cellspacing="0" width="100%">            
    </table>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $("#leave-type-table").appTable({
            source: '<?php echo_uri("hrs/leave_types/list_data") ?>',
            columns: [
                {title: '<?php echo lang("title"); ?>'},
                {title: '<?php echo lang("description"); ?>'},
                {title: '<?php echo lang("status"); ?>'},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ],
            printColumns: [0, 1, 2]
        });
    });
</script>