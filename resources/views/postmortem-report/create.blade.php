@extends('layouts.layout')
@section('content')
<style>

#componentTable tbody tr {
    line-height: 1.2em;
    margin-bottom: 0.3em;
}


table textarea {
    border: none;
    outline: none;
    width: 100%;
    text-align: center;
    background-color: transparent;
    resize: none;
}
</style>

<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-12 grid-margin createtable">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 col-md-6 col-sm-6 col-xs-12">
                            <h2 class="card-title">PostMortem Report </h2>
                        </div>
                        <div class="col-6 col-md-6 col-sm-6 col-xs-12 heading" style="text-align:end;">
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
            <label for="postmortem_no"><strong>Postmortem No:</strong></label>
            <input type="text" name="postmortem_no" id="postmortem_no" class="form-control" value="{{$invoice_no}}" readonly>
        </div>
        <div class="col-md-3"></div>
        <div class="col-md-3">
            <label for="inspection_date"><strong>Inspection Date:</strong></label>
            <input type="date" name="inspection_date" id="inspection_date" class="form-control" required>
        </div>
    </div>
        <div><strong>Postmortem Information</strong>(list reasons for hold or condemnations in Comments section):</div> <br>
        <table border="1" style="width: 80%; text-align: center;">
        <thead>
                <tr>
                    <th>Animals Types Inspected</th>
                    <th>Carcasses Approved</th>
                    <th>Carcasses Held</th>
                    <th>Carcasses Condemned</th>
                </tr>
            </thead>
            
            <tbody>
                @foreach (['Sheep & Lamb', 'Goats'] as $animal)
                <tr>
                <tr>
                    <td>
                        <textarea name="animal_type[]" readonly rows="2">{{ $animal }}</textarea>
                    </td>
                    <td>
                        <textarea name="carcasses_approved[]" rows="2"></textarea>
                    </td>
                    <td>
                        <textarea name="carcasses_held[]" rows="2"></textarea>
                    </td>
                    <td>
                        <textarea name="carcasses_condemned[]" rows="2"></textarea>
                    </td>
                </tr>
                </tr>
                @endforeach
            </tbody>
        </table>
        <br>
        
        <div><strong>Postmortem Information</strong>(list reasons for hold or condemnations in Comments section):</div> <br>
        <table border="1" style="width: 80%; text-align: center;">
        <thead>
                <tr>
                    <th>Organ Types Inspected</th>
                    <th>Organs Approved</th>
                    <th>Organs Held</th>
                    <th>Organs Condemned</th>
                </tr>
            </thead>
            
            <tbody>
                @foreach (['Heart ',' Liver', 'Kidneys ',' Lungs','Tongue'] as $organ)
                <tr>
                <tr>
                    <td>
                        <textarea name="organ_type[]" readonly rows="2">{{ $organ }}</textarea>
                    </td>
                    <td>
                        <textarea name="organs_approved[]" rows="2"></textarea>
                    </td>
                    <td>
                        <textarea name="organs_held[]" rows="2"></textarea>
                    </td>
                    <td>
                        <textarea name="organs_condemned[]" rows="2"></textarea>
                    </td>
                </tr>
                </tr>
                @endforeach
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
            <td><textarea name="sample_identification_type[]" rows="2"></textarea></td>
            <td><textarea name="sample_location[]" rows="2"></textarea></td>
            <td><textarea name="hold_tag[]" rows="2"></textarea></td>
            <td><input type="date" name="date_submitted[]" style="width: 100%; border: none; outline: none; text-align: center;"></td>
        </tr>
        @endfor
    </tbody>
</table>
<br>
<div><strong>Comments:</strong></div> <br>
<table border="1" style="width: 80%; text-align: center; border-collapse: collapse;">
    <tbody>
        @for ($i = 0; $i < 6; $i++)  
        <tr>
            <td>
                <textarea name="comments[]" rows="1" style="width: 100%; border: none; outline: none; resize: none;"></textarea>
            </td>
        </tr>
        @endfor
    </tbody>
</table>

 <br>
<!--<br>
    <label><strong>Inspector's Signature</strong> <span style="border-bottom: 1px solid black; display: inline-block; width: 200px;"></span></label> -->
    <div class="submitbutton">
                    <button type="submit" class="btn btn-primary mb-2 submit">Submit<i class="fas fa-save"></i></button>
                  </div>

    </form>
    
    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


