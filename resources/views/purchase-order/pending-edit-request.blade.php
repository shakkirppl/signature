@extends('layouts.layout')
@section('content')
<div class="main-panel">
  <div class="content-wrapper">
    <div class="col-12 grid-margin">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Pending Edit Requests (Purchase Orders)</h4>
          @if($pendingEdits->isEmpty())
            <p>No edit requests pending.</p>
          @else
            @foreach($pendingEdits as $order)
@if(!empty($order->changed_fields) && count($order->changed_fields))
              <div class="border p-3 mb-4">
                <h5>PO Code: {{ $order->code }}</h5>

                <table class="table table-sm table-bordered">
                  <thead>
                    <tr>
                      <th>Field</th>
                      <th>Original</th>
                      <th>Requested</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php
                    $fieldNames = [
                      'supplier_id' => 'Supplier',
                      'date' => 'Date',
                      'reference_no' => 'Reference No',
                      'currency' => 'Currency',
                      'description' => 'Description',
                      'total_amount' => 'Total Amount',
                    ];
                    @endphp

                    @foreach($order->changed_fields as $field => $values)
                    <tr>
                      <td>{{ $fieldNames[$field] ?? ucfirst(str_replace('_', ' ', $field)) }}</td>
                      <td>
                        @if($field === 'supplier_id')
                          {{ \App\Models\Supplier::find($values['original'])->name ?? 'N/A' }}
                        @else
                          {{ $values['original'] }}
                        @endif
                      </td>
                      <td>
                        @if($field === 'supplier_id')
                          {{ \App\Models\Supplier::find($values['requested'])->name ?? 'N/A' }}
                        @else
                          {{ $values['requested'] }}
                        @endif
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>

                <div>
                  <form action="{{ route('purchaseorder.approveEdit', $order->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button class="btn btn-success btn-sm">Approve</button>
                  </form>

                  <form action="{{ route('purchaseorder.rejectEditRequest', $order->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Reject this edit request?');">
                    @csrf
                    <button class="btn btn-danger btn-sm">Reject</button>
                  </form>
                </div>
              </div>
              @endif
            @endforeach
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
