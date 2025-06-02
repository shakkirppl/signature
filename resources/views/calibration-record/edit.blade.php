@extends('layouts.layout')
@section('content')

<style>.required:after { content: " *"; color: red; }</style>

<div class="main-panel">
  <div class="content-wrapper">
    <div class="col-lg-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Edit Calibration Record</h4>
          <div class="col-md-6 heading">
            <a href="{{ route('calibration-record.index') }}" class="backicon"><i class="mdi mdi-backburger"></i></a>
          </div>

          <form id="editForm" action="{{ route('calibration-record.update', $record->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
              <!-- Left -->
              <div class="col-md-6">
                <div class="form-group">
                  <label class="required">Date</label>
                  <input type="date" class="form-control" name="date" value="{{ $record->date }}" required>
                </div>
                <div class="form-group">
                  <label class="required">Equipment Name</label>
                  <input type="text" class="form-control" name="equipment_name" value="{{ $record->equipment_name }}" required>
                </div>
                <div class="form-group">
                  <label class="required">Standard Used</label>
                  <input type="text" class="form-control" name="standard_used" value="{{ $record->standard_used }}" required>
                </div>
                <div class="form-group">
                  <label class="required">Calibration Result</label>
                  <select name="calibration_result" class="form-control" required>
                    <option value="Pass" {{ $record->calibration_result == 'Pass' ? 'selected' : '' }}>Pass</option>
                    <option value="Fail" {{ $record->calibration_result == 'Fail' ? 'selected' : '' }}>Fail</option>
                  </select>
                </div>
                <div class="form-group">
                  <label>Current Signature</label><br>
                  @if ($record->signature)
                    <img src="{{ asset('storage/signatures/' . $record->signature) }}" style="width: 300px;" alt="Signature">
                  @else
                    <p>No signature available.</p>
                  @endif
                </div>
              </div>

              <!-- Right -->
              <div class="col-md-6">
                <div class="form-group">
                  <label class="required">Next Calibration Due</label>
                  <input type="date" class="form-control" name="next_calibration_due" value="{{ $record->next_calibration_due }}" required>
                </div>
                <div class="form-group">
                  <label class="required">Technician Name</label>
                  <input type="text" class="form-control" name="technician_name" value="{{ $record->technician_name }}" required>
                </div>

                

                <div class="form-group mt-3">
                  <label>New Signature (optional)</label><br>
                  <canvas id="signature-pad" width="400" height="150" style="border:1px solid #ccc;"></canvas><br>
                  <button type="button" class="btn btn-sm btn-warning mt-2" onclick="clearSignature()">Clear</button>
                  <input type="hidden" name="signature" id="signature">
                </div>
              </div>
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
          </form>

        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script>
  const canvas = document.getElementById('signature-pad');
  const signaturePad = new SignaturePad(canvas);

  function clearSignature() {
    signaturePad.clear();
  }

  document.getElementById('editForm').addEventListener('submit', function(e) {
    if (!signaturePad.isEmpty()) {
      document.getElementById('signature').value = signaturePad.toDataURL();
    }
  });
</script>
@endsection
