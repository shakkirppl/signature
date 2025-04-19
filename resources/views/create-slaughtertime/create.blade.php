@extends('layouts.layout')
@section('content')

<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-12 grid-margin createtable">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-6">
                            <h4 class="card-title">Create Slaughter Timing</h4>
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
                    
                    <form method="POST" action="{{ url('store-new-scheduletime') }}">
                        @csrf
                        
                    

                        <div class="card mb-3">
                            <div class="card-body">
                                <h5 class="card-title"> </h5>
                                <div class="row">
                                <div class="col-md-6">
                                <label for="date"> Slaughter  Date:</label>
                                <input type="date" class="form-control" id="date" name="date">
                                    </div>
                                    <div class="col-md-6">
                                       
                                    </div>
                                    <br>
                                    </div>
                                    <div class="row">
                                    <div class="col-md-6">
                                    <label for="time">Starting Time:</label>
                                    <input type="time" class="form-control timepicker" id="time" name="time" >
                                    </div>
                                    <!-- <div class="col-md-6">
                                    <label for="ending_time_of_slaughter">Ending Time:</label>
                                    <input type="text" class="form-control timepicker" id="ending_time_of_slaughter" name="ending_time_of_slaughter" >
                                    </div> -->
                                   
                                </div>
                            </div>
                        </div>

                        
                
                          
             

                     
                        
                        <button type="submit" class="btn btn-primary"> Schedule</button>
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
        dateFormat: "h:i K", // 12-hour format with AM/PM
        time_24hr: false,
        onOpen: function(selectedDates, dateStr, instance) {
            let currentTime = new Date().toLocaleTimeString("en-US", { 
                timeZone: "Africa/Dar_es_Salaam",
                hour: "2-digit",
                minute: "2-digit",
                hour12: true 
            });
            instance.setDate(currentTime, true);
        }
    });
});


</script>

@endsection
