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
              <h4 class="card-title">Edit Customer</h4>
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
          <form class="form-sample" action="{{ route('customer.update', $customer->id) }}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="row">
              <div class="col-md-12">
                <div class="form-group row">
                  <label class="col-sm-2 col-form-label required">Customer Code</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" name="customer_code" value="{{ $customer->customer_code }}" required="true" />
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group row">
                  <label class="col-sm-2 col-form-label required">Customer Name</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" placeholder="" name="customer_name" required="true" value="{{ $customer->customer_name }}" />
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group row">
                  <label class="col-sm-2 col-form-label required">Email</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" placeholder="E-mail" name="email" value="{{ $customer->email }}" />
                  </div>
                </div>
              </div>
             
              <div class="col-md-12">
                <div class="form-group row">
                  <label class="col-sm-2 col-form-label required">Address</label>
                  <div class="col-sm-9">
                    <textarea class="form-control" name="address">{{ $customer->address }}</textarea>
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group row">
                  <label class="col-sm-2 col-form-label required">State</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" name="state" value="{{ $customer->state }}" />
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group row">
                  <label class="col-sm-2 col-form-label required">Country</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" name="country" value="{{ $customer->country }}" />
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group row">
                  <label class="col-sm-2 col-form-label required">Credit Limit Days</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" name="credit_limit_days" value="{{ $customer->credit_limit_days }}" />
                  </div>
                </div>
              </div>
              <div class="col-md-12">
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">Opening Balance</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="opening_balance" value="{{ old('opening_balance', $customer->opening_balance) }}">
        </div>
    </div>
</div>

<div class="col-md-12">
    <div class="form-group row">
        <label class="col-sm-2 col-form-label"></label>
        <div class="col-sm-9">
            <label><input type="radio" name="dr_cr" value="Dr" {{ old('dr_cr', $customer->dr_cr) == 'Dr' ? 'checked' : '' }}> Dr</label>
            <label><input type="radio" name="dr_cr" value="Cr" {{ old('dr_cr', $customer->dr_cr) == 'Cr' ? 'checked' : '' }}> Cr</label>
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
