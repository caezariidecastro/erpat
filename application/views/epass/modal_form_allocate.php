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
    <div class="form-group">
        <div class="col-md-12">
            <?php
            echo form_input(array(
                "id" => "action",
                "name" => "action",
                "value" => "",
                "class" => "form-control notepad-title",
                "placeholder" => lang('action'),
                "autofocus" => true,
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <div class="alert alert-danger" role="alert">
            <strong>Warning!</strong> This action should only be execute with caution. This will re-assign all seats from distributor, seller, and viewer respectively. 
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="submit" class="btn btn-danger"><span class="fa fa-recycle "></span> <?php echo lang('execute'); ?></button>
</div>

<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $('#action').select2({data: <?php echo json_encode($action_lists); ?>});
        
        function log(message) {
            $('#consolelog').val('> '+message+'\n'+$('#consolelog').val());
        }
        log('Click execute to unset all seat and re-assign a new seat acccording to the order of date registered.');

        let epasses = [];
        let loops = null;
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

                if(result.action == "clear") {
                    return;
                }

                if(result.action == "allocate") {

                    const allocate = async (curremt) => {
                        return $.ajax({
                            url: "<?php echo base_url()?>/EventPass/allocate_seats",
                            data: curremt,
                            method: "POST",
                            dataType: "json",
                            success: function(response){
                                if(response.success){
                                    log('Success on id of '+response.data);
                                } else {
                                    log('Failed on id of '+response.data);
                                }
                                return response;
                            }
                        })
                    }

                    let totalItems = epasses.length;
                    let totalProcessed = 0;

                    const maxProcesses = 10;
                    let processing = 0;
                    loops = setInterval(async () => {
                        if(processing < maxProcesses && epasses.length > 0) {
                            let current = epasses.shift();
                            processing++;

                            const process = await allocate(current)
                            log(process.message+' Progress is '+(totalProcessed+1)+' out of '+totalItems);
                            processing--;

                            totalProcessed += 1;
                        }

                        if(processing == 0 && epasses.length == 0) {
                            log('Completed reallocation of seats for '+totalItems+' ePass.');
                            clearInterval(loops)
                        }
                    }, 100);
                }
            }
        });

        $('#ajaxModal').on('hide.bs.modal', function (event) {
            clearInterval(loops);
        });
    });
</script>    