<div class="table-responsive">
    <table id="item-categories-table" class="display" cellspacing="0" width="100%">            
    </table>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#item-categories-table").appTable({
            source: '<?php echo_uri("mes/ProductCategories/list_data") ?>',
            order: [[0, 'desc']],
            columns: [
                {title: "<?php echo lang('title') ?> ", "class": "w20p"},
                {title: "<?php echo lang('description') ?>"},
                {title: "<?php echo lang('created_on') ?>",},
                {title: "<?php echo lang('created_by') ?>",},
                {title: "<i class='fa fa-bars'></i>", "class": "text-center option w100"}
            ],
            printColumns: [0, 1, 2, 3, 4],
            xlsColumns: [0, 1, 2, 3, 4]
        });

        setInterval(function(){
            $("#products-tabs").find("li.active").text() == "<?= lang("categories")?>" ? $('#add_category_button').show() : $('#add_category_button').hide()
            $("#products-tabs").find("li.active").text() == "<?= lang("entries")?>" ? $('#add_entry_button').show() : $('#add_entry_button').hide();
            $("#products-tabs").find("li.active").text() == "<?= lang("inventory")?>" ? $('#product-panel').css("background-color", "transparent") : $('#product-panel').css("background-color", "#fff");
        }, 200)
    });
</script>