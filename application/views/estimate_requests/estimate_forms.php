<div class="table-responsive">
    <table id="estimate-form-main-table" class="display" cellspacing="0" width="100%">            
    </table>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#estimate-form-main-table").appTable({
            source: '<?php echo_uri("sales/Estimate_requests/estimate_forms_list_data") ?>',
            order: [[0, 'asc']],
            columns: [
                {title: "<?php echo lang("title"); ?>"},
                {title: "<?php echo lang("public"); ?>", "class": "w150"},
                {title: "<?php echo lang("embed"); ?>", "class": "option w150"},
                {title: "<?php echo lang("status"); ?>", "class": "w150"},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ]
        });
    });
</script>