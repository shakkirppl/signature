@extends('layouts.layout')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Customer Payment Report</h4>

                    <form method="GET" action="{{ route('customer-payment.report') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-3">
                                <select class="form-control" name="customer_id">
                                    <option value="">Select Customer</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
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
                                    <th>Date</th>
                                    <th>Customer</th>
                                    <th>Bank</th>
                                    <th>Outstanding Amount</th>
                                    <th>Allocated Amount</th>
                                    <th>Paid Amount</th>
                                    <th>Balance</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($customerPayments as $payment)
                                <tr>
                                    <td>{{ $payment->payment_date }}</td>
                                    <td>{{ $payment->customer->customer_name ?? 'N/A' }}</td>
                                    <td>{{ $payment->bank_name ?? 'N/A' }}</td>
                                    <td>{{ number_format($payment->outstanding_amount, 2) }}</td>
                                    <td>{{ number_format($payment->allocated_amount, 2) }}</td>
                                    <td>{{ number_format($payment->total_paid, 2) }}</td>
                                    <td>{{ number_format($payment->balance, 2) }}</td>
                                    <td>
                                        <a href="{{ route('customer-payment.view', $payment->id) }}" class="btn btn-warning">View</a>
                                    </td>
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
