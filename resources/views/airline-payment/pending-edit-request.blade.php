@extends('layouts.layout')

@section('content')
@php
$fieldNames = [
    'date' => 'Date',
    'code' => 'Code',
    'airline_name' => 'Airline Name',
    'airline_number' => 'Flight Number',
    'shipment_id' => 'Shipment No',
    'customer_id' => 'Customer',
    'air_waybill_no' => 'Air Waybill No',
    'air_waybill_charge' => 'Air Waybill Charge',
    'documents_charge' => 'Documents Charge',
    'amount' => 'Amount',
];
@endphp

<div class="container">
    <h3>Pending Airline Payment Edit Requests</h3>

    @if($payments->isEmpty())
        <p>No edit requests pending.</p>
    @else
        @foreach($payments as $key => $payment)
            @php
                $editData = json_decode($payment->edit_request_data, true);
                $hasChanges = false;
                $changes = [];

                foreach ($editData as $field => $newValue) {
                    $originalValue = $payment->$field;

                    // Related fields
                    if ($field == 'shipment_id') {
                        $originalValue = $payment->shipment->shipment_no ?? 'N/A';
                        $new = \App\Models\Shipment::find($newValue);
                        $newValue = $new->shipment_no ?? 'N/A';
                    } elseif ($field == 'customer_id') {
                        $originalValue = $payment->customer->customer_name ?? 'N/A';
                        $new = \App\Models\Customer::find($newValue);
                        $newValue = $new->customer_name ?? 'N/A';
                    }

                    // Format numbers
                    if (in_array($field, ['amount', 'air_waybill_charge', 'documents_charge'])) {
                        $originalValue = number_format((float) $originalValue, 2);
                        $newValue = number_format((float) $newValue, 2);
                    }

                    if ($originalValue != $newValue) {
                        $hasChanges = true;
                        $changes[] = [
                            'field' => $fieldNames[$field] ?? ucfirst(str_replace('_', ' ', $field)),
                            'old' => $originalValue,
                            'new' => $newValue,
                        ];
                    }
                }
            @endphp

            @if($hasChanges)
            <div class="border p-3 mb-4">
                <h5>Voucher Code: {{ $payment->code }}</h5>
                <table class="table table-sm table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Field</th>
                            <th>Current Value</th>
                            <th>Requested Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($changes as $index => $change)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $change['field'] }}</td>
                                <td>{{ $change['old'] }}</td>
                                <td>{{ $change['new'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <form action="{{ route('airline.approveEdit', $payment->id) }}" method="POST" style="display:inline-block;">
                    @csrf
                    <button type="submit" class="btn btn-success btn-sm">Approve</button>
                </form>

                <form action="{{ route('airline.rejectEdit', $payment->id) }}" method="POST" style="display:inline-block;">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                </form>
            </div>
            @endif
        @endforeach
    @endif
</div>
@endsection
