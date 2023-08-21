<?php echo form_open("", array("id" => "no_schedule-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />

    <div class="form-group">
        <div class=" col-md-12">
            <div class="alert alert-danger" role="alert">
                The employees that are listed in this form dont an active schedule.
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class=" col-md-12">
            <input type="text" value="<?php echo $members_no_scheds; ?>" name="30min_break_employee" id="team_members_dropdown_30min_break" class="w100p validate-hidden" disabled/>    
        </div>
    </div>
    
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#team_members_dropdown_30min_break").select2({
            multiple: true,
            data: <?php echo ($team_members_dropdown); ?>
        });
    });
</script>