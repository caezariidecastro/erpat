<div id="page-content" class="clearfix p20">
    <div id="product-panel" class="panel clearfix">
        <ul id="products-tabs" data-toggle="ajax-tab" class="nav nav-tabs bg-white title" role="tablist">
            <li class="title-tab"><h4 class="pl15 pt10 pr15"><?php echo lang("products"); ?></h4></li>
            <li><a role="presentation" href="<?php echo_uri("entries"); ?>" data-target="#item-entries"><?php echo lang('entries'); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("categories"); ?>" data-target="#item-categories"><?php echo lang('categories'); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("brands"); ?>" data-target="#item-brands"><?php echo lang('brands'); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("inventory"); ?>" data-target="#item-inventory"><?php echo lang('inventory'); ?></a></li>
            <div class="tab-title clearfix no-border">
                <div class="title-button-group">
                    <?php echo modal_anchor(get_uri("sales/ProductEntries/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_entry'), array("class" => "btn btn-default", "title" => lang('add_entry'), "id" => "add_entry_button")); ?>
                    <?php echo modal_anchor(get_uri("sales/ProductCategories/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_category'), array("class" => "btn btn-default", "title" => lang('add_category'), "id" => "add_category_button")); ?>
                    <?php echo modal_anchor(get_uri("sales/ProductBrands/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_brands'), array("class" => "btn btn-default", "title" => lang('add_brands'), "id" => "add_brands_button")); ?>
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
            <div role="tabpanel" class="tab-pane fade" id="item-brands">
                <?php $this->load->view('products/brands/index')?>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="item-inventory">
                <?php $this->load->view('products/inventory/index')?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        setInterval(function(){
            $("#products-tabs").find("li.active").text() == "<?= lang("categories")?>" ? $('#add_category_button').show() : $('#add_category_button').hide()
            $("#products-tabs").find("li.active").text() == "<?= lang("entries")?>" ? $('#add_entry_button').show() : $('#add_entry_button').hide();
            $("#products-tabs").find("li.active").text() == "<?= lang("brands")?>" ? $('#add_brands_button').show() : $('#add_brands_button').hide();
            $("#products-tabs").find("li.active").text() == "<?= lang("inventory")?>" ? $('#product-panel').css("background-color", "transparent") : $('#product-panel').css("background-color", "#fff");
        }, 200)       
    });
</script>