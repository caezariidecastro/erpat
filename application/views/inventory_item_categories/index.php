<div id="page-content" class="clearfix p20">
    <div class="panel clearfix">
        <ul id="products-tabs" data-toggle="ajax-tab" class="nav nav-tabs bg-white title" role="tablist">
            <li class="title-tab"><h4 class="pl15 pt10 pr15"><?php echo lang("products"); ?></h4></li>
            <li><a role="presentation" href="<?php echo_uri("inventory_item_entries"); ?>" data-target="#item-entries"><?php echo lang('entries'); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("inventory_item_categories"); ?>" data-target="#item-categories"><?php echo lang('categories'); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("vendors"); ?>" data-target="#item-vendors"><?php echo lang('vendors'); ?></a></li>
            <div class="tab-title clearfix no-border">
                <div class="title-button-group">
                    <?php echo modal_anchor(get_uri("inventory_item_categories/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_category'), array("class" => "btn btn-default", "title" => lang('add_category'), "id" => "add_category_button")); ?>
                    <?php echo modal_anchor(get_uri("inventory_item_entries/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_entry'), array("class" => "btn btn-default", "title" => lang('add_entry'), "id" => "add_entry_button")); ?>
                    <?php echo modal_anchor(get_uri("vendors/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_vendor'), array("class" => "btn btn-default", "title" => lang('add_vendor'), "id" => "add_vendor_button")); ?>
                </div>
            </div>
        </ul>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade" id="item-entries">
                <?php $this->load->view('inventory_item_entries/index')?>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="item-categories">
                <div class="table-responsive">
                    <table id="item-categories-table" class="display" cellspacing="0" width="100%">   
                    </table>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="item-vendors">
                <?php $this->load->view('vendors/index')?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#item-categories-table").appTable({
            source: '<?php echo_uri("inventory_item_categories/list_data") ?>',
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
            $("#products-tabs").find("li.active").text() == "Categories" ? $('#add_category_button').show() : $('#add_category_button').hide()
            $("#products-tabs").find("li.active").text() == "Entries" ? $('#add_entry_button').show() : $('#add_entry_button').hide();
            $("#products-tabs").find("li.active").text() == "Vendors" ? $('#add_vendor_button').show() : $('#add_vendor_button').hide();
        }, 200)
    });
</script>