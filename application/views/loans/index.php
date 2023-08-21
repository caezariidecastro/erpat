<div id="page-content" class="p20 clearfix">
    <div class="panel panel-default">
        <ul id="loan-tabs" data-toggle="ajax-tab" class="nav nav-tabs bg-white inner" role="tablist">
            <li class="title-tab"><h4 class="pl15 pt10 pr15"><?php echo lang("loans"); ?></h4></li>
            <li><a  role="presentation" class="active" href="<?php echo_uri("finance/Loans/entries/"); ?>" data-target="#loan-entries"><?php echo lang("entries"); ?></a></li>
            <li><a  role="presentation" href="<?php echo_uri("finance/Loans/view_transactions/"); ?>" data-target="#loan-transactions"><?php echo lang("transaction"); ?></a></li>
            <li><a  role="presentation" href="<?php echo_uri("finance/Loans/view_payments_tab/"); ?>" data-target="#loan-payments"><?php echo lang("payments"); ?></a></li>
            <li><a  role="presentation" href="<?php echo_uri("finance/Loans/view_fees_tab/"); ?>" data-target="#loan-fees"><?php echo lang("fees"); ?></a></li>
            <li><a  role="presentation" href="<?php echo_uri("finance/Loans/view_categories/"); ?>" data-target="#loan-categories"><?php echo lang("categories"); ?></a></li>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade" id="loan-entries"></div>
            <div role="tabpanel" class="tab-pane fade" id="loan-transactions"></div>
            <div role="tabpanel" class="tab-pane fade" id="loan-payments"></div>
            <div role="tabpanel" class="tab-pane fade" id="loan-fees"></div>
            <div role="tabpanel" class="tab-pane fade" id="loan-categories"></div>
        </div>
    </div>
</div>