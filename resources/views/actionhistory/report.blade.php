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

                            // Extract numeric ID from record_id
                            $recordIdParts = explode('-', $history->record_id);
                            $realRecordId = $recordIdParts[0] ?? null;

                            // Load the original record
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
    
                            ];

                            $coaNames = $coaNames ?? [];
                            $bankNames = $bankNames ?? [];
                            $shipments = $shipments ?? [];
                            $customers = $customers ?? [];
                        @endphp

                        @if (!$record)
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
                                            $originalValue = $record->getOriginal($field);
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

                                        // Normalize numeric values
                                        if (is_numeric($originalValue) && is_numeric($newValue)) {
                                            $originalValue = (float) $originalValue;
                                            $newValue = (float) $newValue;
                                        }
                                    @endphp

                                    @if ($originalValue != $newValue)
                                        <li>
                                            <strong>{{ $fieldNames[$field] ?? ucfirst(str_replace('_', ' ', $field)) }}:</strong>
                                            {{ is_array($originalValue) ? json_encode($originalValue) : $originalValue }}
                                            →
                                            {{ is_array($newValue) ? json_encode($newValue) : $newValue }}
                                        </li>
                                    @endif
                                @endforeach
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
