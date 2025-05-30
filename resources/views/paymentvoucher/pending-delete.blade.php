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
              <h4 class="card-title">Pending Deletion Vouchers</h4>
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
                  <th>Description</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($vouchers as $index => $voucher)
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
                  <td>{{ $voucher->description ?? 'N/A' }}</td>
                  <td>
                    <form action="{{ route('admin.paymentvoucher.destroy', $voucher->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to approve permanent deletion?');">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-danger btn-sm"><i class="mdi mdi-delete"></i> Approve Delete</button>
                    </form>
                  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="10">No vouchers pending deletion.</td>
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
</style>
@endsection
