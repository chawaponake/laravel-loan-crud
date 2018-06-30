<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Loan extends Model
{
    use SoftDeletes;

    protected $table = 'loans';

    protected $fillable = ['loan_amount', 'loan_term', 'interest_rate', 'start_date'];

    public function repaymentSchedules(){
        return $this->hasMany(RepaymentSchedule::class);
    }
}
