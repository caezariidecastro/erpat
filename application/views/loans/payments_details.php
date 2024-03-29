<div class="modal-body">
    <div class="row">
        <div class="p10 clearfix">
            <div class="media m0 bg-white">
                <div class="media-body w100p pt5">
                    <div class="media-heading mb10">
                        Borrower Name: <?php echo $loan_info->borrower_name; ?>
                    </div>
                    <div class="media-heading mb20">
                        Loan ID: <?php echo $loan_id_name; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive mb15">
            <table class="table dataTable display b-t">
                <tr>
                    <td>ID</td>
                    <td>Date</td>
                    <td>Amount</td>
                    <td>Penalty</td>
                    <td>Sub-Total</td>
                </tr>
                <?php 
                    $total = 0;
                    $penalty = 0;
                    foreach($payments_detail as $payment) { 
                        $total += $payment->amount;
                        $penalty = $payment->late_interest;
                ?>
                    <tr>
                        <td> <?= $payment->title_link ?></td>
                        <td> <?= convert_date_utc_to_local($payment->date_paid, "d M Y h:i A") ?> </td>
                        <td><?= to_currency($payment->amount); ?></td>
                        <td><?= to_currency($penalty); ?></td>
                        <td><?= to_currency($payment->amount+$penalty); ?></td>
                    </tr>
                <?php } ?>
                    <tr>
                        <td></td>
                        <td></td>
                        <td><strong><?= to_currency($total); ?></strong></td>
                        <td><strong><?= to_currency($penalty); ?></strong></td>
                        <td><strong><?= to_currency($total+$penalty); ?></strong></td>
                    </tr>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {

    });
</script>    