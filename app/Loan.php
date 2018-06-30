<?php

namespace App;

use App\ModelFilters\LoanFilter;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Loan extends Model
{
    use SoftDeletes;
    use Filterable;

    protected $table = 'loans';

    protected $fillable = ['loan_amount', 'loan_term', 'interest_rate', 'start_date'];

    public function modelFilter(){
        return $this->provideFilter(LoanFilter::class);
    }

    public function repaymentSchedules(){
        return $this->hasMany(RepaymentSchedule::class);
    }
}
