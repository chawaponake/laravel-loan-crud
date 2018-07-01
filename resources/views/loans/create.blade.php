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
                    <h1 class="page-header">Create Loans</h1>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-6">
                        <form id="create_loans" method="POST" action="{{route('loans.store')}}">
                            <div class="form-group row">
                                <label for="loan_amount" class="col-sm-3 col-form-label"><b>Loan Amount:</b></label>
                                <div class="col-sm-9">
                                    <div class="input-group row">
                                        <input type="text" class="form-control" id="loan_amount" name="loan_amount" placeholder="10000" value="{{ old('loan_amount') }}">
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
                                        <input type="text" class="form-control" id="loan_term" name="loan_term" placeholder="1" value="{{ old('loan_term') }}">
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
                                        <input type="text" class="form-control" id="interest_rate" name="interest_rate" placeholder="10" value="{{ old('interest_rate') }}">
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
                                                    <option value="{{$value}}" @if(old('month') == $value) selected @endif>{{$key}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-6">
                                            <select class="custom-select" id="year" name="year">
                                                <option value="" hidden >2017</option>
                                                @foreach($year_range as $year)
                                                    <option value="{{$year}}" @if(old('year') == $year) selected @endif>{{$year}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                </div>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-primary">Create</button>
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