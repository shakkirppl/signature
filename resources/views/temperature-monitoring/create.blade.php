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
                    <h4 class="card-title">Temperature Monitoring Record </h4>
                    <div class="col-md-6 heading">
                        <a href=" {{ route('temperature-monitoring.index') }}" class="backicon"><i class="mdi mdi-backburger"></i></a>
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

                    <form action="{{ route('temperature-monitoring.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                        <div class="row">
                            <!-- First Section -->
                            <div class="col-md-6">
                              

                                <div class="form-group">
                                    <label for="date" class="required">Date</label>
                                    <input type="date" class="form-control" id="date" name="date" required>
                                </div>
                                <div class="form-group">
                                  <label for="text" class="">Temp Before (°C)</label>
                                  <input type="text" class="form-control" id="temp_before" name="temp_before" required>
                              </div>  
                               <div class="form-group">
                                  <label for="text" class="">Temp After (°C)</label>
                                  <input type="text" class="form-control" id="temp_after" name="temp_after" required>
                              </div>                              
                          
</div>

<div class="col-md-6">
                              
                                <div class="form-group">
                                    <label for="time" class="required">Time</label>
                                    <input type="time" class="form-control" id="time" name="time" required>
                                </div>
                                <div class="form-group">
                                    <label for="text" class="">Slaughter area</label>
                                    <input type="text" class="form-control" id="slaughter_area" name="slaughter_area" >
                              </div>                           
                              <div class="form-group">
                                  <label for="text" class="">Average Carcass Temp</label>
                                  <input type="text" class="form-control" id="average_carcass" name="average_carcass" required>
                             </div>          
           
                          
                              <div class="form-group">
                                  <label for="text" class="">Inspector Name and Signature</label>
                                  <input type="text" class="form-control" id="inspector_name" name="inspector_name" required>
                                  <canvas id="inspector-signature-pad" width="400" height="150" style="border:1px solid #ccc;"></canvas>
  
                               <button type="button" class="btn btn-sm btn-warning mt-2" onclick="clearInspectorSignature()">Clear </button>
                               <input type="hidden" id="inspector_signature_image" name="inspector_signature">
                             
                        
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
  const inspectorCanvas = document.getElementById('inspector-signature-pad');
  const inspectorSignaturePad = new SignaturePad(inspectorCanvas);

  function clearInspectorSignature() {
    inspectorSignaturePad.clear();
  }

  document.querySelector('form').addEventListener('submit', function (e) {
    if (!inspectorSignaturePad.isEmpty()) {
      const dataUrl = inspectorSignaturePad.toDataURL();
      document.getElementById('inspector_signature_image').value = dataUrl;
    } else {
      e.preventDefault();
      alert('Please provide Inspector Signature.');
    }
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
