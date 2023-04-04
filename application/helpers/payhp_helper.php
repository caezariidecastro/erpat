<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PayHP {

    protected $term = 'weekly';

    //CONSTANTS VARIABLES
    protected $overtime_rate = 1.25;
    protected $nightdiff_rate = 0.10;
    protected $restday_rate = 0.30;
    protected $legalhd_rate = 1.00;
    protected $spclhd_rate = 0.44;

    //DYNAMIC VARIABLES
    protected $monthly_salary = 0;
    protected $hourly_rate = 0.0;

    protected $compensation_level = 0;
    protected $train_range = 0.00;
    protected $tax_on_train_range = 0.00;
    protected $rate_in_excess = 0.00;

    protected $daily_tax_table = array();
    protected $weekly_tax_table = array();
    protected $biweekly_tax_table = array();
    protected $monthly_tax_table = array();

    //Taxable
    protected $incentives = 0.0;
    protected $month13th = 0.0;
    protected $add_other = 0.0;

    //Non Taxable
    protected $allowance = 0.0;
    protected $bonus = 0.0;
    protected $add_adjust = 0.0;

    //Work Schedule
    protected $sched_hour = 0.0;
    protected $worked_hour = 0.0;
    protected $absent_hour = 0.0;
    protected $late_hour = 0.0;
    protected $over_hour = 0.0;
    protected $under_hour = 0.0;
    protected $pto_hour = 0.0;

    //Overtime Pay
    protected $regular_ot = 0.0;
    protected $restday_ot = 0.0;
    protected $legalhd_ot = 0.0;
    protected $spclhd_ot = 0.0;

    //Night Differential
    protected $regular_nd = 0.0;
    protected $restday_nd = 0.0;
    protected $legalhd_nd = 0.0;
    protected $spclhd_nd = 0.0;

    //Overtime ND
    protected $regular_otnd = 0.0;
    protected $restday_otnd = 0.0;
    protected $legalhd_otnd = 0.0;
    protected $spclhd_otnd = 0.0;

    //Contribution
    protected $sss_contri = 0.0;
    protected $pagibig_contri = 0.0;
    protected $phealth_contri = 0.0;
    protected $hmo_contri = 0.0;

    //Loans
    protected $com_loan = 0.0;
    protected $sss_loan = 0.0;
    protected $pagibig_loan = 0.0;

    //Loans
    protected $deduct_amount = 0.0;
    protected $deduct_adjust = 0.0;
    protected $deduct_other = 0.0;

    /**
     * Initialize how will PayHP compute the summary.
     * @var value decimal(1,2) in decimal.
     * @var array overtime_rate, restday_rate, legalhd_rate, spclhd_rate.
     */
    function __construct() {
        return $this;
    }

    /**
     * Set the monthly salary for this payslip.
     */
    function setMonthlySalary($monthly_salary) {
        $this->monthly_salary = $monthly_salary;
        return $this;
    }

    /**
     * Set the hourly rate for this payslip.
     */
    function setHourlyRate($hourly_rate) {
        $this->hourly_rate = $hourly_rate;
        return $this;
    }

    /**
     * Set the differential rates like overtime or nightdiff rates.
     */
    function setDiffRates($type, $value = 0) {
        if($type === 'overtime') {
            $this->overtime_rate = $object;
        } else if($type === 'nightdiff') {
            $this->nightdiff_rate = $object;
        } else if($type === 'restday') {
            $this->restday_rate = $object;
        } else if($type === 'legalhd') {
            $this->legalhd_rate = $object;
        } else if($type === 'spclhd') {
            $this->spclhd_rate = $object;
        }
        return $this;
    }

    /**
     * Set all the relavant tax table like weekly or monthly. 
     * You can also set the target term using term as type.
     */
    function setTaxTable($type, $object) {
        if($type === 'term') {
            $this->term = $object;
        } else if($type === 'daily') {
            $this->daily_tax_table = $object;
        } else if($type === 'weekly') {
            $this->weekly_tax_table = $object;
        } else if($type === 'biweekly') {
            $this->biweekly_tax_table = $object;
        } else if($type === 'monthly') {
            $this->monthly_tax_table = $object;
        }
        return $this;
    }

    /**
     * Add earnings other than the basic pay.
     * @var value decimal(10,2) in amount.
     * @var type allowance, incentive, bonus, 13thmonth.
     */
    function addEarnings( $type, $value ) {
        switch($type) {
            case $type == "allowance":
                $this->allowance += is_numeric($value)?$value:0;
                break;
            case $type == "incentive":
                $this->incentives += is_numeric($value)?$value:0;
                break;
            case $type == "bonus":
                $this->bonus += is_numeric($value)?$value:0;
                break;
            case $type == "13thmonth":
                $this->month13th += is_numeric($value)?$value:0;
                break;
            case $type == "adjust":
                $this->add_adjust += is_numeric($value)?$value:0;
                break;
            case $type == "other":
                $this->add_other += is_numeric($value)?$value:0;
            default:
                break;
        }
        return $this;
    }

    /**
     * Set all the hour variables for biometrics.
     * @var value decimal(10,2) in hours
     * @var type schedule, absent, late, overbreak, undertime
     */
    function setHour( $type, $value ) {
        switch($type) {
            case $type == "schedule":
                $this->sched_hour += is_numeric($value)?$value:0;
                break;
            case $type == "worked":
                $this->worked_hour += is_numeric($value)?$value:0;
                break;
            case $type == "bonus":
                $this->worked_hour += is_numeric($value)?$value:0;
                break;
            case $type == "absent":
                $this->absent_hour += is_numeric($value)?$value:0;
                break;
            case $type == "late":
                $this->late_hour += is_numeric($value)?$value:0;
                break;
            case $type == "overbreak":
                $this->over_hour += is_numeric($value)?$value:0;
                break;
            case $type == "undertime":
                $this->under_hour += is_numeric($value)?$value:0;
                break;
            case $type == "pto":
                $this->pto_hour += is_numeric($value)?$value:0;
                break;
            default:
                break;
        }
        return $this;
    }

    /**
     * Set all the hour variables for overtime.
     * @var value decimal(10,2) in hours
     * @var type regular, restday, legalhd, specialhd
     */
    function setOvertime( $type, $value ) {
        switch($type) {
            case $type == "regular":
                $this->regular_ot += is_numeric($value)?$value:0;
                break;
            case $type == "restday":
                $this->restday_ot += is_numeric($value)?$value:0;
                break;
            case $type == "legalhd":
                $this->legalhd_ot += is_numeric($value)?$value:0;
                break;
            case $type == "specialhd":
                $this->spclhd_ot += is_numeric($value)?$value:0;
                break;
            default:
                break;
        }
        return $this;
    }

    /**
     * Set all the hour variables for night differential.
     * @var value decimal(10,2) in hours
     * @var type regular, restday, legalhd, specialhd
     */
    function setNightdiff( $type, $value ) {
        switch($type) {
            case $type == "regular":
                $this->regular_nd += is_numeric($value)?$value:0;
                break;
            case $type == "restday":
                $this->restday_nd += is_numeric($value)?$value:0;
                break;
            case $type == "legalhd":
                $this->legalhd_nd += is_numeric($value)?$value:0;
                break;
            case $type == "specialhd":
                $this->spclhd_nd += is_numeric($value)?$value:0;
                break;
            default:
                break;
        }
        return $this;
    }

    /**
     * Set all the hour variables for overtime with night differential.
     * TODO: SOON TO BE DEPRECATED.
     * @var value decimal(10,2) in hours
     * @var type regular, restday, legalhd, specialhd
     */
    function setOtNd( $type, $value ) {
        switch($type) {
            case $type == "regular":
                $this->regular_otnd += is_numeric($value)?$value:0;
                break;
            case $type == "restday":
                $this->restday_otnd += is_numeric($value)?$value:0;
                break;
            case $type == "legalhd":
                $this->legalhd_otnd += is_numeric($value)?$value:0;
                break;
            case $type == "specialhd":
                $this->spclhd_otnd += is_numeric($value)?$value:0;
                break;
            default:
                break;
        }
        return $this;
    }

    /**
     * Deduction for all contributions.
     * @var value decimal(10,2) in amount.
     * @var type sss, pagibig, phealth, hmo.
     */
    function deductContribution( $type, $value ) {
        switch($type) {
            case $type == "sss":
                $this->sss_contri += is_numeric($value)?$value:0;
                break;
            case $type == "pagibig":
                $this->pagibig_contri += is_numeric($value)?$value:0;
                break;
            case $type == "phealth":
                $this->phealth_contri += is_numeric($value)?$value:0;
                break;
            case $type == "hmo":
                $this->hmo_contri += is_numeric($value)?$value:0;
                break;
            default:
                break;
        }
        return $this;
    }

    /**
     * Deduction for all loans.
     * @var value decimal(10,2) in amount.
     * @var type company, pagibig, sss.
     */
    function deductLoan( $type, $value ) {
        switch($type) {
            case $type == "company":
                $this->com_loan += is_numeric($value)?$value:0;
                break;
            case $type == "pagibig":
                $this->pagibig_loan += is_numeric($value)?$value:0;
                break;
            case $type == "sss":
                $this->sss_loan += is_numeric($value)?$value:0;
                break;
            default:
                break;
        }
        return $this;
    }

    /**
     * Deduction for adjustment and others.
     * @var value decimal(10,2) in amount.
     * @var type adjust, other.
     */
    function deductOther( $type, $value ) {
        switch($type) {
            case $type == "adjust":
                $this->deduct_adjust += is_numeric($value)?$value:0;
                break;
            case $type == "other":
                $this->deduct_other += is_numeric($value)?$value:0;
                break;
            default:
                break;
        }
        return $this;
    }

    //REQUIRED TO EXEC.
    function calculate() {
        return array(
            "clevel" => $this->compensation_level,
            "totat_contribution" => $this->totalContribution(),
            "total_loan" => $this->totalLoans(),

            "monthly_salary" => to_currency($this->monthly_salary),
            "basic_pay" => $this->basicPay(),
            "unwork_deduction" => $this->deductPay(),
            "hours_paid" => $this->hoursPaid(),

            "worked_hour" => $this->worked_hour,
            "overtime_hour" => $this->regular_ot,
            "nightdiff_hour" => $this->regular_nd,

            "allowance" => $this->allowance,
            "incentives" => $this->incentives,
            "bonus" => $this->bonus,
            "month13th" => $this->month13th,
            "add_adjust" => $this->add_adjust,
            "add_other" => $this->add_other,

            "sss_contri" => $this->sss_contri,
            "pagibig_contri" => $this->pagibig_contri,
            "phealth_contri" => $this->phealth_contri,
            "hmo_contri" => $this->hmo_contri,

            "com_loan" => $this->com_loan,
            "sss_loan" => $this->sss_loan,
            "hdmf_loan" => $this->pagibig_loan,

            "deduct_adjust" => $this->deduct_adjust,
            "deduct_other" => $this->deduct_other,

            "overtime_pay" => $this->overtimePay(),
            "nightdiff_pay" => $this->nightdiffPay(),
            "special_pay" => $this->specialPay(),

            "total_earn" => $this->totalEarnings(),
            "total_deduct" => $this->totalDeductions(),
            "total_adjustother" => $this->otherDeductions(),

            "net_taxable" => $this->netTaxable(),
            "tax_due" => $this->taxDue(),
            "gross_pay" => $this->grossPay(),
            "net_pay" => $this->netPay()
        );
    }

    /**
     * Get all the unworked hours if there is late, over break, under time, and idle time.
     * You can call setHour('absent', hours), to automatically deduct to the basic pay.
     */
    function unworkHour() {
        return $this->absent_hour + $this->late_hour + $this->over_hour + $this->under_hour;
    }
    
    /**
     * By default, basic pay is computed with the scheduled hours x the hourly rate.
     */
    function basicPay() {

        $monthly_salary = get_monthly_from_hourly($this->hourly_rate, false);
        if($this->monthly_salary) {
            $monthly_salary = convert_number_to_decimal($this->monthly_salary);
        }
        
        if($this->term == 'daily') {
            $regular = convert_number_to_decimal(floatval($this->hourly_rate) * 8);
        } else if($this->term == 'weekly') {
            $regular = $monthly_salary / 4;
        } else if($this->term == 'biweekly') {
            $regular = $monthly_salary / 2;
        } else if($this->term == 'monthly') {
            $regular = $monthly_salary;
        }
        
        return convert_number_to_decimal($regular);
    }

    /**
     * This is a an official leave that will be paid by the company.
     */
    function ptoPay() {
        return $this->pto_hour * $this->hourly_rate;
    }

    /**
     * The hours paid in excess to the scheduled hour.
     */
    function overtimePay() {
        $sub_total = 0.00;

        $sub_total += ($this->regular_ot * $this->overtime_rate);

        $restday_ot = $this->restday_ot * $this->restday_rate;
        $sub_total += ($restday_ot + ($restday_ot * $this->overtime_rate));

        $legalhd_ot = $this->legalhd_ot * $this->legalhd_rate;
        $sub_total += ($legalhd_ot + ($legalhd_ot * $this->overtime_rate));

        $spclhd_ot = $this->spclhd_ot * $this->spclhd_rate;
        $sub_total += ($spclhd_ot + ($spclhd_ot * $this->overtime_rate));

        return $sub_total * $this->hourly_rate;
    }

    /**
     * The night differential is by default 10pm to 6am.
     */
    function nightdiffPay() {
        $sub_total = 0.00;

        $sub_total += ($this->regular_nd * $this->nightdiff_rate);

        $restday_nd = $this->restday_nd * $this->restday_rate;
        $sub_total += ($restday_nd + ($restday_nd * $this->nightdiff_rate));

        $legalhd_nd = $this->legalhd_nd * $this->legalhd_rate;
        $sub_total += ($legalhd_nd + ($legalhd_nd * $this->nightdiff_rate));

        $spclhd_nd = $this->spclhd_nd * $this->spclhd_rate;
        $sub_total += ($spclhd_nd + ($spclhd_nd * $this->nightdiff_rate));

        return $sub_total * $this->hourly_rate;
    }

    /**
     * The special pay is the combination of night diff and overtime pay.
     * TODO: SOON TO BE DEPRECATED.
     */
    function specialPay() {
        $sub_total = 0.00;
        
        $special_rate = $this->nightdiff_rate + $this->overtime_rate;
        $sub_total += ($this->regular_otnd *  $special_rate);

        $restday_otnd = $this->restday_otnd * $this->restday_rate;
        $sub_total += ($restday_otnd + ($restday_otnd * $special_rate));

        $legalhd_otnd = $this->legalhd_otnd * $this->legalhd_rate;
        $sub_total += ($legalhd_otnd + ($legalhd_otnd * $special_rate));

        $spclhd_otnd = $this->spclhd_otnd * $this->spclhd_rate;
        $sub_total += ($spclhd_otnd + ($spclhd_otnd * $special_rate));

        return $sub_total * $this->hourly_rate;
    }

    /**
     * Hour's paid is the worked hours x the hourly rate plus all the differential pays
     * like the paid timeoff, overtime, nightdiff, and special pay.
     */
    function hoursPaid() {
        $basicpay_calculation = get_setting('basic_pay_calculation', 'hourly_based');
        if($basicpay_calculation == 'hourly_based') {
            $regular_pay = $this->worked_hour * $this->hourly_rate;
        } else { //scheduled_based
            $regular_pay = $this->basicPay();
        }
        
        return $regular_pay + $this->ptoPay() + $this->overtimePay() + $this->nightdiffPay() + $this->specialPay();
    }

    /**
     * This deduction is a product of unworked hours x the hourly rate.
     */
    function deductPay() {
        return $this->unworkHour() * $this->hourly_rate;
    }
    
    /**
     * Get all the taxable earnings which is the other earnings.
     */
    function taxableAdditional() {
        return $this->add_other;
    }

    /**
     * Get the non-taxable additional like allowance, adjustment, etc.
     */
    function nontaxAdditional() {
        return $this->allowance + $this->incentives + $this->month13th + $this->bonus + $this->add_adjust;
    }

    /**
     * Get the total taxample earnings which includeds hours paid and taxable additional.
     */
    function netTaxable() {
        return $this->hoursPaid() + $this->taxableAdditional();
    }

    /**
     * Get the netTaxable plus the non taxable.
     */
    function grossPay() {
        return $this->netTaxable() + $this->nontaxAdditional();
    }

    /**
     * Get the total contributions of the employee.
     */
    function totalContribution() {
        return $this->sss_contri + $this->pagibig_contri + $this->phealth_contri + $this->hmo_contri;
    }

    /**
     * Get the total loans of the employee.
     */
    function totalLoans() {
        return $this->com_loan + $this->sss_loan + $this->pagibig_loan;
    }

    /**
     * Get all other deduction and adjustment.
     */
    function otherDeductions() {
        return $this->deduct_adjust + $this->deduct_other;
    }

    /**
     * Get the tax due based on compensation. TODO: Should be retrieved from the tax form dynamic data.
     */
    function taxDue() {

        $tax_due = 0;
        $current_compensation = $this->netTaxable();

        //get the current tax table for this payroll.
        $tax_table = false;
        if($this->term == 'daily') {
            $tax_table = $this->daily_tax_table;
        } else if($this->term == 'weekly') {
            $tax_table = $this->weekly_tax_table;
        } else if($this->term == 'biweekly') {
            $tax_table = $this->biweekly_tax_table;
        } else if($this->term == 'monthly') {
            $tax_table = $this->monthly_tax_table;
        }

        // set the tax row value.
        if($tax_table) {

            $this->compensation_level = 0;
            $this->train_range = 0.00;
            $this->tax_on_train_range = 0.00;
            $this->rate_in_excess = 0.00;

            switch(true) {
                case $current_compensation <=  floatval($tax_table[0][1]): //1
                    $this->compensation_level = floatval($tax_table[0][0]);
                    $this->train_range = floatval($tax_table[0][1]);
                    $this->tax_on_train_range = floatval($tax_table[0][3]);
                    $this->rate_in_excess = floatval($tax_table[0][4]);
                    break;
                case $current_compensation > floatval($tax_table[1][1]) && $current_compensation <= floatval($tax_table[1][2]): //2
                    $this->compensation_level = floatval($tax_table[1][0]);
                    $this->train_range = floatval($tax_table[1][1]);
                    $this->tax_on_train_range = floatval($tax_table[1][3]);
                    $this->rate_in_excess = floatval($tax_table[1][4]);
                    break;
                case $current_compensation > floatval($tax_table[2][1]) && $current_compensation <= floatval($tax_table[2][2]): //3
                    $this->compensation_level = floatval($tax_table[2][0]);
                    $this->train_range = floatval($tax_table[2][1]);
                    $this->tax_on_train_range = floatval($tax_table[2][3]);
                    $this->rate_in_excess = floatval($tax_table[2][4]);
                    break;
                case $current_compensation > floatval($tax_table[3][1]) && $current_compensation <= floatval($tax_table[3][2]): //4
                    $this->compensation_level = floatval($tax_table[3][0]);
                    $this->train_range = floatval($tax_table[3][1]);
                    $this->tax_on_train_range = floatval($tax_table[3][3]);
                    $this->rate_in_excess = floatval($tax_table[3][4]);
                    break;
                case $current_compensation > floatval($tax_table[4][1]) && $current_compensation <= floatval($tax_table[4][2]): //5
                    $this->compensation_level = floatval($tax_table[4][0]);
                    $this->train_range = floatval($tax_table[4][1]);
                    $this->tax_on_train_range = floatval($tax_table[4][3]);
                    $this->rate_in_excess = floatval($tax_table[4][4]);
                    break;
                case $current_compensation > floatval($tax_table[5][1]): //6
                    $this->compensation_level = floatval($tax_table[5][0]);
                    $this->train_range = floatval($tax_table[5][1]);
                    $this->tax_on_train_range = floatval($tax_table[5][3]);
                    $this->rate_in_excess = floatval($tax_table[5][4]);
                    break;
                default:
                    break;
            }
        }

        $in_excess_of_range = $current_compensation - $this->train_range;
        $tax_of_in_excess = $in_excess_of_range * $this->rate_in_excess;
        $tax_due = $tax_of_in_excess + $this->tax_on_train_range;

        return max($tax_due, 0); //tax due
    }

    /**
     * 
     */
    function totalEarnings() {
        return $this->grossPay(); //trace back, added the differential and etc.
    }

    /**
     * Calculate totalDeductions starting from tax dues, contribution, loans, deductions like unworked, and other deductions.
     */
    function totalDeductions() {
        return $this->taxDue() + $this->totalContribution() + $this->totalLoans() + $this->deductPay() + $this->otherDeductions();
    }

    /**
     * Compute the total compensation that the employee will received!
     */
    function netPay() {
        return $this->totalEarnings() - $this->totalDeductions();
    }
}