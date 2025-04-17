@extends('layouts.layout')
@section('content')

<style>
table {
    border-collapse: collapse;
    width: 80%;
}

table th, table td {
    border: 1px solid black; 
    text-align: center;
    padding: 5px;
}
</style>

<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-12 grid-margin createtable">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title">View AnteMortem Report</h2>
                    <a href="{{ url('postmortem-report-index') }}" class="backicon"><i class="mdi mdi-backburger"></i></a>

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label><strong>Antemortem No:</strong></label>
                            <p>{{ $report->antemortem_no }}</p>
                        </div>
                        <div class="col-md-3"></div>
                        <div class="col-md-3">
                            <label><strong>Inspection Date:</strong></label>
                            <p>{{ $report->inspection_date }}</p>
                        </div>
                    </div>

                    <h4>AnteMortem Information</h4>
                    <table>
                        <thead>
                            <tr>
                                <th>Animals Types Inspected</th>
                                <th>Quantity Pass</th>
                                <th>Quantity Held</th>
                                <th>Qty. Condemned on Ante-Mortem</th>
                                <th>Vet. Contacted</th>
                                <th>Manager Contacted</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($report->animal as $animal)
                                <tr>
                                    <td>{{ $animal->animal_type }}</td>
                                    <td>{{ $animal->quantity_pass }}</td>
                                    <td>{{ $animal->quantity_held }}</td>
                                    <td>{{ $animal->quantity_condemned }}</td>
                                    <td>{{ $animal->vet_contacted }}</td>
                                    <td>{{ $animal->manager_contacted }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <h4>General Conditions</h4>
                    <table>
                        <thead>
                            <tr>
                                <th>General Condition</th>
                                <th>Suspect</th>
                                <th>Not Suspect</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($generalConditions as $condition)
                                <tr>
                                    <td><strong>{{ $condition->condition_type }}</strong></td>
                                    <td>{{ $condition->suspect }}</td>
                                    <td>{{ $condition->not_suspect }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <h4>Sample Submission</h4>
                    <table>
                        <thead>
                            <tr>
                                <th>Sample Identification Type</th>
                                <th>Sample Location</th>
                                <th>Hold Tag</th>
                                <th>Date Submitted</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($report->sampleType as $sample)
                                <tr>
                                    <td>{{ $sample->sample_identification_type }}</td>
                                    <td>{{ $sample->sample_location }}</td>
                                    <td>{{ $sample->hold_tag }}</td>
                                    <td>{{ $sample->date_submitted }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <h4>Comments</h4>
                    <table>
                        <tbody>
                            @foreach ($report->comment as $comt)
                                <tr>
                                    <td>{{ $comt->comment_text }}</td>
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
