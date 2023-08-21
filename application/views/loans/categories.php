
<div class="page-title clearfix">
    <div class="title-button-group">
        <?php echo modal_anchor(get_uri("finance/Loans/modal_form_categories"), "<i class='fa fa-plus-circle'></i> " . lang('add_category'), array("class" => "btn btn-default", "title" => lang('add_category'))); ?>
    </div>
</div>
<div class="table-responsive">
    <table id="list-loan-categories-table" class="display" cellspacing="0" width="100%">            
    </table>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $("#list-loan-categories-table").appTable({
            source: '<?php echo_uri("finance/Loans/list_categories") ?>',
            columns: [
                {title: '<?php echo lang("name") ?>', "class": "w20p"},
                {title: '<?php echo lang("description") ?>', "class": "w20p"},
                {title: '<?php echo lang("status") ?>', "class": "w20p"},
                {title: '<?php echo lang("created_by") ?>', "class": "w20p"},
                {title: '<?php echo lang("timestamp") ?>', "class": "w15p"},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ],
            printColumns: [0, 1, 2, 3, 4],
            xlsColumns: [0, 1, 2, 3, 4],
            tableRefreshButton: true,
        });
    });
</script>

