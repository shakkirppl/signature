@extends('layouts.layout')
@section('content')
<style>
/* Ensure form fields are properly spaced and fit within 1024x768 */
.createtable .form-group {
    margin-bottom: 10px;
}

.createtable .form-control {
    height: 36px; /* Optimize for easier entry */
    font-size: 14px;
}

/* Adjust label widths */
.createtable .form-group label {
    font-size: 14px;
    text-align: right;
    padding-right: 5px;
}

/* Ensure table compatibility */
#componentTable {
    width: 100%;
    overflow-x: auto;
}

@media (max-width: 1024px) {
    .createtable .form-group label {
        font-size: 13px;
    }

    .createtable .form-control {
        font-size: 13px;
    }
}
</style>

<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-12 grid-margin createtable">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="card-title">Edit Bank Master</h4>
                        </div>
                        <div class="col-md-6 text-end">
                            <a href="{{ url('/bank-master') }}" class="backicon">
                                <i class="mdi mdi-backburger"></i>
                            </a>
                        </div>
                    </div>

                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('bank-master.update', $bankMaster->id) }}" method="POST">
                        @csrf
                        @method('POST')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label required">Code:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="code" value="{{ $bankMaster->code }}" required />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label required">Bank Name</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="bank_name" value="{{ $bankMaster->bank_name }}" required />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label required">Account Name:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="account_name" value="{{ $bankMaster->account_name }}" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label required">Currency</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" name="currency">
                                            <option value="USD" {{ $bankMaster->currency == 'USD' ? 'selected' : '' }}>USD</option>
                                            <option value="Shilling" {{ $bankMaster->currency == 'Shilling' ? 'selected' : '' }}>Shilling</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label required">Account No:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="account_no" value="{{ $bankMaster->account_no }}" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label required">GL</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="gl" value="{{ $bankMaster->gl }}" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label required">Account Type</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="type" value="{{ $bankMaster->type }}" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="submitbutton">
                            <button type="submit" class="btn btn-primary">Update <i class="fas fa-save"></i></button>
                            <a href="{{ route('bank-master.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
