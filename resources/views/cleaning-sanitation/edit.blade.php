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
          <h4 class="card-title">Edit Cleaning and Sanitation Record</h4>
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

          <form id="editForm" action="{{ route('cleaning-sanitation.update', $record->id) }}" method="POST">
            @csrf
            <div class="row">
              <!-- Left Column -->
              <div class="col-md-6">
                <div class="form-group">
                  <label class="required">Date</label>
                  <input type="date" name="date" class="form-control" value="{{ $record->date }}" required>
                </div>

                <div class="form-group">
                  <label class="required">Cleaning Method Used</label>
                  <input type="text" name="cleaning_method" class="form-control" value="{{ $record->cleaning_method }}" required>
                </div>

                <div class="form-group">
                  <label class="required">Cleaning Agent Used</label>
                  <input type="text" name="cleaning_agent" class="form-control" value="{{ $record->cleaning_agent }}" required>
                </div>

                <div class="form-group">
                  <label class="required">Verification Signature</label><br>
                  <canvas id="signature-pad" width="400" height="150" style="border:1px solid #ccc;"></canvas><br>
                  <button type="button" class="btn btn-warning mt-2" onclick="clearSignature()">Clear</button>
                  <input type="hidden" name="verification_signature" id="verification_signature">
                
                </div>
              </div>

              <!-- Right Column -->
              <div class="col-md-6">
                <div class="form-group">
                  <label>Area Cleaned</label>
                  <input type="text" name="area_cleaned" class="form-control" value="{{ $record->area_cleaned }}">
                </div>

                <div class="form-group">
                  <label class="required">Cleaner Name</label>
                  <input type="text" name="cleaner_name" class="form-control" value="{{ $record->cleaner_name }}" required>
                </div>

                <div class="form-group">
                  <label class="required">Supervisor Check</label>
                  <select name="supervisor_check" class="form-control" required>
                    <option value="">Select</option>
                    <option value="Yes" {{ $record->supervisor_check == 'Yes' ? 'selected' : '' }}>Yes</option>
                    <option value="No" {{ $record->supervisor_check == 'No' ? 'selected' : '' }}>No</option>
                  </select>
                </div>

                <div class="form-group">
                  <label>Comments</label>
                  <textarea name="comments" class="form-control" rows="4">{{ $record->comments }}</textarea>
                </div>
                  @if($record->verification_signature)
                    <p class="mt-2">Current Signature:</p>
                    <img src="{{ asset('storage/signatures/' . $record->verification_signature) }}" width="200">
                  @endif
              </div>
            </div>

            <div class="submitbutton">
              <button type="submit" class="btn btn-primary">Update <i class="fas fa-save"></i></button>
            </div>
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

  document.getElementById('editForm').addEventListener('submit', function (e) {
    if (!signaturePad.isEmpty()) {
      document.getElementById('verification_signature').value = signaturePad.toDataURL();
    }
  });
</script>
@endsection
