<style>
    .item-row{
        padding-top: 8px;
        padding-right: 15px;
        padding-bottom: 8px;
        padding-left: 0px;
    }

    #item-details-preview{
        display:  none;
    }
</style>

<div class="row mt20">
    <div class="box">
        <div class="box-content message-view">
            <input type="hidden" id="item_id">
            <div class="col-sm-12 col-md-4">
                <div id="message-list-box" class="panel panel-default">
                    <div class="panel-heading clearfix">
                        <div class="pull-left p5" style="font-size: 18px;">
                            <?= lang('products')?>
                        </div>
                        <div class="pull-right">
                            <input type="text" id="search-items" class="datatable-search" placeholder="<?php echo lang('search') ?>">
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="items-table" class="display no-thead no-padding clickable" cellspacing="0" width="100%">            
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-8" id="item-details-placeholder">
                <div id="message-details-section" class="panel panel-default"> 
                    <div id="empty-message" class="text-center mb15 box">
                        <div class="box-content" style="vertical-align: middle; height: 100%; padding: 10px"> 
                            <div><?php echo lang("select_a_product"); ?></div>
                            <span class="fa fa-object-group" style="font-size: 1100%; color:#f6f8f8"></span>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-8" id="item-details-preview">
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

<script type="text/javascript">
    $(document).ready(function () {
        $("#items-table").appTable({
            source: '<?php echo_uri("mes/ProductInventory/item_list_data/") ?>',
            columns: [
                {title: '<?php echo lang("product") ?>'},
            ]
        });

        var itemsTable = $('#items-table').DataTable();
        $('#search-items').keyup(function () {
            itemsTable.search($(this).val()).draw();
        });

        $("#items-table tbody").on("click", "tr", function () {

            if (!$(this).hasClass("active")) {
                var item_id = $(this).find(".item-row").attr("data-id");

                if(item_id) {
                    $("tr.active").removeClass("active");
                    $(this).addClass("active");
                    $('#item_id').val(item_id);

                    $('#item-details-placeholder').hide();
                    $('#item-details-preview').show();

                    appLoader.show();

                    $.ajax({
                        url: "<?php echo get_uri("mes/ProductInventory/item_details/"); ?>"+item_id,
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