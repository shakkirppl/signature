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
                    <h4 class="card-title">Edit Chilling Room Temperature & Humidity Log</h4>
                    <div class="col-md-6 heading">
                        <a href="{{ route('chilling-room.index') }}" class="backicon"><i class="mdi mdi-backburger"></i></a>
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

                    <form action="{{ route('chilling-room.update', $record->id) }}" method="POST">
                        @csrf
                       
                        <div class="row">
                            <!-- First Section -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date" class="required">Date</label>
                                    <input type="date" class="form-control" id="date" name="date" value="{{ $record->date }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="initial_carcass_temp">Initial carcass temp</label>
                                    <input type="text" class="form-control" id="initial_carcass_temp" name="initial_carcass_temp" value="{{ $record->initial_carcass_temp }}">
                                </div>

                                <div class="form-group">
                                    <label for="exit_temp_carcass" class="required">Exit temp of carcasses</label>
                                    <input type="text" class="form-control" id="exit_temp_carcass" name="exit_temp_carcass" value="{{ $record->exit_temp_carcass }}" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="time" class="required">Time of entry</label>
                                    <input type="text" class="form-control timepicker" id="time" name="time" value="{{ $record->time }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="chiller_temp_humidity" class="required">Chiller temp & humidity every 2-4 hours</label>
                                    <input type="text" class="form-control" id="chiller_temp_humidity" name="chiller_temp_humidity" value="{{ $record->chiller_temp_humidity }}" required>
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

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script>
    document.addEventListener("DOMContentLoaded", function () {
        flatpickr(".timepicker", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "h:i K",
            time_24hr: false
        });
    });
</script>

@endsection
