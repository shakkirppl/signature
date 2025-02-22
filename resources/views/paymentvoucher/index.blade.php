

@extends('layouts.layout')
@section('content')
<div class="main-panel">
  <div class="content-wrapper">
    <div class="col-12 grid-margin">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <h4 class="card-title">Payment Vouchers List</h4>
            </div>
            <div class="col-md-6 text-right">
            <a href="{{ route('paymentvoucher.create') }}" class="newicon"><i class="mdi mdi-new-box"></i></a>
            </div>
          </div>
          <div class="table-responsive">
            <table class="table">
              <thead>
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
                        <a href="{{ route('paymentvoucher.edit', $voucher->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <a href="{{ route('paymentvoucher.destroy', $voucher->id) }}" 
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
@endsection









