@extends('layouts.layout')

@section('content')
<div class="container-fluid">
    <h4 class="mb-4">Supplier Aging Report</h4>

    <!-- Summary Section -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Aging Summary</h6>
        </div>
        <div class="card-body">
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
                            <td class="text-right">{{ number_format($summary[$period]['count'] ?? 0) }}</td>
                            <td class="text-right">{{ number_format($summary[$period]['amount'] ?? 0, 2) }}</td>
                        </tr>
                        @endforeach
                        <tr class="table-active">
                            <td><strong>Total</strong></td>
                            <td class="text-right"><strong>{{ number_format($summary['total']['count'] ?? 0) }}</strong></td>
                            <td class="text-right"><strong>{{ number_format($summary['total']['amount'] ?? 0, 2) }}</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Detailed Transactions -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Detailed Transactions</h6>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Supplier Name</th>
                            <th>Date</th>
                            <th>Days Outstanding</th>
                            <th>Transaction ID</th>
                            <th>Description</th>
                            <th class="text-right">Amount</th>
                            <th>Aging Bucket</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $transaction)
                            @php
                                $amount = $transaction->payment - $transaction->receipt;
                                $absAmount = abs($amount);

                                if ($transaction->days_old <= 30) {
                                    $bucket = '0-30';
                                    $badgeClass = 'badge-success';
                                } elseif ($transaction->days_old <= 60) {
                                    $bucket = '31-60';
                                    $badgeClass = 'badge-info';
                                } elseif ($transaction->days_old <= 90) {
                                    $bucket = '61-90';
                                    $badgeClass = 'badge-warning';
                                } else {
                                    $bucket = '90+';
                                    $badgeClass = 'badge-danger';
                                }
                            @endphp
                            @if($amount > 0)
                            <tr>
                                <td>{{ $transaction->supplier->supplier_name ?? 'N/A' }}</td>
                                <td>{{ $transaction->date }}</td>
                                <td class="text-center">{{ $transaction->days_old }}</td>
                                <td>{{ $transaction->transaction_id }}</td>
                                <td>{{ $transaction->description }}</td>
                                <td class="text-right text-danger">
                                    {{ number_format($absAmount, 2) }}
                                </td>
                                <td class="text-center">
                                    <span class="badge {{ $badgeClass }}">{{ $bucket }}</span>
                                </td>
                            </tr>
                            @endif
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
    $(document).ready(function () {
        // Initialize DataTable
        $('#dataTable').DataTable({
            order: [[2, 'desc']],
            pageLength: 25,
            dom: 'Bfrtip',
            buttons: ['excel', 'pdf', 'print'],
            columnDefs: [
                { targets: [2, 5], className: 'text-right' },
                { targets: [6], className: 'text-center' }
            ]
        });
    });
</script>
@endsection