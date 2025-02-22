
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
           <label>Current Date</label>
           <input type="date" class="form-control" name="date" value="{{ $currentDate }}" readonly>
        </div>

        <div class="form-group">
            <label>Current Time</label>
            <input type="time" class="form-control" name="time" value="{{ $currentTime }}" readonly>
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
