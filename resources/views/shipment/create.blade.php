
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
    let timeInput = document.querySelector('input[name="time"]');

    timeInput.addEventListener("focus", function() {
        let currentTime = new Date().toLocaleTimeString("en-US", { 
            timeZone: "Africa/Dar_es_Salaam",
            hour: "2-digit",
            minute: "2-digit",
            second: "2-digit",
            hour12: false 
        });

        
        let formattedTime = currentTime.slice(0, 5); 

        timeInput.value = formattedTime;
    });
});

</script>