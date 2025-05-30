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
              <h4 class="card-title">Expense Vouchers List</h4>
            </div>
            <div class="col-md-6 text-right">
              <a href="{{ route('expensevoucher.create') }}" class="newicon"><i class="mdi mdi-new-box"></i></a>
            </div>
          </div>

          <!-- Search form -->
          <form method="GET" action="{{ route('expensevoucher.index') }}" class="form-inline mb-3 mt-3">
            <div class="input-group">
              <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Search ">
              <div class="input-group-append">
                <button type="submit" class="btn btn-sm btn-primary">Search</button>
              </div>
            </div>
          </form>

          <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
            <table class="table table-bordered table-striped table-sm" style="font-size: 12px;">
              <thead style="background-color: #d6d6d6; color: #000;">
                <tr>
                  <th>No</th>
                  <th>Code</th>
                  <th>Date</th>
                  <th>COA</th>
                  <th>Shipment No</th>
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
                  <td>{{ $voucher->shipment->shipment_no ?? '-' }}</td>
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
                    @if($user->designation_id == 1)
                      <a href="{{ route('expensevoucher.edit', $voucher->id) }}" class="btn btn-warning btn-sm">Edit</a>
                      <a href="{{ route('expensevoucher.destroy', $voucher->id) }}"
                         class="btn btn-danger btn-sm delete-btn"
                         onclick="return confirm('Are you sure you want to delete this record?')">
                        <i class="mdi mdi-delete"></i> Delete
                      </a>
                    @endif
                     @if($user->designation_id == 3 && $voucher->status != 3)
    <form method="POST" action="{{ route('expensevoucher.requestDelete', $voucher->id) }}" style="display:inline;">
      @csrf
      <button type="submit" class="btn btn-danger btn-sm"
              onclick="return confirm('Are you sure you want to request delete for this record?')">
        Request Delete
      </button>
    </form>
  @endif
                  </td>
                </tr>
                @endforeach
              </tbody>
               @php
                  $totalAmount = $vouchers->sum('amount');
                @endphp
                <tr style="background-color: #f0f0f0;">
                  <td colspan="5" class="text-right"><strong>Total</strong></td>
                  <td><strong>{{ number_format($totalAmount, 2) }}</strong></td>
                  <td colspan="3"></td>
                </tr>
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
    font-size: 30px;
  }
</style>
@endsection
