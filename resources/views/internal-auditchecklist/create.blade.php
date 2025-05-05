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
          <h4 class="card-title">Internal Audit Checklist and Report</h4>
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

          <form action="{{ route('internal-auditchecklist.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
              <!-- Left Column -->
              <div class="col-md-6">
                <div class="form-group">
                  <label for="audit_date" class="required">Audit Date</label>
                  <input type="date" class="form-control" id="audit_date" name="audit_date" required>
                </div>
                <div class="form-group">
                  <label for="area_audited" class="required">Area Audited</label>
                  <input type="text" class="form-control" id="area_audited" name="area_audited" required>
                </div>
                <div class="form-group">
                  <label for="auditor_name" class="required">Auditor Name</label>
                  <input type="text" class="form-control" id="auditor_name" name="auditor_name" required>
                </div>
                <div class="form-group">
                  <label for="non_conformities_found">Non-Conformities Found</label>
                  <textarea class="form-control" id="non_conformities_found" name="non_conformities_found" rows="3"></textarea>
                </div>
              </div>

              <!-- Right Column -->
              <div class="col-md-6">
                <div class="form-group">
                  <label for="corrective_actions_needed">Corrective Actions Needed</label>
                  <textarea class="form-control" id="corrective_actions_needed" name="corrective_actions_needed" rows="3"></textarea>
                </div>
                <div class="form-group">
                  <label for="follow_up_date" class="required">Follow-Up Date</label>
                  <input type="date" class="form-control" id="follow_up_date" name="follow_up_date" required>
                </div>
                <div class="form-group">
                  <label for="auditor_signature" class="required">Auditor Signature</label>
                  <input type="file" class="form-control" id="auditor_signature" name="auditor_signature" accept="image/*" required>
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
    const dateInput = document.getElementById('audit_date');
    let today = new Date().toISOString().split('T')[0];
    dateInput.value = today;
  });
</script>
@endsection
