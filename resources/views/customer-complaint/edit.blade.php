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
          <h4 class="card-title">Edit Customer Complaint</h4>
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

          <form id="temperatureForm" action="{{ route('customer-complaint.update', $record->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
              <!-- Left Column -->
              <div class="col-md-6">
                <div class="form-group">
                  <label for="date_received" class="required">Date Received</label>
                  <input type="date" class="form-control" id="date_received" name="date_received" value="{{ $record->date_received }}" required>
                </div>
                <div class="form-group">
                  <label for="customer_name" class="required">Customer Name</label>
                  <input type="text" class="form-control" id="customer_name" name="customer_name" value="{{ $record->customer_name }}" required>
                </div>
                <div class="form-group">
                  <label for="complaint_details" class="required">Complaint Details</label>
                  <textarea class="form-control" id="complaint_details" name="complaint_details" rows="3" required>{{ $record->complaint_details }}</textarea>
                </div>
                <div class="form-group">
                  <label for="investigation_findings" class="required">Investigation Findings</label>
                  <textarea class="form-control" id="investigation_findings" name="investigation_findings" rows="3" required>{{ $record->investigation_findings }}</textarea>
                </div>
                 <!-- @if($record->manager_signature)
                    <div class="mt-2">
                      <strong>Current Signature:</strong><br>
                      <img src="{{ asset('storage/signatures/' . $record->manager_signature) }}" alt="Signature" >
                    </div>
                  @endif -->
              </div>

              <!-- Right Column -->
              <div class="col-md-6">
                <div class="form-group">
                  <label for="corrective_action" class="required">Corrective Action</label>
                  <textarea class="form-control" id="corrective_action" name="corrective_action" rows="3" required>{{ $record->corrective_action }}</textarea>
                </div>
                <div class="form-group">
                  <label for="responsible_person" class="required">Responsible Person</label>
                  <input type="text" class="form-control" id="responsible_person" name="responsible_person" value="{{ $record->responsible_person }}" required>
                </div>
                <div class="form-group">
                  <label for="date_closed" class="required">Date Closed</label>
                  <input type="date" class="form-control" id="date_closed" name="date_closed" value="{{ $record->date_closed }}" required>
                </div>

                <div class="form-group">
                  <label class="required">Manager Review Signature</label>
                  <br>
                  <canvas id="signature-pad" width="400" height="100" style="border:1px solid #ccc;"></canvas>
                  <br>
                  <button type="button" class="btn btn-sm btn-warning" onclick="clearSignature()">Clear</button>
                  <input type="hidden" id="signature" name="manager_signature">
                 
                </div>
                

              </div>
            </div>

            <div class="submitbutton">
              <button type="submit" class="btn btn-primary mb-2 submit">Update <i class="fas fa-save"></i></button>
            </div>
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
    const hiddenInput = document.getElementById('signature');

    const existingSignature = "{{ asset('storage/signatures/' . $record->manager_signature) }}";
    if (existingSignature && {{ $record->manager_signature ? 'true' : 'false' }}) {
      const img = new Image();
      img.src = existingSignature;
      img.onload = () => {
        const ctx = canvas.getContext('2d');
        ctx.drawImage(img, 0, 0);
      };
    }

    window.clearSignature = function () {
      signaturePad.clear();
    };

    document.getElementById('temperatureForm').addEventListener('submit', function (e) {
      if (!signaturePad.isEmpty()) {
        hiddenInput.value = signaturePad.toDataURL();
      }
    });
  });
</script>

@endsection
