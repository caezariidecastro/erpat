
<style>
    .disabled:disabled {
        border-right: 3px solid red !important;
    }

    .input-detail {
        text-align: center;
        background-color: white;
        padding: 5px;
    }
</style>

<?php echo form_open(get_uri("fas/payrolls/save"), array("id" => "payslip-form", "class" => "general-form", "role" => "form")); ?>

<div class="clearfix" style="padding: 0 20px 15px;">
    <div class="col-md-3"> 
        <h6>NAME</h6><input id="fullname" type="text" name="fullname" value="<?= $fullname ?>" class="input-detail" disabled></input>
    </div>
    <div class="col-md-3"> 
        <h6>DEPARTMENT</h6><input name="department" value="<?= $department ?>" class="input-detail" disabled></input>
    </div>
    <div class="col-md-3"> 
        <h6>TITLE</h6><input name="job_title" value="<?= $job_title ?>" class="input-detail" disabled></input>
    </div>
    <div class="col-md-3"> 
        <h6>MONTHLY SALARY</h6><input name="monthly_salary" value="<?= $salary ?>" class="input-detail" disabled></input>
    </div>
</div>

<div id="page-content" class="clearfix" style="padding: 0 20px 15px;">
    <ul id="team-member-view-tabs" data-toggle="ajax-tab" class="nav nav-tabs" role="tablist">
        <li><a  role="presentation" class="active" href="javascript:;" data-target="#tab-biometric-logs"> <?php echo lang('biometric_logs'); ?></a></li>
        <li><a  role="presentation" href="javascript:;" data-target="#tab-earnings"> <?php echo lang('earnings'); ?></a></li>
        <li><a  role="presentation" href="javascript:;" data-target="#tab-deductions"> <?php echo lang('deductions'); ?></a></li>
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane mt15 fade active" id="tab-biometric-logs">
            <div class="row">

                <div class="col-md-4"> 
                    <h6 class="b-info" style="text-align: left; margin-bottom: 20px; font-weight: bold;">WORK SCHEDULE (hrs)</h6>
                    <?php foreach($biometrics as $addt) { ?>
                        <div class="form-group">
                            <label for="<?= $addt['key'] ?>" class=" col-md-4"><?php echo lang( $addt['key'] ); ?></label>
                            <div class="col-md-8">
                                <?php 
                                    $addt_config = array(
                                        "id" => $addt['key'],
                                        "name" => $addt['key'],
                                        "type" => "number",
                                        "value" => $addt['value'],
                                        "class" => "form-control",
                                        "style" => "text-align: right;",
                                        "placeholder" => "0.00",
                                        "autocomplete" => "off",
                                    ); 
                                    if( isset($addt['disabled']) && $addt['disabled'] == true) {
                                        $addt_config['disabled'] = true;
                                    }
                                    if( isset($addt['type']) ) {
                                        $addt_config['type'] = "text";
                                    }
                                    if( isset($addt['class']) ) {
                                        $addt_config['class'] .= " ".$addt['class'];
                                    }
                                    echo form_input( $addt_config );
                                ?>
                            </div>
                        </div>
                    <?php } ?>
                </div> 

                <div class="col-md-4"> 
                    <h6 class="b-info" style="text-align: left; margin-bottom: 20px; font-weight: bold;">Holidays (hrs)</h6>
                    <?php foreach($holiday as $addt) { ?>
                        <div class="form-group">
                            <label for="<?= $addt['key'] ?>" class=" col-md-4"><?php echo lang( $addt['key'] ); ?></label>
                            <div class="col-md-8">
                                <?= form_input(array(
                                    "id" => $addt['key'],
                                    "name" => $addt['key'],
                                    "type" => "number",
                                    "value" => $addt['value'],
                                    "class" => "form-control",
                                    "style" => "text-align: right;",
                                    "placeholder" => "0.00",
                                    "autocomplete" => "off",
                                )); ?>
                            </div>
                        </div>
                    <?php } ?>
                </div> 

                <div class="col-md-4">
                    <h6 class="b-info" style="text-align: left; margin-bottom: 20px; font-weight: bold;">OT/ND (hrs)</h6>
                    <?php foreach($overtime as $eother) { ?>
                        <div class="form-group">
                            <label for="<?= $eother['key'] ?>" class=" col-md-4"><?php echo lang( $eother['key'] ); ?></label>
                            <div class="col-md-8">
                                <?= form_input(array(
                                    "id" => $eother['key'],
                                    "name" => $eother['key'],
                                    "type" => "number",
                                    "value" => $eother['value'],
                                    "class" => "form-control",
                                    "style" => "text-align: right;",
                                    "placeholder" => "0.00",
                                    "autocomplete" => "off",
                                )); ?>
                            </div>
                        </div>
                    <?php } ?>
                </div> 

            </div>
        </div>
        <div role="tabpanel" class="tab-pane mt15 fade" id="tab-earnings">
            <div class="row">
                <div class="col-md-4"> 
                    <h6 class="b-info" style="text-align: left; margin-bottom: 20px; font-weight: bold;">WORK RATE</h6>
                    <?php foreach($earnings as $earn) { ?>
                        <div class="form-group">
                            <label for="<?= $earn['key'] ?>" class=" col-md-4"><?php echo lang( $earn['key'] ); ?></label>
                            <div class="col-md-8">

                                <?php
                                    $earn_config = array(
                                        "id" => $earn['key'],
                                        "name" => $earn['key'],
                                        "type" => "number",
                                        "value" => $earn['value'],
                                        "class" => "form-control",
                                        "style" => "text-align: right;",
                                        "placeholder" => "0.00",
                                        "autocomplete" => "off",
                                    );
                                    if( isset($earn['disabled']) && $earn['disabled'] == true) {
                                        $earn_config['disabled'] = true;
                                    }
                                    if( isset($earn['type']) ) {
                                        $earn_config['type'] = "text";
                                    }
                                    if( isset($earn['class']) ) {
                                        $earn_config['class'] .= " ".$earn['class'];
                                    }
                                    echo form_input($earn_config); 
                                ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <div class="col-md-4"> 
                    <h6 class="b-info" style="text-align: left; margin-bottom: 20px; font-weight: bold;">ADDITIONALS</h6>
                    <?php foreach($additionals as $addt) { ?>
                        <div class="form-group">
                            <label for="<?= $addt['key'] ?>" class=" col-md-4"><?php echo lang( $addt['key'] ); ?></label>
                            <div class="col-md-8">
                                <?= form_input(array(
                                    "id" => $addt['key'],
                                    "name" => $addt['key'],
                                    "type" => "number",
                                    "value" => $addt['value'],
                                    "class" => "form-control",
                                    "style" => "text-align: right;",
                                    "placeholder" => "0.00",
                                    "autocomplete" => "off",
                                )); ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <div class="col-md-4"> 
                    <h6 class="b-info" style="text-align: left; margin-bottom: 20px; font-weight: bold;">OTHERS</h6>
                    <?php foreach($earn_other as $eother) { ?>
                        <div class="form-group">
                            <label for="<?= $eother['key'] ?>" class=" col-md-4"><?php echo lang( $eother['key'] ); ?></label>
                            <div class="col-md-8">
                                <?= form_input(array(
                                    "id" => $eother['key'],
                                    "name" => $eother['key'],
                                    "type" => $eother['type'],
                                    "value" => $eother['value'],
                                    "class" => "form-control",
                                    "style" => "text-align: right;",
                                    "placeholder" => $eother['type'],
                                    "autocomplete" => "off",
                                )); ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane mt15 fade" id="tab-deductions">
            <div class="row">
                <div class="col-md-4"> 
                    <h6 class="b-info" style="text-align: left; margin-bottom: 20px; font-weight: bold;">CONTRIBUTIONS</h6>
                    <?php foreach($non_tax_deducts as $non_tax) { ?>
                        <div class="form-group">
                            <label for="<?= $non_tax['key'] ?>" class=" col-md-4"><?php echo lang( $non_tax['key'] ); ?></label>
                            <div class="col-md-8">
                                <?= form_input(array(
                                    "id" => $non_tax['key'],
                                    "name" => $non_tax['key'],
                                    "type" => "number",
                                    "value" => $non_tax['value'],
                                    "class" => "form-control",
                                    "style" => "text-align: right;",
                                    "placeholder" => "0.00",
                                    "autocomplete" => "off",
                                )); ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <div class="col-md-4"> 
                    <h6 class="b-info" style="text-align: left; margin-bottom: 20px; font-weight: bold;">LOANS</h6>
                    <?php foreach($non_tax_loans as $loans) { ?>
                        <div class="form-group">
                            <label for="<?= $loans['key'] ?>" class=" col-md-4"><?php echo lang( $loans['key'] ); ?></label>
                            <div class="col-md-8">
                                <?= form_input(array(
                                    "id" => $loans['key'],
                                    "name" => $loans['key'],
                                    "type" => "number",
                                    "value" => $other['value'],
                                    "class" => "form-control",
                                    "style" => "text-align: right;",
                                    "placeholder" => "0.00",
                                    "autocomplete" => "off",
                                )); ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <div class="col-md-4"> 
                    <h6 class="b-info" style="text-align: left; margin-bottom: 20px; font-weight: bold;">OTHERS</h6>
                    <?php foreach($non_tax_other as $other) { ?>
                        <div class="form-group">
                            <label for="<?= $other['key'] ?>" class=" col-md-4"><?php echo lang( $other['key'] ); ?></label>
                            <div class="col-md-8">
                                <?= form_input(array(
                                    "id" => $other['key'],
                                    "name" => $other['key'],
                                    "type" => $other['type'],
                                    "value" => $other['value'],
                                    "class" => "form-control",
                                    "style" => "text-align: right;",
                                    "placeholder" => $other['type'],
                                    "autocomplete" => "off",
                                )); ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>   
</div>

<div class="clearfix" style="padding: 0 20px 15px;">
    <div class="row">
        <div class="col-md-4"> 
            <h6 class="b-info" style="text-align: left; margin-bottom: 20px; font-weight: bold;">Earnings (auto)</h6>
            <?php foreach($summary_additionals as $summ) { ?>
                <div class="form-group">
                    <label for="<?= $summ['key'] ?>" class=" col-md-4"><?php echo lang( $summ['key'] ); ?></label>
                    <div class="col-md-8">
                        <?= form_input(array(
                            "id" => $summ['key'],
                            "name" => $summ['key'],
                            "type" => "text",
                            "disabled" => true,
                            "value" => $summ['value'],
                            "class" => "disabled form-control",
                            "style" => "text-align: right;",
                            "placeholder" => "0.00",
                            "autocomplete" => "off",
                        )); ?>
                    </div>
                </div>
            <?php } ?>
        </div>
        <div class="col-md-4"> 
            <h6 class="b-info" style="text-align: left; margin-bottom: 20px; font-weight: bold;">Deductions (auto)</h6>
            <?php foreach($summary_deductions as $summ) { ?>
                <div class="form-group">
                    <label for="<?= $summ['key'] ?>" class=" col-md-4"><?php echo lang( $summ['key'] ); ?></label>
                    <div class="col-md-8">
                        <?= form_input(array(
                            "id" => $summ['key'],
                            "name" => $summ['key'],
                            "type" => "text",
                            "disabled" => true,
                            "value" => $summ['value'],
                            "class" => "disabled form-control",
                            "style" => "text-align: right;",
                            "placeholder" => "0.00",
                            "autocomplete" => "off",
                        )); ?>
                    </div>
                </div>
            <?php } ?>
        </div>
        <div class="col-md-4"> 
            <h6 class="b-info" style="text-align: left; margin-bottom: 20px; font-weight: bold;">Summary (auto)</h6>
            <?php foreach($summary_totals as $summ) { ?>
                <div class="form-group">
                    <label for="<?= $summ['key'] ?>" class=" col-md-4"><?php echo lang( $summ['key'] ); ?></label>
                    <div class="col-md-8">
                        <?= form_input(array(
                            "id" => $summ['key'],
                            "name" => $summ['key'],
                            "type" => "text",
                            "disabled" => true,
                            "value" => $summ['value'],
                            "class" => "disabled form-control",
                            "style" => "text-align: right;",
                            "placeholder" => "0.00",
                            "autocomplete" => "off",
                        )); ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
    <div class="row mt15" style="text-align: center;">
        <button type="button" class="calculate btn btn-info mr15" disabled><span class="fa fa-calculator"></span> <?php echo lang('calculate'); ?></button>
        <button type="button" class="save_data btn btn-danger"><span class="fa fa-check-circle"></span> <?php echo lang('overwrite'); ?></button>
    </div>
</div>

<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        function getFormData($form){
            var unindexed_array = $form.serializeArray();
            var indexed_array = {};

            $.map(unindexed_array, function(n, i){
                indexed_array[n['name']] = n['value'];
            });

            return indexed_array;
        }

        $('.calculate').on('click', function() {
            
            var jsonData = getFormData( $("#payslip-form") );
            $.ajax({
                url: "<?php echo get_uri("payrolls/override_calculate/".$payslip_id); ?>",
                data: jsonData,
                method: "POST",
                dataType: "json",
                success: function (result) {
                    appLoader.hide();
                    const pageName = 'Override <?= lang('payslip'); ?>';
                    $("#payslip-section-head").html(pageName);
                    $("#payslip-section").html(result);
                    $("[data-target=#tab-summary]").trigger("click");
                }
            });
        });

        $('.save_data').on('click', function() {
            appLoader.show();
            var jsonData = getFormData( $("#payslip-form") );
                jsonData.action = 'save';

            $.ajax({
                url: "<?php echo get_uri("payrolls/override_calculate/".$payslip_id); ?>",
                data: jsonData,
                method: "POST",
                dataType: "json",
                success: function (result) {
                    appLoader.hide();
                    $('#payslip-table').dataTable()._fnAjaxUpdate();
                }
            });
        });

        setTimeout(function () {
            $("[data-target=#tab-biometric-logs]").trigger("click");
        }, 210);
    });

    $(".modal-dialog").addClass("modal-lg");
</script>