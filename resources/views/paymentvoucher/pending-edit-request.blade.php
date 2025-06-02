@extends('layouts.layout')
@section('content')
<div class="main-panel">
  <div class="content-wrapper">
    <div class="col-12 grid-margin">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Pending Edit Requests</h4>
 @if($vouchers->isEmpty())
                        <p>No edit requests pending.</p>
                    @else

          @foreach($vouchers as $voucher)
            @if(count($voucher->changed_fields))
              <div class="border p-3 mb-4">
                <h5>Voucher Code: {{ $voucher->code }}</h5>

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
    'coa_id' => 'COA',
    'employee_id' => 'Payment To',
    'bank_id' => 'Bank',
    'date' => 'Date',
    'amount' => 'Amount',
    'type' => 'Payment Type',
    'description' => 'Description',
    'currency' => 'Currency',
    // Add other fields here...
];
@endphp
                    @foreach($voucher->changed_fields as $field => $values)
                      <tr>
<td>{{ $fieldNames[$field] ?? ucfirst(str_replace('_', ' ', $field)) }}</td>
                        <td>
                          @if($field === 'coa_id')
                            {{ \App\Models\AccountHead::find($values['original'])->name ?? 'N/A' }}
                          @elseif($field === 'employee_id')
                            {{ \App\Models\Employee::find($values['original'])->name ?? 'N/A' }}
                          @elseif($field === 'bank_id')
                            {{ \App\Models\BankMaster::find($values['original'])->bank_name ?? 'N/A' }}
                          @else
                            {{ $values['original'] }}
                          @endif
                        </td>
                        <td>
                          @if($field === 'coa_id')
                            {{ \App\Models\AccountHead::find($values['requested'])->name ?? 'N/A' }}
                          @elseif($field === 'employee_id')
                            {{ \App\Models\Employee::find($values['requested'])->name ?? 'N/A' }}
                          @elseif($field === 'bank_id')
                            {{ \App\Models\BankMaster::find($values['requested'])->bank_name ?? 'N/A' }}
                          @else
                            {{ $values['requested'] }}
                          @endif
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>

                <div>
                  <form action="{{ route('paymentvoucher.approveEdit', $voucher->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button class="btn btn-success btn-sm">Approve</button>
                  </form>

                  <form action="{{ route('paymentvoucher.rejectEditRequest', $voucher->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Reject this edit request?');">
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
