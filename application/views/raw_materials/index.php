<div id="page-content" class="clearfix p20">
    <div id="material-panel" class="panel clearfix">
        <ul id="materials-tabs" data-toggle="ajax-tab" class="nav nav-tabs bg-white title" role="tablist">
            <li class="title-tab"><h4 class="pl15 pt10 pr15"><?php echo lang("raw_materials"); ?></h4></li>
            <li><a role="presentation" href="<?php echo_uri("raw_materials/entries"); ?>" data-target="#material-entries"><?php echo lang('entries'); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("raw_materials/categories"); ?>" data-target="#material-categories"><?php echo lang('categories'); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("raw_materials/inventory"); ?>" data-target="#material-inventory"><?php echo lang('inventory'); ?></a></li>
            <div class="tab-title clearfix no-border">
                <div class="title-button-group">
                    <?php echo modal_anchor(get_uri("mes/RawMaterialEntries/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_entry'), array("class" => "btn btn-default", "title" => lang('add_entry'), "id" => "add_entry_button")); ?>
                    <?php echo modal_anchor(get_uri("mes/RawMaterialCategories/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_category'), array("class" => "btn btn-default", "title" => lang('add_category'), "id" => "add_category_button")); ?>
                </div>
            </div>
        </ul>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade" id="material-entries">
                <?php $this->load->view('raw_materials/entries/index')?>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="material-categories">
                <?php $this->load->view('raw_materials/categories/index')?>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="material-inventory">
                <?php $this->load->view('raw_materials/inventory/index')?>
            </div>
        </div>
    </div>
</div>

