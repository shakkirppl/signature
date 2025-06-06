@extends('layouts.layout')

@section('content')
<div class="container-fluid">
    <h4 class="mb-4">Customer Aging Report</h4>

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

    <!-- Filter and Get Button -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Detailed Transactions</h6>
        </div>

        <div class="row m-3 align-items-end">
            <!-- <div class="col-md-4">
                <label for="customerFilter">Select Customer:</label>
                <select id="customerFilter" class="form-control">
                    <option value="">-- All Customers --</option>
                    @foreach($customers as $id => $cust)
                        <option value="{{ $id }}">{{ $cust->customer_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <button id="btnGet" class="btn btn-primary">Get</button>
                <button id="btnReset" class="btn btn-secondary ml-2">Reset</button>
            </div>

            <div class="col-md-6">
                <div id="customerSummary" class="alert alert-info mt-4 d-none">
                    <strong>Customer Summary:</strong>
                    Total Amount: <span id="summaryAmount">0.00</span>,
                    Avg. Days Outstanding: <span id="summaryDays">0</span> days
                </div>
            </div>
        </div> -->

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Customer Name</th>
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
                                $customer = $customers[$transaction->account_id] ?? null;
                                $amount = $transaction->receipt - $transaction->payment;
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
                            <tr data-account-id="{{ $transaction->account_id }}" 
                                data-amount="{{ $absAmount }}" 
                                data-days="{{ $transaction->days_old }}">
                                <td>{{ $customer->customer_name ?? 'N/A' }}</td>
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
        const dataTable = $('#dataTable').DataTable({
            order: [[2, 'desc']],
            pageLength: 25,
            dom: 'Bfrtip',
            buttons: ['excel', 'pdf', 'print'],
            columnDefs: [
                { targets: [2, 5], className: 'text-right' },
                { targets: [6], className: 'text-center' }
            ]
        });

        const $customerSummary = $('#customerSummary');
        const $summaryAmount = $('#summaryAmount');
        const $summaryDays = $('#summaryDays');

        // Filter button click handler
        $('#btnGet').on('click', function () {
            const selectedId = $('#customerFilter').val();
            
            if (selectedId === '') {
                // Show all rows if no customer selected
                dataTable.search('').columns().search('').draw();
                $customerSummary.addClass('d-none');
                return;
            }

            // Filter by customer name (first column)
            const customerName = $('#customerFilter option:selected').text();
            dataTable.columns(0).search(customerName).draw();

            // Calculate summary
            let totalAmount = 0;
            let totalDays = 0;
            let count = 0;

            dataTable.rows({ search: 'applied' }).every(function() {
                const rowData = this.data();
                const rowNode = this.node();
                const amount = parseFloat($(rowNode).data('amount'));
                const days = parseInt($(rowNode).data('days'));
                
                totalAmount += amount;
                totalDays += days;
                count++;
            });

            if (count > 0) {
                $summaryAmount.text(totalAmount.toFixed(2));
                $summaryDays.text(Math.round(totalDays / count));
                $customerSummary.removeClass('d-none');
            } else {
                $customerSummary.addClass('d-none');
            }
        });

        // Reset button click handler
        $('#btnReset').on('click', function() {
            $('#customerFilter').val('');
            dataTable.search('').columns().search('').draw();
            $customerSummary.addClass('d-none');
        });
    });
</script>
@endsection 