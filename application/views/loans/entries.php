
<div class="page-title clearfix">
    <div class="title-button-group">
        <?php echo modal_anchor(get_uri("finance/Loans/modal_form_fee"), "<i class='fa fa-tag'></i> " . lang('add_fee'), array("class" => "btn btn-default", "title" => lang('add_fee'))); ?>
        <?php echo modal_anchor(get_uri("finance/Loans/modal_form_payment"), "<i class='fa fa-money'></i> " . lang('add_payment'), array("class" => "btn btn-default", "title" => lang('add_payment'))); ?>
        <?php echo modal_anchor(get_uri("finance/Loans/modal_form"), "<i class='fa fa-plus-circle'></i> " . lang('create_loan'), array("class" => "btn btn-default", "title" => lang('create_loan'))); ?>
    </div>
</div>
<div class="table-responsive">
    <table id="all-application-table" class="display" cellspacing="0" width="100%">            
    </table>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $("#all-application-table").appTable({
            source: '<?php echo_uri("finance/Loans/list_data") ?>',
            dateRangeType: "yearly",
            columns: [
                {title: '<?php echo lang("loan") ?>', "class": "w10p"},
                {title: '<?php echo lang("categories") ?>', "class": "w10p"},
                {title: '<?php echo lang("date_applied") ?>', "class": "w10p"},
                {title: '<?php echo lang("borrower") ?>', "class": "w20p"},
                {title: '<?php echo lang("fees") ?>', "class": "w15p"},
                {title: '<?php echo lang("principal") ?>', "class": "w15p"},
                {title: '<?php echo lang("months_to_pay") ?>', "class": "w15p"},
                {title: '<?php echo lang("minimum_payment") ?>', "class": "w15p"},
                {title: '<?php echo lang("payments") ?>', "class": "w15p"},
                {title: '<?php echo lang("balance") ?>', "class": "w15p"},
                {title: '<i class="fa fa-bars"></i>', "class": "text-center dropdown-option w100"}
            ],
            printColumns: [0, 1, 2, 3, 4, 5, 6, 7, 8],
            xlsColumns: [0, 1, 2, 3, 4, 5, 6, 7, 8],

        });
    });
</script>

