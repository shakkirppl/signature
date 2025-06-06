@extends('layouts.layout')
@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Supplier Aging Report</h1>
    
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Payables</h6>
            <a href="{{ route('aging.summary') }}" class="btn btn-sm btn-secondary">Back to Summary</a>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>Aging Period</th>
                                    <th class="text-right">Count</th>
                                    <th class="text-right">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(['0-30', '31-60', '61-90', '90+'] as $period)
                                <tr>
                                    <td>{{ $period }} days</td>
                                    <td class="text-right">{{ number_format($summary[$period]['count']) }}</td>
                                    <td class="text-right">{{ number_format($summary[$period]['amount'], 2) }}</td>
                                </tr>
                                @endforeach
                                <tr class="table-active">
                                    <td><strong>Total</strong></td>
                                    <td class="text-right"><strong>{{ number_format($summary['total']['count']) }}</strong></td>
                                    <td class="text-right"><strong>{{ number_format($summary['total']['amount'], 2) }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-bordered" id="supplierAgingTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Supplier</th>
                            <th>Date</th>
                            <th>Description</th>
                            <th>Amount</th>
                            <th>Days Old</th>
                            <th>Aging Bucket</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions->where('amount', '<', 0) as $transaction)
                        <tr>
                            <td>{{ $transaction->account_name }}</td>
                            <td>{{ $transaction->date->format('d/m/Y') }}</td>
                            <td>{{ $transaction->description }}</td>
                            <td class="text-right">{{ number_format($transaction->absolute_amount, 2) }}</td>
                            <td class="text-right">{{ $transaction->days_old }}</td>
                            <td class="text-center">
                                <span class="badge 
                                    @if($transaction->aging_bucket == '0-30') badge-success
                                    @elseif($transaction->aging_bucket == '31-60') badge-info
                                    @elseif($transaction->aging_bucket == '61-90') badge-warning
                                    @else badge-danger
                                    @endif">
                                    {{ $transaction->aging_bucket }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#supplierAgingTable').DataTable({
            order: [[4, 'desc']], // Default sort by days old
            pageLength: 50
        });
    });
</script>
@endsection