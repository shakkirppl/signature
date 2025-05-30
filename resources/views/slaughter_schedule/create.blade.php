@extends('layouts.layout')
@section('content')

<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-12 grid-margin createtable">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-6">
                            <h4 class="card-title">Slaughter Schedule</h4>
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
                    
                    <form method="POST" action="{{ url('slaughter-schedule') }}">
                        @csrf
                        
                        <!-- General Information -->
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="slaughter_no">Slaughter No:</label>
                                        <input type="text" class="form-control" id="slaughter_no" name="slaughter_no"  value="{{$invoice_no}}" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="date">Date:</label>
                                        <input type="date" class="form-control" id="date" name="date" >
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Transportation Details -->

                   


                        <div class="card mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Slaughter Timing</h5>
                                <div class="row">
                                <div class="col-md-6">
                                <label for="slaughter_date"> Slaughter Start Date:</label>
                                <input type="date" class="form-control" id="slaughter_date" name="slaughter_date">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="slaughter_end_date">Slaughter End Date: :</label>
                                        <input type="date" class="form-control " id="slaughter_end_date" name="slaughter_end_date" >
                                    </div>
                                    </div>
                                    <div class="row">
                                    <div class="col-md-6">
                                    <label for="starting_time_of_slaughter">Starting Time:</label>
                                    <input type="text" class="form-control timepicker" id="starting_time_of_slaughter" name="starting_time_of_slaughter" >
                                    </div>
                                    <div class="col-md-6">
                                    <label for="ending_time_of_slaughter">Ending Time:</label>
                                    <input type="text" class="form-control timepicker" id="ending_time_of_slaughter" name="ending_time_of_slaughter" >
                                    </div>
                                   
                                </div>
                            </div>
                        </div>

                        <br>
                        <div class="card mb-3">
                        <div class="card-body">
        <h5 class="card-title">Loading Details</h5>          

        <div class="row mt-3">
          <div class="col-md-6">
                 <label for="loading_start_date"> Loading Start Date:</label>
                    <input type="date" class="form-control" id="loading_start_date" name="loading_start_date" 
                          >
            </div>
                <div class="col-md-6">
                <label for="loading_end_date"> Loading End Date:</label>
                 <input type="date" class="form-control " id="loading_end_date" name="loading_end_date" 
                    >
            </div>
            </div>
            <div class="row">
            <div class="col-md-6">
                <label for="loading_time">Loading StartTime:</label>
                <input type="text" class="form-control timepicker" id="loading_time" name="loading_time"
                    value="" >
            </div>

            <!-- Airport Time -->
            <div class="col-md-6">
                <label for="loading_end_time">Loading End Time:</label>
                <input type="text" class="form-control timepicker" id="loading_end_time" name="loading_end_time"
                    >
            </div>
        </div>
</div>
</div>
<br>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Transportation Details</h5>
                                <div class="row">
                                <div class="col-md-6">
                                        <label for="airline_date"> Transportation Date:</label>
                                        <input type="date" class="form-control" id="transportation_date" name="transportation_date" >
                                    </div>
                                    <div class="col-md-6">
                                        <label for="airline_time"> Transportation Time:</label>
                                        <input type="text" class="form-control timepicker" id="transportation_time" name="transportation_time" >
                                    </div>
                                    </div>
                                    <div class="row">
                                    
                                   
                                   
                                   
                                </div>
                            </div>
                        </div>

                        <!-- Airline Details -->
                        <div class="card mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Airline Details</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="airline_name">Airline Name:</label>
                                        <select class="form-control" id="airline_name" name="airline_name">
                                          <option value="Qatar Airways" selected>Qatar Airways</option>
                                              <option value="Ethiopian Airlines">Ethiopian Airlines</option>
                                         </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="airline_number">Flight Number:</label>
                                        <select class="form-control" id="airline_number" name="airline_number">
                                          <option value="QR 1476" selected>QR 1476</option>
                                              <option value="ET 814">ET 814</option>
                                         </select>
                                    </div>
                                    </div>
                                    <div class="row"><br>
                                    <div class="col-md-6">
                                        <label for="airline_date">Date:</label>
                                        <input type="date" class="form-control" id="airline_date" name="airline_date" >
                                    </div>
                                    <div class="col-md-6">
                                        <label for="airline_time">Time:</label>
                                        <input type="text" class="form-control timepicker" id="airline_time" name="airline_time" >
                                    </div>
                                    <div class="col-md-6">
                                        <label for="airport_time">Airport Cutoff Time:</label>
                                        <input type="text" class="form-control timepicker" id="airport_time" name="airport_time" >
                                    </div>
                                </div>
                            </div>
                        </div>
                          
                 <!-- Products Selection -->
                 <div class="card mb-3">
                            <div class="card-body">
                                <h5 class="card-title">Products</h5>
                                <div id="product-list">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="col-md-6">
                                        <select name="products[]" class="form-control" >
                                            <option value="">Select Product</option>
                                              @foreach($products as $product)
                                             <option value="{{ $product->id }}">{{ $product->product_name }}</option>
                                                @endforeach
                                        </select>


                                        </div>
                                        <button type="button" class="btn btn-danger remove-product ms-2">X</button>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <button type="button" class="btn btn-success" id="add-product">Add Product</button>
                                </div>
                            </div>
                        </div>

                     
                        
                        <button type="submit" class="btn btn-primary">Create Schedule</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
   document.getElementById('add-product').addEventListener('click', function() {
    let productList = document.getElementById('product-list');
    
    let newProduct = document.createElement('div');
    newProduct.classList.add('d-flex', 'align-items-center', 'mb-2', 'product-item');
    newProduct.innerHTML = `
        <div class="col-md-6">
            <select name="products[]" class="form-control" >
                <option value="">Select Product</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}">{{ $product->product_name }}</option>
                @endforeach
            </select>
        </div>
        <button type="button" class="btn btn-danger remove-product ms-2">X</button>
    `;

    productList.appendChild(newProduct);
});

// Event delegation for dynamically added elements
document.getElementById('product-list').addEventListener('click', function(event) {
    if (event.target.classList.contains('remove-product')) {
        event.target.closest('.product-item').remove();
    }
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
<script>
document.addEventListener('DOMContentLoaded', function () {
    const dateInput = document.getElementById('date');
    let today = new Date().toISOString().split('T')[0];
    dateInput.value = today;
});
</script>

@endsection
