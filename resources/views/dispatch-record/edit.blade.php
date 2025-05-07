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
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Edit Dispatch Record</h4>
                    <div class="col-md-6 heading">
                        <a href="{{ route('dispatch-record.index') }}" class="backicon"><i class="mdi mdi-backburger"></i></a>
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('dispatch-record.update', $dispatch->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- First Section -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date" class="required">Date</label>
                                    <input type="date" class="form-control" id="date" name="date" value="{{ old('date', $dispatch->date) }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="no_of_carcasses">No. of carcasses dispatched</label>
                                    <input type="text" class="form-control" id="no_of_carcasses" name="no_of_carcasses" value="{{ old('no_of_carcasses', $dispatch->no_of_carcasses) }}">
                                </div>
                                <div class="form-group">
                                    <label for="customer_name" class="required">Customer name/export destination</label>
                                    <input type="text" class="form-control" id="customer_name" name="customer_name" value="{{ old('customer_name', $dispatch->customer_name) }}" required>
                                </div>  
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="dispatch_temperature">Dispatch temperature</label>
                                    <input type="text" class="form-control" id="dispatch_temperature" name="dispatch_temperature" value="{{ old('dispatch_temperature', $dispatch->dispatch_temperature) }}">
                                </div>
                                <div class="form-group">
                                    <label for="packaging_material_used">Packaging material used</label>
                                    <input type="text" class="form-control" id="packaging_material_used" name="packaging_material_used" value="{{ old('packaging_material_used', $dispatch->packaging_material_used) }}">
                                </div>
                                <div class="form-group">
                                    <label for="comments">Comments</label>
                                    <input type="text" class="form-control" id="comments" name="comments" value="{{ old('comments', $dispatch->comments) }}">
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
