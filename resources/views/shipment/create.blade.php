
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
                    <h4 class="card-title">create Shipmnet</h4>
                    <div class="col-md-6 heading">
                        <a href=" {{ route('shipment.index') }}" class="backicon"><i class="mdi mdi-backburger"></i></a>
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

                    <form action="{{ route('shipment.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
            <label for="code" class="">Shipment NO</label>
            <input type="text" class="form-control" id="shipment_no" name="shipment_no" value="{{$invoice_no}}" readonly>
         </div>

        <div class="form-group">
           <label> Date</label>
           <input type="date" class="form-control" name="date" value= "">
        </div>

        <div class="form-group">
            <label>Time</label>
            <input type="time" class="form-control" name="time" value="" >
        </div>

       

                   

                        <div class="submitbutton">
                        <button type="submit" class="btn btn-success">Save Shipment</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection
<script>
    document.addEventListener("DOMContentLoaded", function() {
        let dateInput = document.querySelector('input[name="date"]');
        let timeInput = document.querySelector('input[name="time"]');

        // Get current time in Tanzania timezone
        function getTanzaniaTime() {
            let options = { timeZone: "Africa/Dar_es_Salaam", hour12: false, hour: "2-digit", minute: "2-digit" };
            return new Intl.DateTimeFormat("en-GB", options).format(new Date());
        }

        // Set the date field to the current date
        let today = new Date().toISOString().split('T')[0]; 
        dateInput.value = today;

        // Set the time field to the current Tanzania time
        timeInput.value = getTanzaniaTime();

        // Update time on focus (if user clicks)
        timeInput.addEventListener("focus", function() {
            timeInput.value = getTanzaniaTime();
        });
    });
</script>
