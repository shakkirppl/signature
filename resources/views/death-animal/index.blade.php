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
                    <table class="table">
                        <thead>
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
                                        <form action="{{ route('deathanimal.destroy', $deathAnimal->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Delete</button>
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

@endsection
