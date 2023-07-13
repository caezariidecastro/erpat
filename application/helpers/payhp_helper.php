<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PayHP {

    protected $term = 'biweekly';
    protected $annual_workdays = 261;
    protected $calculation = 'hourly_based'; //or scheduled_based

    //CONSTANTS VARIABLES
    protected $overtime_rate = 1.25;
    protected $nightdiff_rate = 0.10;
    protected $restday_rate = 1.30;
    protected $legalhd_rate = 2.00;
    protected $spclhd_rate = 1.44;

    //DYNAMIC VARIABLES
    protected $hourly_rate = 0.0;
    protected $monthly_salary = 0.0;
    protected function monthly_salary() {
        if($this->monthly_salary) {
            return $this->monthly_salary;
        }
        
        return ($this->hourly_rate * 8) * ($this->annual_workdays/12);
    }

    // START Encapsule with a function.
    protected $compensation_level = 0;
    protected $train_range = 0.00;
    protected $tax_on_train_range = 0.00;
    protected $rate_in_excess = 0.00;

    protected $daily_tax_table = array();
    protected $weekly_tax_table = array();
    protected $biweekly_tax_table = array();
    protected $monthly_tax_table = array();
    // END Encapsule with a function.

    //Work Schedule
    protected $sched_hour = 0.0;
    protected $worked_hour = 0.0;
    protected $absent_hour = 0.0;
    protected $bonus_hour = 0.0;
    protected $pto_hour = 0.0;

    //OT/ND/HD Pay
    protected $regular_ot = 0.0;
    protected $restday_ot = 0.0;
    protected $regular_nd = 0.0;
    protected $special_hd = 0.0;
    protected $legal_hd = 0.0;

    //List of objects: title, amount, remarks
    protected $earnings = [];
    public function getEarnings() {
        return $this->earnings;
    } 

    protected $deductions = [];
    public function getDeductions() {
        return $this->deductions;
    } 

    /**
     * Initialize how will PayHP compute the summary.
     * @var value decimal(1,2) in decimal.
     * @var array overtime_rate, restday_rate, legalhd_rate, spclhd_rate.
     */
    function __construct() {
        return $this;
    }

    /**
     * Set the calculation mode where to base either on worked hour on unwork.
     * @var value decimal(1,2) in decimal.
     * @var array overtime_rate, restday_rate, legalhd_rate, spclhd_rate.
     */
    function setCalculationMode($mode = "hourly_based") {
        if($mode == "scheduled_based") {
            $this->calculation = "scheduled_based";
        } else {
            $this->calculation = "hourly_based"; //default
        }
        return $this;
    }

    /**
     * Set the monthly salary for this payslip.
     */
    function setMonthlySalary($monthly_salary) {
        $this->monthly_salary = $monthly_salary;
        $this->hourly_rate = ($this->monthly_salary()*12)/$this->annual_workdays/8;
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
     * @var amount decimal(10,2) in amount.
     * @var title allowance, incentive, bonus, 13thmonth.
     */
    function addEarnings( $title, $amount, $taxable = false, $remark = "" ) {
        $this->earnings[] = array(
            "title" => $title,
            "amount" => is_numeric($amount)?$amount:0,
            "taxable" => $taxable
        );
        return $this;
    }

    /**
     * Add deductions other than the tax dues.
     * @var amount decimal(10,2) in amount.
     * @var title sss, pagibig, phealth, loans, etc
     */
    function addDeductions( $title, $amount, $tax_excess = false, $remark = "" ) {
        $this->deductions[] = array(
            "title" => $title,
            "amount" => is_numeric($amount)?$amount:0,
            "tax_excess" => $tax_excess
        );
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
                $this->bonus_hour += is_numeric($value)?$value:0;
                break;
            case $type == "absent":
                $this->absent_hour += is_numeric($value)?$value:0;
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
    function setNightdiff( $value ) {
        $this->regular_nd += is_numeric($value)?$value:0;
        return $this;
    }

    /**
     * Set all the hour variables for overtime.
     * @var value decimal(10,2) in hours
     * @var type regular, restday, legalhd, specialhd
     */
    function setHoliday( $type, $value ) {
        switch($type) {
            case $type == "special":
                $this->special_hd += is_numeric($value)?$value:0;
                break;
            case $type == "legal":
                $this->legal_hd += is_numeric($value)?$value:0;
                break;
            default:
                break;
        }
        return $this;
    }

    //REQUIRED TO EXEC.
    function calculate() {
        return array(
            "monthly_salary_title" => $this->calculation=="hourly_based"?
                "Hourly Rate":"Monthly Salary",
            "monthly_salary" => $this->calculation=="hourly_based"?
                to_currency($this->hourly_rate):to_currency($this->monthly_salary()),
            "basic_pay" => $this->basicPay(),
            "unwork_deduction" => $this->unworkedDeductions(),

            "earnings" => $this->getEarnings(),
            "deductions" => $this->getDeductions(),

            "worked_hour" => $this->worked_hour,
            "unworked_hour" => $this->unworkedHours(),
            "nightdiff_hour" => $this->regular_nd,
            "overtime_hour" => $this->regular_ot+$this->restday_ot,
            "bonus_hour" => $this->bonus_hour,
            "holiday_hour" => $this->special_hd+$this->legal_hd,

            "regular_ot" => $this->regOverPay(),
            "restday_ot" => $this->resOverPay(),
            "regular_ot_hour" => $this->regular_ot,
            "restday_ot_hour" => $this->restday_ot,

            "special_hd" => $this->specialHDPay(),
            "legal_hd" => $this->legalHDPay(),
            "special_hd_hour" => $this->special_hd,
            "legal_hd_hour" => $this->legal_hd,

            "bonus_pay" => $this->bonusPay(),
            "nightdiff_pay" => $this->nightdiffPay(),
            "overtime_pay" => $this->overtimePay(),
            "holiday_pay" => $this->holidayPay(),
            "pto_pay" => $this->ptoPay(),

            "net_taxable" => $this->netTaxable(),
            "tax_due" => $this->taxDue(),
            "gross_pay" => $this->grossPay(),
            "total_deductions" => $this->netDeductables(),
            "net_pay" => $this->netPay()
        );
    }
    
    /**
     * Either expected daily, weekly, biweekly, and monthly pay based on hourly rate.
     */
    function basicPay() {

        if($this->term == 'daily') {
            $regular = floatval($this->hourly_rate) * 8;
        } else if($this->term == 'weekly') {
            $regular = $this->monthly_salary() / 4;
        } else if($this->term == 'biweekly') {
            $regular = $this->monthly_salary() / 2;
        } else if($this->term == 'monthly') {
            $regular = $this->monthly_salary();
        }
        
        return convert_number_to_decimal($regular);
    }

    /**
     * This is a an official leave that will be paid by the company.
     */
    function ptoPay() {
        return $this->pto_hour * $this->hourly_rate;
    }

    function bonusPay() {
        return $this->bonus_hour * $this->hourly_rate;
    }

    /**
     * The hours paid in excess to the scheduled hour.
     */
    function holidayPay() {
        return $this->specialHDPay() + $this->legalHDPay();
    }

    function specialHDPay() {
        $sub_total = $this->special_hd * $this->spclhd_rate;
        return $sub_total * $this->hourly_rate;
    }

    function legalHDPay() {
        $sub_total = $this->legal_hd * $this->legalhd_rate;
        return $sub_total * $this->hourly_rate;
    }

    /**
     * The hours paid in excess to the scheduled hour.
     */
    function overtimePay() {
        return $this->regOverPay() + $this->resOverPay();
    }

    function regOverPay() {
        $sub_total = $this->hourly_rate * $this->overtime_rate;
        return $sub_total * $this->regular_ot;
    }

    function resOverPay() {
        $sub_total = $this->hourly_rate * $this->restday_rate;
        return $sub_total * $this->restday_ot;
    }

    /**
     * The night differential is by default 10pm to 6am.
     */
    function nightdiffPay() {
        $sub_total = $this->hourly_rate * $this->nightdiff_rate;
        return $sub_total * $this->regular_nd;
    }

    /**
     * Hour's paid is the worked hours x the hourly rate plus all the differential pays
     * like the paid timeoff, overtime, nightdiff, and special pay.
     */
    function hoursPaid() {
        if($this->calculation == 'hourly_based') {
            $regular_pay = $this->worked_hour * $this->hourly_rate;
        } else { //scheduled_based
            $regular_pay = $this->basicPay() - $this->unworkedDeductions();
        }
        
        return $regular_pay;
    }

    /**
     * This deduction is a product of unworked hours x the hourly rate.
     */
    function unworkedDeductions() {
        if($this->calculation == 'hourly_based') {
            return 0;
        }

        return num_limit($this->unworkedHours()) * $this->hourly_rate; //scheduled_based
    }

    function unworkedHours() {
        if($this->calculation == 'hourly_based') {
            return $this->absent;
        }

        //scheduled_based
        $sched_unwork = $this->sched_hour - $this->worked_hour;
        return num_limit($sched_unwork-$this->pto_hour);
    }
    
    /**
     * Get the non-taxable additional like allowance, adjustment, etc.
     */
    function nonTaxableAdditional() {
        $sub_total = 0;
        foreach($this->earnings as $earn) {
            if(!isset($earn['taxable']) || (isset($earn['taxable']) && $earn['taxable'] == false)) {
                $sub_total += $earn['amount'];
            }
        }
        return $sub_total;
    }

    /**
     * Get the taxable additional like allowance, adjustment, etc.
     */
    function taxableAdditional() {
        $sub_total = 0;
        foreach($this->earnings as $earn) {
            if(isset($earn['taxable']) && $earn['taxable'] == true) {
                $sub_total += $earn['amount'];
            }
        }
        return $sub_total;
    }

    /**
     * Get all deductions that should be deducted to next taxables.
     */
    function nonTaxablesDeductions() {
        $sub_total = 0;
        foreach($this->deductions as $deduct) {
            if(isset($deduct['tax_excess']) && $deduct['tax_excess'] == true) {
                $sub_total += $deduct['amount'];
            }
        }
        return $sub_total;
    }

    function paidHours() {
        return $this->basicPay() + $this->overtimePay() + $this->nightdiffPay() + $this->holidayPay();
    }

    /**
     * Get the total taxample earnings which includeds hours paid and taxable additional.
     */
    function netTaxable() {
        return num_limit( ($this->paidHours()+$this->taxableAdditional()) );
    }

    /**
     * Get the netTaxable plus the non taxable.
     */
    function grossPay() {
        return $this->paidHours() + $this->taxableAdditional() + $this->nonTaxableAdditional() + $this->bonusPay();
    }

    /**
     * Get all other deduction and adjustment.
     */
    function totalDeductions() {
        $sub_total = 0;
        foreach($this->deductions as $deduct) {
            $sub_total += $deduct['amount'];
        }
        return $sub_total;
    }

    /**
     * Get the tax due based on compensation. TODO: Should be retrieved from the tax form dynamic data.
     */
    function taxDue() {

        $tax_due = 0;
        $current_compensation = num_limit($this->netTaxable()-$this->nonTaxablesDeductions());

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
     * Calculate totalDeductions starting from tax dues, contribution, loans, deductions like unworked, and other deductions.
     */
    function netDeductables() {
        return $this->unworkedDeductions() + $this->taxDue() + $this->totalDeductions();
    }

    /**
     * Compute the total compensation that the employee will received!
     */
    function netPay() {
        return $this->grossPay() - $this->netDeductables();
    }
}