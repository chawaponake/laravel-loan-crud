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
                @if(Session::has('error'))
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <strong>{{ Session::get('error') }}</strong>
                    </div>
                @endif
                <div class="row">
                    <h1 class="page-header">All Loans</h1>
                </div>
                <div class="row mt-1">
                    <div class="col-sm-12">
                        <div class="float-left">
                            <button class="btn btn-primary" onclick="location.href='{{route('loans.create')}}'"> Add New Loan</button>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-light" data-toggle="collapse" href="#ad_search" role="button" aria-expanded="false" aria-controls="ad_search">
                                Advanced Search
                            </a>
                        </div>
                    </div>
                </div>
                <div class="row mt-1">
                    <div class="col-sm-12">
                        <div class="collapse" id="ad_search">
                            <div class="card card-header">
                                Advanced Search
                            </div>
                            <form id="create_loans" method="GET" action="{{route('searchLoan')}}">
                                <div class="card card-body">
                                    <div class="col-sm-6">
                                        <h5>Loan Amount(THB)</h5>
                                        <div class="form-group row">
                                            <label for="loanAmountMin" class="col-sm-2 col-form-label"><b>Min:</b></label>
                                            <div class="col-sm-4">
                                                <div class="input-group-sm row">
                                                    <input type="text" class="form-control" id="loanAmountMin" name="loanAmountMin" placeholder="10000" value="{{ old('loanAmountMin') }}">
                                                </div>
                                            </div>
                                            <label for="loanAmountMax" class="col-sm-2 col-form-label"><b>Max:</b></label>
                                            <div class="col-sm-4">
                                                <div class="input-group-sm row">
                                                    <input type="text" class="form-control" id="loanAmountMax" name="loanAmountMax" placeholder="100000000" value="{{ old('loanAmountMax') }}">
                                                </div>
                                            </div>
                                        </div>

                                        <h5>Loan Term(Years)</h5>
                                        <div class="form-group row">
                                            <label for="loanTermMin" class="col-sm-2 col-form-label"><b>Min:</b></label>
                                            <div class="col-sm-4">
                                                <div class="input-group-sm row">
                                                    <input type="text" class="form-control" id="loanTermMin" name="loanTermMin" placeholder="1" value="{{ old('loanTermMin') }}">
                                                </div>
                                            </div>
                                            <label for="loanTermMax" class="col-sm-2 col-form-label"><b>Max:</b></label>
                                            <div class="col-sm-4">
                                                <div class="input-group-sm row">
                                                    <input type="text" class="form-control" id="loanTermMax" name="loanTermMax" placeholder="50" value="{{ old('loanTermMax') }}">
                                                </div>
                                            </div>
                                        </div>

                                        <h5>Interest Rate(%)</h5>
                                        <div class="form-group row">
                                            <label for="interestMin" class="col-sm-2 col-form-label"><b>Min:</b></label>
                                            <div class="col-sm-4">
                                                <div class="input-group-sm row">
                                                    <input type="text" class="form-control" id="interestMin" name="interestMin" placeholder="1" value="{{ old('interestMin') }}">
                                                </div>
                                            </div>
                                            <label for="interestMax" class="col-sm-2 col-form-label"><b>Max:</b></label>
                                            <div class="col-sm-4">
                                                <div class="input-group-sm row">
                                                    <input type="text" class="form-control" id="interestMax" name="interestMax" placeholder="36" value="{{ old('interestMax') }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-sm-12">
                                                <button type="submit" class="btn btn-default">Search</button>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>

                </div>
                <div class="row mt-1 table-responsive">
                    <div class="col-sm-12">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Loan Amount</th>
                                <th scope="col">Loan Term</th>
                                <th scope="col">Interest Rate</th>
                                <th scope="col">Create at</th>
                                <th scope="col">Edit</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($loans as $loan)
                                <tr>
                                    <th scope="row">{{ $loan->id }}</th>
                                    <td>{{ number_format($loan->loan_amount,2) }} ฿</td>
                                    <td>{{ number_format($loan->loan_term,0) }} Year</td>
                                    <td>{{ number_format($loan->interest_rate,2) }}%</td>
                                    <td>{{ $loan->created_at }}</td>
                                    <td>
                                        <button class="btn btn-info btn-sm" onclick="location.href='{{route('loans.show',$loan->id)}}'">View</button>
                                        <button class="btn btn-success btn-sm" onclick="location.href='{{ route('loans.edit', $loan->id) }}'">Edit</button>
                                        <form style="display:inline;" action="{{route('loans.destroy', $loan->id)}}" method="post">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                            <button class="btn btn-danger btn-sm">Delete</button>
                                        </form>

                                    </td>

                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                {{ $loans->links() }}
            </div>
        </div>
        <!-- /.row -->
    </div>
@endsection