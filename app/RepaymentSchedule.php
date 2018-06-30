<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RepaymentSchedule extends Model
{

    use SoftDeletes;

    protected $table = 'repayment_schedules';

    protected $fillable = ['date', 'payment_amount', 'principal', 'interest', 'balance', 'loan_id'];

    public function loan(){
        return $this->belongsTo('App\Loan', 'loan_id');
    }
}
