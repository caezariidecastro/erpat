
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
            filterDropdown: [
                {name: "user_id", class: "w200", options: <?php echo $team_members_dropdown; ?> },
                {id: "department_select2_filter", name: "department_select2_filter", class: "w150", options: <?php echo json_encode($department_select2); ?>},
                {name: "action", class: "w10", options: <?= json_encode(array(
                        array('id' => '', 'text'  => '- Transactions -'),
                        array('id' => 'balance', 'text'  => '- Balance -'),
                        array('id' => 'debit', 'text'  => '- Debit Only -'),
                        array('id' => 'credit', 'text'  => '- Credit Only -')
                    )); ?> 
                },
                {name: "leave_type_id", class: "w200", options: <?= json_encode($leave_types_dropdown) ?> },
            ],
            columns: [
                {title: '<?php echo lang("employee"); ?>'},
                {title: '<?php echo lang("action"); ?>'},
                {title: '<?php echo lang("days"); ?>'},
                {title: '<?php echo lang("remarks"); ?>'}, //if no ref. leaves, then auto fill with, Generated.
                {title: '<?php echo lang("date_created"); ?>'},
                {title: '<?php echo lang("created_by"); ?>'},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center option w100"}
            ],
            printColumns: [0, 1, 2, 3],
            xlsColumns: [0, 1, 2, 3],
            summation: [{column: 2, dataType: 'number'}],
            tableRefreshButton: true
        });
    });
</script>