
<div class="page-title clearfix">
    <div class="title-button-group">
        <?php echo modal_anchor(get_uri("hrs/leaves/modal_form_add_credit"), "<i class='fa fa-plus-circle'></i> " . lang('add_leave_credits'), array("class" => "btn btn-default", "title" => lang('leave_credit_add_form'))); ?>
        <?php echo modal_anchor(get_uri("hrs/leaves/modal_form_deduct_credit"), "<i class='fa fa-minus-circle'></i> " . lang('remove_leave_credits'), array("class" => "btn btn-default", "title" => lang('leave_credit_deduct_form'))); ?>
    </div>
</div>
<div class="table-responsive">
    <table id="leave-credit-table" class="display" cellspacing="0" width="100%">            
    </table>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#leave-credit-table").appTable({
            source: '<?php echo_uri("hrs/leave_credits/list_data") ?>',
            radioButtons: [{text: '<?php echo lang("debit") ?>', name: "action", value: "debit", isChecked: false}, {text: '<?php echo lang("credit") ?>', name: "action", value: "credit", isChecked: false}],
            filterDropdown: [{name: "user_id", class: "w200", options: <?php echo $team_members_dropdown; ?> }],
            //rangeDatepicker: [{startDate: {name: "start_date", value: moment().format("YYYY-MM-DD")}, endDate: {name: "end_date", value: moment().format("YYYY-MM-DD")}}],
            columns: [
                {title: '<?php echo lang("employee"); ?>'},
                {title: '<?php echo lang("action"); ?>'},
                {title: '<?php echo lang("days"); ?>'},
                {title: '<?php echo lang("remarks"); ?>'}, //if no ref. leaves, then auto fill with, Generated.
                {title: '<?php echo lang("date_created"); ?>'},
                {title: '<?php echo lang("created_by"); ?>'},
                // {title: '<?php //echo lang("type"); ?>'},
                // {title: '<?php //echo lang("date"); ?>'},
                // {title: '<?php //echo lang("approve_date"); ?>'},
                // {title: '<?php //echo lang("approve_by"); ?>'},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ],
            printColumns: [0, 1, 2, 3],
            xlsColumns: [0, 1, 2, 3],
            summation: [{column: 2, dataType: 'number'}]
        });
    });
</script>