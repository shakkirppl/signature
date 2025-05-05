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
                    <h4 class="card-title">Cleaning and Sanitation Record After Production </h4>
                    <div class="col-md-6 heading">
                        <a href=" {{ route('cleaning-sanitation.index') }}" class="backicon"><i class="mdi mdi-backburger"></i></a>
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

                    <form action="{{ route('cleaning-sanitation.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                        <div class="row">
                            <!-- First Section -->
                            <div class="col-md-6">
                              

                                <div class="form-group">
                                    <label for="date" class="required">Date</label>
                                    <input type="date" class="form-control" id="date" name="date" required>
                                </div>
                                <div class="form-group">
                                  <label for="text" class="">Cleaning Method  Used</label>
                                  <input type="text" class="form-control" id="cleaning_method" name="cleaning_method" required>
                              </div>  
                               <div class="form-group">
                                  <label for="text" class="">Cleaning Agent Used</label>
                                  <input type="text" class="form-control" id="cleaning_agent" name="cleaning_agent" required>
                              </div>
                              <div class="form-group">
                                  <label for="text" class="">Verification signature</label>
                                  <input type="text" class="form-control" id="verification_signature" name="pest_control" required>
                              </div>                              
                          
</div>


<div class="col-md-6">
                              

<div class="form-group">
                                    <label for="text" class="">Area cleaned</label>
                                    <input type="text" class="form-control" id="area_cleaned" name="area_cleaned" >
</div>                           
                              <div class="form-group">
                                  <label for="text" class="">Cleaner Name</label>
                                  <input type="text" class="form-control" id="cleaner_name" name="cleaner_name" required>
</div>          
<div class="form-group">
    <label for="supervisor_check">Supervisor Check</label>
    <select class="form-control" id="supervisor_check" name="supervisor_check" required>
        <option value="">Select</option>
        <option value="Yes">Yes</option>
        <option value="No">No</option>
    </select>
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
