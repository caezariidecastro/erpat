<div id="js-clock-in-out" class="panel <?php echo (isset($clock_status->id)) ? 'panel-info' : 'panel-coral'; ?>">
    <div class="panel-body ">
        <div class="widget-icon">
            <i class="fa fa-clock-o"></i>
        </div>
        <div class="widget-details">
            <?php
            if (isset($clock_status->id)) {
                $in_time = format_to_time($clock_status->in_time);
                $in_datetime = format_to_datetime($clock_status->in_time);

                $pre_break = $on_break?lang('breaktime_starts_at'):lang('clock_started_at');
                $break_time = $on_break?$break_time:$in_time;
                echo "<div class='mb15' title='$in_datetime'>" . $pre_break . " : $break_time</div>";
              
                $break = array(
                    "class" => "btn btn-default no-border", 
                    "title" => lang('resume_work'), 
                    "style" => "margin-right: 10px;", 
                    "id"=>"timecard-break", 
                    "data-post-id" => $clock_status->id, 
                    "data-post-action"=>$on_break,
                    "data-inline-loader" => "1",
                    "data-closest-target" => "#js-clock-in-out"
                );
                $break_text = $on_break ?lang('resume_work'):lang("take_a_break");
                echo ajax_anchor(get_uri("hrs/attendance/log_breaktime"), "<i class='fa fa-pause-circle'></i> " . $break_text, $break);

                $clock_out = array(
                    "class" => "btn btn-default no-border", 
                    "title" => lang('exit'), 
                    "id"=>"timecard-clock-out", 
                    "data-post-id" => $clock_status->id, 
                    "data-post-clock_out"=>1,
                );
                if($on_break) {
                    $clock_out['disabled'] = true;
                }
                echo modal_anchor(get_uri("hrs/attendance/note_modal_form"), "<i class='fa fa-sign-out'></i> " . lang('exit'), $clock_out );
            } else {
                echo "<div class='mb15'>" . lang('you_are_currently_clocked_out') . "</div>";
                echo ajax_anchor(get_uri("hrs/attendance/log_time"), "<i class='fa fa-sign-in'></i> " . lang('clock_in'), array("class" => "btn btn-default no-border", "title" => lang('clock_in'), "data-inline-loader" => "1", "data-closest-target" => "#js-clock-in-out"));
            }
            ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        console.log('<?= json_encode($on_break) ?>');
    });
</script>