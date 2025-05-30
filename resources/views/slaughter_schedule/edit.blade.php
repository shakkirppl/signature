@extends('layouts.layout')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-12 grid-margin createtable">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Edit Slaughter Schedule</h4>
                    <div class="col-6 text-end">
                            <a href="{{ url('slaughter-schedules-index') }}" class="backicon"><i class="mdi mdi-backburger"></i></a>
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

                     <form action="{{ route('slaughter-schedule.update', $schedule->id) }}" method="POST">
        @csrf
     

        <div class="row">
            <!-- Slaughter Number -->
            <div class="col-md-4">
                <label for="slaughter_no">Slaughter No:</label>
                <input type="text" class="form-control" id="slaughter_no" name="slaughter_no"
                    value="{{ old('slaughter_no', $schedule->slaughter_no) }}" readonly>
            </div>

            <!-- Date -->
            <div class="col-md-4">
                <label for="date">Date:</label>
                <input type="date" class="form-control" id="date" name="date"
                    value="{{ old('date', $schedule->date) }}" required>
            </div>
        </div><br>
        <h5 class="card-title">Slaughter Timing</h5>
        <div class="row mt-3">
        <div class="col-md-4">
                                <label for="slaughter_date"> Slaughter Start Date:</label>
                                <input type="date" class="form-control" id="slaughter_date" name="slaughter_date" 
                                value="{{ old('slaughter_date', $schedule->slaughter_date) }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="slaughter_end_date">Slaughter End Date: :</label>
                                        <input type="date" class="form-control " id="slaughter_end_date" name="slaughter_end_date" 
                                        value="{{ old('slaughter_end_date', $schedule->slaughter_end_date) }}">
                                    </div>
                                    </div>
                                    <div class="row">
                                    <div class="col-md-4">
                                    <label for="starting_time_of_slaughter">Starting Time:</label>
                                    <input type="text" class="form-control timepicker" id="starting_time_of_slaughter" name="starting_time_of_slaughter" 
                                    value="{{ old('starting_time_of_slaughter', $schedule->starting_time_of_slaughter) }}">
                                    </div>
                                    <div class="col-md-4">
                                    <label for="ending_time_of_slaughter">Ending Time:</label>
                                    <input type="text" class="form-control timepicker" id="ending_time_of_slaughter" name="ending_time_of_slaughter" 
                                    value="{{ old('ending_time_of_slaughter', $schedule->ending_time_of_slaughter) }}">
                                    </div>
                                   
                                </div>
                                <br>
        <h5 class="card-title">Loading Details</h5>          

        <div class="row mt-3">
          <div class="col-md-4">
                 <label for="loading_start_date">  Loading Start Date:</label>
                    <input type="date" class="form-control" id="loading_start_date" name="loading_start_date" 
                          value="{{ old('loading_start_date', $schedule->loading_start_date) }}">
            </div>
                <div class="col-md-4">
                <label for="loading_end_date">  Loading End Date::</label>
                 <input type="date" class="form-control " id="loading_end_date" name="loading_end_date" 
                    value="{{ old('loading_end_date', $schedule->loading_end_date) }}">
            </div>
            </div>
            <div class="row">
            <div class="col-md-4">
                <label for="loading_time">Loading Start Time:</label>
                <input type="text" class="form-control timepicker" id="loading_time" name="loading_time"
                    value="{{ old('loading_time', $schedule->loading_time) }}" >
            </div>

            <!-- Airport Time -->
            <div class="col-md-4">
                <label for="loading_end_time">Loading End Time:</label>
                <input type="text" class="form-control timepicker" id="loading_end_time" name="loading_end_time"
                    value="{{ old('loading_end_time', $schedule->loading_end_time) }}" >
            </div>
        </div>



        <br>
        <h5 class="card-title">Transportation Details</h5>          

        <div class="row mt-3">
          <div class="col-md-4">
                 <label for="airline_date"> Transportation Date:</label>
                    <input type="date" class="form-control" id="transportation_date" name="transportation_date" 
                          value="{{ old('transportation_date', $schedule->transportation_date) }}">
            </div>
                <div class="col-md-4">
                <label for="airline_time"> Transportation Time:</label>
                 <input type="text" class="form-control timepicker" id="transportation_time" name="transportation_time" 
                    value="{{ old('transportation_time', $schedule->transportation_time) }}">
            </div>
            </div>
            <div class="row">
           
         
            <!-- Airport Time -->
           
        </div>
