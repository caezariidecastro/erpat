<div id="page-content" class="clearfix p20">
    <div class="panel clearfix">
        <ul id="services-tabs" data-toggle="ajax-tab" class="nav nav-tabs bg-white title" role="tablist">
            <li class="title-tab"><h4 class="pl15 pt10 pr15"><?php echo lang("services"); ?></h4></li>
            <li><a role="presentation" href="<?php echo_uri("services"); ?>" data-target="#services-entries"><?php echo lang('entries'); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("services_categories"); ?>" data-target="#services-categories"><?php echo lang('categories'); ?></a></li>
            <div class="tab-title clearfix no-border">
                <div class="title-button-group">
                    <?php echo modal_anchor(get_uri("sales/Services/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_entry'), array("class" => "btn btn-default", "title" => lang('add_entry'), "id" => "add_entry_button")); ?>
                    <?php echo modal_anchor(get_uri("sales/Services_categories/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_category'), array("class" => "btn btn-default", "title" => lang('add_category'), "id" => "add_category_button")); ?>
                    <?php echo modal_anchor(get_uri("labels/modal_form"), "<i class='fa fa-tags'></i> " . lang('manage_labels'), array("class" => "btn btn-default", "title" => lang('manage_labels'), "data-post-type" => "services")); ?>
                </div>
            </div>
        </ul>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade" id="services-entries">
                <div class="table-responsive">
                    <table id="services-table" class="display" cellspacing="0" width="100%">   
                    </table>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="services-categories">
                <?php $this->load->view('services/categories/index')?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#services-table").appTable({
            source: '<?php echo_uri("sales/Services/list_data") ?>',
            filterDropdown: [
                {name: "labels_select2_filter", class: "w200", options: <?php echo $services_labels_dropdown; ?>}, {name: "status_select2_filter", class: "w150", options: <?php echo json_encode($status_select2); ?>}, 
                {id: "category_select2_filter", name: "category_select2_filter", class: "w200", options: <?php echo json_encode($category_select2); ?>},
            ],
            order: [[0, 'desc']],
            columns: [
                {title: "<?php echo lang('title') ?> ", "class": "w150"},
                {title: "<?php echo lang('description') ?>"},
                {title: "<?php echo lang('category') ?>", "class": "text-center w100"},
                {title: "<?php echo lang('unit_type') ?>", "class": "text-center w100"},
                {title: "<?php echo lang('rate') ?>", "class": "text-center w100"},
                {title: "<?php echo lang('labels') ?>", "class": "w150"},
                {title: "<?php echo lang('status') ?>", "class": "text-center w50"},
                {title: "<?php echo lang('created_at') ?>", "class": "text-center w100"},
                {title: "<?php echo lang('updated_at') ?>", "class": "text-center w100"},
                {title: "<?php echo lang('created_by') ?>", "class": "text-center w100"},
                {title: "<i class='fa fa-bars'></i>", "class": "text-center option w100"}
            ],
            printColumns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
            xlsColumns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
            tableRefreshButton: true,
        });

        setInterval(function(){
            $("#services-tabs").find("li.active").text() == "Entries" ? $('#add_entry_button').show() : $('#add_entry_button').hide()
            $("#services-tabs").find("li.active").text() == "Categories" ? $('#add_category_button').show() : $('#add_category_button').hide()
        }, 200)
    });
</script>