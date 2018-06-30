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
                <div class="row">
                    <button class="btn btn-primary" onclick="location.href='{{route('loans.create')}}'"> Add New Loan</button>
                </div>
                <div class="row">
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
                {{ $loans->links() }}
            </div>
        </div>
        <!-- /.row -->
    </div>
@endsection