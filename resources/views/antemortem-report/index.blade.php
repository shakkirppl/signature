@extends('layouts.layout')
@section('content')

<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h2 class="card-title">Antemortem Reports</h2>
                        </div>
                        <div class="col-md-6 text-end">
                            <a href="{{ route('antemortem.create') }}" class="btn btn-primary">Add New Report</a>
                        </div>
                    </div>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Antemortem No</th>
                                <th>Inspection Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reports as $report)
                                <tr>
                                    <td>{{ $report->id }}</td>
                                    <td>{{ $report->antemortem_no }}</td>
                                    <td>{{ $report->inspection_date }}</td>
                                    <td>
                                        <a href="{{ route('antemortem.edit', $report->id) }}" class="btn btn-warning">Edit</a>
                                        
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{ $reports->links() }} <!-- Pagination -->
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
