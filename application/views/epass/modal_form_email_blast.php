<?php echo form_open(get_uri("EventPass/prepare_email_instance"), array("id" => "epass-email-form", "class" => "general-form", "role" => "form")); ?>
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

        let epasses = [];
        $("#epass-email-form").appForm({
            closeModalOnSuccess: false,
            showLoader: false,
            onSuccess: function (result) {
                
                epasses = result.data;
                log('The total epass to process is: ' + epasses.length);

                //Unmask modal
                var $maskTarget = $(".modal-body").removeClass("hide");
                $maskTarget.closest('.modal-dialog').find('[type="submit"]').removeAttr('disabled');
                $maskTarget.removeClass("hide");
                $(".modal-mask").remove();

                const sendEmail = async (curremt) => {
                    return $.ajax({
                        url: "<?php echo base_url()?>/EventPass/prepare_epass_email",
                        data: curremt,
                        method: "POST",
                        dataType: "json",
                        success: function(response){
                            if(response.success){
                                log('ePass #'+curremt.id+' process return '+(response.success?"successfull!":"failed!"));
                            }
                            return response;
                        }
                    })
                }

                const process = async (lists) => {
                    for (let index = 0; index < lists.length; index++) {
                        const curremt = lists[index]
                        const process = await sendEmail(curremt)
                        log(process.message+' Progress is '+(index+1)+' out of '+lists.length);
                    }
                }

                if(epasses.length > 0) {
                    process(epasses);
                }
            }
        });

        
    });
</script>    