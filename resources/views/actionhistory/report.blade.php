@extends('layouts.layout')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Action History Report</h4>

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
            $changes = json_decode($history->changes, true);

            if (is_null($changes)) {
                echo '<em>Invalid JSON changes data</em>';
                $changes = [];
            }

            // Improved record ID extraction
            $recordId = $history->record_id;
            $realRecordId = null;
            
            // Handle different record_id formats
            if (str_contains($recordId, '-')) {
                $parts = explode('-', $recordId);
                $realRecordId = is_numeric($parts[0]) ? $parts[0] : $recordId;
            } else {
                $realRecordId = is_numeric($recordId) ? $recordId : null;
            }

            // Load the original record
            $record = null;
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
                    break;
                default:
                    $record = null;
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
            ];
        @endphp

        @if (!$record && $realRecordId)
            <div class="text-danger fw-bold">
                Original record not found (ID: {{ $realRecordId }})
            </div>
        @else
            <ul class="mb-0">
                @foreach ($changes as $field => $value)
                    @php
                        if (is_array($value) && isset($value['old'], $value['new'])) {
                            $originalValue = $value['old'];
                            $newValue = $value['new'];
                        } else {
                            // For purchase order products
                            if ($field === 'products' && $history->page_name === 'Purchase Order') {
                                $originalValue = $record->details ?? [];
                                $newValue = $value;
                                continue; // We'll handle products specially below
                            }
                            
                            $originalValue = $record ? $record->getOriginal($field) : null;
                            $newValue = $value;
                        }

                        // Translate foreign keys
                        if ($field === 'coa_id') {
                            $originalValue = $coaNames[$originalValue] ?? $originalValue;
                            $newValue = $coaNames[$newValue] ?? $newValue;
                        }
                        if ($field === 'bank_id') {
                            $originalValue = $bankNames[$originalValue] ?? $originalValue;
                            $newValue = $bankNames[$newValue] ?? $newValue;
                        }
                        if ($field === 'shipment_id') {
                            $originalValue = $shipments[$originalValue] ?? $originalValue;
                            $newValue = $shipments[$newValue] ?? $newValue;
                        }
                        if ($field === 'customer_id') {
                            $originalValue = $customers[$originalValue] ?? $originalValue;
                            $newValue = $customers[$newValue] ?? $newValue;
                        }
                        if ($field === 'supplier_id') {
                            $originalValue = $suppliers[$originalValue] ?? $originalValue;
                            $newValue = $suppliers[$newValue] ?? $newValue;
                        }
                         if ($field === 'product_id') {
                            $originalValue = $products[$originalValue] ?? $originalValue;
                            $newValue = $products[$newValue] ?? $newValue;
                        }
                        if ($field === 'SalesOrder_id') {
                            $originalValue = $salesOrders[$originalValue] ?? $originalValue;
                            $newValue = $salesOrders[$newValue] ?? $newValue;
                        }

                        // Normalize numeric values
                        if (is_numeric($originalValue) && is_numeric($newValue)) {
                            $originalValue = (float) $originalValue;
                            $newValue = (float) $newValue;
                        }
                    @endphp

                    <!-- @if ($originalValue != $newValue && $field !== 'products')
                        <li>
                            <strong>{{ $fieldNames[$field] ?? ucfirst(str_replace('_', ' ', $field)) }}:</strong>
                            {{ is_array($originalValue) ? json_encode($originalValue) : $originalValue }}
                            →
                            {{ is_array($newValue) ? json_encode($newValue) : $newValue }}
                        </li>
                    @endif -->
                @endforeach

                {{-- Special handling for purchase order products --}}
                @if (isset($changes['products']) && $history->page_name === 'Purchase Order')
                    @php
                        $productChanges = $changes['products'];
                        $originalProducts = $record->details ?? [];
                    @endphp
                    
                 @foreach ($productChanges as $productChange)
    @if (isset($productChange['product_id']))
        @php
            $productId = $productChange['product_id'];
            $product = \App\Models\Product::find($productId);
            $productName = $product ? $product->product_name : "Product $productId";

            $originalProduct = collect($originalProducts)->firstWhere('product_id', $productId);
        @endphp
        
        <li class="mt-2">
            <strong>Product: {{ $productName }}</strong>
            <ul>
                @foreach (['qty', 'male', 'female'] as $field)
                    @if (isset($productChange[$field]))
                        @php
                            $originalValue = $originalProduct ? $originalProduct->$field : null;
                            $newValue = $productChange[$field];
                        @endphp
                        
                        @if ($originalValue != $newValue)
                            <li>
                                <strong>{{ ucfirst($field) }}:</strong>
                                {{ $originalValue ?? 'N/A' }}
                                →
                                {{ $newValue }}
                            </li>
                        @endif
                    @endif
                @endforeach
            </ul>
        </li>
    @endif
@endforeach

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
