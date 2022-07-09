<?php $this->load->view("includes/cropbox"); ?>

<div id="page-content" class="clearfix p20">
    <div class="panel clearfix">
        <ul id="stores-tabs" data-toggle="ajax-tab" class="nav nav-tabs bg-white title" role="tablist">
            <li class="title-tab"><h4 class="pl15 pt10 pr15"><?php echo lang("stores"); ?></h4></li>
            <li><a role="presentation" href="<?php echo_uri("stores"); ?>" data-target="#stores-entries"><?php echo lang('entries'); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("stores_categories"); ?>" data-target="#stores-categories"><?php echo lang('categories'); ?></a></li>
            <div class="tab-title clearfix no-border">
                <div class="title-button-group">
                    <?php echo modal_anchor(get_uri("stores/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_entry'), array("class" => "btn btn-default", "title" => lang('add_entry'), "id" => "add_entry_button")); ?>
                    <?php echo modal_anchor(get_uri("stores_categories/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_category'), array("class" => "btn btn-default", "title" => lang('add_category'), "id" => "add_category_button")); ?>
                </div>
            </div>
        </ul>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade" id="stores-entries">
                <div class="table-responsive">
                    <table id="stores-table" class="display" cellspacing="0" width="100%">   
                    </table>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="stores-categories">
                <?php $this->load->view('stores/categories/index')?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#stores-table").appTable({
            source: '<?php echo_uri("stores/list_data") ?>',
            filterDropdown: [
                {id: "category_select2_filter", name: "category_select2_filter", class: "w200", options: <?php echo json_encode($category_select2); ?>},
            ],
            order: [[0, 'desc']],
            columns: [
                {title: "<?php echo lang('uuid') ?> ", "class": "w10p"},
                {title: "<?php echo lang('image') ?> ", "class": "w10p"},
                {title: "<?php echo lang('title') ?> ", "class": "w15p"},
                {title: "<?php echo lang('description') ?>"},
                {title: "<?php echo lang('category') ?>"},
                {title: "<?php echo lang('status') ?>", "class": "text-center"},
                {title: "<?php echo lang('created_at') ?>"},
                {title: "<?php echo lang('updated_at') ?>"},
                {title: "<?php echo lang('created_by') ?>"},
                {title: "<i class='fa fa-bars'></i>", "class": "text-center option w100"}
            ],
            printColumns: [0, 1, 2, 3, 4, 5],
            xlsColumns: [0, 1, 2, 3, 4, 5],
            onInitComplete: function () {
                $(".upload").change(function () {
                    var split = $(this).attr('id').split("-");
                    var id = split[0];
                    if (typeof FileReader == 'function') {
                        showCropBox(this);
                    } else {
                        $("#"+id+"-store-form").submit();
                    }
                });

                $(".store_base64").change(function () {
                    var split = $(this).attr('id').split("-");
                    var id = split[0]
                    var base64 = $('#'+id+'-store_image').attr('value');
                    
                    appLoader.show();
                    $.ajax({
                        url: "<?php echo get_uri("Stores/update_store_image"); ?>/",
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

        setInterval(function(){
            $("#stores-tabs").find("li.active").text() == "Entries" ? $('#add_entry_button').show() : $('#add_entry_button').hide()
            $("#stores-tabs").find("li.active").text() == "Categories" ? $('#add_category_button').show() : $('#add_category_button').hide()
        }, 200)
    });
</script>