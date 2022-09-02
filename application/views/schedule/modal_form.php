<?php echo form_open(get_uri("hrs/schedule/save"), array("id" => "schedule-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />

    <div class="form-group">
        <div class=" col-md-12">
            <div class="alert alert-info" role="alert">
                It is recommended to base the input time to <strong><?= get_setting('timezone'); ?></strong> timezone. Failure to follow the said instruction may result to invalid lates and undertime reports.
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="name" class=" col-md-3"><?php echo lang('title'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_input(array(
                "id" => "title",
                "name" => "title",
                "class" => "form-control",
                "placeholder" => lang('title'),
                "value" => $model_info->title,
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => lang("field_required"),
            ));
            ?>
        </div>
    </div>

    <div class="form-group">
        <label for="desc" class=" col-md-3"><?php echo lang('description'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_textarea(array(
                "id" => "desc",
                "name" => "desc",
                "class" => "form-control",
                "placeholder" => lang('description'),
                "value" => $model_info->desc,
                "data-rich-text-editor" => true
            ));
            ?>
        </div>
    </div>

    <?php 
    $days = ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'];
    foreach($days as $day) { ?>
        <div class="clearfix">
            <?php $hours = $day."_hours"; ?> 
            <label for="<?= $day ?>_enable" class="col-sm-12"><strong><?= lang(get_day_name($day)); ?> (<?= $model_info->$hours ?>)</strong></label>

            <?php 
            $trackings = ['', '_first', '_lunch', '_second'];
            foreach($trackings as $tracking) {?>
                <div class="col-md-4 col-sm-4 form-group" style="transform: scale(1.2);">
                    <div>
                        <?php
                        $enabled = $day."_enable".$tracking;
                        echo form_checkbox($enabled, "1", $model_info->$enabled, " id='".$enabled."' class='ml15'");
                        ?> 
                        <label for="<?= $enabled ?>"><?= get_sched_title($tracking) ?></label>
                    </div>
                </div>
                <label for="<?= $day ?>_in<?= $tracking ?>" class=" col-md-1 col-sm-1"><?= $tracking!=''?lang('start'):lang('in'); ?></label>
                <div class="col-md-3 col-sm-3  form-group">
                    <?php
                    $in_time = $day."_in".$tracking;
                    echo form_input(array(
                        "id" => $day."_in".$tracking,
                        "name" => $day."_in".$tracking,
                        "value" => $model_info->$in_time,
                        "class" => "form-control",
                        "placeholder" => lang('in_time'.$tracking),
                    ));
                    ?>
                </div>
                <label for="<?= $day ?>_out<?= $tracking ?>" class=" col-md-1 col-sm-1"><?= $tracking!=''?lang('end'):lang('out'); ?></label>
                <div class="col-md-3 col-sm-3 form-group">
                    <?php
                    $out_time = $day."_out".$tracking;
                    echo form_input(array(
                        "id" => $day."_out".$tracking,
                        "name" => $day."_out".$tracking,
                        "value" => $model_info->$out_time,
                        "class" => "form-control",
                        "placeholder" => lang('out_time'.$tracking),
                    ));
                    ?>
                </div>
            <?php } ?>
        </div>
    <?php } ?>
    
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#schedule-form").appForm({
            onSuccess: function (result) {
                $(".dataTable:visible").appTable({reload: true});
                //TODO: Instead of reloading, remove previous and load the new.
                //$(".dataTable:visible").appTable({newData: result.data, dataId: result.id});
            }
        });
        
        <?php 
            $timepickers = [];
            foreach($days as $day) {
                $trackings = ['', '_first', '_lunch', '_second'];
                foreach($trackings as $tracking) {
                    $timepickers[] = "#".$day."_in".$tracking;
                    $timepickers[] = "#".$day."_out".$tracking;
                }
            }
            $timepicks = implode(', ', $timepickers);
        ?>
        setTimePicker("<?= $timepicks ?>");

        $("#name").focus();
    });
</script>