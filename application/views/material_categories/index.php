<div id="page-content" class="clearfix p20">
    <div id="material-panel" class="panel clearfix">
        <ul id="materials-tabs" data-toggle="ajax-tab" class="nav nav-tabs bg-white title" role="tablist">
            <li class="title-tab"><h4 class="pl15 pt10 pr15"><?php echo lang("materials"); ?></h4></li>
            <li><a role="presentation" href="<?php echo_uri("material_entries"); ?>" data-target="#material-entries"><?php echo lang('entries'); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("material_categories"); ?>" data-target="#material-categories"><?php echo lang('categories'); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("vendors"); ?>" data-target="#material-vendors"><?php echo lang('vendors'); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("inventory"); ?>" data-target="#material-inventory"><?php echo lang('inventory'); ?></a></li>
            <div class="tab-title clearfix no-border">
                <div class="title-button-group">
                    <?php echo modal_anchor(get_uri("material_categories/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_category'), array("class" => "btn btn-default", "title" => lang('add_category'), "id" => "add_category_button")); ?>
                    <?php echo modal_anchor(get_uri("material_entries/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_entry'), array("class" => "btn btn-default", "title" => lang('add_entry'), "id" => "add_entry_button")); ?>
                    <?php echo modal_anchor(get_uri("vendors/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_vendor'), array("class" => "btn btn-default", "title" => lang('add_vendor'), "id" => "add_vendor_button")); ?>
                </div>
            </div>
        </ul>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade" id="material-entries">
                <?php $this->load->view('material_entries/index')?>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="material-categories">
                <div class="table-responsive">
                    <table id="material-categories-table" class="display" cellspacing="0" width="100%">   
                    </table>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="material-vendors">
                <?php $this->load->view('vendors/index')?>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="material-inventory">
                <?php $this->load->view('material_inventory/index')?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#material-categories-table").appTable({
            source: '<?php echo_uri("material_categories/list_data") ?>',
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
            $("#materials-tabs").find("li.active").text() == "Vendors" ? $('#add_vendor_button').show() : $('#add_vendor_button').hide();
            $("#materials-tabs").find("li.active").text() == "Inventory" ? $('#material-panel').css("background-color", "transparent") : $('#material-panel').css("background-color", "#fff");
        }, 200)
    });
</script>