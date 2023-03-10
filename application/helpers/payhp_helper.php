<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PayHP {

    protected $term_table = 'biweekly';

    //CONSTANTS VARIABLES
    protected $overtime_rate = 0.25;
    protected $nightdiff_rate = 0.10;
    protected $restday_rate = 0.30;
    protected $legalhd_rate = 1.00;
    protected $spclhd_rate = 0.44;

    //DYNAMIC VARIABLES
    protected $hourly_rate = 0.0;

    protected $compensation_level = 0;
    protected $expected_compensation = 0;

    protected $train_range = 0.00;
    protected $tax_on_train_range = 0.00;
    protected $rate_in_excess = 0.00;

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
    function __construct( $hourly_rate, $rates, $expected_compensation = 0, $term = "biweekly" ) {
        $this->hourly_rate = $hourly_rate;
        $this->overtime_rate = isset($rates['overtime_rate']) && is_numeric($rates['overtime_rate'])?$rates['overtime_rate']:$this->overtime_rate;
        $this->nightdiff_rate = isset($rates['nightdiff_rate']) && is_numeric($rates['nightdiff_rate'])?$rates['nightdiff_rate']:$this->nightdiff_rate;
        $this->restday_rate = isset($rates['restday_rate']) && is_numeric($rates['restday_rate'])?$rates['restday_rate']:$this->restday_rate;
        $this->legalhd_rate = isset($rates['legalhd_rate']) && is_numeric($rates['legalhd_rate'])?$rates['legalhd_rate']:$this->legalhd_rate;
        $this->spclhd_rate = isset($rates['spclhd_rate']) && is_numeric($rates['spclhd_rate'])?$rates['spclhd_rate']:$this->spclhd_rate;
        $this->expected_compensation = $expected_compensation;
        $this->term_table = $term;
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

            "basic_pay" => $this->basicPay(),
            "unwork_deduction" => $this->deductPay(),
            "hours_paid" => $this->hoursPaid(),

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

    //EARNINGS SUMMARY

    function unworkHour() {
        return $this->absent_hour + $this->late_hour + $this->over_hour + $this->under_hour;
    }

    function basicPay() {
        if($this->expected_compensation > 0) {
            return $this->expected_compensation;
        } else {
            return $this->sched_hour * $this->hourly_rate;
        }        
    }

    //Optional
    function deductPay() {
        return $this->unworkHour() * $this->hourly_rate;
    }

    function hoursPaid() {
        $regular_pay = ($this->sched_hour - $this->unworkHour()) * $this->hourly_rate;
        return $regular_pay + $this->ptoPay() + $this->overtimePay() + $this->nightdiffPay() + $this->specialPay();
    }

    function ptoPay() {
        return $this->pto_hour * $this->hourly_rate;
    }

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

    function taxableAdditional() {
        return $this->add_other;
    }

    function nontaxAdditional() {
        return $this->allowance + $this->incentives + $this->month13th + $this->bonus + $this->add_adjust;
    }

    function netTaxable() {
        return $this->hoursPaid() + $this->taxableAdditional(); //TODO TO CONFIRM IF SOME IN ADDITIONAL IS INCLUDED.
    }

    function grossPay() {
        return $this->netTaxable() + $this->nontaxAdditional();
    }

    //DEDUCTIONS SUMMARY

    function totalContribution() {
        return $this->sss_contri + $this->pagibig_contri + $this->phealth_contri + $this->hmo_contri;
    }

    function totalLoans() {
        return $this->com_loan + $this->sss_loan + $this->pagibig_loan;
    }

    function otherDeductions() {
        return $this->deduct_adjust + $this->deduct_other;
    }

    //FINAL

    function taxDue() {

        $this->train_range = 0.00;
        $this->tax_on_train_range = 0.00;
        $this->rate_in_excess = 0.00;

        $current_compensation = $this->netTaxable();

        if($this->term = 'monthly') {
            switch(true) {
                case $current_compensation <=  20833.00: //1
                    $this->compensation_level = 1;
                    $this->train_range = 20833.00;
                    $this->tax_on_train_range = 0.00;
                    $this->rate_in_excess = 0.00;
                    break;
                case $current_compensation > 20833.00 && $current_compensation <= 33333.00: //2
                    $this->compensation_level = 2;
                    $this->train_range = 20833.00;
                    $this->tax_on_train_range = 0.00;
                    $this->rate_in_excess = 0.15;
                    break;
                case $current_compensation > 33333.00 && $current_compensation <= 66667.00: //3
                    $this->compensation_level = 3;
                    $this->train_range = 33333.00;
                    $this->tax_on_train_range = 1875.00;
                    $this->rate_in_excess = 0.20;
                    break;
                case $current_compensation > 66667.00 && $current_compensation <= 166667.00: //4
                    $this->compensation_level = 4;
                    $this->train_range = 66667.00;
                    $this->tax_on_train_range = 8541.80;
                    $this->rate_in_excess = 0.25;
                    break;
                case $current_compensation > 166667.00 && $current_compensation <= 666667.00: //5
                    $this->compensation_level = 5;
                    $this->train_range = 166667.00;
                    $this->tax_on_train_range = 33541.80;
                    $this->rate_in_excess = 0.30;
                    break;
                case $current_compensation > 666667.00: //6
                    $this->compensation_level = 6;
                    $this->train_range = 666667.00;
                    $this->tax_on_train_range = 183541.80;
                    $this->rate_in_excess = 0.35;
                    break;
                default:
                    break;
            } 
        } else if($this->term = 'biweekly') {
            switch(true) {
                case $current_compensation <=  10417.00: //1
                    $this->compensation_level = 1;
                    $this->train_range = 10417.00;
                    $this->tax_on_train_range = 0.00;
                    $this->rate_in_excess = 0.00;
                    break;
                case $current_compensation > 10417.00 && $current_compensation <= 16667.00: //2
                    $this->compensation_level = 2;
                    $this->train_range = 10417.01;
                    $this->tax_on_train_range = 0.00;
                    $this->rate_in_excess = 0.15;
                    break;
                case $current_compensation > 16667.00 && $current_compensation <= 33333.00: //3
                    $this->compensation_level = 3;
                    $this->train_range = 16667.00;
                    $this->tax_on_train_range = 937.50;
                    $this->rate_in_excess = 0.20;
                    break;
                case $current_compensation > 33333.00 && $current_compensation <= 83333.00: //4
                    $this->compensation_level = 4;
                    $this->train_range = 33333.00;
                    $this->tax_on_train_range = 4270.70;
                    $this->rate_in_excess = 0.25;
                    break;
                case $current_compensation > 83333.00 && $current_compensation <= 333333.00: //5
                    $this->compensation_level = 5;
                    $this->train_range = 83333.00;
                    $this->tax_on_train_range = 16770.70;
                    $this->rate_in_excess = 0.30;
                    break;
                case $current_compensation > 333333.00: //6
                    $this->compensation_level = 6;
                    $this->train_range = 333333.00;
                    $this->tax_on_train_range = 91770.70;
                    $this->rate_in_excess = 0.35;
                    break;
                default:
                    break;
            } 
        } else if($this->term = 'weekly') {
            switch(true) {
                case $current_compensation <=  4808.00: //1
                    $this->compensation_level = 1;
                    $this->train_range = 4808.00;
                    $this->tax_on_train_range = 0.00;
                    $this->rate_in_excess = 0.00;
                    break;
                case $current_compensation > 4808.00 && $current_compensation <= 7692.00: //2
                    $this->compensation_level = 2;
                    $this->train_range = 4808.01;
                    $this->tax_on_train_range = 0.00;
                    $this->rate_in_excess = 0.15;
                    break;
                case $current_compensation > 7692.00 && $current_compensation <= 15385.00: //3
                    $this->compensation_level = 3;
                    $this->train_range = 7692.00;
                    $this->tax_on_train_range = 432.60;
                    $this->rate_in_excess = 0.20;
                    break;
                case $current_compensation > 15385.00 && $current_compensation <= 38462.00: //4
                    $this->compensation_level = 4;
                    $this->train_range = 15385.00;
                    $this->tax_on_train_range = 1971.20;
                    $this->rate_in_excess = 0.25;
                    break;
                case $current_compensation > 38462.00 && $current_compensation <= 153846.00: //5
                    $this->compensation_level = 5;
                    $this->train_range = 38462.00;
                    $this->tax_on_train_range = 7740.45;
                    $this->rate_in_excess = 0.30;
                    break;
                case $current_compensation > 153846.00: //6
                    $this->compensation_level = 6;
                    $this->train_range = 153846.00;
                    $this->tax_on_train_range = 42355.65;
                    $this->rate_in_excess = 0.35;
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

    function totalEarnings() {
        return $this->basicPay() + $this->ptoPay() + $this->overtimePay() + $this->nightdiffPay() + $this->specialPay() + $this->taxableAdditional() + $this->nontaxAdditional();
    }

    function totalDeductions() {
        return $this->deductPay() + $this->taxDue() + $this->totalContribution() + $this->totalLoans() + $this->otherDeductions();
    }

    function netPay() {
        return $this->totalEarnings() - $this->totalDeductions();
    }
}