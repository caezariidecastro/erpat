<!-- Modal -->
<div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModal" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 400px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="confirmationModalTitle"><?php echo lang('delete') . "?"; ?></h4>
            </div>
            <div id="confirmationModalContent" class="modal-body">
                <?php echo lang('delete_confirmation_message'); ?>
            </div>
            <div class="modal-footer clearfix">
                <button id="confirmDeleteButton" type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-trash"></i> <?php echo lang("delete"); ?></button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> <?php echo lang('cancel'); ?></button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('#confirmationModal').on('shown.bs.modal', function (e) {
            var reason = JSON.stringify($('.user-status-confirm').attr('class'))
            if(typeof reason !== 'undefined') {
                if(reason.indexOf('restore') !== -1) {
                    $('#confirmationModalTitle').text('Restore?');
                    $('#confirmDeleteButton').removeClass('btn-danger');
                    $('#confirmDeleteButton').addClass('btn-primary');
                    $('#confirmDeleteButton').text('Restore');
                    return;
                }
            }

            $('#confirmationModalTitle').text('<?php echo lang('delete') . "?"; ?>');
            $('#confirmDeleteButton').removeClass('btn-primary');
            $('#confirmDeleteButton').addClass('btn-danger');
            $('#confirmDeleteButton').text('<?php echo lang("delete"); ?>');
            
        })
    })
</script>