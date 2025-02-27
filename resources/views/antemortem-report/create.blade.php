@extends('layouts.layout')
@section('content')

<style>
    #componentTable tbody tr {
        line-height: 1.2em;
        margin-bottom: 0.3em;
    }

    table input {
        border: none;
        outline: none;
        width: 100%;
        text-align: center;
        background-color: transparent;
    }

    table, th, td {
        border: 1px solid black;
        text-align: center;
    }

    th, td {
        padding: 8px;
    }
</style>

<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-12 grid-margin createtable">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <h2 class="card-title">AnteMortem Report</h2>
                        </div>
                        <div class="col-6 text-end">
                            <a href="{{ url('postmortem-report-index') }}" class="backicon"><i class="mdi mdi-backburger"></i></a>
                        </div>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div><br />
                    @endif

                    <form action="{{ route('postmortem.store') }}" method="POST">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label for="antemortem_no"><strong>Antemortem No:</strong></label>
                                <input type="text" name="antemortem_no" id="antemortem_no" class="form-control" value="{{$invoice_no}}" readonly>
                            </div>
                            <div class="col-md-3"></div>
                            <div class="col-md-3">
                                <label for="inspection_date"><strong>Inspection Date:</strong></label>
                                <input type="date" name="inspection_date" id="inspection_date" class="form-control" required>
                            </div>
                        </div>

                        <div><strong>Antemortem Information</strong> (list reasons for hold or condemnations in Comments section):</div>
                        <br>
                        <div class="table-responsive">
                       
                            <table class="table table-bordered">
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
                                    <tr>
                                        <td>Goats</td>
                                        <td><input type="text" name="quantity_pass[goats]"></td>
                                        <td><input type="text" name="quantity_held[goats]"></td>
                                        <td><input type="text" name="quantity_condemned[goats]"></td>
                                        <td><input type="text" name="vet_contacted[goats]"></td>
                                        <td><input type="text" name="manager_contacted[goats]"></td>
                                    </tr>
                                    <tr>
                                        <td>Sheep</td>
                                        <td><input type="text" name="quantity_pass[sheep]"></td>
                                        <td><input type="text" name="quantity_held[sheep]"></td>
                                        <td><input type="text" name="quantity_condemned[sheep]"></td>
                                        <td><input type="text" name="vet_contacted[sheep]"></td>
                                        <td><input type="text" name="manager_contacted[sheep]"></td>
                                    </tr>
                                </tbody>
                            </table>

                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>General Conditions</th>
                                        <th>Suspect</th>
                                        <th>Not Suspect</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><strong>Reportable diseases:</strong> visual suspicion of BSE, Foot and Mouth, etc.</td>
                                        <td><input type="text" name="suspect[reportable_diseases]"></td>
                                        <td><input type="text" name="not_suspect[reportable_diseases]"></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Other health risk to staff: </strong>visual suspicion of ringworm, enraged animal, etc.</td>
                                        <td><input type="text" name="suspect[health_risk]"></td>
                                        <td><input type="text" name="not_suspect[health_risk]"></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Unfit for consumption:</strong> visual suspicion for emaciation, multiple abscess, etc.</td>
                                        <td><input type="text" name="suspect[unfit_consumption]"></td>
                                        <td><input type="text" name="not_suspect[unfit_consumption]"></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Antibiotics:</strong> visual evidence of needle marks, down animals, cull animals.</td>
                                        <td><input type="text" name="suspect[antibiotics]"></td>
                                        <td><input type="text" name="not_suspect[antibiotics]"></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Heavy contamination:</strong> visual evidence of excessively contaminated animals.</td>
                                        <td><input type="text" name="suspect[contamination]"></td>
                                        <td><input type="text" name="not_suspect[contamination]"></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Animal welfare:</strong> evidence of abuse, improper conditions, etc.</td>
                                        <td><input type="text" name="suspect[welfare]"></td>
                                        <td><input type="text" name="not_suspect[welfare]"></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Feeding:</strong> evidence that animals have not been taken off feed prior to slaughter.</td>
                                        <td><input type="text" name="suspect[feeding]"></td>
                                        <td><input type="text" name="not_suspect[feeding]"></td>
                                    </tr>
                                </tbody>
                            </table>
                       

                        <br>
                        <div><strong>Sample Submission</strong></div> <br>
<table border="1" style="width: 80%; text-align: center; border-collapse: collapse;">
    <thead>
        <tr>
            <th>Sample Identification Type</th>
            <th>Sample Location</th>
            <th>Hold Tag</th>
            <th>Date Submitted</th>
        </tr>
    </thead>
    <tbody>
        @for ($i = 0; $i < 4; $i++) 
        <tr>
            <td><input type="text" name="sample_identification_type[]" style="width: 100%; border: none; outline: none; text-align: center;"></td>
            <td><input type="text" name="sample_location[]" style="width: 100%; border: none; outline: none; text-align: center;"></td>
            <td><input type="text" name="hold_tag[]" style="width: 100%; border: none; outline: none; text-align: center;"></td>
            <td><input type="date" name="date_submitted[]" style="width: 100%; border: none; outline: none; text-align: center;"></td>
        </tr>
        @endfor
    </tbody>
</table>
<br><div><strong>Comments:</strong></div> <br>
<table border="1" style="width: 80%; text-align: center; border-collapse: collapse;">
    <tbody>
        @for ($i = 0; $i < 6; $i++)  
        <tr>
            <td>
                <input type="text" name="comments[]" rows="1" style="width: 100%; border: none; outline: none; resize: none;">
            </td>
        </tr>
        @endfor
    </tbody>
</table>
</div>
<br>

                        <div class="submitbutton">
                            <button type="submit" class="btn btn-primary mb-2">Submit <i class="fas fa-save"></i></button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
