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
          @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
          @elseif(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
          @endif

          <div class="row">
            <div class="col-md-6">
              <h4 class="card-title">GMP Checklist Recordst</h4>
            </div>
            <div class="col-md-6 text-right">
                     <a href="{{ route('gmp.create') }}" class="newicon">
                <i class="mdi mdi-new-box"></i>
              </a>            </div>
          </div>

          <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
            <table class="table table-bordered table-striped table-sm" style="font-size: 12px;">
              <thead style="background-color: #d6d6d6; color: #000;">
                 <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Facility Cleanliness</th>
                                <th>Pest Control</th>
                                <th>Personal Hygiene</th>
                                <th>Equipment Sanitation</th>
                               <th>created By</th>
                            </tr>
              </thead>
             <tbody>
                            @foreach($records as $index => $record)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $record->date }}</td>
                                <td>{{ $record->facility_cleanliness }}</td>
                                <td>{{ $record->pest_control }}</td>
                                <td>{{ $record->personal_hygiene ?? '-' }}</td>
                                <td>{{ $record->equipment_sanitation }}</td>
                                  <td>{{ $record->user->name ?? 'N/A' }}</td>
                               
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
    font-size: 30px;
  }
</style>
@endsection
