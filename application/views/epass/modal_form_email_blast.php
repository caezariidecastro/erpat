<?php echo form_open(get_uri("EventPass/prepare_epass_instance"), array("id" => "epass-email-form", "class" => "general-form", "role" => "form")); ?>
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
            <strong>Warning!</strong> This action should only be execute with caution. This will send an email to all franchisee, distributor, seller, and viewer with the list of epass. This action cannot be undone!
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
        log('Click execute to send an email to all franchisee, distributor, seller, and viewer with the list of epass.');
        log('Total number of seats: <?php echo json_encode($seats); ?>');
        log('Total number of epass: <?php echo json_encode(count($lists)); ?>');

        let action = "";
        let epasses = [];
        let loops = null;
        $("#epass-email-form").appForm({
            closeModalOnSuccess: false,
            showLoader: false,
            onSuccess: function (result) {
                action = result.action;
                epasses = result.data;
                log('The total epass to process is: ' + epasses.length);

                //Unmask modal
                var $maskTarget = $(".modal-body").removeClass("hide");
                $maskTarget.closest('.modal-dialog').find('[type="submit"]').removeAttr('disabled');
                $maskTarget.removeClass("hide");
                $(".modal-mask").remove();

                const prapreTicket = async (current) => {
                    return $.ajax({
                        url: "<?php echo base_url()?>/EventPass/prepare_epass_render_or_email",
                        data: current,
                        method: "POST",
                        dataType: "json",
                        success: function(response){
                            if(response.success){
                                log('ePass #'+current.id+' process return '+(response.success?"successfull!":"failed!"));
                            }
                            return response;
                        }
                    })
                }

                let totalItems = epasses.length;
                let totalProcessed = 0;

                const maxProcesses = 2;
                let processing = 0;
                loops = setInterval(async () => {
                    if(processing < maxProcesses && epasses.length > 0) {
                        let current = epasses.shift();
                        processing++;
                        current.action = action;

                        const process = await prapreTicket(current)
                        log(process.message+' Progress is '+(totalProcessed+1)+' out of '+totalItems);
                        processing--;

                        totalProcessed += 1;
                    }

                    if(processing == 0 && epasses.length == 0) {
                        log('Completed sending of email for '+totalItems+' ePass.');
                        clearInterval(loops)
                    }
                }, 1000);
            }
        });

        $('#ajaxModal').on('hide.bs.modal', function (event) {
            clearInterval(loops);
        });
    });
</script>    