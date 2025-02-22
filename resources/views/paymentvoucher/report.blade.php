@extends('layouts.layout')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Payment Voucher Report</h4>

                    <form method="GET" action="{{ route('paymentvoucher.report') }}">
                        @csrf
                        <div class="row">
                            
                            <div class="col-md-3">
                                <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}" placeholder="From Date">
                            </div>
                            <div class="col-md-3">
                                <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}" placeholder="To Date">
                            </div>
                            <div class="col-md-1">
                                <button type="submit" class="btn btn-primary">Get</button>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive mt-4">
                        <table class="table table-hover" id="report-table">
                            <thead>
                                <tr>
                                    <th>Voucher Code</th>
                                    <th>Date</th>
                                    <th>COA</th>
                                    <th>Amount</th>
                                    <th>Type</th>
                                    <th>Bank</th>
                                    
                                   
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($paymentVouchers as $voucher)
                                <tr>
                                    <td>{{ $voucher->code }}</td>
                                    <td>{{ $voucher->date }}</td>
                                    <td>{{ $voucher->account->name ?? 'N/A' }}</td>
                                    <td>{{ number_format($voucher->amount, 2) }}</td>
                                    <td>{{ ucfirst($voucher->type) }}</td>
                                    <td>{{ $voucher->bank->bank_name ?? 'N/A' }}</td>
                                    
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
