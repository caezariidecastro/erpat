<style>
    #daily-tax-table_wrapper{
        padding: 20px 10px 25px;
    }
    
    #daily-tax-table_wrapper .datatable-tools {
        display: none;
    }

    #weekly-tax-table_wrapper{
        padding: 20px 10px 25px;
    }
    
    #weekly-tax-table_wrapper .datatable-tools {
        display: none;
    }

    #biweekly-tax-table_wrapper{
        padding: 20px 10px 25px;
    }
    
    #biweekly-tax-table_wrapper .datatable-tools {
        display: none;
    }

    #monthly-tax-table_wrapper{
        padding: 20px 10px 25px;
    }
    
    #monthly-tax-table_wrapper .datatable-tools {
        display: none;
    }
</style>

<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
        <div class="page-title clearfix">
            <h4> <?php echo lang('taxes'); ?></h4>
            <div class="title-button-group">
                <?php echo modal_anchor(get_uri("taxes/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('add_tax'), array("class" => "btn btn-default", "title" => lang('add_tax'))); ?>
            </div>
        </div>
        <div class="table-responsive">
            <table id="taxes-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
    </div>

    <div class="panel panel-default">
        <?php echo form_open(get_uri("taxes/save_daily_tax"), array("id" => "daily-tax-form", "class" => "general-form", "role" => "form")); ?>
        <div class="page-title clearfix">
            <h4> <?php echo lang('daily_tax_table'); ?></h4>
            <div class="title-button-group">
                <button type="submit" class="btn btn-default"><span class="fa fa-check-circle"></span> <?php echo lang('save_changes'); ?></button>
            </div>
        </div>
        <div class="table-responsive">
            <table id="daily-tax-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
        <?php echo form_close(); ?>
    </div>
    <div class="panel panel-default">
        <?php echo form_open(get_uri("taxes/save_weekly_tax"), array("id" => "weekly-tax-form", "class" => "general-form", "role" => "form")); ?>
        <div class="page-title clearfix">
            <h4> <?php echo lang('weekly_tax_table'); ?></h4>
            <div class="title-button-group">
                <button type="submit" class="btn btn-default"><span class="fa fa-check-circle"></span> <?php echo lang('save_changes'); ?></button>
            </div>
        </div>
        <div class="table-responsive">
            <table id="weekly-tax-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
        <?php echo form_close(); ?>
    </div>
    <div class="panel panel-default">
        <?php echo form_open(get_uri("taxes/save_biweekly_tax"), array("id" => "biweekly-tax-form", "class" => "general-form", "role" => "form")); ?>
        <div class="page-title clearfix">
            <h4> <?php echo lang('biweekly_tax_table'); ?></h4>
            <div class="title-button-group">
                <button type="submit" class="btn btn-default"><span class="fa fa-check-circle"></span> <?php echo lang('save_changes'); ?></button>
            </div>
        </div>
        <div class="table-responsive">
            <table id="biweekly-tax-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
        <?php echo form_close(); ?>
    </div>
    <div class="panel panel-default">
        <?php echo form_open(get_uri("taxes/save_monthly_tax"), array("id" => "monthly-tax-form", "class" => "general-form", "role" => "form")); ?>
        <div class="page-title clearfix">
            <h4> <?php echo lang('monthly_tax_table'); ?></h4>
            <div class="title-button-group">
                <button type="submit" class="btn btn-default"><span class="fa fa-check-circle"></span> <?php echo lang('save_changes'); ?></button>
            </div>
        </div>
        <div class="table-responsive">
            <table id="monthly-tax-table" class="display" cellspacing="0" width="100%">            
            </table>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $("#taxes-table").appTable({
            source: '<?php echo_uri("taxes/list_data") ?>',
            columns: [
                {title: '<?php echo lang("name"); ?>'},
                {title: '<?php echo lang("percentage"); ?>'},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ]
        });

        $("#daily-tax-table").appTable({
            source: '<?php echo_uri("taxes/list_daily") ?>',
            order: [[1, "desc"]],
            columns: [
                {visible: false, searchable: false},
                {title: '<?php echo lang("name"); ?>'},
                {title: '<?php echo lang("starts_at"); ?>', "class": "text-right"},
                {title: '<?php echo lang("not_over"); ?>', "class": "text-right"},
                {title: '<?php echo lang("amount"); ?>', "class": "text-right"},
                {title: '<?php echo lang("rate"); ?>', "class": "text-right"}
            ]
        });

        $("#daily-tax-form").appForm({
            isModal: false,
            onSuccess: function(result) {
                if(result.success) {
                    appAlert.success(result.message, {duration: 5000});
                } else {
                    appAlert.error(result.message, {duration: 3000})
                }
            }
        });

        $("#weekly-tax-table").appTable({
            source: '<?php echo_uri("taxes/list_weekly") ?>',
            order: [[1, "desc"]],
            columns: [
                {visible: false, searchable: false},
                {title: '<?php echo lang("name"); ?>'},
                {title: '<?php echo lang("starts_at"); ?>', "class": "text-right"},
                {title: '<?php echo lang("not_over"); ?>', "class": "text-right"},
                {title: '<?php echo lang("amount"); ?>', "class": "text-right"},
                {title: '<?php echo lang("rate"); ?>', "class": "text-right"}
            ]
        });

        $("#weekly-tax-form").appForm({
            isModal: false,
            onSuccess: function(result) {
                if(result.success) {
                    appAlert.success(result.message, {duration: 5000});
                } else {
                    appAlert.error(result.message, {duration: 3000})
                }
            }
        });

        $("#biweekly-tax-table").appTable({
            source: '<?php echo_uri("taxes/list_biweekly") ?>',
            order: [[1, "desc"]],
            columns: [
                {visible: false, searchable: false},
                {title: '<?php echo lang("name"); ?>'},
                {title: '<?php echo lang("starts_at"); ?>', "class": "text-right"},
                {title: '<?php echo lang("not_over"); ?>', "class": "text-right"},
                {title: '<?php echo lang("amount"); ?>', "class": "text-right"},
                {title: '<?php echo lang("rate"); ?>', "class": "text-right"}
            ]
        });

        $("#biweekly-tax-form").appForm({
            isModal: false,
            onSuccess: function(result) {
                if(result.success) {
                    appAlert.success(result.message, {duration: 5000});
                } else {
                    appAlert.error(result.message, {duration: 3000})
                }
            }
        });

        $("#monthly-tax-table").appTable({
            source: '<?php echo_uri("taxes/list_monthly") ?>',
            order: [[1, "desc"]],
            columns: [
                {visible: false, searchable: false},
                {title: '<?php echo lang("name"); ?>'},
                {title: '<?php echo lang("starts_at"); ?>', "class": "text-right"},
                {title: '<?php echo lang("not_over"); ?>', "class": "text-right"},
                {title: '<?php echo lang("amount"); ?>', "class": "text-right"},
                {title: '<?php echo lang("rate"); ?>', "class": "text-right"}
            ]
        });

        $("#monthly-tax-form").appForm({
            isModal: false,
            onSuccess: function(result) {
                if(result.success) {
                    appAlert.success(result.message, {duration: 5000});
                } else {
                    appAlert.error(result.message, {duration: 3000})
                }
            }
        });
    });
</script>