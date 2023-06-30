<div class="modal-body" style="margin: 20px;">
    <div class="row">
        <div class="p10 clearfix">
            <div class="media m0 bg-white">
                <div class="media-left">
                    <span class="avatar avatar-sm">
                        <img src="<?php echo get_avatar($model_info->applicant_avatar); ?>" alt="..." />
                    </span>
                </div>
                <div class="media-body w100p pt5">
                    <div class="media-heading m0">
                        <?php echo $model_info->applicant_name; ?>
                    </div>
                    <p><span class='label label-info'><?php echo $model_info->job_title; ?></span> </p>
                </div>
            </div>
        </div>
        <div class="table-responsive mb15">
            <table class="table dataTable display b-t">
                <tr>
                    <td> <?php echo lang('log_type'); ?></td>
                    <td><?php echo $model_info->log_type_meta; ?></td>
                </tr>
                <tr>
                    <td> <?php echo lang('start_date'); ?></td>
                    <td><?php echo $model_info->start_date_meta; ?></td>
                </tr>
                <tr>
                    <td> <?php echo lang('end_date'); ?></td>
                    <td><?php echo $model_info->end_date_meta; ?></td>
                </tr>
                <tr>
                    <td> <?php echo lang('duration'); ?></td>
                    <td><?php echo $model_info->duration_meta; ?></td>
                </tr>
                <tr>
                    <td> <?php echo lang('status'); ?></td>
                    <td><?php echo $model_info->status_meta; ?></td>
                </tr>

                <?php if($model_info->log_type === "schedule") { ?>
                </table>
                <table  class="table dataTable display b-t" style="margin-top: 20px;">
                <tr>
                    <td> <?php echo lang('worked'); ?></td>
                    <td> <?php echo lang('overtime'); ?></td>
                    <td> <?php echo lang('lates'); ?></td>
                    <td> <?php echo lang('overbreak'); ?></td>
                    <td> <?php echo lang('undertime'); ?></td>
                </tr>
                <tr>
                    <td><?php echo $model_info->worked_meta; ?></td>
                    <td><?php echo $model_info->overtime_meta; ?></td>
                    <td><?php echo $model_info->lates_meta; ?></td>
                    <td><?php echo $model_info->overbreak_meta; ?></td>
                    <td><?php echo $model_info->undertime_meta; ?></td>
                </tr>
                </table>
                <table  class="table dataTable display b-t">
                <?php } ?>
                
                
                <?php if ($model_info->status === "rejected") { ?>
                    <tr>
                        <td> <?php echo lang('rejected_by'); ?></td>
                        <td><?php
                            $image_url = get_avatar($model_info->checker_avatar);
                            echo "<span class='avatar avatar-xs mr10'><img src='$image_url' alt=''></span><span>" . $model_info->checker_name . "</span>";
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td> <?php echo lang('rejected_date'); ?></td>
                        <td><?php
                            echo "<span>" . $model_info->checked_date . "</span>";
                            ?>
                        </td>
                    </tr>
                <?php } ?>
                <?php if ($model_info->status === "approved") { ?>
                    <tr>
                        <td> <?php echo lang('approved_by'); ?></td>
                        <td><?php
                            $image_url = get_avatar($model_info->checker_avatar);
                            echo "<span class='avatar avatar-xs mr10'><img src='$image_url' alt=''></span><span>" . $model_info->checker_name . "</span>";
                            ?>
                    </td>
                    <tr>
                        <tr>
                        <td> <?php echo lang('approved_date'); ?></td>
                        <td><?php
                            echo "<span>" . $model_info->checked_date . "</span>";
                            ?>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</div>
<?php echo form_open(get_uri("hrs/attendance/update_status"), array("id" => "log-status-form", "class" => "general-form", "role" => "form")); ?>
<input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
<input id="log_status_input" type="hidden" name="status" value="" />
<div class="modal-footer">
    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><span class="fa fa-close"></span> <?php echo lang('close'); ?></button>
    <?php if ($model_info->status === "pending" && $can_approve) { ?>
        <button data-status="rejected" type="submit" class="btn btn-danger btn-sm update-log-status"><span class="fa fa-times-circle-o"></span> <?php echo lang('reject'); ?></button>
        <button data-status="approved" type="submit" class="btn btn-success btn-sm update-log-status"><span class="fa fa-check-circle-o"></span> <?php echo lang('approve'); ?></button>
    <?php } ?>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function() {

        $(".update-log-status").click(function() {
            $("#log_status_input").val($(this).attr("data-status"));
        });

        $("#log-status-form").appForm({
            onSuccess: function() {
                location.reload();
            }
        });

    });
</script>    