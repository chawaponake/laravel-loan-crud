<?php

namespace App\Http\Controllers;

use App\Loan;
use Illuminate\Http\Request;
use Validator;
use App\Http\Controllers\RepaymentScheduleController;
use Illuminate\Support\Facades\DB;

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
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $loans = Loan::orderBy('id', 'desc')->paginate(15);
        if($request->all()){
            $filter = $request->all();
            $loans = Loan::filter($filter)->orderBy('id', 'desc')->paginateFilter(15);
            return view('loans.index', compact('loans'));
        }
        return view('loans.index', compact('loans'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $months = $this->months;
        $year_range = $this->year_range;
        return view('loans.create', compact('months', 'year_range'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $start_date = date('Y-m',strtotime($input['year'] . '-' .  $input['month']));
        $input['start_date'] = $start_date;
        $validator = Validator::make($input,[
            'loan_amount' => 'required|integer|min:1000|max:100000000',
            'loan_term' => 'required|integer|min:1|max:50',
            'interest_rate' => 'required|numeric|between:1,36.99',
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|between:2017,2050',
            'start_date' => 'required|date_format:"Y-m"'
        ]);

        if($validator->fails()){
            return redirect('loans/create')->withErrors($validator)->withInput();
        }
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
            return redirect('loans/'.$loan->id)->with('successfully','The loan has been created successfully.');
        }catch (QueryException $queryException){
            DB::rollback();
            return redirect('loans')->with('error',$queryException->getMessage());
        }catch (\Exception $exception){
            DB::rollback();
            return redirect('loans')->with('error',$exception->getMessage());
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
        return view('loans.view', compact('loan'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $months = $this->months;
        $year_range = $this->year_range;
        $loan = Loan::findOrFail($id);
        return view('loans.edit', compact('loan', 'months', 'year_range'));
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
        $start_date = date('Y-m',strtotime($input['year'] . '-' .  $input['month']));
        $input['start_date'] = $start_date;
        $validator = Validator::make($input,[
            'loan_amount' => 'required|integer|min:1000|max:100000000',
            'loan_term' => 'required|integer|min:1|max:50',
            'interest_rate' => 'required|numeric|between:1,36.99',
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|between:2017,2050',
            'start_date' => 'required|date_format:"Y-m"'
        ]);

        if($validator->fails()){
            return redirect('loans/edit')->withErrors($validator)->withInput();
        }

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
            return redirect('loans/'.$loan->id)->with('successfully','The loan has been updated successfully.');
        }catch (QueryException $queryException){
            DB::rollback();
            return redirect('loans')->with('error',$queryException->getMessage());
        }catch (\Exception $exception){
            DB::rollback();
            return redirect('loans')->with('error',$exception->getMessage());
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
            return redirect('loans')->with('successfully','The loan has been deleted successfully.');
        }catch (QueryException $queryException){
            DB::rollback();
            return redirect('loans')->with('error',$queryException->getMessage());
        }catch (\Exception $exception){
            DB::rollback();
            return redirect('loans')->with('error',$exception->getMessage());
        }

    }
}
