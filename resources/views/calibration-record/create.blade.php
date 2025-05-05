@extends('layouts.layout')
@section('content')
<style>
  .required:after {
    content: " *";
    color: red;
  }
</style>

<div class="main-panel">
  <div class="content-wrapper">
    <div class="col-lg-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Calibration Record Form</h4>
          <div class="col-md-6 heading">
            <a href="{{ route('calibration-record.index') }}" class="backicon"><i class="mdi mdi-backburger"></i></a>
          </div>

          @if($errors->any())
          <div class="alert alert-danger">
            <ul>
              @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
          @endif

          <form action="{{ route('calibration-record.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
              <!-- Left Column -->
              <div class="col-md-6">
                <div class="form-group">
                  <label for="date" class="required">Date</label>
                  <input type="date" class="form-control" id="date" name="date" required>
                </div>
                <div class="form-group">
                  <label for="equipment_name" class="required">Equipment ID/Name</label>
                  <input type="text" class="form-control" id="equipment_name" name="equipment_name" required>
                </div>
                <div class="form-group">
                  <label for="standard_used" class="required">Calibration Standard Used</label>
                  <input type="text" class="form-control" id="standard_used" name="standard_used" required>
                </div>
                <div class="form-group">
                  <label for="calibration_result" class="required">Calibration Result</label>
                  <select class="form-control" id="calibration_result" name="calibration_result" required>
                    <option value="">-- Select Result --</option>
                    <option value="Pass">Pass</option>
                    <option value="Fail">Fail</option>
                  </select>
                </div>
              </div>

              <!-- Right Column -->
              <div class="col-md-6">
                <div class="form-group">
                  <label for="next_calibration_due" class="required">Next Calibration Due</label>
                  <input type="date" class="form-control" id="next_calibration_due" name="next_calibration_due" required>
                </div>
                <div class="form-group">
                  <label for="technician_name" class="required">Technician Name</label>
                  <input type="text" class="form-control" id="technician_name" name="technician_name" required>
                </div>
                <div class="form-group">
                  <label for="signature" class="required">Signature</label>
                  <input type="file" class="form-control" id="signature" name="signature" accept="image/*" required>
                </div>
              </div>
            </div>

            <div class="submitbutton">
              <button type="submit" class="btn btn-primary mb-2 submit">Submit <i class="fas fa-save"></i></button>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const dateInput = document.getElementById('date');
    let today = new Date().toISOString().split('T')[0];
    dateInput.value = today;
  });
</script>
@endsection
