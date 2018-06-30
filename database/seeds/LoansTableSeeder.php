<?php

use Illuminate\Database\Seeder;

class LoansTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Loan::class,2)->create()->each(function ($loan){
            \App\Http\Controllers\RepaymentScheduleController::generateSchedule($loan);
        });
    }
}
