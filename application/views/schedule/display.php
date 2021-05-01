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
                    <td class="w100"> <?php echo lang('monday'); ?></td>
                    <td><?php echo $model_info->mon_text; ?></td>
                </tr>
                <tr>
                    <td> <?php echo lang('tuesday'); ?></td>
                    <td><?php echo $model_info->tue_text; ?></td>
                </tr>
                <tr>
                    <td> <?php echo lang('wednesday'); ?></td>
                    <td><?php echo $model_info->wed_text; ?></td>
                </tr>
                <tr>
                    <td> <?php echo lang('thursday'); ?></td>
                    <td><?php echo nl2br($model_info->thu_text); ?></td>
                </tr>
                <tr>
                    <td> <?php echo lang('friday'); ?></td>
                    <td><?php echo $model_info->fri_text; ?></td>
                </tr>
                <tr>
                    <td> <?php echo lang('saturday'); ?></td>
                    <td><?php echo nl2br($model_info->sat_text); ?></td>
                </tr>
                <tr>
                    <td> <?php echo lang('sunday'); ?></td>
                    <td><?php echo $model_info->sun_text; ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
<?php echo form_open(null, array("id" => "schedule-display-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-footer">
    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
</div>
<?php echo form_close(); ?>  