<?php
    $payslip = array(
        "fullname" => $payslip->fullname,
        "basic_salary" => to_currency($payslip->salary),
        "leave_credit" => convert_number_to_decimal($payslip->leave_credit),

        "job_title" => $payslip->job_title,
        "department" => $payslip->department,
        "directorate" => "None",

        "earnings" => [
            array(
                "name" => "Basic Pay",
                "value" => to_currency($summary['basic_pay'])
            ),
            array(
                "name" => "Overtime Pay",
                "value" => to_currency($summary['overtime_pay'])
            ),
            array(
                "name" => "Night Differential",
                "value" => to_currency($summary['nightdiff_pay'])
            ),
            array(
                "name" => "Overtime ND",
                "value" => to_currency($summary['special_pay'])
            )
        ],
        "deductions" => array(
            array(
                "name" => "No Work Deductions",
                "value" => to_currency($summary['unwork_deduction'])
            ),
            array(
                "name" => "Compensation Tax",
                "value" => to_currency($summary['tax_due'])
            ),
            array(
                "name" => "SSS Contribution",
                "value" => to_currency($summary['sss_contri'])
            ),
            array(
                "name" => "PAGIBIG Contribution",
                "value" => to_currency($summary['pagibig_contri'])
            ),
            array(
                "name" => "PhilHealth Contribution",
                "value" => to_currency($summary['phealth_contri'])
            ),
        ),

        "total_earn" => to_currency($summary['total_earn']),
        "total_deduct" => to_currency($summary['total_deduct']),

        "net_pay" => to_currency($summary['net_pay']),
        "pay_period" => $payslip->pay_period,
        "payment_date" => $payslip->pay_date,

        "bank_name" => "",
        "account_name" => "",
        "account_number" => "",

        "accountant_sign" => "",
        "accountant_name" => $payslip->accountant_name,
    );

    if( isset($summary['allowance']) ) {
        $payslip['earnings'][] = array(
            "name" => "Allowance",
            "value" => to_currency($summary['allowance'])
        );
    }
    if( isset($summary['incentives']) ) {
        $payslip['earnings'][] = array(
            "name" => "Incentives",
            "value" => to_currency($summary['incentives'])
        );
    }
    if( isset($summary['bonus']) ) {
        $payslip['earnings'][] = array(
            "name" => "Bonus",
            "value" => to_currency($summary['bonus'])
        );
    }
    if( isset($summary['month13th']) ) {
        $payslip['earnings'][] = array(
            "name" => "13th Month",
            "value" => to_currency($summary['month13th'])
        );
    }
    if( isset($summary['add_adjust']) ) {
        $payslip['earnings'][] = array(
            "name" => "Adjustment",
            "value" => to_currency($summary['add_adjust'])
        );
    }
    if( isset($summary['add_other']) ) {
        $payslip['earnings'][] = array(
            "name" => "Other",
            "value" => to_currency($summary['add_other'])
        );
    }


    if( isset($summary['hmo_contri']) ) {
        $payslip['deductions'][] = array(
            "name" => "HMO Contribution",
            "value" => to_currency($summary['hmo_contri'])
        );
    }
    if( isset($summary['com_loan']) ) {
        $payslip['deductions'][] = array(
            "name" => "Company Loan",
            "value" => to_currency($summary['com_loan'])
        );
    }
    if( isset($summary['sss_loan']) ) {
        $payslip['deductions'][] = array(
            "name" => "SSS Loan",
            "value" => to_currency($summary['sss_loan'])
        );
    }
    if( isset($summary['hdmf_loan']) ) {
        $payslip['deductions'][] = array(
            "name" => "HDMF Loan",
            "value" => to_currency($summary['hdmf_loan'])
        );
    }
    if( isset($summary['deduct_adjust']) ) {
        $payslip['deductions'][] = array(
            "name" => "Adjustment",
            "value" => to_currency($summary['deduct_adjust'])
        );
    }
    if( isset($summary['deduct_other']) ) {
        $payslip['deductions'][] = array(
            "name" => "Other Deduction",
            "value" => to_currency($summary['deduct_other'])
        );
    }
?>

<style>
    table {
        font-family: arial, sans-serif;
        border-collapse: collapse;
        width: 100%;
    }

    td, th {
        border: 1px solid grey;
        text-align: left;
        padding: 5px;
    }

    tr:nth-child(even) {
        background-color: #ebf3fa;
    }

    ul { 
        list-style-type: none; 
    }

    .background-color {
        background-color: #f7f7f7;
    }

    .heading-color {
        background-color: #d4e8fa;
    }

    .column-color {
        background-color: #c2e3ff;
    }

    .body-color {
        background-color: #edf5fa;
    }
</style>
<?php $footer_color = "#c2e3ff"; ?>

