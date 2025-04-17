@extends('layouts.layout')
@section('content')
@php
    $user = Auth::user();
@endphp

<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h2 class="card-title">Antemortem Reports</h2>
                        </div>
                       
                        <div class="col-md-6 text-right">
            <a href="{{ route('antemortem.create') }}" class="newicon"><i class="mdi mdi-new-box"></i></a>
            </div>
                    </div>

                    <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
            <table class="table table-bordered table-striped table-sm" style="font-size: 12px;">
              <thead style="background-color: #d6d6d6; color: #000;">
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
                                    @if($user->designation_id == 1)
                                    <td>

                                        <a href="{{ route('antemortem.edit', $report->id) }}" class="btn btn-warning">Edit</a>
                                        
                                    </td>
                                    @endif
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
