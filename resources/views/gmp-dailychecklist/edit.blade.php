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
                    <h4 class="card-title">Edit GMP Daily Checklist</h4>
                    <div class="col-md-6 heading">
                        <a href="{{ route('gmp.index') }}" class="backicon"><i class="mdi mdi-backburger"></i></a>
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

                    <form action="{{ route('gmp.update', $record->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date" class="required">Date</label>
                                    <input type="date" class="form-control" name="date" value="{{ $record->date }}" required>
                                </div>
                                <div class="form-group">
                                    <label class="required">Facility cleanliness</label>
                                    <input type="text" class="form-control" name="facility_cleanliness" value="{{ $record->facility_cleanliness }}" required>
                                </div>
                                <div class="form-group">
                                    <label class="required">Pest control</label>
                                    <input type="text" class="form-control" name="pest_control" value="{{ $record->pest_control }}" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Personal hygiene</label>
                                    <input type="text" class="form-control" name="personal_hygiene" value="{{ $record->personal_hygiene }}">
                                </div>
                                <div class="form-group">
                                    <label class="required">Equipment sanitation</label>
                                    <input type="text" class="form-control" name="equipment_sanitation" value="{{ $record->equipment_sanitation }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="submitbutton">
                            <button type="submit" class="btn btn-primary mb-2 submit">Update <i class="fas fa-save"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
