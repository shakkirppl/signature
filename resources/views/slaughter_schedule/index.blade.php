
                      
@extends('layouts.layout')
@section('content')
@php
    $user = Auth::user();
@endphp
<div class="main-panel">
     <div class="content-wrapper">
         <div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
                    <div class="card-body">
                       <div class="row">
                           <div class="col-6 col-md-6 col-sm-6 col-xs-12" >
                                <h4 class="card-title">Slaughter Schedule List</h4>
                            </div>
                            <div class="col-6 col-md-6 col-sm-6 col-xs-12  heading" style="text-align:end;">
                            <a href="{{ url('slaughter-schedule-create') }}" class="newicon"><i class="mdi mdi-new-box"></i></a>
                            </div>
                  

                            <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
            <table class="table table-bordered table-striped table-sm" style="font-size: 12px;">
              <thead style="background-color: #d6d6d6; color: #000;">
                                <tr>
                                <th>No</th>
                                    <th>Slaughter No</th>
                                    <th>Loading Start Date</th>
                                    <th>Loading End Date</th>
                                    <th>Loading Start Time</th>
                                    <th>Loading End Time</th>
                                    <th>Transportation Date</th>
                                    <th>Transportation Time</th>
                                  
                                    <th>Airport Time</th>
                                    <th>Airline</th>
                                    <th>Airline Number</th>
                                    <th>Airline Date</th>
                                    <th>Slaughter Start Date</th>
                                    <th>Slaughter End Date</th>
                                    <th> SlaughterStart Time</th>
                                    <th> SlaughterEnd Time</th>
                                    
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($schedules as $key => $schedule)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $schedule->slaughter_no }}</td>
                                        <td>{{ $schedule->loading_start_date }}</td>
                                        <td>{{ $schedule->loading_end_date }}</td>
                                        <td>{{ $schedule->loading_time }}</td>
                                        <td>{{ $schedule->loading_end_time }}</td>
                                     
                                        <td>{{ $schedule->transportation_date }}</td>
                                        <td>{{ $schedule->transportation_time }}</td>
                                       
                                        <td>{{ $schedule->airport_time }}</td>
                                        <td>{{ $schedule->airline_name }}</td>
                                        <td>{{ $schedule->airline_number }}</td>
                                        <td>{{ $schedule->airline_date }}</td>
                                        <td>{{ $schedule->slaughter_date }}</td>
                                        <td>{{ $schedule->slaughter_end_date }}</td>
                                        <td>{{ $schedule->starting_time_of_slaughter }}</td>
                                        <td>{{ $schedule->ending_time_of_slaughter }}</td>
                                        
                                        <td>
    <a href="{{ route('slaughter.view-products', $schedule->id) }}" class="btn btn-info btn-sm">
        View 
    </a>
    <a href="{{ route('slaughter-schedule.print', $schedule->id) }}" class="btn btn-primary btn-sm"> <i class="mdi mdi-printer"></i>Print </a>

    @if($user->designation_id == 1)
    <a href="{{ route('slaughter-schedule.edit', $schedule->id) }}" class="btn btn-warning btn-sm">Edit</a>

    <a href="{{ route('slaughter-schedule.destroy',  $schedule->id) }}" 
                                                    class="btn btn-danger btn-sm" 
                                                    onclick="return confirm('Are you sure you want to delete this record?')">
                                                     Delete
                                            </a>
@endif
</td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
  .table-responsive {
    overflow-x: auto;
  }
  .table th, .table td {
    padding: 5px;
    text-align: center;
  }
  .btn-sm {
    padding: 3px 6px;
    font-size: 10px;
  }
  .newicon i {
    font-size: 30px;}
</style>
@endsection
