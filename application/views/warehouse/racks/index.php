<div id="page-content" class="clearfix">
    <div class="mt15">
        <div class="panel panel-default">
            <div class="page-title clearfix">
                <div class="title-button-group">
                    <?php echo modal_anchor(get_uri("labels/modal_form"), "<i class='fa fa-tags'></i> " . lang('manage_labels'), array("class" => "btn btn-default", "title" => lang('manage_labels'), "data-post-type" => "racks")); ?>
                    <?php echo modal_anchor(get_uri("inventory/Racks/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_rack'), array("class" => "btn btn-default", "title" => lang('add_rack'))); ?>
                </div>
            </div>
            <div class="table-responsive">
                <table id="rack-table" class="display" cellspacing="0" width="100%">            
                </table>
            </div>
        </div>
    </div>

    <div class="mt25 mb25" style="border-top: 1px solid #c1c1c1;">
        <div id="rack-contain" class="panel panel-default b-t">
            <ul id="racks-tabs" data-toggle="ajax-tab" class="nav nav-tabs bg-white title" role="tablist">
                <li><a role="presentation" href="<?php echo_uri("inventory/Bays/index"); ?>" data-target="#bays-tab"><?php echo lang('bays'); ?></a></li>
                <li><a role="presentation" href="<?php echo_uri("inventory/Levels/index"); ?>" data-target="#levels-tab"><?php echo lang('levels'); ?></a></li>
                <li><a role="presentation" href="<?php echo_uri("inventory/Positions/index"); ?>" data-target="#positions-tab"><?php echo lang('positions'); ?></a></li>
            </ul>

            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade" id="bays-tab"></div>
                <div role="tabpanel" class="tab-pane fade" id="levels-tab"></div>
                <div role="tabpanel" class="tab-pane fade" id="positions-tab"></div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#rack-table").appTable({
            source: '<?php echo_uri("inventory/Racks/list_data/".$warehouse_id) ?>',
            order: [[0, 'desc']],
            filterDropdown: [
                {name: "labels_select2_filter", class: "w150", options: <?php echo $racks_labels_dropdown; ?>}, 
                {name: "status_select2_filter", class: "w150", options: <?php echo json_encode($status_select2); ?>}, 
                {id: "zone_select2_filter", name: "zone_select2_filter", class: "w200", options: <?php echo json_encode($zone_select2); ?>}, 
            ],
            columns: [
                {title: "<?php echo lang('rack_id') ?> "},
                {title: "<?php echo lang('warehouse') ?> "},
                {title: "<?php echo lang('zone') ?> "},
                {title: "<?php echo lang('qrcode') ?>"},
                {title: "<?php echo lang('barcode') ?>"},
                {title: "<?php echo lang('rfid') ?>"},
                {title: "<?php echo lang('labels') ?>"},
                {title: "<?php echo lang('remarks') ?>"},
                {title: "<?php echo lang('status') ?>"},
                {title: "<?php echo lang('date_created') ?>"},
                {title: "<?php echo lang('created_by') ?>",},
                {title: "<i class='fa fa-bars'></i>", "class": "text-center option w100"}
            ],
            printColumns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
            xlsColumns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
            onInitComplete: function () {
                $('.view_btn').on('click', function() {
                    
                    appLoader.show();
                    var id = $( this ).attr('id');

                    $("#bays-tab").load('<?php echo_uri("bays/index/"); ?>'+id)
                    $("#levels-tab").load('<?php echo_uri("levels/index/"); ?>'+id)
                    $("#positions-tab").load('<?php echo_uri("positions/index/"); ?>'+id)
                    
                    appLoader.hide();
                });
            },
            tableRefreshButton: true,
        });
    });
</script>