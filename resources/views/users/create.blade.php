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
              <a href="{{ route('users.index') }}" class="backicon"><i class="mdi mdi-backburger"></i></a>
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
          <form action="{{ route('users.store') }}" method="POST">
        @csrf
        <div class="col-md-6">
            <label for="name" class="form-label required" >Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="col-md-6">
            <label for="email" class="form-label required">Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="col-md-6">
            <label for="password" class="form-label required">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="col-md-6">
            <label for="password_confirmation" class="form-label required" >Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>

        <div class="col-md-6">
            <label for="designation_id" class="form-label required">Designation</label>
            <select name="designation_id" class="form-control" >
                <option value="">Select Designation</option>
                @foreach($designations as $designation)
                    <option value="{{ $designation->id }}">{{ $designation->designation }}</option>
                @endforeach
            </select>
        </div>

    <br>
            <div class="submitbutton">
              <button type="submit" class="btn btn-primary mb-4 submit">Submit<i class="fas fa-save"></i></button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('script')

@endsection
