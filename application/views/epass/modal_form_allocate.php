<?php echo form_open(get_uri("EventPass/clear_allocation"), array("id" => "epass-ticket-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div class="form-group">
        <div class="col-md-12">Console</div>
        <div class="col-md-12">
            <div class="notepad">
                <?php
                echo form_textarea(array(
                    "id" => "consolelog",
                    "name" => "consolelog",
                    "class" => "form-control",
                    "disabled"=>false
                ));
                ?>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="submit" class="btn btn-danger"><span class="fa fa-recycle "></span> <?php echo lang('execute'); ?></button>
</div>

<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        function log(message) {
            $('#consolelog').val('> '+message+'\n');
        }
        log('Click execute to unset all seat and re-assign a new seat acccording to the order of date registered.');

        let epasses = [];
        $("#epass-ticket-form").appForm({
            closeModalOnSuccess: false,
            showLoader: false,
            onSuccess: function (result) {
                //console.log(result.data);
                epasses = result.data;
                log('The total epass to process is: ' + epasses.length);

                //Unmask modal
                var $maskTarget = $(".modal-body").removeClass("hide");
                $maskTarget.closest('.modal-dialog').find('[type="submit"]').removeAttr('disabled');
                $maskTarget.removeClass("hide");
                $(".modal-mask").remove();

                const allocate = async (curremt) => {
                    return $.ajax({
                        url: "<?php echo base_url()?>/EventPass/allocate_seats",
                        data: curremt,
                        method: "POST",
                        dataType: "json",
                        success: function(response){
                            if(response.success){
                                log('Success on id of '+response.data);
                            }
                            return response;
                        }
                    })
                }

                const process = async (lists) => {
                    for (let index = 0; index < lists.length; index++) {
                        const curremt = lists[index]
                        const process = await allocate(curremt)
                        log(process.message+' Progress is '+(index+1)+' out of '+lists.length);
                    }
                }

                if(epasses.length > 0) {
                    process(epasses);
                }

                //reload instead
                //$("#epass-table").appTable({newData: result.data, dataId: result.id});
            }
        });

        
    });
</script>    