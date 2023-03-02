<div id="page-content" class="p20 clearfix">
    <div class='text-center mb15'> <?php echo anchor(get_uri("sales/Estimate_requests/edit_estimate_form/" . $model_info->id), lang('close_preview'), array("class" => "btn btn-default round", "title" => lang('close_preview'))); ?> </div>
    <?php
    $this->load->view("estimate_requests/estimate_request_form", array("is_preview" => true));
    ?>
</div>
