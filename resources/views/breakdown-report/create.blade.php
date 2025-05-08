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
                    <h4 class="card-title">Breakdown Report</h4>
                    <div class="col-md-6 heading">
                        <a href=" {{ route('breakdown-report.index') }}" class="backicon"><i class="mdi mdi-backburger"></i></a>
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

                    <form action="{{ route('breakdown-report.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                        <div class="row">
                            <!-- First Section -->
                            <div class="col-md-6">
                              

                                <div class="form-group">
                                    <label for="date" class="required">Date</label>
                                    <input type="date" class="form-control" id="date" name="date" required>
                                </div>
                                <div class="form-group">
    <label for="problem_reported" class="">Problem reported</label>
    <textarea class="form-control" id="problem_reported" name="problem_reported" rows="3"></textarea>
</div>  

<div class="form-group">
    <label for="action_taken" class="">Action taken</label>
    <textarea class="form-control" id="action_taken" name="action_taken" rows="3" required></textarea>
</div>
                            
                          
</div>

<div class="col-md-6">
                              

<div class="form-group">
                                  <label for="text" class="">Equipment ID</label>
                                  <input type="text" class="form-control" id="equipment_id" name="equipment_id" required>
</div>                              
                              <div class="form-group">
                                  <label for="text" class="">Time out of service</label>
                                  <input type="text" class="form-control" id="time_out_of_service" name="time_out_of_service" required>
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
