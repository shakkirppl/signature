@extends('layouts.layout')
@section('content')
<style>
/* Adjust spacing between table rows */
#componentTable tbody tr {
    line-height: 1.2em;
    margin-bottom: 0.3em;
}
</style>

<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-12 grid-margin createtable">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 col-md-6 col-sm-6 col-xs-12">
                            <h4 class="card-title">Edit Bank Master</h4>
                        </div>
                        <div class="col-6 col-md-6 col-sm-6 col-xs-12 heading" style="text-align:end;">
                            <a href="{{ url('/bank-master') }}" class="backicon"><i class="mdi mdi-backburger"></i></a>
                        </div>
                    </div>

                    <form class="form-sample" action="{{ route('bank-master.update', $bankMaster->id) }}" method="POST">
                        @csrf
                        @method('PUT')  <!-- To indicate that it's an update request -->
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label required">Code:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" placeholder="Enter the code" name="code" value="{{ $bankMaster->code }}" required />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label required">Bank Name</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" placeholder="Enter Your Bank Name" name="bank_name" value="{{ $bankMaster->bank_name }}" required />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label required">Currency</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" placeholder="" name="currency" value="{{ $bankMaster->currency }}" required />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label required">Type</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" name="type" required>
                                            <option value="">Select Type</option>
                                            <option value="cash" {{ $bankMaster->type == 'cash' ? 'selected' : '' }}>Cash</option>
                                            <option value="bank" {{ $bankMaster->type == 'bank' ? 'selected' : '' }}>Bank</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label required">GL</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" placeholder="" name="gl" value="{{ $bankMaster->gl }}" required />
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="submitbutton">
                            <button type="submit" class="btn btn-primary mb-2 submit">Update <i class="fas fa-save"></i></button>
                            <a href="{{ route('bank-master.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
