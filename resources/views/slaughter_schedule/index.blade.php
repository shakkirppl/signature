
                      
@extends('layouts.layout')
@section('content')

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
                  

                    <div class="table-responsive">
                        <table class="table table-hover" id="value-table">
                            <thead>
                                <tr>
                                <th>No</th>
                                    <th>Slaughter No</th>
                                    <th>Transportation Date</th>
                                    <th>Transportation Time</th>
                                    <th>Loading Time</th>
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
                                        <td>{{ $schedule->transportation_date }}</td>
                                        <td>{{ $schedule->transportation_time }}</td>
                                        <td>{{ $schedule->loading_time }}</td>
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

    <a href="{{ route('slaughter-schedule.edit', $schedule->id) }}" class="btn btn-warning">Edit</a>

<form action="{{ url('slaughter-schedule/delete/'.$schedule->id) }}" method="POST" style="display:inline;">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this schedule?');">Delete</button>
</form>
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

@endsection
