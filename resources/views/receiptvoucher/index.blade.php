

@extends('layouts.layout')
@section('content')
<div class="main-panel">
  <div class="content-wrapper">
    <div class="col-12 grid-margin">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <h4 class="card-title">Receipt Vouchers List</h4>
            </div>
            <div class="col-md-6 text-right">
            <a href="{{ route('receiptvoucher.create') }}" class="newicon"><i class="mdi mdi-new-box"></i></a>
            </div>
          </div>
          <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
            <table class="table table-bordered table-striped table-sm" style="font-size: 12px;">
              <thead style="background-color: #d6d6d6; color: #000;">
                <tr>
                  <th>No</th>
                  <th>Code</th>
                  <th>Date</th>
                  <th>COA</th>
                  <th>Amount</th>
                  <th>Type</th>
                  <th>Bank</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($vouchers as $index => $voucher)
                <tr>
                  <td>{{ $index + 1 }}</td>
                  <td>{{ $voucher->code }}</td>
                  <td>{{ \Carbon\Carbon::parse($voucher->date)->format('d-m-Y') }}</td>
                  <td>{{ $voucher->account ? $voucher->account->name : 'N/A' }}</td>

                  <td>{{ number_format($voucher->amount, 2) }}</td>
                  <td>{{ ucfirst($voucher->type) }}</td>
                    <td>
                        @if($voucher->type === 'bank' && $voucher->bank_id)
                             {{ $voucher->bank->bank_name }}
                                     @else
                                        N/A
                                     @endif
                    </td>
                    <td>
                        <a href="{{ route('receiptvoucher.edit', $voucher->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <a href="{{ route('receiptvoucher.destroy', $voucher->id) }}" 
                                                    class="btn btn-danger btn-sm delete-btn" 
                                                    onclick="return confirm('Are you sure you want to delete this record?')">
                                                    <i class="mdi mdi-delete"></i> Delete
                                            </a>
                                           
                    </td>
                 
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<style>
  .table-responsive {
    overflow-x: auto;
  }
  .table th, .table td {
    padding: 5px;
    text-align: center;
  }
  .btn-sm {
    padding: 3px 6px;
    font-size: 10px;
  }
  .newicon i {
    font-size: 30px;}
</style>
@endsection









