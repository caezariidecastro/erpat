<div class="page-title clearfix no-border bg-off-white">
    <h1>
        <?php echo $model_info->name ?>
    </h1>
    <h6><?= $model_info->address ?></h6>
    <h6><?= $model_info->email ?></h6>
</div>

<div id="page-content" class="clearfix">
    <?php
        $boxes = [
            array(
                "icon" => "fa-sign-in",
                "name" => "Receiving",
                "value" => 0
            ),
            array(
                "icon" => "fa-sort-amount-asc",
                "name" => "Sorting",
                "value" => 0
            ),
            array(
                "icon" => "fa-map-signs",
                "name" => "Loading",
                "value" => 0
            ),
            array(
                "icon" => "fa-archive",
                "name" => "Picking",
                "value" => 0
            ),
            array(
                "icon" => "fa-money",
                "name" => "Packing",
                "value" => 0
            ),
            array(
                "icon" => "fa-truck",
                "name" => "Shipping",
                "value" => 0
            )
        ];
    ?>
    <div class="mt15">
        <div class="clearfix">
            <?php foreach($boxes as $item) { ?>
                <div class="col-lg-2 col-md-4 col-sm-6 widget-container">
                    <div class="panel panel-info">
                        <div class="panel-body ">
                            <div class="widget-icon">
                                <i class="fa <?= $item['icon'] ?>" style="font-size: -webkit-xxx-large;"></i>
                            </div>
                            <div class="widget-details">
                                <h3><?= $item['value'] ?></h3>
                                <?= $item['name'] ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <ul id="client-tabs" data-toggle="ajax-tab" class="nav nav-tabs" role="tablist">
        <li><a  role="presentation" href="<?php echo_uri("inventory/Warehouses/dashboard/" . $model_info->id); ?>" data-target="#warehouse-dashboard"> <?php echo lang('dashboard'); ?></a></li>
        <li><a  role="presentation" href="<?php echo_uri("inventory/Zones/index/" . $model_info->id); ?>" data-target="#warehouse-zones"> <?php echo lang('zones'); ?></a></li>
        <li><a  role="presentation" href="<?php echo_uri("inventory/Racks/index/" . $model_info->id); ?>" data-target="#warehouse-racks"> <?php echo lang('racks'); ?></a></li>
        <li><a  role="presentation" href="<?php echo_uri("inventory/Pallets/index/" . $model_info->id); ?>" data-target="#warehouse-pallets"> <?php echo lang('pallet'); ?></a></li>
        <li><a  role="presentation" href="<?php echo_uri("inventory/Warehouses/members/" . $model_info->id); ?>" data-target="#warehouse-members"> <?php echo lang('members'); ?></a></li>
        <li><a  role="presentation" href="<?php echo_uri("inventory/Warehouses/settings/" . $model_info->id); ?>" data-target="#warehouse-settings"> <?php echo lang('settings'); ?></a></li>
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade" id="warehouse-dashboard"></div>
        <div role="tabpanel" class="tab-pane fade" id="warehouse-zones"></div>
        <div role="tabpanel" class="tab-pane fade" id="warehouse-racks"></div>
        <div role="tabpanel" class="tab-pane fade" id="warehouse-pallets"></div>
        <div role="tabpanel" class="tab-pane fade" id="warehouse-members"></div>
        <div role="tabpanel" class="tab-pane fade" id="warehouse-settings"></div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {

        setTimeout(function () {
            var tab = "<?php echo $tab; ?>";
            if (tab === "info") {
                $("[data-target=#client-info]").trigger("click");
            }
        }, 210);

    });
</script>
