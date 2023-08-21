<?php $this->load->view("includes/cropbox"); ?>

<div id="page-content" class="p20 clearfix prizes-modal">

    <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url("Raffle_draw") ?>"><?= lang("raffle") ?></a></li>
        <li class="breadcrumb-item active" aria-current="page"><?= $model_info->title ?></li>
    </ol>
    </nav>

    <div class="page-title clearfix">
        <h1></h1>
        <div class="title-button-group">
            <?php echo modal_anchor(get_uri("Raffle_draw/modal_form_prizes/".$model_info->id), "<i class='fa fa-plus'></i> " . lang('create_prizes'), array("class" => "btn btn-default", "title" => lang('create_prizes'))); ?>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="table-responsive">
            <table id="prize_list-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#prize_list-table").appTable({
            source: '<?php echo_uri("Raffle_draw/list_prizes/".$model_info->id) ?>',
            //order: [[1, 'desc']],
            columns: [
                {visible: false, searchable: false},
                {title: ''},
                {title: '<?php echo lang("image"); ?>'},
                {title: '<?php echo lang("participants"); ?>'},
                {title: '<?php echo lang("remarks"); ?>'},
                {title: '<?php echo lang("updated_at"); ?>'},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ],
            tableRefreshButton: true,
            printColumns: [1,2,3,4],
            xlsColumns: [1,2,3,4],
            onInitComplete: function () {
                $(".upload").change(function () {
                    var split = $(this).attr('id').split("-");
                    var id = split[0];
                    if (typeof FileReader == 'function') {
                        showCropBox(this);
                    } else {
                        $("#"+id+"-prize-form").submit();
                    }
                });

                $(".prize_base64").change(function () {
                    var split = $(this).attr('id').split("-");
                    var id = split[0]
                    var base64 = $('#'+id+'-prize_image').attr('value');
                    
                    appLoader.show();
                    $.ajax({
                        url: "<?php echo get_uri("Raffle_draw/update_prize_image"); ?>",
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

                //apply sortable
                $("#prize_list-table").find("tbody").attr("id", "prize_list-table-sortable");
                var $selector = $("#prize_list-table-sortable");

                Sortable.create($selector[0], {
                    animation: 150,
                    chosenClass: "sortable-chosen",
                    ghostClass: "sortable-ghost",
                    onUpdate: function (e) {
                        //appLoader.show();
                        //prepare sort indexes 
                        // var data = "";
                        // $.each($selector.find(".item-row"), function (index, ele) {
                        //     if (data) {
                        //         data += ",";
                        //     }

                        //     data += $(ele).attr("data-id") + "-" + index;
                        // });

                        //update sort indexes
                        // $.ajax({
                        //     url: '<?php //echo_uri("sales/Invoices/update_item_sort_values") ?>',
                        //     type: "POST",
                        //     data: {sort_values: data},
                        //     success: function () {
                        //         appLoader.hide();
                        //     }
                        // });
                    }
                });
            }
        });

        $("#single_draw-form").appForm({
            closeModalOnSuccess: false,
            onSuccess: function (result) {
                if(result.success) {
                    for(var i=0; i<result.data.length; i++) {
                        $("#prize_list-table").appTable({newData: result.data[i]});
                    }
                }
            },
            beforeAjaxSubmit: function(data, self, options) {
                appLoader.show({container: ".prizes-modal", css: "left:0; top: 50%;"});
            },
            onAjaxSuccess: function (result) {
                if(!result.success) {
                    alert(result.message);
                }
                appLoader.hide();
            }
        });

        $("#clear_prizes-form").appForm({
            closeModalOnSuccess: false,
            onSuccess: function (result) {
                if(result.success) {
                    console.log('Cleared');
                    $('#prize_list-table').DataTable().clear().draw();
                }
            },
            onAjaxSuccess: function (result) {
                if(!result.success) {
                    alert(result.message);
                }
            }
        });
    });
</script>