@extends('layouts.app')
@section('content')
    <div class="container">
        <!-- Page Heading -->
        <div class="row" id="main" >
            <div class="col-sm-12 col-md-12">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="row">
                    <h1 class="page-header">Edit Loans</h1>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-6">
                        <form id="edit_loans" method="POST" action="{{route('loans.update', $loan->id)}}">
                            {{method_field('PUT')}}
                            <div class="form-group row">
                                <label for="loan_amount" class="col-sm-3 col-form-label"><b>Loan Amount:</b></label>
                                <div class="col-sm-9">
                                    <div class="input-group row">
                                        <input type="text" class="form-control" id="loan_amount" name="loan_amount" placeholder="10000" value="{{ $loan->loan_amount }}">
                                        <div class="input-group-append">
                                            <span class="input-group-text">฿</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="loan_term" class="col-sm-3 col-form-label"><b>Loan Term:</b></label>
                                <div class="col-sm-9">
                                    <div class="input-group row">
                                        <input type="text" class="form-control" id="loan_term" name="loan_term" placeholder="1" value="{{ $loan->loan_term }}">
                                        <div class="input-group-append">
                                            <span class="input-group-text">Years</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="interest_rate" class="col-sm-3 col-form-label"><b>Interest Rate:</b></label>
                                <div class="col-sm-9">
                                    <div class="input-group row">
                                        <input type="text" class="form-control" id="interest_rate" name="interest_rate" placeholder="10" value="{{ number_format($loan->interest_rate,2) }}">
                                        <div class="input-group-append">
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label"><b>Start Date:</b></label>
                                <div class="col-sm-9">
                                    <div class="input-group row">
                                        <div class="col-sm-6">
                                            <select class="custom-select" id="month" name="month">
                                                <option value="" hidden >May</option>
                                                @foreach($months as $key=>$value)
                                                    <option value="{{$value}}" @if( date('n', strtotime($loan->start_date)) == $value ) selected @endif>{{$key}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-6">
                                            <select class="custom-select" id="year" name="year">
                                                <option value="" hidden >2017</option>
                                                @foreach($year_range as $year)
                                                    <option value="{{$year}}" @if( date('Y', strtotime($loan->start_date)) == $year) selected @endif >{{$year}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                </div>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                    <button type="button" class="btn btn-default" onclick="location.href='{{route('searchLoan')}}'">Back</button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
        <!-- /.row -->
    </div>
@endsection