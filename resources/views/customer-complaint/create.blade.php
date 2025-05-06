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
          <h4 class="card-title">Customer Complaint Handling Form</h4>
          <div class="col-md-6 heading">
            <a href="{{ route('customer-complaint.index') }}" class="backicon"><i class="mdi mdi-backburger"></i></a>
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

          <form action="{{ route('customer-complaint.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
              <!-- Left Column -->
              <div class="col-md-6">
                <div class="form-group">
                  <label for="date_received" class="required">Date Received</label>
                  <input type="date" class="form-control" id="date_received" name="date_received" required>
                </div>
                <div class="form-group">
                  <label for="customer_name" class="required">Customer Name</label>
                  <input type="text" class="form-control" id="customer_name" name="customer_name" required>
                </div>
                <div class="form-group">
                  <label for="complaint_details" class="required">Complaint Details</label>
                  <textarea class="form-control" id="complaint_details" name="complaint_details" rows="3" required></textarea>
                </div>
                <div class="form-group">
                  <label for="investigation_findings" class="required">Investigation Findings</label>
                  <textarea class="form-control" id="investigation_findings" name="investigation_findings" rows="3" required></textarea>
                </div>
              </div>

              <!-- Right Column -->
              <div class="col-md-6">
                <div class="form-group">
                  <label for="corrective_action" class="required">Corrective Action</label>
                  <textarea class="form-control" id="corrective_action" name="corrective_action" rows="3" required></textarea>
                </div>
                <div class="form-group">
                  <label for="responsible_person" class="required">Responsible Person</label>
                  <input type="text" class="form-control" id="responsible_person" name="responsible_person" required>
                </div>
                <div class="form-group">
                  <label for="date_closed" class="required">Date Closed</label>
                  <input type="date" class="form-control" id="date_closed" name="date_closed" required>
                </div>
                <div class="form-group">
  <label class="required">Manager Review Signature</label>
  <br>
  <canvas id="signature-pad" width="400" height="200" style="border:1px solid #ccc;"></canvas>
  <br>
  <button type="button" class="btn btn-sm btn-warning" onclick="clearSignature()">Clear</button>
  <input type="hidden" id="signature_image" name="manager_signature">
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
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script>
  let canvas = document.getElementById('signature-pad');
  let signaturePad = new SignaturePad(canvas);

  function clearSignature() {
    signaturePad.clear();
  }

  // On submit, convert the signature to base64 and store it in hidden input
  document.querySelector('form').addEventListener('submit', function (e) {
    if (!signaturePad.isEmpty()) {
      let dataUrl = signaturePad.toDataURL();
      document.getElementById('signature_image').value = dataUrl;
    } else {
      e.preventDefault();
      alert('Please provide a signature.');
    }
  });
</script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const dateInput = document.getElementById('date_received');
    let today = new Date().toISOString().split('T')[0];
    dateInput.value = today;
  });
</script>


@endsection
