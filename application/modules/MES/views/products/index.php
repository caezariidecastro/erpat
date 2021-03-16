<div id="page-content" class="clearfix p20">
    <div id="product-panel" class="panel clearfix">
        <ul id="products-tabs" data-toggle="ajax-tab" class="nav nav-tabs bg-white title" role="tablist">
            <li class="title-tab"><h4 class="pl15 pt10 pr15"><?php echo lang("products"); ?></h4></li>
            <li><a role="presentation" href="<?php echo_uri("entries"); ?>" data-target="#item-entries"><?php echo lang('entries'); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("categories"); ?>" data-target="#item-categories"><?php echo lang('categories'); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("inventory"); ?>" data-target="#item-inventory"><?php echo lang('inventory'); ?></a></li>
            <div class="tab-title clearfix no-border">
                <div class="title-button-group">
                    <?php echo modal_anchor(get_uri("mes/ProductCategories/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_category'), array("class" => "btn btn-default", "title" => lang('add_category'), "id" => "add_category_button")); ?>
                    <?php echo modal_anchor(get_uri("mes/ProductEntries/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_entry'), array("class" => "btn btn-default", "title" => lang('add_entry'), "id" => "add_entry_button")); ?>
                </div>
            </div>
        </ul>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade" id="item-entries">
                <?php $this->load->view('products/entries/index')?>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="item-categories">
                <?php $this->load->view('products/categories/index')?>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="item-inventory">
                <?php $this->load->view('products/inventory/index')?>
            </div>
        </div>
    </div>
</div>