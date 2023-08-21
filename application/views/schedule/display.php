<div class="modal-body" style="padding: 15px 50px;">
    <div class="row">
        <div class="p10 clearfix">
            <div class="media m0 bg-white">
                <div class="media-body w100p pt5">
                    <div class="media-heading m0">
                        <?php echo "<strong>".lang('name')."</strong>: ".$model_info->title; ?>
                    </div>
                    <p><?php echo "<strong>".lang('description')."</strong>: ".$model_info->desc; ?> </p>
                </div>
            </div>
        </div>
        <div class="table-responsive mb15">
            <table class="table dataTable display b-t">
                <tr>
                    <th class="w100"> <?= lang("day") ?></th>
                    <th> <?= lang("schedule") ?></th>
                    <th> <?= lang("lunch_break") ?></th>
                </tr>
                <?php foreach(["mon", "tue", "wed", "thu", "fri", "sat", "sun"] as $day) { ?>
                <tr>
                    <td class="w100"> <?php echo lang($day); ?></td>
                    <td>
                        <?php if( isset($model_info->{$day."_text"}) ) { ?>
                        <?php echo $model_info->{$day."_text"}; ?> (<?= $model_info->{$day."_hours"} ?>h)</td>
                        <?php } ?>
                    <td>
                        <?php if( isset($model_info->{$day."_enable_lunch"}) ) { ?>
                            <?php echo $model_info->{$day."_in_lunch"}; ?> - 
                            <?php echo $model_info->{$day."_out_lunch"}; ?>  (<?= $model_info->{$day."_lunch"} ?>h)
                        <?php } ?>
                    </td>
                </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</div>
<?php echo form_open(null, array("id" => "schedule-display-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-footer">
    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
</div>
<?php echo form_close(); ?>  