<div class="background-color" style="border: 2px solid grey;">
    
    <!-- HEADING -->
    <table>
        <tr>
            <th width="98.8%" style="border: none;">
                <ul style="font-size: x-small; font-weight: 100; ">
                    <li></li>
                    <li style="text-align: center;">
                        <img style="width: auto; max-height: 50px;" src="<?php echo get_file_from_setting('invoice_logo', true); ?>" alt="">
                    </li>
                    <li style="text-align: center;">
                        <span><?= get_setting('company_address') ?></span>
                    </li>
                    <li style="text-align: center;">
                        <span><?= get_setting('company_email') ?></span> | <span><?= get_setting('company_phone') ?></span>
                    </li>
                    <li></li>
                </ul>
            </th>
        </tr>
    </table>

    <!-- GENERAL -->
    <table class="heading-color">
        <tr>
            <th width="49.5%">
                <ul style="font-weight: 100;">
                    <li ></li>
                    <li style="line-height: 25px;">
                        Full Name: <span style="color: #454545;"><?= $payslip['fullname'] ?></span>
                    </li>
                    <li style="line-height: 25px;">
                        Basic Salary: <span style="color: #454545;"><?= $payslip['basic_salary'] ?></span>
                    </li>
                    <li style="line-height: 25px;">
                        Leave Credits: <span style="color: #454545;"><?= $payslip['leave_credit'] ?></span>
                    </li>
                    <li ></li>
                </ul>
            </th>
            <th width="49.5%">
                <ul style="font-weight: 100;">
                    <li></li>
                    <li style="line-height: 25px;">
                        Job Title: <span style="color: #454545;"><?= $payslip['job_title'] ?></span>
                    </li>
                    <li style="line-height: 25px;">
                        Department: <span style="color: #454545;"><?= $payslip['department'] ?></span>
                    </li>
                    <li style="line-height: 25px;">
                        Directorate: <span style="color: #454545;"><?= $payslip['directorate'] ?></span>
                    </li>
                    <li></li>
                </ul>
            </th>
        </tr>
    </table>

    <!-- DESCRIPTION -->
    <table class="body-color">
        <tr class="column-color">
            <th width="49.5%" style="text-align: center;"><strong style="color: #262626;">Description</strong></th>
            <th width="24.8%" style="text-align: center;"><strong style="color: #262626;">Earnings</strong></th>
            <th width="24.7%" style="text-align: center;"><strong style="color: #262626;">Deductions</strong></th>
        </tr>
        <?php foreach($payslip['earnings'] as $earns) { ?>
            <?php if($earns['value'] != to_currency("0") ) { ?>
            <tr style="color: #454545;">
                <td><?= $earns['name'] ?></td>
                <td style="text-align: right;"><?= $earns['value'] ?></td>
                <td style="text-align: right;">-</td>
            </tr>
            <?php } ?>
        <?php } ?>

        <!-- Allowance, Incentives, 13 Month, Bonus, Adjustment-->
        <?php foreach($payslip['deductions'] as $deducts) { ?>
            <?php if($deducts['value'] != to_currency("0") ) { ?>
            <tr style="color: #454545;">
                <td><?= $deducts['name'] ?></td>
                <td style="text-align: right;">-</td>
                <td style="text-align: right;"><?= $deducts['value'] ?></td>
            </tr>
            <?php } ?>
        <?php } ?>
        
        <tr style="color: #242323;">
            <td><strong>Total</strong></td>
            <td style="text-align: right;"><strong><?= $payslip['total_earn'] ?></strong></td>
            <td style="text-align: right;"><strong><?= $payslip['total_deduct'] ?></strong></td>
        </tr>
    </table>

    <!-- BANK & SUMMARY -->
    <table style="background-color: white;">
        <tr style="background-color: <?= $footer_color ?>;">
            <th width="49.5%">
                <ul style="font-weight: 100;" >
                    <li ></li>
                    <li style="line-height: 25px;">
                        Bank Name: <span style="color: #454545;"><?= $payslip['bank_name'] ?></span>
                    </li>
                    <li style="line-height: 25px;">
                        Account Name: <span style="color: #454545;"><?= $payslip['account_name'] ?></span>
                    </li>
                    <li style="line-height: 25px;">
                        Account Number: <span style="color: #454545;"><?= $payslip['account_number'] ?></span>
                    </li>
                    <li ></li>
                </ul>
            </th>
            <th width="49.5%">
                <table >
                    <tr>
                        <th width="49.5%" style="border: none;"></th>
                        <th width="49.5%" style="border: none;"></th>
                    </tr>
                    <tr style="background-color: <?= $footer_color ?>;">
                        <td style="border: none; text-align: right; font-size: 20px;">
                            <strong>NET PAY: </strong>
                        </td>
                        <td style="border: none; text-align: left; font-size: 20px;">
                            <strong><?= $payslip['net_pay'] ?></strong>
                        </td>
                    </tr>
                </table>
                <table >
                    <tr>
                        <th width="49.5%" style="border: none;">
                            <p style="text-align: center; font-size: 15px; line-height: 15px;">
                                Pay Period: <span style="color: #454545;"><?= $payslip['pay_period'] ?></span>
                            </p>
                        </th>
                        <th width="49.5%" style="border: none;">
                            <p style="text-align: center; font-size: 15px; line-height: 15px;">
                                Payment Date: <span style="color: #454545;"><?= $payslip['payment_date'] ?></span>
                            </p>
                        </th>
                    </tr>
                    <tr style="background-color: <?= $footer_color ?>;">
                        <td style="border: none;"></td>
                        <td style="border: none;"></td>
                    </tr>
                </table>
            </th>
        </tr>
    </table>

    <!-- FOOTER -->
    <table style="background-color: white;">
        <tr>
            <th width="49.5%">
                <p style="text-align: center; color: grey; font-size: 30px;">CONFIDENTIAL</p>
            </th>
            <th width="49.5%">
                <small style="text-align: left;">
                    Certified true and correct:
                </small>
                <p style="text-align: center; font-size: 20px;">
                    <?= $payslip['accountant_name'] ?>
                </p>
                <p style="text-align: center; font-size: 15px; line-height: 20px; font-weight: 200; border-top: 1px solid grey;">
                    Head, HR/Accounting   
                </p>
            </th>
        </tr>
    </table>
</div>

<?php if ($payroll_info->note) { ?>
    <div style="border-top: 2px solid #f2f2f2; color:#444; padding:0 0 20px 0;"><br /><?php echo nl2br($payroll_info->note); ?></div>
<?php }?>
