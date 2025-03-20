@extends('layouts.layout')
@section('content')
<style>
  .required:after {
    content: " *";
    color: red;
  }
</style>
<div class="main-panel">
  <div class="content-wrapper">
    <div class="col-12 grid-margin createtable">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <h4 class="card-title">Add Users</h4>
            </div>
            <div class="col-md-6 heading">
              <a href="{{ route('customer.index') }}" class="backicon"><i class="mdi mdi-backburger"></i></a>
            </div>
          </div>
          <div class="row">
            <br>
          </div>
          <div class="col-xl-12 col-md-12 col-sm-12 col-12">
            @if ($errors->any())
            <div class="alert alert-danger">
              <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div><br />
            @endif
          </div>
          <form action="{{ route('users.update', $user->id) }}" method="post" enctype="multipart/form-data" >
       
          {{ csrf_field() }}

        <div class="col-md-6">
            <label for="name" class="form-label">Name</label>
            <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
        </div>

        <div class="col-md-6">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
        </div>

        <div class="col-md-6">
            <label for="designation_id" class="form-label">Designation</label>
            <select name="designation_id" class="form-control" required>
                @foreach($designations as $designation)
                    <option value="{{ $designation->id }}" {{ $user->designation_id == $designation->id ? 'selected' : '' }}>
                        {{ $designation->designation }}
                    </option>
                @endforeach
            </select>
        </div>
<br>
        <button type="submit" class="btn btn-primary">Update </button>
    </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

