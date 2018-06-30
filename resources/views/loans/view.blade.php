@extends('layouts.app')
@section('content')
    <div class="container">
        <!-- Page Heading -->
        <div class="row" id="main" >
            <div class="col-sm-12 col-md-12">
                @if(Session::has('successfully'))
                    <div class="alert alert-primary alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <strong>{{ Session::get('successfully') }}</strong>
                    </div>
                @endif
                <div class="row">
                    <h1 class="page-header">Loan Details</h1>
                </div>
                <div class="row mt-1">
                    <div class="col-sm-12 col-md-12 col-lg-6">
                        <div class="form-group row">
                            <label for="loan_amount" class="col-sm-3"><b>ID:</b></label>
                            <div class="col-sm-9">
                               {{$loan->id}}
                            </div>

                            <label for="loan_amount" class="col-sm-3"><b>Loan Amount:</b></label>
                            <div class="col-sm-9">
                                {{number_format($loan->loan_amount,2)}} ฿
                            </div>

                            <label for="loan_term" class="col-sm-3 col-form-label"><b>Loan Term:</b></label>
                            <div class="col-sm-9">
                                {{$loan->loan_term}} Years
                            </div>

                            <label for="interest_rate" class="col-sm-3 col-form-label"><b>Interest Rate:</b></label>
                            <div class="col-sm-9">
                               {{ number_format($loan->interest_rate,2)}} %
                            </div>

                            <label for="created_at" class="col-sm-3 col-form-label"><b>Created at:</b></label>
                            <div class="col-sm-9">
                                {{$loan->created_at}}
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-12">
                                <button type="button" class="btn btn-default" onclick="location.href='{{route('loans.index')}}'">Back</button>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="row mt-1">
                    <h1 class="page-header">Repayment Schedules</h1>
                </div>
                <div class="row mt-1 table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th scope="col">Payment No</th>
                            <th scope="col">Date</th>
                            <th scope="col">Payment Amount</th>
                            <th scope="col">Principal</th>
                            <th scope="col">Interest</th>
                            <th scope="col">Balance</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($loan->repaymentSchedules()->get() as $item)
                            <tr>
                                <th scope="row">{{ $item->id }}</th>
                                <td>{{ date('M Y' ,strtotime($item->date)) }}</td>
                                <td>{{ number_format($item->payment_amount,2) }}</td>
                                <td>{{ number_format($item->principal,2) }}</td>
                                <td>{{ number_format($item->interest,2) }}</td>
                                <td>{{ number_format($item->balance,2) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-6">
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <button type="button" class="btn btn-default" onclick="location.href='{{route('loans.index')}}'">Back</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- /.row -->
    </div>
@endsection