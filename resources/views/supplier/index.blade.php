


@extends('layouts.layout')
@section('content')
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
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th>No</th>
                 
                   <th>Code</th>
                   <th>Name</th>
                   <th>Email</th>
                   <th>Address</th>
                   <th>Contact Number</th>
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
            <td>{{ $supplier->address }}</td>
            <td>{{ $supplier->contact_number }}</td>
            <td>{{ $supplier->credit_limit_days }}</td>
            <td>{{ $supplier->opening_balance }}</td>
            <td>{{ $supplier->dr_cr }}</td>
                       
                  <td>
                  <a href="{{ route('supplier.edit', $supplier->id) }}" class="btn btn-warning btn-sm">Edit</a>

                   
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









