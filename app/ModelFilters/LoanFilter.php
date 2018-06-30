<?php namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class LoanFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];

    public function loanAmountMin($value){
        return $this->where('loan_amount' ,'>=', $value);
    }

    public function loanAmountMax($value){
        return $this->where('loan_amount', '<=', $value);
    }

    public function loanTermMin($value){
        return $this->where('loan_term', '>=', $value);
    }

    public function loanTermMax($value){
        return $this->where('loan_term', '<=', $value);
    }

    public function interestMin($value){
        return $this->where('interest_rate', '>=', $value);
    }

    public function interestMax($value){
        return $this->where('interest_rate', '<=', $value);
    }

}
