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
              <h4 class="card-title">Local Customer List</h4>
            </div>
            <div class="col-md-6 text-right">
            <a href="{{ route('localcustomer.create') }}" class="newicon"><i class="mdi mdi-new-box"></i></a>
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
                  <th>State</th>
                  <th>Country</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($localcustomers as $index => $localcustomer)
                <tr>
                  <td>{{ $index + 1 }}</td>
                  <td>{{ $localcustomer->customer_code }}</td>
                  <td>{{ $localcustomer->customer_name }}</td>
                  <td>{{ $localcustomer->email }}</td>
                  <td>{{ $localcustomer->address }}</td>
                  <td>{{ $localcustomer->state }}</td>
                  <td>{{ $localcustomer->country }}</td>
                  <td>
                  @if($user->designation_id == 1) 
                  <a href="{{ route('localcustomer.edit', $localcustomer->id) }}" class="btn btn-warning btn-sm">
                      <i class="mdi mdi-pencil"></i> Edit
                    </a>
                  
                    <a href="{{route('localcustomer.destroy', $localcustomer->id) }}" 
                                                    class="btn btn-danger btn-sm delete-btn" 
                                                    onclick="return confirm('Are you sure you want to delete this record?')">
                                                    <i class="mdi mdi-delete"></i> Delete
                                            </a>
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



