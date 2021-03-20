
<div id="page-content" class="clearfix p20">
    <div class="panel clearfix">
        <div class="table-responsive">
            <table id="category-table" class="display" cellspacing="0" width="100%">
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#category-table").appTable({
            source: '<?php echo_uri("fas/expense_categories/list_data") ?>',
            columns: [
                {title: '<?php echo lang("title") ?>'},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"},
            ]
        });
    });
</script>