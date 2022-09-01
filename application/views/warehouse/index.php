<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
        <ul id="warehouse-tabs" data-toggle="ajax-tab" class="nav nav-tabs bg-white title" role="tablist">
            <li class="title-tab"><h4 class="pl15 pt10 pr15"><?php echo lang("warehouse"); ?></h4></li>
            <li><a role="presentation" class="active" href="javascript:;" data-target="#warehouse-tab"><?php echo lang("lists"); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("zones/index"); ?>" data-target="#zones-tab"><?php echo lang('zones'); ?></a></li>
            <li><a role="presentation" href="<?php echo_uri("racks/index"); ?>" data-target="#racks-tab"><?php echo lang('racks'); ?></a></li>
            <div class="tab-title clearfix no-border">
                <div class="title-button-group">
                    
                </div>
            </div>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade" id="warehouse-tab">
                <div class="page-title clearfix">
                    <div class="title-button-group">
                        <?php echo modal_anchor(get_uri("lds/warehouses/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_warehouse'), array("class" => "btn btn-default", "title" => lang('add_warehouse'))); ?>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="warehouse-table" class="display" cellspacing="0" width="100%"></table>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="zones-tab"></div>
            <div role="tabpanel" class="tab-pane fade" id="racks-tab"></div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#warehouse-table").appTable({
            source: '<?php echo_uri("lds/warehouses/list_data") ?>',
            order: [[0, 'desc']],
            columns: [
                {title: "<?php echo lang('name') ?> "},
                {title: "<?php echo lang('address') ?>"},
                {title: "<?php echo lang('zip_code') ?>"},
                {title: "<?php echo lang('email') ?>"},
                {title: "<?php echo lang('head') ?>"},
                {title: "<?php echo lang('created_by') ?>",},
                {title: "<?php echo lang('date_created') ?>",},
                {title: "<i class='fa fa-bars'></i>", "class": "text-center option w100"}
            ],
            printColumns: [0, 1, 2, 3, 4, 5, 6],
            xlsColumns: [0, 1, 2, 3, 4, 5, 6],
            tableRefreshButton: true,
        });
    });
</script>