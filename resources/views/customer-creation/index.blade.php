@extends('layouts.layout')
@section('content')
<div class="main-panel">
  <div class="content-wrapper">
    <div class="col-12 grid-margin">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <h4 class="card-title">Customer List</h4>
            </div>
            <div class="col-md-6 text-right">
            <a href="{{ route('customer.create') }}" class="newicon"><i class="mdi mdi-new-box"></i></a>
            </div>
          </div>
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Customer Code</th>
                  <th>Customer Name</th>
                  <th>Email</th>
                  <th>Address</th>
                  <!-- <th>State</th> -->
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
                  <td>{{ $customer->country }}</td>
                  <td>{{ $customer->credit_limit_days }}</td>
                  <td>{{ $customer->opening_balance }}</td>
                  <td>{{ $customer->dr_cr }}</td>
                  <td>
                  <a href="{{ route('customer.edit', $customer->id) }}" class="btn btn-warning btn-sm">
                      <i class="mdi mdi-pencil"></i> Edit
                    </a>
                  
                    <a href="{{route('category.destroy', $customer->id) }}" 
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



