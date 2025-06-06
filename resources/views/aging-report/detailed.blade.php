@extends('layouts.layout')
@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Detailed Aging Report</h1>
    
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">All Transactions</h6>
            <a href="{{ route('aging.summary') }}" class="btn btn-sm btn-secondary">Back to Summary</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Account</th>
                            <th>Type</th>
                            <th>Description</th>
                            <th>Amount</th>
                            <th>Days Old</th>
                            <th>Aging Bucket</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $transaction)
                        <tr>
                            <td>{{ $transaction->date->format('d/m/Y') }}</td>
                            <td>
                                @if($transaction->account_type == 'customer')
                                    {{ $transaction->customer->customer_name ?? 'N/A' }}
                                @else
                                    {{ $transaction->supplier->name ?? 'N/A' }}
                                @endif
                            </td>
                            <td>{{ ucfirst($transaction->account_type) }}</td>
                            <td>{{ $transaction->description }}</td>
                            <td class="text-right {{ $transaction->amount < 0 ? 'text-danger' : 'text-success' }}">
                                {{ number_format(abs($transaction->amount), 2) }}
                            </td>
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
        $('#dataTable').DataTable({
            order: [[5, 'desc']], // Default sort by days old
            pageLength: 50
        });
    });
</script>
@endsection