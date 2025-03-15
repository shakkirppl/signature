@extends('layouts.layout')
@section('content')

<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-12 grid-margin createtable">
            <div class="card">
                <div class="card-body">
                <div class="col-6 col-md-6 col-sm-6 col-xs-12  heading" style="text-align:end;">
                    <a href="{{ route('deathanimal.create') }}" class="newicon"><i class="mdi mdi-new-box"></i></a>
                    </div>

                    <h4 class="card-title">Death Animal Records</h4>
                    <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
            <table class="table table-bordered table-striped table-sm" style="font-size: 12px;">
              <thead style="background-color: #d6d6d6; color: #000;">
                        
                            <tr>
                                <th>Date</th>
                                <th>Shipment No</th>
                                <th>Supplier</th>
                                <th>Inspection No</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($deathAnimals as $deathAnimal)
                                <tr>
                                    <td>{{ $deathAnimal->date }}</td>
                                    <td>{{ $deathAnimal->shipment->shipment_no }}</td>
                                    <td>{{ $deathAnimal->supplier->name }}</td>
                                    <td>{{ $deathAnimal->inspection ? $deathAnimal->inspection->inspection_no : 'N/A' }}</td>
                                    <td>
                                    <a href="{{ route('deathanimal.show', $deathAnimal->id) }}" class="btn btn-info btn-sm">View</a>

                                        <form action="{{ route('deathanimal.destroy', $deathAnimal->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
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
