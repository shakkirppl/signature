@extends('layouts.layout')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Expense Voucher Report</h4>

                    <form method="GET" action="{{ route('expensevoucher.report') }}">
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
                        <table class="table table-bordered table-striped table-sm">
                            <thead style="background-color: #d6d6d6; color: #000;">
                                <tr>
                                    <th>Voucher Code</th>
                                    <th>Date</th>
                                    <th>COA</th>
                                    <th>Shipment No</th>
                                    <th>Amount</th>
                                    <th>Type</th>
                                    <th>Bank</th>
                                    <th>Currency</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $total = 0; @endphp
                                @foreach($vouchers as $voucher)
                                    @php $total += $voucher->amount; @endphp
                                    <tr>
                                        <td>{{ $voucher->code }}</td>
                                        <td>{{ \Carbon\Carbon::parse($voucher->date)->format('d-m-Y') }}</td>
                                        <td>{{ $voucher->account->name ?? 'N/A' }}</td>
                                        <td>{{ $voucher->shipment->shipment_no ?? '-' }}</td>
                                        <td>{{ number_format($voucher->amount, 2) }}</td>
                                        <td>{{ ucfirst($voucher->type) }}</td>
                                        <td>{{ $voucher->type === 'bank' && $voucher->bank ? $voucher->bank->bank_name : 'N/A' }}</td>
                                        <td>{{ $voucher->currency }}</td>
                                    </tr>
                                @endforeach
                                <tr style="background-color: #f0f0f0;">
                                    <td colspan="4" class="text-right"><strong>Total</strong></td>
                                    <td><strong>{{ number_format($total, 2) }}</strong></td>
                                    <td colspan="3"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
