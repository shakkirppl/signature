@extends('layouts.layout')
@section('content')

<div class="main-panel">
  <div class="content-wrapper">
    <div class="col-12 grid-margin createtable">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Edit Employee</h4>

          @if ($errors->any())
            <div class="alert alert-danger">
              <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form class="form-sample" action="{{ route('employee.update', $employee->id) }}" method="post">
            @csrf
            <div class="row">
              <div class="col-md-12">
                <div class="form-group row">
                  <label class="col-sm-2 col-form-label required">Employee Code</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" name="employee_code" value="{{ $employee->employee_code }}" required />
                  </div>
                </div>
              </div>
              
              <div class="col-md-12">
                <div class="form-group row">
                  <label class="col-sm-2 col-form-label required">Name</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" name="name" value="{{ $employee->name }}" required />
                  </div>
                </div>
              </div>

              <div class="col-md-12">
                <div class="form-group row">
                  <label class="col-sm-2 col-form-label required">Email</label>
                  <div class="col-sm-9">
                    <input type="email" class="form-control" name="email" value="{{ $employee->email }}" />
                  </div>
                </div>
              </div>

              <div class="col-md-12">
                <div class="form-group row">
                  <label class="col-sm-2 col-form-label required">Contact Number</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" name="contact_number" value="{{ $employee->contact_number }}" />
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
                        <option value="{{ $designation->id }}" {{ $employee->designation_id == $designation->id ? 'selected' : '' }}>
                          {{ $designation->designation }}
                        </option>
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>
            </div>

            <div class="submitbutton">
              <button type="submit" class="btn btn-primary mb-2 submit">Update<i class="fas fa-save"></i></button>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
