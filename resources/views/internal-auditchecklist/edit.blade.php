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
          <h4 class="card-title">Edit Internal Audit Checklist</h4>
          <div class="col-md-6 heading">
            <a href="{{ route('internal-auditchecklist.index') }}" class="backicon"><i class="mdi mdi-backburger"></i></a>
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

          <form id="auditForm" action="{{ route('internal-auditchecklist.update', $record->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="audit_date" class="required">Audit Date</label>
                  <input type="date" class="form-control" id="audit_date" name="audit_date" value="{{ old('audit_date', $record->audit_date) }}" required>
                </div>
                <div class="form-group">
                  <label for="area_audited" class="required">Area Audited</label>
                  <input type="text" class="form-control" id="area_audited" name="area_audited" value="{{ old('area_audited', $record->area_audited) }}" required>
                </div>
                <div class="form-group">
                  <label for="auditor_name" class="required">Auditor Name</label>
                  <input type="text" class="form-control" id="auditor_name" name="auditor_name" value="{{ old('auditor_name', $record->auditor_name) }}" required>
                </div>
                <div class="form-group">
                  <label for="non_conformities_found">Non-Conformities Found</label>
                  <textarea class="form-control" id="non_conformities_found" name="non_conformities_found" rows="3">{{ old('non_conformities_found', $record->non_conformities_found) }}</textarea>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <label for="corrective_actions_needed">Corrective Actions Needed</label>
                  <textarea class="form-control" id="corrective_actions_needed" name="corrective_actions_needed" rows="3">{{ old('corrective_actions_needed', $record->corrective_actions_needed) }}</textarea>
                </div>
                <div class="form-group">
                  <label for="follow_up_date" class="required">Follow-Up Date</label>
                  <input type="date" class="form-control" id="follow_up_date" name="follow_up_date" value="{{ old('follow_up_date', $record->follow_up_date) }}" required>
                </div>

                <div class="form-group">
                  <label class="required">Auditor Signature</label><br>
                  <canvas id="signature-pad" width="400" height="100" style="border:1px solid #ccc;"></canvas><br>
                  <button type="button" class="btn btn-sm btn-warning" onclick="clearSignature()">Clear</button>
                  <input type="hidden" id="signature" name="auditor_signature">

                  @if ($record->auditor_signature)
                    <div class="mt-2">
                      <small>Current Signature:</small><br>
                      <img src="{{ asset('storage/signatures/' . $record->auditor_signature) }}" width="200">
                    </div>
                  @endif
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

    window.clearSignature = function () {
      signaturePad.clear();
    };

    const form = document.getElementById('auditForm');
    form.addEventListener('submit', function (e) {
      if (!signaturePad.isEmpty()) {
        document.getElementById('signature').value = signaturePad.toDataURL();
      }
    });
  });
</script>

@endsection
