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
                        <div class="col-6">
                            <h4 class="card-title">Bank Master</h4>
                        </div>
                        <div class="col-6 text-end">
                            <a href="{{ url('bank-master-create') }}" class="backicon">
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

                    <form class="form-sample" action="{{ url('bank-master-store') }}" method="post">
                        {{ csrf_field() }}

                        <div class="row">
                            <!-- Code Field -->
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label required">Code:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" placeholder="Enter code" name="code" required />
                                    </div>
                                </div>
                            </div>

                            <!-- Bank Name -->
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label required">Bank Name:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" placeholder="Enter bank name" name="bank_name" required />
                                    </div>
                                </div>
                            </div>

                            <!-- Account Name -->
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label required">Account Name:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" placeholder="Enter account name" name="account_name" />
                                    </div>
                                </div>
                            </div>

                            <!-- Currency -->
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label required">Currency:</label>
                                    <div class="col-sm-9">
                                        <select class="form-control" name="currency">
                                            <option value="USD" selected>USD</option>
                                            <option value="Shilling">Shilling</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Account No -->
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label required">Account No:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" placeholder="Enter account number" name="account_no" />
                                    </div>
                                </div>
                            </div>

                            <!-- GL -->
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label required">GL:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="gl" />
                                    </div>
                                </div>
                            </div>

                            <!-- Account Type -->
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label required">Account Type:</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" placeholder="Enter type" name="type" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="text-center mt-3">
                            <button type="submit" class="btn btn-primary">Submit <i class="fas fa-save"></i></button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection
