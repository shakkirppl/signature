@extends('layouts.layout')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Inspection Report</h4>

                    <form method="GET" action="{{ route('inspection.report') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-3">
                                <select class="form-control" name="supplier_id">
                                    <option value="">Select Supplier</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="date" name="from_date" class="form-control" placeholder="From Date">
                            </div>
                            <div class="col-md-3">
                                <input type="date" name="to_date" class="form-control" placeholder="To Date">
                            </div>
                            <div class="col-md-1">
                                <button type="submit" class="btn btn-primary">Get</button>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive mt-4">
                        <table class="table table-hover" id="report-table">
                            <thead>
                                <tr>
                                    <th>Inspection No</th>
                                    <th>Order No</th>
                                    <th>Date</th>
                                    <th>Supplier</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($inspections as $inspection)
                                <tr>
                                <td>{{ $inspection->inspection_no }}</td>
                                    <td>{{ $inspection->order_no }}</td>
                                    <td>{{ $inspection->date }}</td>
                                    <td>{{ $inspection->supplier->name }}</td>
                                    <td>
                                        @if($inspection->status == 1)
                                            <span class="badge badge-success">Completed</span>
                                        @else
                                            <span class="badge badge-warning">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                    @if($inspection->weight_status == 1)
                                      <a href="{{ route('inspection.edit', $inspection->id) }}" class="btn btn-primary">Edit</a>
                                    @endif


                                    <a href="{{ route('inspection.inspectionview', $inspection->id) }}" class="btn btn-info">View</a>
                                    <form action="{{ route('inspection.destroy', $inspection->id) }}" method="POST" style="display:inline;">
                                     @csrf
                                     @method('DELETE')
                                     <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this inspection?');">Delete</button>
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
