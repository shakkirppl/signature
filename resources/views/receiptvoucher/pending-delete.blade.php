@extends('layouts.layout')
@section('content')
<div class="main-panel">
  <div class="content-wrapper">
    <h4>Delete Requests </h4>
            <table class="table table-bordered table-striped table-sm" style="font-size: 12px;">
              <thead style="background-color: #d6d6d6; color: #000;">
        <tr>
          <th>Code</th>
          <th>Date</th>
          <th>Amount</th>
          <th>Requested By</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($vouchers as $voucher)
        <tr>
          <td>{{ $voucher->code }}</td>
          <td>{{ \Carbon\Carbon::parse($voucher->date)->format('d-m-Y') }}</td>
          <td>{{ number_format($voucher->amount, 2) }}</td>
          <td>{{ $voucher->user->name ?? 'N/A' }}</td>
          <td>
            <form method="POST" action="{{ route('receiptvoucher.confirmDelete', $voucher->id) }}">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-danger btn-sm">Approve Delete</button>
            </form>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection
