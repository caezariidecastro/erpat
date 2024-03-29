<?php $this->load->view("includes/cropbox"); ?>

<div class="table-responsive">
    <table id="inventory-item-entries-table" class="display" cellspacing="0" width="100%">            
    </table>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#inventory-item-entries-table").appTable({
            source: '<?php echo_uri("sales/ProductEntries/list_data") ?>',
            filterDropdown: [
                {id: "category_select2_filter", name: "category_select2_filter", class: "w200", options: <?php echo json_encode($category_select2); ?>},
                {id: "vendor_select2_filter", name: "vendor_select2_filter", class: "w200", options: <?php echo json_encode($vendor_select2); ?>},
            ],
            order: [[0, 'desc']],
            columns: [
                {title: "<?php echo lang('preview') ?>", "class": "w10 text-center"},
                {title: "<?php echo lang('name') ?>", "class": "w20p"},
                {title: "<?php echo lang('sku') ?>"},
                {title: "<?php echo lang('unit') ?>"},
                {title: "<?php echo lang('category') ?>"},
                {title: "<?php echo lang('brand') ?>"},
                {title: "<?php echo lang('cost_price') ?>"},
                {title: "<?php echo lang('selling_price') ?>"},
                {title: "<?php echo lang('supplier') ?>"},
                {title: "<?php echo lang('created_on') ?>",},
                {title: "<?php echo lang('created_by') ?>",},
                {title: "<?php echo lang('bom') ?>", "class": "text-center dropdown-option w50"},
                {title: "<i class='fa fa-bars'></i>", "class": "text-center option w100"}
            ],
            printColumns: [0, 1, 2, 3, 4, 5, 6],
            xlsColumns: [0, 1, 2, 3, 4, 5, 6],
            onInitComplete: function () {
                $(".upload").change(function () {
                    var split = $(this).attr('id').split("-");
                    var id = split[0];
                    if (typeof FileReader == 'function') {
                        showCropBox(this);
                    } else {
                        $("#"+id+"-product-form").submit();
                    }
                });

                $(".product_base64").change(function () {
                    var split = $(this).attr('id').split("-");
                    var id = split[0]
                    var base64 = $('#'+id+'-product_image').attr('value');
                    
                    appLoader.show();
                    $.ajax({
                        url: "<?php echo get_uri("sales/ProductEntries/update_product_image"); ?>/",
                        method: "POST",
                        data: {
                            id: id,
                            image: base64
                        },
                        dataType: 'json',
                        success: function (result) {
                            if (result.success) {
                                appAlert.success(result.message);
                            } else {
                                appAlert.error(result.message);
                            }
                            appLoader.hide();
                        }
                    });
                });
            }
        });
    });
</script>