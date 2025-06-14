

@extends('layouts.layout')
@section('content')
@php
    $user = Auth::user();
@endphp
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
            @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

          <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
            <table class="table table-bordered table-striped table-sm" style="font-size: 12px;">
              <thead style="background-color: #d6d6d6; color: #000;">
                <tr>
                  <th>No</th>
                  <th>Code</th>
                  <th>Date</th>
                  <th>COA</th>
                  <th>Payment To</th>
                  <th>Amount</th>
                  <th>Type</th>
                  <th>Bank</th>
                  <th>Currency</th>
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
                  <td>{{ $voucher->employee ? $voucher->employee->name : 'N/A' }}</td>
                  <td>{{ number_format($voucher->amount, 2) }}</td>
                  <td>{{ ucfirst($voucher->type) }}</td>
                    <td>
                        @if($voucher->type === 'bank' && $voucher->bank_id)
                             {{ $voucher->bank->bank_name }}
                                     @else
                                        N/A
                                     @endif
                    </td>
                    <td>{{ $voucher->currency }}</td>
                    <td>
                    @if($user->designation_id == 1)
                        <a href="{{ route('paymentvoucher.edit', $voucher->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <a href="{{ route('paymentvoucher.destroy', $voucher->id) }}" 
                                                    class="btn btn-danger btn-sm delete-btn" 
                                                    onclick="return confirm('Are you sure you want to delete this record?')">
                                                    <i class="mdi mdi-delete"></i> Delete
                                            </a>
                       @endif
                     @if($user->designation_id == 3  && $voucher->edit_status == 'none')
                         <div class="d-flex align-items-center gap-1">
                         <a href="{{ route('paymentvoucher.editrequest', $voucher->id) }}" class="btn btn-primary btn-sm " >Edit Request</a>
                           @endif
                          @if($user->designation_id == 3  )
                        <form action="{{ route('paymentvoucher.softdelete', $voucher->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to request deletion of this voucher?');">
                        @csrf
                       <button type="submit" class="btn btn-danger btn-sm delete-btn">Request Delete</button>
                      </form>
                      @endif   
                      </div>               
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









