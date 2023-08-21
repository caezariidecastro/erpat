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
                        <td>Date</td>
                        <td>Title</td>
                        <td>Remarks</td>
                        <td>Amount</td>
                    </tr>
                <?php 
                    $total = 0;
                    foreach($fees_detail as $fee) { 
                        $total += $fee->amount;
                ?>
                    <tr>
                        <td> <?= convert_date_format($fee->updated_at, "d M Y h:i A") ?></td>
                        <td> <?= $fee->title_link ?></td>
                        <td><?= $fee->remarks; ?></td>
                        <td><?= to_currency($fee->amount); ?></td>
                    </tr>
                <?php } ?>
                    <tr>
                        <td> </td>
                        <td> </td>
                        <td> <strong><?= lang("total") ?></strong></td>
                        <td><strong><?= to_currency($total); ?></strong></td>
                    </tr>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {

    });
</script>    