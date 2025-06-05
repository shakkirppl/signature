@extends('layouts.layout')
@section('content')
@php $fieldNames = [
  'date' => 'Date',
  'coa_id' => 'Chart of Account',
  'type' => 'Payment Type',
  'amount' => 'Amount',
  'description' => 'Description',
  'bank_id' => 'Bank',
  'shipment_id' => 'Shipment No',
  'currency' => 'Currency',
]; @endphp

<div class="container">
  <h3>Pending Edit Requests (Expense voucher)</h3>
   @if($pendingVouchers->isEmpty())
                        <p>No edit requests pending.</p>
                    @else
  @foreach($pendingVouchers as $voucher)
  <div class="border p-3 mb-4">
    <h5>Voucher Code: {{ $voucher->code }}</h5>
    <table class="table table-sm table-bordered">
      <thead>
        <tr>
          <th>Field</th>
          <th>Current Value</th>
          <th>Requested Value</th>
        </tr>
      </thead>
      <tbody>
  @php
      $editData = json_decode($voucher->edit_request_data, true);
  @endphp
  @foreach($editData as $field => $newValue)
    @php
      $oldValue = $voucher->$field;
      if ($field == 'coa_id') {
          $oldValue = $voucher->account->name ?? 'N/A';
          $newValue = \App\Models\AccountHead::find($newValue)->name ?? 'N/A';
      } elseif ($field == 'bank_id') {
          $oldValue = $voucher->bank->bank_name ?? 'N/A';
          $newValue = \App\Models\BankMaster::find($newValue)->bank_name ?? 'N/A';
      } elseif ($field == 'shipment_id') {
          $oldValue = $voucher->shipment->shipment_no ?? 'N/A';
          $newValue = \App\Models\Shipment::find($newValue)->shipment_no ?? 'N/A';
      }
    @endphp
    @if($oldValue != $newValue)
    <tr>
      <td>{{ $fieldNames[$field] ?? ucfirst(str_replace('_', ' ', $field)) }}</td>
      <td>{{ $oldValue }}</td>
      <td>{{ $newValue }}</td>
    </tr>
    @endif
  @endforeach
</tbody>

    </table>

    <form action="{{ route('expensevoucher.approveEditRequest', $voucher->id) }}" method="POST" style="display:inline-block;">
      @csrf
      <button type="submit" class="btn btn-success btn-sm">Approve</button>
    </form>

    <form action="{{ route('expensevoucher.rejectEditRequest', $voucher->id) }}" method="POST" style="display:inline-block;">
      @csrf
      <button type="submit" class="btn btn-danger btn-sm">Reject</button>
    </form>
<br><br>
  @endforeach
     @endif
</div>
</div>
@endsection
