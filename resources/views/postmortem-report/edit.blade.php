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
                    <h2 class="card-title">Edit PostMortem Report</h2>
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

                    <form action="{{ route('postmortem.update', $postmortem->id) }}" method="POST">
                        @csrf
                        @method('POST')

                        <!-- Postmortem Details -->
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label><strong>Postmortem No:</strong></label>
                                <input type="text" name="postmortem_no" class="form-control" value="{{ $postmortem->postmortem_no }}" readonly>
                            </div>
                            <div class="col-md-3"></div>
                            <div class="col-md-3">
                                <label><strong>Inspection Date:</strong></label>
                                <input type="date" name="inspection_date" class="form-control" value="{{ $postmortem->inspection_date }}" required>
                            </div>
                        </div>

                        <!-- Animals Table -->
                        <h4>Postmortem Information</h4>
                        <table border="1" style="width: 80%; text-align: center;">
                            <thead>
                                <tr>
                                    <th>Animal Type</th>
                                    <th>Carcasses Approved</th>
                                    <th>Carcasses Held</th>
                                    <th>Carcasses Condemned</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $animalTypes = ['Sheep & Lamb', 'Goats'];
                                @endphp
                                @foreach ($animalTypes as $index => $animal)
                                    <tr>
                                        <td>
                                            <input type="text" name="animal_type[]" value="{{ $animal }}"  class="form-control">
                                        </td>
                                        <td>
                                            <textarea name="carcasses_approved[]" rows="2" class="form-control">
                                                {{ $postmortem->animals[$index]->carcasses_approved ?? '' }}
                                            </textarea>
                                        </td>
                                        <td>
                                            <textarea name="carcasses_held[]" rows="2" class="form-control">
                                                {{ $postmortem->animals[$index]->carcasses_held ?? '' }}
                                            </textarea>
                                        </td>
                                        <td>
                                            <textarea name="carcasses_condemned[]" rows="2" class="form-control">
                                                {{ $postmortem->animals[$index]->carcasses_condemned ?? '' }}
                                            </textarea>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Organs Table -->
                        <h4>Organ Inspection</h4>
                        <table border="1" style="width: 80%; text-align: center;">
                            <thead>
                                <tr>
                                    <th>Organ Type</th>
                                    <th>Organs Approved</th>
                                    <th>Organs Held</th>
                                    <th>Organs Condemned</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $organsList = ['Heart', 'Liver', 'Kidneys', 'Lungs', 'Tongue'];
                                @endphp
                                @foreach ($organsList as $index => $organ)
                                    <tr>
                                        <td>
                                            <input type="text" name="organ_type[]" value="{{ $organ }}"  class="form-control">
                                        </td>
                                        <td>
                                            <textarea name="organs_approved[]" rows="2" class="form-control">
                                                {{ $postmortem->organs[$index]->organs_approved ?? '' }}
                                            </textarea>
                                        </td>
                                        <td>
                                            <textarea name="organs_held[]" rows="2" class="form-control">
                                                {{ $postmortem->organs[$index]->organs_held ?? '' }}
                                            </textarea>
                                        </td>
                                        <td>
                                            <textarea name="organs_condemned[]" rows="2" class="form-control">
                                                {{ $postmortem->organs[$index]->organs_condemned ?? '' }}
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
                                @foreach ($postmortem->samples as $sample)
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

                       
                        <h4>Comments</h4>
                     <table border="1" style="width: 80%; text-align: center;">
                  <tbody>
                  @foreach ($postmortem->comments as $comment)
                <tr>
                <td>
                    <textarea name="comments[]" rows="2" class="form-control">{{ $comment->comment }}</textarea>
                </td>
            </tr>
        @endforeach
        
        <tr>
            <td>
                <textarea name="comments[]" rows="2" class="form-control" placeholder="Enter new comment"></textarea>
            </td>
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
