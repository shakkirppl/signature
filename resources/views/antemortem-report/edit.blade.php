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

table input[type="text"], 
table textarea {
    border: none !important;
    outline: none !important;
    background: none !important;
    resize: none;
    width: 100%;
    text-align: center;
    font-family: inherit;
    font-size: inherit;
    padding: 0;
    margin: 0;
    */
}


</style>

<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-12 grid-margin createtable">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title">Edit AnteMortem Report</h2>
                    <a href="{{ url('postmortem-report-index') }}" class="backicon"><i class="mdi mdi-backburger"></i></a>
                    
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('antemortem.update', $report->id) }}" method="POST">
                        @csrf
                        @method('POST')

                        <!-- Postmortem Details -->
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label for="antemortem_no"><strong>Antemortem No:</strong></label>
                                <input type="text" name="antemortem_no" id="antemortem_no" class="form-control" value="{{$report->antemortem_no}}" readonly>
                            </div>
                            <div class="col-md-3"></div>
                            <div class="col-md-3">
                                <label for="inspection_date"><strong>Inspection Date:</strong></label>
                                <input type="date" name="inspection_date" id="inspection_date" class="form-control" value="{{$report->inspection_date}}" required>
                            </div>
                        </div>
                       

                        <!-- Animals Table -->
                        <h4>AnteMortem Information</h4>
                        <table border="1" style="width: 80%; text-align: center;">
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
    @php
        $animalTypes = ['Goats', 'Sheep'];
    @endphp
    @foreach ($animalTypes as $index => $animal)
        <tr>
            <td>
                <input type="text" name="animal_type[]" value="{{ $animal }}" class="form-control">
            </td>
            <td>
                <textarea name="quantity_pass[]" rows="2" class="form-control">{{ $report->animals[$index]->quantity_pass ?? '' }}</textarea>
            </td>
            <td>
                <textarea name="quantity_held[]" rows="2" class="form-control">{{ $report->animals[$index]->quantity_held ?? '' }}</textarea>
            </td>
            <td>
                <textarea name="quantity_condemned[]" rows="2" class="form-control">{{ $report->animals[$index]->quantity_condemned ?? '' }}</textarea>
            </td>
            <td>
                <textarea name="vet_contacted[]" rows="2" class="form-control">{{ $report->animals[$index]->vet_contacted ?? '' }}</textarea>
            </td>
            <td>
                <textarea name="manager_contacted[]" rows="2" class="form-control">{{ $report->animals[$index]->manager_contacted ?? '' }}</textarea>
            </td>
        </tr>
    @endforeach
</tbody>

                        </table>

                        <!-- Organs Table -->
                        <h4>General Conditions</h4>
                        <table border="1" style="width: 80%; text-align: center;">
                            <thead>
                                <tr>
                                    <th>General Conditions</th>
                                        <th>Suspect</th>
                                        <th>Not Suspect</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $condition_type = ['Reportable diseases: visual suspicion of BSE, Foot and Mouth, etc.', 'health_risk', 'unfit_consumption', 'antibiotics', 'contamination','welfare','feeding'];
                                @endphp
                                @foreach ($condition_type as $index => $condition)
                                    <tr>
                                        <td>
                                            <input type="text" name="condition_type[]" value="{{ $condition }}"  class="form-control">
                                        </td>
                                        <td>
                                            <textarea name="suspect[]" rows="2" class="form-control">
                                                {{ $report->condition[$index]->suspect ?? '' }}
                                            </textarea>
                                        </td>
                                        <td>
                                            <textarea name="not_suspect[]" rows="2" class="form-control">
                                                {{ $report->condition[$index]->not_suspect ?? '' }}
                                            </textarea>
                                        </td>
                                                                          </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Sample Submission Table -->
                        <h4>Sample Submission</h4>

                        <table border="1" style="width: 80%; text-align: center;">
                            <thead>
                                <tr>
                                    <th>Sample Identification Type</th>
                                    <th>Sample Location</th>
                                    <th>Hold Tag</th>
                                    <th>Date Submitted</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($report->sampleTypes as $sample)
                                    <tr>
                                        <td>
                                            <textarea name="sample_identification_type[]" rows="2" class="form-control">
                                                {{ $sample->sample_identification_type }}
                                            </textarea>
                                        </td>
                                        <td>
                                            <textarea name="sample_location[]" rows="2" class="form-control">
                                                {{ $sample->sample_location }}
                                            </textarea>
                                        </td>
                                        <td>
                                            <textarea name="hold_tag[]" rows="2" class="form-control">
                                                {{ $sample->hold_tag }}
                                            </textarea>
                                        </td>
                                        <td>
                                            <input type="date" name="date_submitted[]" value="{{ $sample->date_submitted }}" class="form-control">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <!-- <table border="1" style="width: 80%; text-align: center;">
                            <thead>
                                <tr>
                                    <th>Sample Identification Type</th>
                                    <th>Sample Location</th>
                                    <th>Hold Tag</th>
                                    <th>Date Submitted</th>
                                </tr>
                            </thead>
                            <tbody>
    @foreach ($report->sampleTypes ?? [] as $sample)
        <tr>
            <td><textarea name="sample_identification_type[]" rows="2" class="form-control">{{ $sample->sample_identification_type }}</textarea></td>
            <td><textarea name="sample_location[]" rows="2" class="form-control">{{ $sample->sample_location }}</textarea></td>
            <td><textarea name="hold_tag[]" rows="2" class="form-control">{{ $sample->hold_tag }}</textarea></td>
            <td><input type="date" name="date_submitted[]" value="{{ $sample->date_submitted }}" class="form-control"></td>
        </tr>
    @endforeach
</tbody>

                        </table> -->

                       
                        <h4>Comments</h4>
                     <table border="1" style="width: 80%; text-align: center;">
                     <tbody>
    @foreach ($report->comments ?? [] as $comment)
        <tr>
            <td><textarea name="comment_text[]" rows="2" class="form-control">{{ $comment->comment }}</textarea></td>
        </tr>
    @endforeach
    <tr>
        <td><textarea name="comment_text[]" rows="2" class="form-control" placeholder="Enter new comment"></textarea></td>
    </tr>
</tbody>

</table>


                        <br>
                        <button type="submit" class="btn btn-primary">Update Report</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection
