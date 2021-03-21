<div class="table-responsive">
    <table id="material-categories-table" class="display" cellspacing="0" width="100%">            
    </table>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#material-categories-table").appTable({
            source: '<?php echo_uri("mes/RawMaterialCategories/list_data") ?>',
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
            $("#materials-tabs").find("li.active").text() == "Categories" ? $('#add_category_button').show() : $('#add_category_button').hide()
            $("#materials-tabs").find("li.active").text() == "Entries" ? $('#add_entry_button').show() : $('#add_entry_button').hide();
            $("#materials-tabs").find("li.active").text() == "Inventory" ? $('#material-panel').css("background-color", "transparent") : $('#material-panel').css("background-color", "#fff");
        }, 200)
    });
</script>