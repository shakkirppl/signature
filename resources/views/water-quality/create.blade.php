@extends('layouts.layout')
@section('content')
<div class="main-panel">
  <div class="content-wrapper">
    <div class="col-12 grid-margin">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Water Quality Test Record</h4>
          <div class="col-md-12 heading">
            <a href="{{ route('water-quality.index') }}" class="backicon"><i class="mdi mdi-backburger"></i></a>
          </div>
          <form id="temperatureForm" action="{{ route('water-quality.store') }}" method="POST">
            @csrf
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Date</label>
                  <input type="date" name="date" id="date" class="form-control" required>
                </div>
                <div class="form-group">
                  <label>Sampling Point</label>
                  <input type="text" name="sampling_point" class="form-control" required>
                </div>
               <!-- <div class="form-group">
  <label>Test Parameters</label>
  <select name="test_parameters[]" class="form-control" multiple required>
    <option value="pH">pH</option>
    <option value="Temperature">Temperature</option>
    <option value="Microbial Count">Microbial Count</option>
  </select>
</div> -->
<div class="form-group">
  <label>Test Parameters</label>
  <select name="test_parameters" class="form-control"  required>
    <option value="">Select</option>
    <option value="pH">pH</option>
    <option value="Temperature">Temperature</option>
    <option value="Microbial Count">Microbial Count</option>
  </select>
</div>

                <div class="form-group">
                  <label>Standards Met</label>
                  <select name="standards_met" class="form-control" required>
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                  </select>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <label>Results</label>
                  <textarea name="results" class="form-control" required></textarea>
                </div>
                <div class="form-group">
                  <label>Lab Technician</label>
                  <input type="text" name="lab_technician" class="form-control" required>
                </div>
                <div class="form-group">
                  <label>Signature</label><br>
                  <canvas id="signature-pad" width="300" height="100" style="border:1px solid #000;"></canvas>
                  <input type="hidden" name="signature" id="signature">
                  <br>
                  <button type="button" class="btn btn-secondary btn-sm mt-1" onclick="clearSignature()">Clear</button>
                </div>
              </div>
            </div>
            <button type="submit" class="btn btn-primary" onclick="saveSignature()">Submit</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const canvas = document.getElementById('signature-pad');
    const signaturePad = new SignaturePad(canvas);

    window.clearSignature = function () {
      signaturePad.clear();
    };

    document.getElementById('temperatureForm').addEventListener('submit', function (e) {
      if (signaturePad.isEmpty()) {
        alert('Verification signature is required.');
        e.preventDefault();
        return;
      }
      document.getElementById('signature').value = signaturePad.toDataURL();
    });

    // Set current date
    document.getElementById('date').value = new Date().toISOString().split('T')[0];
  });
</script>
@endsection
