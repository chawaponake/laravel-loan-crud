<?php

namespace App\Http\Controllers;

use App\RepaymentSchedule;
use Illuminate\Http\Request;

class RepaymentScheduleController extends Controller
{
    /**
     * @param $loan, $pmt, $balance, $date
     * $loan = Loan Model
     * $pmt = p*(r/12) / 1-(1+r/12)^(12*y)
     * $balance = Outstanding Balance
     * $date = date of repayment schedule
     *
     * $p = Loan Amount
     * $r = Interest per year
     * $y = Loan term in year
     *
     * @return bool
     */
    public static function generateSchedule($loan, $pmt = 0 , $balance = 0, $date = ""){
        $p = $loan->loan_amount;
        $r = $loan->interest_rate / 100;
        $y = $loan->loan_term;
        $month_per_year = 12;

        if($pmt == 0 && $balance == 0 && $date == ""){
            $payment_amount = self::calculatePayment($p, $r, $y, $month_per_year);
            $interest = self::calculateInterest($p, $r, $month_per_year);
            $principal = self::calculatePrincipal($payment_amount, $interest);
            $balance = self::calculateOutstandingBalance($p, $principal);
            $date = $loan->start_date->addMonths(1);
        }else{
            $payment_amount = $pmt;
            $interest = self::calculateInterest($balance, $r, $month_per_year);
            $principal = self::calculatePrincipal($payment_amount, $interest);
            $balance = self::calculateOutstandingBalance($balance, $principal);
            $date = $date->addMonths(1);
        }

        $repayment = RepaymentSchedule::create([
            'date' => $date,
            'payment_amount' => $payment_amount,
            'principal' => $principal,
            'interest' => $interest,
            'balance' => $balance,
            'loan_id' => $loan->id
        ]);

        if($balance == 0){
            return true;
        }

        return self::generateSchedule($loan,$payment_amount, $balance, $date);

    }

    /**
     * @param $loan
     * this function use for update loan only.
     *
     * @return bool
     */
    public static function regenerateSchedules($loan){
        $loan->repaymentSchedules()->forceDelete();
        return self::generateSchedule($loan);
    }


    /**
     * @param $p = loan amount
     * @param $r = interest rate
     * @param $y = loan term
     * @param $m = month per year
     * @return float
     */
    public static function calculatePayment($p, $r, $y, $m){
        $payment = ($p* ($r/$m)) / (1 - pow((1+($r/$m)),-12*$y));
        return round($payment,2);
    }

    /**
     * @param $b = Outstanding balance
     * @param $r = interest rate
     * @param $m = month per year
     * @return float
     */
    public static function calculateInterest($b, $r, $m){
        $interest = ($r / $m) * $b;
        return round($interest,2);
    }

    /**
     * @param $pmt
     * @param $interest
     * @return mixed
     */
    public static function calculatePrincipal($pmt, $interest){
        $principal = $pmt - $interest;
        return $principal;
    }

    /**
     * @param $p
     * @param $principal
     * @return int
     */
    public static function calculateOutstandingBalance($p, $principal){
         $balance = $p - $principal ;
        return $balance < 1 ? 0 : $balance;
    }
}
