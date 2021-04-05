<div id="page-content" class="clearfix p20">
    <div class="panel clearfix">
        <ul id="incentives-tabs" data-toggle="ajax-tab" class="nav nav-tabs bg-white title" role="tablist">
            <li class="title-tab"><h4 class="pl15 pt10 pr15"><?php echo lang("incentives"); ?></h4></li>
            <li><a role="presentation" href="<?php echo_uri("incentive_entries"); ?>" data-target="#incentive-entries"><?php echo lang('entries'); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("incentive_categories"); ?>" data-target="#incentive-categories"><?php echo lang('categories'); ?></a></li>
            <div class="tab-title clearfix no-border">
                <div class="title-button-group">
                    <?php echo modal_anchor(get_uri("fas/incentive_categories/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_category'), array("class" => "btn btn-default", "title" => lang('add_category'), "id" => "add_category_button")); ?>
                    <?php echo modal_anchor(get_uri("fas/incentive_entries/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_entry'), array("class" => "btn btn-default", "title" => lang('add_entry'), "id" => "add_entry_button")); ?>
                </div>
            </div>
        </ul>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade" id="incentive-entries">
                <?php $this->load->view('incentive_entries/index')?>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="incentive-categories">
                <div class="table-responsive">
                    <table id="incentive-categories-table" class="display" cellspacing="0" width="100%">   
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#incentive-categories-table").appTable({
            source: '<?php echo_uri("fas/incentive_categories/list_data") ?>',
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
            $("#incentives-tabs").find("li.active").text() == "Categories" ? $('#add_category_button').show() : $('#add_category_button').hide()
            $("#incentives-tabs").find("li.active").text() == "Entries" ? $('#add_entry_button').show() : $('#add_entry_button').hide()
        }, 200)
    });
</script>