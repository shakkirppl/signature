@extends('layouts.layout')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Action History Report</h4>
                    <!-- Load Material Design Icons -->
<link href="https://cdn.jsdelivr.net/npm/@mdi/font@6.9.96/css/materialdesignicons.min.css" rel="stylesheet">

<!-- Add this style section -->
<style>
    /* Color coding for changes */
    .change-old { color: #6c757d; }  /* Old value - gray */
    .change-new { color: #0d6efd; } /* New value - blue */
    .change-added { color: #198754; } /* Added - green */
    .change-deleted { color: #dc3545; }  /* Deleted - red */
    .change-modified { color: #fd7e14; } /* Changed - orange */
    
    /* Badge styles */
    .change-badge {
        padding: 2px 6px;
        font-size: 0.75em;
        border-radius: 4px;
        margin-right: 5px;
    }
    .badge-added { background-color: #198754; color: white; }
    .badge-deleted { background-color: #dc3545; color: white; }
    .badge-modified { background-color: #fd7e14; color: white; }
    
    /* Icon alignment */
    .change-icon {
        font-size: 1em;
        margin-right: 5px;
        vertical-align: middle;
    }
</style>

                    <form method="GET" action="{{ route('actionhistory.report') }}" class="row g-3 mb-4">
                        <div class="col-md-3">
                            <label for="from_date" class="form-label">From Date</label>
                            <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="to_date" class="form-label">To Date</label>
                            <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="page_name" class="form-label">Page Name</label>
                            <select name="page_name" class="form-control">
                                <option value="">-- All Pages --</option>
                                @foreach ($pageNames as $page)
                                    <option value="{{ $page }}" {{ request('page_name') == $page ? 'selected' : '' }}>
                                        {{ $page }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 align-self-end">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="{{ route('actionhistory.report') }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </form>

                    <div class="table-responsive mt-4">
                    <table class="table table-hover" id="report-table">
    <thead>
        <tr>
            <th>No</th>
            <th>Page</th>
            <th>Record ID</th>
            <th>Action</th>
            <th>User</th>
            <th>Changes</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($histories as $index => $history)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $history->page_name }}</td>
                <td>{{ $history->record_id }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $history->action_type)) }}</td>
                <td>{{ $history->user->name ?? 'N/A' }}</td>
       <td>
    @if ($history->changes)
        @php
            $changes = json_decode($history->changes, true) ?? [];
            $record = null; // Initialize $record
            $detailsRelation = null;
            $realRecordId = null;

            if (!is_null($history->record_id)) {
                $recordIdParts = explode('-', $history->record_id);
                $realRecordId = is_numeric($recordIdParts[0]) ? $recordIdParts[0] : $history->record_id;
            }

            // Load the record based on page type
            switch ($history->page_name) {
                case 'Payment Voucher':
                    $record = \App\Models\PaymentVoucher::find($realRecordId);
                    break;
                case 'Expense Voucher':
                    $record = \App\Models\ExpenseVoucher::find($realRecordId);
                    break;
                case 'Receipt Voucher':
                    $record = \App\Models\ReceiptVoucher::find($realRecordId);
                    break;
                case 'Airline Payment':
                    $record = \App\Models\Airline::find($realRecordId);
                    break;
                case 'Purchase Order':
                    $record = \App\Models\PurchaseOrder::with('details')->find($realRecordId);
                    $detailsRelation = 'details';
                    break;
                case 'Sales Order':
                    $record = \App\Models\SalesOrder::with('details')->find($realRecordId);
                    $detailsRelation = 'details';
                    break;
            }

            // Field name translations
           $fieldNames = [
                'coa_id' => 'COA',
                'employee_id' => 'Payment To',
                'bank_id' => 'Bank',
                'date' => 'Date',
                'amount' => 'Amount',
                'type' => 'Payment Type',
                'description' => 'Description',
                'currency' => 'Currency',
                'shipment_id' => 'Shipment No',
                'airline_name' => 'Airline Name',
                'airline_number' => 'Flight Number',
                'customer_id' => 'Customer',
                'air_waybill_no' => 'Air Waybill No',
                'air_waybill_charge' => 'Air Waybill Charge',
                'documents_charge' => 'Documents Charge',
                'order_no' => 'Order No',
                'supplier_id' => 'Supplier',
                'SalesOrder_id' => 'Sales Order No',
                'advance_amount' => 'Advance Amount',
                'product_id' => 'Products',
                'total' => 'Total Amount',
                'balance_amount' => 'Balance Amount',
                'grand_total' => 'Grand Total'
            ];

        @endphp

        @if ($record === null && $realRecordId)
            <div class="text-danger fw-bold">
                Original record not found (ID: {{ $realRecordId }})
            </div>
        @else
            <ul class="mb-0">
                {{-- Simple field changes --}}
                @if (!isset($changes['main']) && !isset($changes['products']))
                    @foreach ($changes as $field => $value)
                        @if (is_array($value) && isset($value['old'], $value['new']))
                            <li>
                                <strong>{{ $fieldNames[$field] ?? ucfirst(str_replace('_', ' ', $field)) }}:</strong>
                                {{ $value['old'] ?? 'N/A' }} → {{ $value['new'] ?? 'N/A' }}
                            </li>
                        @endif
                    @endforeach
                @else
                    {{-- Main document fields --}}
                    @if (isset($changes['main']))
                        @foreach ($changes['main'] as $field => $newValue)
                            @php
                                $originalValue = $record ? $record->getOriginal($field) : null;
                                // Add your translation logic here if needed
                            @endphp
                            @if ($originalValue != $newValue)
                                <li>
                                    <strong>{{ $fieldNames[$field] ?? ucfirst(str_replace('_', ' ', $field)) }}:</strong>
                                    {{ $originalValue ?? 'N/A' }} → {{ $newValue ?? 'N/A' }}
                                </li>
                            @endif
                        @endforeach
                    @endif

                    {{-- Product changes --}}
                    @if (isset($changes['products']) && in_array($history->page_name, ['Purchase Order', 'Sales Order']))
                        @php
                            $originalProducts = $detailsRelation ? ($record->$detailsRelation ?? []) : [];
                        @endphp
                        
                        @foreach ($changes['products'] as $productChange)
                            @if (isset($productChange['product_id']) || isset($productChange['old_product_id']))
                                @php
                                    $productId = $productChange['product_id'] ?? null;
                                    $oldProductId = $productChange['old_product_id'] ?? $productId;
                                    $product = \App\Models\Product::find($productId ?? $oldProductId);
                                    $productName = $product ? $product->product_name : "Product " . ($productId ?? $oldProductId);
                                @endphp
                                
                                <li class="mt-2">
                                    <strong>{{ $productName }}</strong>
                                    <ul>
                                        @foreach (['qty', 'rate', 'total'] as $field)
                                            @if (isset($productChange[$field]) || isset($productChange['old_' . $field]))
                                                @php
                                                    $originalValue = $productChange['old_' . $field] ?? null;
                                                    $newValue = $productChange[$field] ?? null;
                                                @endphp
                                                <li>
                                                    <strong>{{ ucfirst($field) }}:</strong>
                                                    {{ $originalValue ?? 'N/A' }} → {{ $newValue ?? 'N/A' }}
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </li>
                            @endif
                        @endforeach
                    @endif
                @endif
            </ul>
        @endif
    @else
        -
    @endif
</td>
                <td>{{ $history->created_at->format('Y-m-d H:i') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center">No history found for selected date range.</td>
            </tr>
        @endforelse
    </tbody>
</table>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
