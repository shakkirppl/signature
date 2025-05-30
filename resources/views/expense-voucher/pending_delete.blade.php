@extends('layouts.layout')
@section('content')
<div class="main-panel">
  <div class="content-wrapper">
    <div class="col-12 grid-margin">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Pending Delete Requests</h4>
          <div class="table-responsive">
            <table class="table table-bordered table-striped table-sm">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Code</th>
                  <th>Date</th>
                  <th>Amount</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($vouchers as $index => $voucher)
                <tr>
                  <td>{{ $index + 1 }}</td>
                  <td>{{ $voucher->code }}</td>
                  <td>{{ \Carbon\Carbon::parse($voucher->date)->format('d-m-Y') }}</td>
                  <td>{{ number_format($voucher->amount, 2) }}</td>
                  <td>
                    <form method="POST" action="{{ route('expensevoucher.approveDelete', $voucher->id) }}">
                      @csrf
                      @method('DELETE')
                      <button class="btn btn-danger btn-sm"
                              onclick="return confirm('Are you sure you want to permanently delete this?')">
                        Approve Delete
                      </button>
                    </form>
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
