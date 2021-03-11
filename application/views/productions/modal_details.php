<div class="modal-body clearfix">
    <?php $this->load->view("productions/details")?>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $("#ajaxModal .modal-dialog").addClass("modal-lg");

        $("#ajaxModal").on("hide.bs.modal", function(){
            $("#ajaxModal .modal-dialog").removeClass("modal-lg");
        });
    });
</script>
