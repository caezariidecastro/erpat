<div id="page-content" class="clearfix p20">
    <div id="product-panel" class="panel clearfix">
        <ul id="products-tabs" data-toggle="ajax-tab" class="nav nav-tabs bg-white title" role="tablist">
            <li class="title-tab"><h4 class="pl15 pt10 pr15"><?php echo lang("transfers"); ?></h4></li>
            <li><a role="presentation" href="<?php echo_uri("products"); ?>" data-target="#item-products"><?php echo lang('products'); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("raw_materials"); ?>" data-target="#item-raw_materials"><?php echo lang('raw_materials'); ?></a></li>
        </ul>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade" id="item-products">
                <?php $this->load->view('transfers/products/index')?>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="item-raw_materials">
                <?php $this->load->view('transfers/raw-materials/index')?>
            </div>
        </div>
    </div>
</div>