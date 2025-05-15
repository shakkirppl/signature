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
                    <h4 class="card-title">Temperature Monitoring Record</h4>
                    <div class="col-md-6 heading">
                        <a href="{{ route('temperature-monitoring.index') }}" class="backicon"><i class="mdi mdi-backburger"></i></a>
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

                    <form id="temperatureForm" action="{{ route('temperature-monitoring.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <!-- First Column -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date" class="required">Date</label>
                                    <input type="date" class="form-control" id="date" name="date" required>
                                </div>
                                <div class="form-group">
                                    <label for="temp_before">Temp Before (°C)</label>
                                    <input type="text" class="form-control" id="temp_before" name="temp_before" required>
                                </div>
                                <div class="form-group">
                                    <label for="temp_after">Temp After (°C)</label>
                                    <input type="text" class="form-control" id="temp_after" name="temp_after" required>
                                </div>
                            </div>

                            <!-- Second Column -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="time" class="required">Time</label>
                                    <input type="text" class="form-control timepicker" id="time" name="time" required>
                                </div>
                                 
                                <div class="form-group">
                                    <label for="slaughter_area">Slaughter Area</label>
                                    <input type="text" class="form-control" id="slaughter_area" name="slaughter_area">
                                </div>
                                <div class="form-group">
                                    <label for="average_carcass">Average Carcass Temp</label>
                                    <input type="text" class="form-control" id="average_carcass" name="average_carcass" required>
                                </div>
                                <div class="form-group">
                                    <label for="inspector_name">Inspector Name</label>
                                    <input type="text" class="form-control" id="inspector_name" name="inspector_name" required>
                                </div>
                                <div class="form-group">
                                    <label>Inspector Signature</label><br>
                                    <canvas id="signature-pad" width="400" height="150" style="border:1px solid #ccc;"></canvas><br>
                                    <button type="button" class="btn btn-sm btn-warning mt-2" onclick="clearSignature()">Clear</button>
                                    <input type="hidden" name="inspector_signature" id="inspector_signature">
                                    <!--  -->
                                </div>
                            </div>
                        </div>

                        <div class="submitbutton mt-3">
                            <button type="submit" class="btn btn-primary mb-2 submit">Submit <i class="fas fa-save"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JS for Signature Pad -->
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

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
      document.getElementById('inspector_signature').value = signaturePad.toDataURL();
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
<script>
 document.addEventListener("DOMContentLoaded", function() {
    flatpickr(".timepicker", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "h:i K", // 12-hour format with AM/PM
        time_24hr: false,
        onOpen: function(selectedDates, dateStr, instance) {
            let currentTime = new Date().toLocaleTimeString("en-US", { 
                timeZone: "Africa/Dar_es_Salaam",
                hour: "2-digit",
                minute: "2-digit",
                hour12: true 
            });
            instance.setDate(currentTime, true);
        }
    });
});


</script>

@endsection
