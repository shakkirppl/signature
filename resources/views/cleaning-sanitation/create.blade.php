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
          <h4 class="card-title">Cleaning and Sanitation Record After Production</h4>
          <div class="col-md-6 heading">
            <a href="{{ route('cleaning-sanitation.index') }}" class="backicon"><i class="mdi mdi-backburger"></i></a>
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

          <form id="temperatureForm" action="{{ route('cleaning-sanitation.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
              <!-- Left Column -->
              <div class="col-md-6">
                <div class="form-group">
                  <label for="date" class="required">Date</label>
                  <input type="date" class="form-control" id="date" name="date" required>
                </div>

                <div class="form-group">
                  <label class="required">Cleaning Method Used</label>
                  <input type="text" class="form-control" id="cleaning_method" name="cleaning_method" required>
                </div>

                <div class="form-group">
                  <label class="required">Cleaning Agent Used</label>
                  <input type="text" class="form-control" id="cleaning_agent" name="cleaning_agent" required>
                </div>

                <div class="form-group">
                  <label class="required">Verification Signature</label><br>
                  <canvas id="signature-pad" width="400" height="150" style="border:1px solid #ccc;"></canvas><br>
                  <button type="button" class="btn btn-sm btn-warning mt-2" onclick="clearSignature()">Clear</button>
                  <input type="hidden" name="verification_signature" id="verification_signature">
                </div>
              </div>

              <!-- Right Column -->
              <div class="col-md-6">
                <div class="form-group">
                  <label>Area Cleaned</label>
                  <input type="text" class="form-control" id="area_cleaned" name="area_cleaned">
                </div>

                <div class="form-group">
                  <label class="required">Cleaner Name</label>
                  <input type="text" class="form-control" id="cleaner_name" name="cleaner_name" required>
                </div>

                <div class="form-group">
                  <label class="required">Supervisor Check</label>
                  <select class="form-control" id="supervisor_check" name="supervisor_check" required>
                    <option value="">Select</option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                  </select>
                </div>
                <div class="form-group">
  <label for="comments">Comments</label>
  <textarea class="form-control" id="comments" name="comments" rows="4" placeholder="Enter comments (optional)"></textarea>
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

<!-- Signature Pad Script -->
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const canvas = document.getElementById('signature-pad');
    const signaturePad = new SignaturePad(canvas);

    // Clear button function
    window.clearSignature = function () {
      signaturePad.clear();
    };

    // Set current date
    document.getElementById('date').value = new Date().toISOString().split('T')[0];

    // Form submit
    document.getElementById('temperatureForm').addEventListener('submit', function (e) {
      if (signaturePad.isEmpty()) {
        alert('Verification signature is required.');
        e.preventDefault();
        return;
      }

      // Store base64 image in hidden input
      document.getElementById('verification_signature').value = signaturePad.toDataURL();
    });
  });
</script>
@endsection
