
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
              <h4 class="card-title">Supplier List</h4>
            </div>
            <div class="col-md-6 text-right">
            <a href="{{ route('supplier.create') }}" class="newicon"><i class="mdi mdi-new-box"></i></a>
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
            @if($user->designation_id == 1)      
                  <td>
                  <a href="{{ route('supplier.edit', $supplier->id) }}" class="btn btn-warning btn-sm">Edit</a>
                  <form action="{{ route('supplier.destroy', $supplier->id) }}" method="POST" style="display:inline;">
                     @csrf
                     @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this record?')">
                    <i class="mdi mdi-delete"></i> Delete
                   </button>
                 </form>

                   
                 </td>
                 @endif
                 
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