<br>
<br>
<h5 class="card-title">Airline Details</h5>
        <div class="row mt-3">
            <!-- Airline Name -->
            <div class="col-md-4">
                <label for="airline_name">Airline Name:</label>
                <select class="form-control" id="airline_name" name="airline_name">
        <option value="Qatar Airways" {{ $schedule->airline_name == 'Qatar Airways' ? 'selected' : '' }}>Qatar Airways</option>
        <option value="Ethiopian Airlines" {{ $schedule->airline_name == 'Ethiopian Airlines' ? 'selected' : '' }}>Ethiopian Airlines</option>
    </select>
              
            </div>

            <!-- Airline Flight Number -->
            <div class="col-md-4">
                <label for="airline_number">Flight Number:</label>
                <select class="form-control" id="airline_number" name="airline_number">
                    <option value="QR 1476" {{ $schedule->airline_number == 'QR 1476' ? 'selected' : '' }}>QR 1476</option>
                     <option value="ET 814" {{ $schedule->airline_number == 'ET 814' ? 'selected' : '' }}>ET 814</option>
    </select>
               
            </div>
        </div>
       
        <div class="row mt-3">
            <!-- Airline Date -->
            <div class="col-md-4">
                <label for="airline_date">Airline Date:</label>
                <input type="date" class="form-control" id="airline_date" name="airline_date"
                    value="{{ old('airline_date', $schedule->airline_date) }}" >
            </div>

            <!-- Airline Time -->
            <div class="col-md-4">
                <label for="airline_time">Airline Time:</label>
                <input type="text" class="form-control timepicker" id="airline_time" name="airline_time"
                    value="{{ old('airline_time', $schedule->airline_time) }}" >
            </div>
           
        </div>
        <div class="col-md-4">
                <label for="airport_time">Airport Cutoff Time:</label>
                <input type="text" class="form-control timepicker" id="airport_time" name="airport_time"
                    value="{{ old('airport_time', $schedule->airport_time) }}" >
            </div>
<br>


       
        <div class="card mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Products</h5>
                                <div id="product-list">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="col-md-4">
                                        @foreach ($schedule->details as $detail)
                                      <div class="d-flex align-items-center mb-2 product-item">
                                      <div class="col-md-10">
                                         <select name="products[]" class="form-control product-select" >
                                            <option value="">Select Product</option>
                                                @foreach($products as $product)
                                            <option value="{{ $product->id }}" 
                                             {{ $detail->product_id == $product->id ? 'selected' : '' }}>
                                            {{ $product->product_name }}
                                            </option>
                                              @endforeach
        
                                          </select>

                                        </div>
                                        <button type="button" class="btn btn-danger remove-product ms-2">X</button>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="text-end">
                                    <button type="button" class="btn btn-success" id="add-product">Add Product</button>
                                </div>
                            </div>
                        </div>
        <div class="row mt-4">
            <div class="col-md-12 text-end">
                <button type="submit" class="btn btn-primary">Update Schedule</button>
            </div>
        </div>
    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
  document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("add-product").addEventListener("click", function () {
        let productList = document.getElementById("product-list");
        let newProduct = `
            <div class="row product-item align-items-center mb-2">
                <div class="col-md-3">
                    <select name="products[]" class="form-control product-select" >
                        <option value="">Select Product</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->product_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 text-end">
                    <button type="button" class="btn btn-danger remove-product">X</button>
                </div>
            </div>
        `;
        productList.insertAdjacentHTML("beforeend", newProduct);
    });

    document.addEventListener("click", function (e) {
        if (e.target.classList.contains("remove-product")) {
            e.target.closest(".product-item").remove();
        }
    });
});

</script>
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
