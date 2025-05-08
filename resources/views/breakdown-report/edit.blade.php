@extends('layouts.layout')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <h4 class="card-title">Edit Breakdown Report</h4>
        <form action="{{ route('breakdown-report.update', $report->id) }}" method="POST">
            @csrf
           
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Date</label>
                        <input type="date" name="date" class="form-control" value="{{ $report->date }}">
                    </div>
                    <div class="form-group">
    <label>Problem Reported</label>
    <textarea name="problem_reported" class="form-control" rows="3">{{ $report->problem_reported }}</textarea>
</div>

<div class="form-group">
    <label>Action Taken</label>
    <textarea name="action_taken" class="form-control" rows="3">{{ $report->action_taken }}</textarea>
</div>

                    <div class="form-group">
                        <label>Equipment ID</label>
                        <input type="text" name="equipment_id" class="form-control" value="{{ $report->equipment_id }}">
                    </div>
                    <div class="form-group">
                        <label>Time Out of Service</label>
                        <input type="text" name="time_out_of_service" class="form-control" value="{{ $report->time_out_of_service }}">
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
