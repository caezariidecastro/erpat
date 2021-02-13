<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
        <div class="page-title clearfix">
            <h1> <?php echo $name . " - " . lang('contacts'); ?></h1>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("vendors/add_contact_modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_contact'), array("class" => "btn btn-default", "title" => lang('add_contact'), "id" => "add_contact_button", "data-post-vendor_id" => $vendor_id)); ?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="vendor-contacts-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#vendor-contacts-table").appTable({
            source: '<?php echo_uri("vendors/contact_list_data?vendor_id=".$vendor_id) ?>',
            order: [[0, 'desc']],
            columns: [
                {title: "<?php echo lang('name') ?> ", "class": "w20p"},
                {title: "<?php echo lang('job_title') ?>"},
                {title: "<?php echo lang('email') ?>"},
                {title: "<?php echo lang('phone') ?>"},
                {title: "<?php echo lang('created_on') ?>",},
                {title: "<?php echo lang('created_by') ?>",},
                {title: "<i class='fa fa-bars'></i>", "class": "text-center option w100"}
            ],
            printColumns: [0, 1, 2, 3, 4, 5],
            xlsColumns: [0, 1, 2, 3, 4, 5],
        });
    });
</script>