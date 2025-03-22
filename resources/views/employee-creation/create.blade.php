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
              <h4 class="card-title">New Employee</h4>
            </div>
            <div class="col-md-6 heading">
              <a href="{{ route('employee.index') }}" class="backicon"><i class="mdi mdi-backburger"></i></a>
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
          <form class="form-sample" action="{{ route('employee.store') }}" method="post">
            {{ csrf_field() }}
            <div class="row">
              <div class="col-md-12">
                <div class="form-group row">
                  <label class="col-sm-2 col-form-label required">Employee Code</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" name="employee_code" value="{{$invoice_no}}"   readonly/>
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group row">
                  <label class="col-sm-2 col-form-label required">Name</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" name="name" required />
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group row">
                  <label class="col-sm-2 col-form-label required">Email</label>
                  <div class="col-sm-9">
                    <input type="email" class="form-control" name="email"  />
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group row">
                  <label class="col-sm-2 col-form-label required">Contact Number</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" name="contact_number"  />
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group row">
                  <label class="col-sm-2 col-form-label required">Designation</label>
                  <div class="col-sm-9">
                    <select class="form-control" name="designation_id" required>
                      <option value="">Select Designation</option>
                      @foreach($designations as $designation)
                        <option value="{{ $designation->id }}">{{ $designation->designation }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <div class="submitbutton">
              <button type="submit" class="btn btn-primary mb-2 submit">Submit<i class="fas fa-save"></i></button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
