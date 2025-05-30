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
                    <h4 class="card-title">GMP Daily Checklist</h4>
                    <div class="col-md-6 heading">
                        <a href=" {{ route('gmp.index') }}" class="backicon"><i class="mdi mdi-backburger"></i></a>
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

                    <form action="{{ route('gmp.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                        <div class="row">
                            <!-- First Section -->
                            <div class="col-md-6">
                              

                                <div class="form-group">
                                    <label for="date" class="required">Date</label>
                                    <input type="date" class="form-control" id="date" name="date" required>
                                </div>
                                <div class="form-group">
                                  <label for="text" class="">Facility cleanliness</label>
                                  <input type="text" class="form-control" id="facility_cleanliness" name="facility_cleanliness" required>
                              </div>  
                               <div class="form-group">
                                  <label for="text" class="">Pest control</label>
                                  <input type="text" class="form-control" id="pest_control" name="pest_control" required>
                              </div>                              
                          
</div>

<div class="col-md-6">
                              

<div class="form-group">
                                    <label for="text" class="">Personal hygiene</label>
                                    <input type="text" class="form-control" id="personal_hygiene" name="personal_hygiene" >
</div>                           
                              <div class="form-group">
                                  <label for="text" class="">Equipment sanitation</label>
                                  <input type="text" class="form-control" id="equipment_sanitation" name="equipment_sanitation" required>
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
