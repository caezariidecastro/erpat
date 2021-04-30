<?php echo form_open(get_uri("hrs/schedule/save"), array("id" => "schedule-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />

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

    <!-- Monday -->
    <div class="clearfix">
        <label for="mon_enable" class="col-sm-12"><strong><?php echo lang('monday'); ?></strong></label>
        <div class="col-md-2 col-sm-2 form-group" style="transform: scale(1.2);">
            <div>
                <?php
                echo form_checkbox("mon_enable", "1", $model_info->mon_enable, "id='mon_enable' class='ml15'");
                ?> 
            </div>
        </div>
        <label for="mon_in" class=" col-md-1 col-sm-1"><?php echo lang('in'); ?></label>
        <div class="col-md-4 col-sm-4  form-group">
            <?php
            echo form_input(array(
                "id" => "mon_in",
                "name" => "mon_in",
                "value" => $model_info->mon_in,
                "class" => "form-control",
                "placeholder" => lang('in_time'),
            ));
            ?>
        </div>
        <label for="mon_out" class=" col-md-1 col-sm-1"><?php echo lang('out'); ?></label>
        <div class="col-md-4 col-sm-4 form-group">
            <?php
            echo form_input(array(
                "id" => "mon_out",
                "name" => "mon_out",
                "value" => $model_info->mon_out,
                "class" => "form-control",
                "placeholder" => lang('out_time'),
            ));
            ?>
        </div>
    </div>

    <!-- Tuesday -->
    <div class="clearfix">
        <label for="tue_enable" class="col-sm-12"><strong><?php echo lang('tuesday'); ?></strong></label>
        <div class="col-md-2 col-sm-2 form-group" style="transform: scale(1.2);">
            <div>
                <?php
                echo form_checkbox("tue_enable", "1", $model_info->tue_enable, "id='tue_enable' class='ml15'");
                ?> 
            </div>
        </div>
        <label for="tue_in" class=" col-md-1 col-sm-1"><?php echo lang('in'); ?></label>
        <div class="col-md-4 col-sm-4  form-group">
            <?php
            echo form_input(array(
                "id" => "tue_in",
                "name" => "tue_in",
                "value" => $model_info->tue_in,
                "class" => "form-control",
                "placeholder" => lang('in_time'),
            ));
            ?>
        </div>
        <label for="tue_out" class=" col-md-1 col-sm-1"><?php echo lang('out'); ?></label>
        <div class="col-md-4 col-sm-4 form-group">
            <?php
            echo form_input(array(
                "id" => "tue_out",
                "name" => "tue_out",
                "value" => $model_info->tue_out,
                "class" => "form-control",
                "placeholder" => lang('out_time'),
            ));
            ?>
        </div>
    </div>

    <!-- Wednesday -->
    <div class="clearfix">
        <label for="wed_enable" class="col-sm-12"><strong><?php echo lang('wednesday'); ?></strong></label>
        <div class="col-md-2 col-sm-2 form-group" style="transform: scale(1.2);">
            <div>
                <?php
                echo form_checkbox("wed_enable", "1", $model_info->wed_enable, "id='wed_enable' class='ml15'");
                ?> 
            </div>
        </div>
        <label for="wed_in" class=" col-md-1 col-sm-1"><?php echo lang('in'); ?></label>
        <div class="col-md-4 col-sm-4  form-group">
            <?php
            echo form_input(array(
                "id" => "wed_in",
                "name" => "wed_in",
                "value" => $model_info->wed_in,
                "class" => "form-control",
                "placeholder" => lang('in_time'),
            ));
            ?>
        </div>
        <label for="wed_out" class=" col-md-1 col-sm-1"><?php echo lang('out'); ?></label>
        <div class="col-md-4 col-sm-4 form-group">
            <?php
            echo form_input(array(
                "id" => "wed_out",
                "name" => "wed_out",
                "value" => $model_info->wed_out,
                "class" => "form-control",
                "placeholder" => lang('out_time'),
            ));
            ?>
        </div>
    </div>

    <!-- Thursday -->
    <div class="clearfix">
        <label for="thu_enable" class="col-sm-12"><strong><?php echo lang('thursday'); ?></strong></label>
        <div class="col-md-2 col-sm-2 form-group" style="transform: scale(1.2);">
            <div>
                <?php
                echo form_checkbox("thu_enable", "1", $model_info->thu_enable, "id='thu_enable' class='ml15'");
                ?> 
            </div>
        </div>
        <label for="thu_in" class=" col-md-1 col-sm-1"><?php echo lang('in'); ?></label>
        <div class="col-md-4 col-sm-4  form-group">
            <?php
            echo form_input(array(
                "id" => "thu_in",
                "name" => "thu_in",
                "value" => $model_info->thu_in,
                "class" => "form-control",
                "placeholder" => lang('in_time'),
            ));
            ?>
        </div>
        <label for="thu_out" class=" col-md-1 col-sm-1"><?php echo lang('out'); ?></label>
        <div class="col-md-4 col-sm-4 form-group">
            <?php
            echo form_input(array(
                "id" => "thu_out",
                "name" => "thu_out",
                "value" => $model_info->thu_out,
                "class" => "form-control",
                "placeholder" => lang('out_time'),
            ));
            ?>
        </div>
    </div>

    <!-- Friday -->
    <div class="clearfix">
        <label for="fri_enable" class="col-sm-12"><strong><?php echo lang('friday'); ?></strong></label>
        <div class="col-md-2 col-sm-2 form-group" style="transform: scale(1.2);">
            <div>
                <?php
                echo form_checkbox("fri_enable", "1", $model_info->fri_enable, "id='fri_enable' class='ml15'");
                ?> 
            </div>
        </div>
        <label for="fri_in" class=" col-md-1 col-sm-1"><?php echo lang('in'); ?></label>
        <div class="col-md-4 col-sm-4  form-group">
            <?php
            echo form_input(array(
                "id" => "fri_in",
                "name" => "fri_in",
                "value" => $model_info->fri_in,
                "class" => "form-control",
                "placeholder" => lang('in_time'),
            ));
            ?>
        </div>
        <label for="fri_out" class=" col-md-1 col-sm-1"><?php echo lang('out'); ?></label>
        <div class="col-md-4 col-sm-4 form-group">
            <?php
            echo form_input(array(
                "id" => "fri_out",
                "name" => "fri_out",
                "value" => $model_info->fri_out,
                "class" => "form-control",
                "placeholder" => lang('out_time'),
            ));
            ?>
        </div>
    </div>

    <!-- Saturday -->
    <div class="clearfix">
        <label for="sat_enable" class="col-sm-12"><strong><?php echo lang('saturday'); ?></strong></label>
        <div class="col-md-2 col-sm-2 form-group" style="transform: scale(1.2);">
            <div>
                <?php
                echo form_checkbox("sat_enable", "1", $model_info->sun_enable, "id='sat_enable' class='ml15'");
                ?> 
            </div>
        </div>
        <label for="sat_in" class=" col-md-1 col-sm-1"><?php echo lang('in'); ?></label>
        <div class="col-md-4 col-sm-4  form-group">
            <?php
            echo form_input(array(
                "id" => "sat_in",
                "name" => "sat_in",
                "value" => $model_info->sat_in,
                "class" => "form-control",
                "placeholder" => lang('in_time'),
            ));
            ?>
        </div>
        <label for="sat_out" class=" col-md-1 col-sm-1"><?php echo lang('out'); ?></label>
        <div class="col-md-4 col-sm-4 form-group">
            <?php
            echo form_input(array(
                "id" => "sat_out",
                "name" => "sat_out",
                "value" => $model_info->sat_out,
                "class" => "form-control",
                "placeholder" => lang('out_time'),
            ));
            ?>
        </div>
    </div>

    <!-- Sunday -->
    <div class="clearfix">
        <label for="sun_enable" class="col-sm-12"><strong><?php echo lang('sunday'); ?></strong></label>
        <div class="col-md-2 col-sm-2 form-group" style="transform: scale(1.2);">
            <div>
                <?php
                echo form_checkbox("sun_enable", "1", $model_info->sun_enable, "id='sun_enable' class='ml15'");
                ?> 
            </div>
        </div>
        <label for="sun_in" class=" col-md-1 col-sm-1"><?php echo lang('in'); ?></label>
        <div class="col-md-4 col-sm-4  form-group">
            <?php
            echo form_input(array(
                "id" => "sun_in",
                "name" => "sun_in",
                "value" => $model_info->sun_in,
                "class" => "form-control",
                "placeholder" => lang('in_time'),
            ));
            ?>
        </div>
        <label for="sun_out" class=" col-md-1 col-sm-1"><?php echo lang('out'); ?></label>
        <div class="col-md-4 col-sm-4 form-group">
            <?php
            echo form_input(array(
                "id" => "sun_out",
                "name" => "sun_out",
                "value" => $model_info->sun_out,
                "class" => "form-control",
                "placeholder" => lang('out_time'),
            ));
            ?>
        </div>
    </div>
    
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
                $(".dataTable:visible").appTable({newData: result.data, dataId: result.id});
            }
        });
        setTimePicker("#mon_in, #mon_out, #tue_in, #tue_out, #wed_in, #wed_out, #thu_in, #thu_out, #fri_in, #fri_out, #sat_in, #sat_out, #sun_in, #sun_out");

        $("#name").focus();
    });
</script>