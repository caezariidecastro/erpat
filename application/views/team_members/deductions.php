<style>
    #deductions-table_wrapper{
        padding: 20px 10px 25px;
    }
    
    #deductions-table_wrapper .datatable-tools {
        display: none;
    }
</style>

<div class="tab-content">
    <?php echo form_open(get_uri("hrs/team_members/save_deductions_info/"), array("id" => "deduction-form", "class" => "general-form dashed-row white", "role" => "form")); ?>

    <input name="user_id" type="hidden" value="<?= $user_id; ?>" />
    <div class="panel panel-default">
        <div class="page-title clearfix">
            <h4> <?php echo lang('deductions'); ?></h4>
            <div class="title-button-group">
                <button type="submit" class="btn btn-default"><span class="fa fa-check-circle"></span> <?php echo lang('save_changes'); ?></button>
            </div>
        </div>
        <div class="table-responsive">
            <table id="deductions-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#deduction-form").appForm({
            isModal: false,
            onSuccess: function(result) {
                if(result.success) {
                    appAlert.success(result.message, {duration: 5000});
                } else {
                    appAlert.error(result.message, {duration: 3000})
                }
            }
        });

        $("#deductions-table").appTable({
            source: '<?php echo_uri("team_members/list_deductions/".$user_id) ?>',
            order: [[1, "desc"]],
            columns: [
                {title: '<?php echo lang("name"); ?>'},
                {title: '<?php echo lang("weekly"); ?>', "class": "text-right"},
                {title: '<?php echo lang("biweekly"); ?>', "class": "text-right"},
                {title: '<?php echo lang("monthly"); ?>', "class": "text-right"},
                {title: '<?php echo lang("annually"); ?>', "class": "text-right"}
            ]
        });
    });
</script>    