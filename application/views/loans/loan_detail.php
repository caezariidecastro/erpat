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
                        <td>Name</td>
                        <td>Value</td>
                    </tr>
                    <tr>
                        <td>Date Applied</td>
                        <td><?= $loan_info->date_applied ?></td>
                    </tr>
                    <tr>
                        <td>Start Payment</td>
                        <td><?= $loan_info->start_payment?$loan_info->start_payment:"Not Started" ?></td>
                    </tr>
                    <tr>
                        <td>Days before Due</td>
                        <td><?= $loan_info->days_before_due ?> days</td>
                    </tr>
                    <tr>
                        <td>Penalty Rate</td>
                        <td><?= $loan_info->penalty_rate ?>%</td>
                    </tr>
                    <tr>
                        <td>Category</td>
                        <td><?= $loan_info->category_name ?></td>
                    </tr>
                    <tr>
                        <td>Co-Maker</td>
                        <td><?= $loan_info->cosigner_name ?></td>
                    </tr>
                    <tr>
                        <td>Executed by</td>
                        <td><?= $loan_info->creator_name ?></td>
                    </tr>
                    <tr>
                        <td>Remarks</td>
                        <td><?= $loan_info->remarks ?></td>
                    </tr>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {

    });
</script>    