
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
          <h4 class="card-title">Supplier Delete Requests</h4>
            </div>
           
          </div>
          <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
            <table class="table table-bordered table-striped table-sm" style="font-size: 12px;">
              <thead style="background-color: #d6d6d6; color: #000;">
                <tr>
                  <th>No</th>
                 
                   <th>Code</th>
                   <th>Name</th>
                   <th>Email</th>
                   <th>Contact Number</th>
                   <th>Address</th>
                   <th>State</th>
                   <th>Country</th>
                  <th>Credit Limit Days</th>
                   <th>Opening Balance</th>
                   <th>Dr/Cr</th>
                   <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($suppliers as $index => $supplier)
                <tr>
                  <td>{{ $index + 1 }}</td>
                  <td>{{ $supplier->code }}</td>
            <td>{{ $supplier->name }}</td>
            <td>{{ $supplier->email }}</td>
            <td>{{ $supplier->contact_number }}</td>
            <td>{{ $supplier->address }}</td>
            <td>{{ $supplier->state }}</td>
            <td>{{ $supplier->country }}</td>
            <td>{{ $supplier->credit_limit_days }}</td>
            <td>{{ $supplier->opening_balance }}</td>
            <td>{{ $supplier->dr_cr }}</td>
                
                  <td>
                  <form action="{{ route('supplier.destroy', $supplier->id) }}" method="POST" style="display:inline;">
                     @csrf
                     @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this record?')">
                    <i class="mdi mdi-delete"></i> Approve Delete
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









