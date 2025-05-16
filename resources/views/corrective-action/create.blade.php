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
                    <h4 class="card-title">Corrective Action Report</h4>
                    <div class="col-md-6 heading">
                        <a href=" {{ route('corrective-action.index') }}" class="backicon"><i class="mdi mdi-backburger"></i></a>
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

                    <form id="temperatureForm"  action="{{ route('corrective-action.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                        <div class="row">
                            <!-- First Section -->
                            <div class="col-md-6">
                              

                                <div class="form-group">
                                    <label for="date" class="required">Date</label>
                                    <input type="date" class="form-control" id="date" name="date" required>
                                </div>
                                <div class="form-group">
                                  <label for="text" class="">Description of non-conformity</label>
                                  <input type="text" class="form-control" id="non_conformity" name="non_conformity" required>
                              </div>  
                               <div class="form-group">
                                  <label for="text" class="">Corrective Action Taken</label>
                                  <input type="text" class="form-control" id="action_taken" name="action_taken" required>
                              </div>
                              <div class="form-group">
                                  <label for="text" class="">Responsible Person</label>
                                  <input type="text" class="form-control" id="responsible_person" name="responsible_person" required>
                              </div>                              
                          
</div>


<div class="col-md-6">
                              

<div class="form-group">
                                    <label for="text" class="">Department</label>
                                    <input type="text" class="form-control" id="department" name="department" >
</div>                           
                              <div class="form-group">
                                  <label for="text" class="">Root  Cause</label>
                                  <input type="text" class="form-control" id="root_cause" name="root_cause" required>
</div>    


 <div class="form-group">
                                    <label for="date" class="required">Date Of Completion</label>
                                    <input type="date" class="form-control" id="date" name="date_of_completion" required>
                                </div>
                                <div class="form-group">
                                  <label for="text" class="">Verified By</label>
                                  <input type="text" class="form-control" id="verified_by" name="verified_by" required>
</div>    
<div class="form-group">
  <label class="required">Inspector Signature</label><br>
  <canvas id="signature-pad" width="400" height="150" style="border:1px solid #ccc;"></canvas><br>
  <button type="button" class="btn btn-sm btn-warning mt-2" onclick="clearSignature()">Clear</button>
  <input type="hidden" name="signature" id="signature">
  </div>


   
                 
                        
</div>
                             
                           

                                
                               
                               
                            </div>
                        </div>

                        <div class="submitbutton">
                    <button type="submit" class="btn btn-primary mb-2 submit">Submit<i class="fas fa-save"></i></button>
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
      document.getElementById('signature').value = signaturePad.toDataURL();
    });
  });
</script>


<script>
        function formatNumber(input) {
            // Remove any existing formatting
            let value = input.value.replace(/,/g, '');
            
            // Convert to a number
            let number = parseFloat(value);
            
            // Format with commas
            if (!isNaN(number)) {
                input.value = new Intl.NumberFormat('en-US').format(number);
            }
        }
    </script>
    <script>
document.addEventListener('DOMContentLoaded', function () {
    const dateInput = document.getElementById('date');
    let today = new Date().toISOString().split('T')[0];
    dateInput.value = today;
});
</script>

@endsection
