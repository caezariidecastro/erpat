<?php echo form_open(get_uri("hrs/team_members/update_schedule/".$model_info->id), array("id" => "update_sched-team_member-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" id="user_id" name="user_id" value="<?= $model_info->id ?>"/>
    <div role="tabpanel" class="tab-pane">
        <div class="form-group">
            <label for="name" class=" col-md-3"><?php echo lang('fullname').": "; ?></label>
            <label for="name" class=" col-md-5"><?= $model_info->first_name." ".$model_info->last_name; ?></label>
            <label for="name" class=" col-md-3"><?= $model_info->job_title; ?></label>
        </div>

        <div class="form-group">
            <label for="schedule_id" class="col-md-3"><?php echo lang('schedule').": "; ?></label>
            <div class="col-md-9">
                <?php
                echo form_dropdown("schedule_id", $schedule_dropdown, array($model_info->sched_id), "class='select2 validate-hidden' id='user-schedule'");
                ?>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
    <button id="form-submit" type="button" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#update_sched-team_member-form").appForm({
            onSuccess: function (result) {
                if (result.success) {
                    $("#team_member-table").appTable({newData: result.data, dataId: result.id});
                }
            }
        });

        $("#update_sched-team_member-form .select2").select2();

        $("#form-submit").click(function () {
            $("#update_sched-team_member-form").trigger('submit');
        });
    });
</script>