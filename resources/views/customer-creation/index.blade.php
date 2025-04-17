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
        @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
</div>
@endif
          <div class="row">
            <div class="col-md-6">
              <h4 class="card-title">Customer List</h4>
            </div>
            <div class="col-md-6 text-right">
            <a href="{{ route('customer.create') }}" class="newicon"><i class="mdi mdi-new-box"></i></a>
            </div>
          </div>
          <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
            <table class="table table-bordered table-striped table-sm" style="font-size: 12px;">
              <thead style="background-color: #d6d6d6; color: #000;">
                <tr>
                  <th>No</th>
                  <th>Customer Code</th>
                  <th>Customer Name</th>
                  <th>Email</th>
                  <th>Address</th>
                  <th>Contact No</th>
                  <th>Country</th>
                  <th>Credit Limit Days</th>
                  <th>Opening Balance</th>
                  <th>Dr/Cr</th>
                  <th>Actions</th>
                  
                </tr>
              </thead>
              <tbody>
                @foreach ($customers as $index => $customer)
                <tr>
                  <td>{{ $index + 1 }}</td>
                  <td>{{ $customer->customer_code }}</td>
                  <td>{{ $customer->customer_name }}</td>
                  <td>{{ $customer->email }}</td>
                  <td>{{ $customer->address }}</td>
                  <!-- <td>{{ $customer->state }}</td> -->
                  <td>{{ $customer->contact_number }}</td>
                  <td>{{ $customer->country }}</td>
                  <td>{{ $customer->credit_limit_days }}</td>
                  <td>{{ $customer->opening_balance }}</td>
                  <td>{{ $customer->dr_cr }}</td>
                  <td>
                  @if($user->designation_id == 1) 
                  <a href="{{ route('customer.edit', $customer->id) }}" class="btn btn-warning btn-sm">
                      <i class="mdi mdi-pencil"></i> Edit
                    </a>
                  
                    <form action="{{ route('customer.destroy', $customer->id) }}" method="POST" style="display:inline;">
                     @csrf
                     @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this record?')">
                    <i class="mdi mdi-delete"></i> Delete
                   </button>
                 </form>
@endif

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



