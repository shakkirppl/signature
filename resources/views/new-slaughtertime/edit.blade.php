@extends('layouts.layout')
@section('content')

<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-12 grid-margin createtable">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-6">
                            <h4 class="card-title">Edit Slaughter Timing</h4>
                        </div>
                        <div class="col-6 text-end">
                            <a href="{{ url('slaughter-schedules-index') }}" class="backicon"><i class="mdi mdi-backburger"></i></a>
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
                    
                    <form method="POST" action="{{ url('update-new-scheduletime/' . $timing->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="date">Slaughter Date:</label>
                                        <input type="date" class="form-control" id="date" name="date" value="{{ $timing->date }}">
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <label for="time">Starting Time:</label>
                                        <input type="text" class="form-control timepicker" id="time" name="time" value="{{ $timing->time }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Update Schedule</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    flatpickr(".timepicker", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "h:i K",
        time_24hr: false
    });
});
</script>

@endsection
