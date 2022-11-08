<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\RepaymentScheduleController;
use App\Loan;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LoanController extends Controller
{
    private $months;
    private $year_min;
    private $year_max;
    private $year_range;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    function __construct()
    {
        $this->months = array('Jan' => 1, 'Feb' => 2, 'Mar' => 3, 'Apr' =>4, 'May' => 5, 'Jun' => 6,
            'Jul' =>7 , 'Aug' => 8, 'Sep' => 9, 'Oct' => 10, 'Nov' => 11, 'Dec' => 12);
        $this->year_min = 2017;
        $this->year_max = 2050;
        $this->year_range = range($this->year_min, $this->year_max);
    }

    public function index(Request $request)
    {
        if($request->all()){
            $filter = $request->all();
            $loans = Loan::filter($filter)->orderBy('id', 'desc')->paginateFilter(15);
        } else {
            $loans = Loan::orderBy('id', 'desc')->paginate(15);
        }

        $loans->load('repaymentSchedules');

        return response()->json(['data' => $loans]);
    }

    public function store(Request $request)
    {
        $input = $request->all();
        Validator::make($input,[
            'loan_amount' => 'required|integer|min:1000|max:100000000',
            'loan_term' => 'required|integer|min:1|max:50',
            'interest_rate' => 'required|numeric|between:1,36.99',
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|between:2017,2050',
        ])->validate();

        $start_date = date('Y-m',strtotime($input['year'] . '-' .  $input['month']));
        $input['start_date'] = $start_date;

        DB::beginTransaction();
        try{
            $loan = Loan::create([
                'loan_amount' => $input['loan_amount'],
                'loan_term' => $input['loan_term'],
                'interest_rate' => $input['interest_rate'],
                'start_date' => \Carbon\Carbon::createFromFormat('Y-m', $input['start_date'])
            ]);

            $repayment =  RepaymentScheduleController::generateSchedule($loan);

            if($repayment){
                DB::commit();
            }

            $loan->refresh();
            $loan->load('repaymentSchedules');

            return response()->json(['data' => $loan]);
        }catch (QueryException $queryException){
            DB::rollback();

            return response('error', 400)->json(['errors' => $queryException->getMessage()]);
        }catch (\Exception $exception){
            DB::rollback();

            return response('error', 400)->json(['errors' => $exception->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $loan = Loan::findOrFail($id);

        return response()->json(['data' => $loan]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $loan = Loan::findOrFail($id);
        $input = $request->all();
        Validator::make($input,[
            'loan_amount' => 'required|integer|min:1000|max:100000000',
            'loan_term' => 'required|integer|min:1|max:50',
            'interest_rate' => 'required|numeric|between:1,36.99',
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|between:2017,2050',
        ])->validate();

        $start_date = date('Y-m',strtotime($input['year'] . '-' .  $input['month']));
        $input['start_date'] = $start_date;

        DB::beginTransaction();
        try{
            $loan->loan_amount = $input['loan_amount'];
            $loan->loan_term = $input['loan_term'];
            $loan->interest_rate = $input['interest_rate'];
            $loan->start_date = \Carbon\Carbon::createFromFormat('Y-m', $input['start_date']);
            $loan->save();

            $repayment =  RepaymentScheduleController::regenerateSchedules($loan);

            if($repayment){
                DB::commit();
            }

            DB::commit();

            $loan->refresh();
            $loan->load('repaymentSchedules');

            return response()->json(['data' => $loan]);
        }catch (QueryException $queryException){
            DB::rollback();
            return response('error', 400)->json(['errors' => $queryException->getMessage()]);
        }catch (\Exception $exception){
            DB::rollback();
            return response('error', 400)->json(['errors' => $exception->getMessage()]);
        }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $loan = Loan::findOrFail($id);
        try{
            DB::beginTransaction();

            $loan->repaymentSchedules()->delete();
            $loan->delete();

            DB::commit();

            return response('The loan has been deleted successfully.');
        }catch (QueryException $queryException){
            DB::rollback();
            return response('error', 400)->json(['errors' => $queryException->getMessage()]);
        }catch (\Exception $exception){
            DB::rollback();
            return response('error', 400)->json(['errors' => $exception->getMessage()]);
        }

    }
}