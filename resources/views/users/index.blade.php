@extends('layouts.layout')
@section('content')
<div class="main-panel">
  <div class="content-wrapper">
    <div class="col-12 grid-margin">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <h4 class="card-title">Users List</h4>
            </div>
            <div class="col-md-6 text-right">
            @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
            <a href="{{ route('users.create') }}" class="newicon"><i class="mdi mdi-new-box"></i></a>
            </div>
          </div>
          <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
            <table class="table table-bordered table-striped table-sm" style="font-size: 12px;">
              <thead style="background-color: #d6d6d6; color: #000;">
                <tr>
                <tr>
                <th>NO</th>
                <th>Name</th>
                <th>Email</th>
                <th>Password</th>
                <th>Designation</th>
                <th>Actions</th>
               </tr>
                  
                </tr>
              </thead>
              <tbody>
              @foreach($users as $index => $user)
            <tr>
              
                <td>{{ $index + 1 }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->visible_password }}</td>
                <td>{{ $user->designation ? $user->designation->designation : 'N/A' }}</td>
                <td>
                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-info btn-sm">Edit</a>
                <!-- <a href="#" class="btn btn-danger btn-sm">Delete</a> -->
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



