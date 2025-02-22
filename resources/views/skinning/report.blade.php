@extends('layouts.layout')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Skinning Report</h4>

                    <form method="GET" action="{{ route('skinning.report') }}">
                        @csrf
                        <div class="row">
                            <!-- Shipment Dropdown -->
                            <div class="col-md-3">
                                <select class="form-control" name="shipment_id">
                                    <option value="">Select Shipment</option>
                                    @foreach($shipments as $shipment)
                                        <option value="{{ $shipment->id }}" {{ request('shipment_id') == $shipment->id ? 'selected' : '' }}>
                                            {{ $shipment->shipment_no }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Employees Dropdown -->
                            <div class="col-md-3">
                                <select class="form-control" name="employee_id">
                                    <option value="">Select Employee</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}" {{ request('employee_id') == $employee->id ? 'selected' : '' }}>
                                            {{ $employee->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- From Date -->
                            <div class="col-md-3">
                                <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}" placeholder="From Date">
                            </div>

                            <!-- To Date -->
                            <div class="col-md-3">
                                <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}" placeholder="To Date">
                            </div>

                            <!-- Submit Button -->
                            <div class="col-md-1">
                                <button type="submit" class="btn btn-primary">Get</button>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive mt-4">
                        <table class="table table-hover" id="report-table">
                            <thead>
                                <tr>
                                    <th>Skinning Code</th>
                                    <th>Date</th>
                                    <th>Shipment</th>
                                   
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($skinningRecords as $record)
                                <tr>
                                    <td>{{ $record->skinning_code }}</td>
                                    <td>{{ $record->date }}</td>
                                    <td>{{ $record->shipment->shipment_no ?? 'N/A' }}</td>
                                    <td>
                                        <a href="{{ route('skinning.view', $record->id) }}" class="btn btn-warning">View</a>
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
