<?php

use Faker\Generator as Faker;

$factory->define(App\Loan::class, function (Faker $faker) {
    return [
        'loan_amount' => rand(1000,1000000),
        'loan_term' => rand(1,5),
        'interest_rate' => rand(1,36),
        'start_date' => \Carbon\Carbon::createFromFormat('Y-m',rand(2017,2050).'-'.rand(1,12)),
    ];
});
