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
                        <td>Stage</td>
                        <td>Executed by</td>
                        <td>Remarks</td>
                    </tr>
                <?php 
                    foreach($stages_detail as $stage) { 
                ?>
                    <tr>
                        <td> <?= $stage->stage_name ?></td>
                        <td><?= $stage->executer_name ?></td>
                        <td><?= $stage->remarks ?></td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {

    });
</script>    