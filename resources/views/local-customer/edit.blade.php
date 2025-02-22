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
              <h4 class="card-title">Edit Local Customer</h4>
            </div>
            <div class="col-md-6 heading">
              <a href="{{ route('localcustomer.index') }}" class="backicon"><i class="mdi mdi-backburger"></i></a>
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
          <form class="form-sample" action="{{ route('localcustomer.update', $localcustomer->id) }}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="row">
              <div class="col-md-12">
                <div class="form-group row">
                  <label class="col-sm-2 col-form-label required">Customer Code</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" name="customer_code" value="{{ $localcustomer->customer_code }}" readonly />
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group row">
                  <label class="col-sm-2 col-form-label required">Customer Name</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" placeholder="" name="customer_name" required="true" value="{{ $localcustomer->customer_name }}" />
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group row">
                  <label class="col-sm-2 col-form-label required">Email</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" placeholder="E-mail" name="email" value="{{ $localcustomer->email }}" />
                  </div>
                </div>
              </div>
             
              <div class="col-md-12">
                <div class="form-group row">
                  <label class="col-sm-2 col-form-label required">Address</label>
                  <div class="col-sm-9">
                    <textarea class="form-control" name="address">{{ $localcustomer->address }}</textarea>
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group row">
                  <label class="col-sm-2 col-form-label required">State</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" name="state" value="{{ $localcustomer->state }}" />
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group row">
                  <label class="col-sm-2 col-form-label required">Country</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" name="country" value="{{ $localcustomer->country }}" />
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
