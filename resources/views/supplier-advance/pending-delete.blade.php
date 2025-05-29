@extends('layouts.layout')
@section('content')
<div class="main-panel">
  <div class="content-wrapper">
    <div class="card">
      <div class="card-body">
        <h4>Pending Delete Requests</h4>
        <table class="table table-bordered table-sm">
          <thead>
            <tr>
              <th>No</th>
              <th>Code</th>
              <th>Date</th>
              <th>Supplier</th>
              <th>Amount</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach($pendingDeletes as $key => $advance)
            <tr>
              <td>{{ $key + 1 }}</td>
              <td>{{ $advance->code }}</td>
              <td>{{ $advance->date }}</td>
              <td>{{ $advance->supplier->name ?? 'N/A' }}</td>
              <td>{{ number_format($advance->advance_amount, 2) }}</td>
              <td>
                <form action="{{ route('supplieradvance.approveDelete', $advance->id) }}" method="POST" onsubmit="return confirm('Approve delete?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-danger">Approve Delete</button>
                </form>
              </td>
            </tr>
            @endforeach
            @if($pendingDeletes->isEmpty())
            <tr><td colspan="6">No pending delete requests.</td></tr>
            @endif
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
