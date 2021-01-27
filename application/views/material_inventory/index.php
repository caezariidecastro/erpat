<style>
    .material-row{
        padding-top: 8px;
        padding-right: 15px;
        padding-bottom: 8px;
        padding-left: 0px;
    }

    #material-details-preview{
        display:  none;
    }
</style>

<div class="row">
    <div class="box mt20">
        <div class="box-content message-view">
            <input type="hidden" id="material_id">
            <div class="col-sm-12 col-md-4">
                <div id="message-list-box" class="panel panel-default">
                    <div class="panel-heading clearfix">
                        <div class="pull-left p5" style="font-size: 18px;">
                            <?= lang('materials')?>
                        </div>
                        <div class="pull-right">
                            <input type="text" id="search-materials" class="datatable-search" placeholder="<?php echo lang('search') ?>">
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="materials-table" class="display no-thead no-padding clickable" cellspacing="0" width="100%">            
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-8" id="material-details-placeholder">
                <div id="message-details-section" class="panel panel-default"> 
                    <div id="empty-message" class="text-center mb15 box">
                        <div class="box-content" style="vertical-align: middle; height: 100%; padding: 10px"> 
                            <div><?php echo lang("select_a_material"); ?></div>
                            <span class="fa fa-object-group" style="font-size: 1100%; color:#f6f8f8"></span>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-8" id="material-details-preview">
                <div id="message-details-section" class="panel panel-default"> 
                    <div id="empty-message" class="text-center mb15 box">
                        <div class="box-content" style="vertical-align: middle; height: 100%; padding: 10px" id="result"> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style type="text/css">
    #materials-table_wrapper > .datatable-tools:first-child, #material-inventory-table_wrapper > .datatable-tools:first-child {
        display:  none;
    }
</style>

<script type="text/javascript">
    $(document).ready(function () {
        $("#materials-table").appTable({
            source: '<?php echo_uri("material_inventory/material_list_data/") ?>',
            columns: [
                {title: '<?php echo lang("material") ?>'},
            ]
        });

        var materialsTable = $('#materials-table').DataTable();
        $('#search-materials').keyup(function () {
            materialsTable.search($(this).val()).draw();
        });

        $("#materials-table tbody").on("click", "tr", function () {

            if (!$(this).hasClass("active")) {
                var material_id = $(this).find(".material-row").attr("data-id");

                if(material_id) {
                    $("tr.active").removeClass("active");
                    $(this).addClass("active");
                    $('#material_id').val(material_id);

                    $('#material-details-placeholder').hide();
                    $('#material-details-preview').show();

                    appLoader.show();

                    $.ajax({
                        url: "<?php echo get_uri("material_inventory/material_details/"); ?>"+material_id,
                        success: function (result) {
                            $('#result').html(result);
                            appLoader.hide();
                        }
                    });
                }
            }
        });
    });
</script>