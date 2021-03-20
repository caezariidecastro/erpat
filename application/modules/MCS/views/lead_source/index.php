
<div id="page-content" class="p20 clearfix">
    <ul data-toggle="ajax-tab" class="nav nav-tabs bg-white title" role="tablist">
        <li class="title-tab"><h4 class="pl15 pt10 pr15"><?php echo lang("lead_source"); ?></h4></li>    
        <li><a  role="presentation" class="active" href="javascript:;" data-target="#lead-source-tab"></a></li>
        <div class="tab-title clearfix no-border">
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("mcs/lead_source/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_lead_source'), array("class" => "btn btn-default", "title" => lang('add_lead_source'), "id" => "lead-status-source-add-btn")); ?>
            </div>
        </div>
    </ul>

    <div class="tab-content">

        <div role="tabpanel" class="tab-pane fade" id="lead-source-tab">
            <div class="table-responsive">
                <table id="lead-source-table" class="display no-thead b-t b-b-only no-hover" cellspacing="0" width="100%">         
                </table>
            </div>
        </div>
        
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function () {
        $("#lead-source-table").appTable({
            source: '<?php echo_uri("mcs/lead_source/list_data") ?>',
            order: [[0, "asc"]],
            hideTools: true,
            displayLength: 100,
            columns: [
                {visible: false},
                {title: '<?php echo lang("title"); ?>'},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ],
            onInitComplete: function () {
                //apply sortable
                $("#lead-source-table").find("tbody").attr("id", "custom-field-table-sortable-source");
                var $selector = $("#custom-field-table-sortable-source");

                Sortable.create($selector[0], {
                    animation: 150,
                    chosenClass: "sortable-chosen",
                    ghostClass: "sortable-ghost",
                    onUpdate: function (e) {
                        appLoader.show();
                        //prepare sort indexes 
                        var data = "";
                        $.each($selector.find(".field-row"), function (index, ele) {
                            if (data) {
                                data += ",";
                            }

                            data += $(ele).attr("data-id") + "-" + index;
                        });

                        //update sort indexes
                        $.ajax({
                            url: '<?php echo_uri("mcs/lead_source/update_field_sort_values") ?>',
                            type: "POST",
                            data: {sort_values: data},
                            success: function () {
                                appLoader.hide();
                            }
                        });
                    }
                });

            }

        });
    });
</script>