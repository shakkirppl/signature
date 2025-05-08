@extends('layouts.layout')
@section('content')
<style>
  .required:after {
    content: " *";
    color: red;
  }
</style>
<!--  -->
<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Dispatch Record Form</h4>
                    <div class="col-md-6 heading">
                        <a href=" {{ route('dispatch-record.index') }}" class="backicon"><i class="mdi mdi-backburger"></i></a>
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
                    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
                    <form action="{{ route('dispatch-record.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                        <div class="row">
                            <!-- First Section -->
                            <div class="col-md-6">
                              

                                <div class="form-group">
                                    <label for="date" class="required">Date</label>
                                    <input type="date" class="form-control" id="date" name="date" required>
                                </div>
                                <div class="form-group">
                                    <label for="text" class="">No. of carcasses dispatched</label>
                                    <input type="text" class="form-control" id="no_of_carcasses" name="no_of_carcasses" >
                               </div>  
                               <div class="form-group">
                                  <label for="text" class="">Customer name/export destination</label>
                                  <input type="text" class="form-control" id="customer_name" name="customer_name" required>
                              </div> 
                              <div class="form-group">
                                    <label for="date" class="required">Production date</label>
                                    <input type="date" class="form-control" id="production_date" name="production_date" required>
                                </div>                             
                          
</div>

<div class="col-md-6">
                              

<div class="form-group">
                                  <label for="text" class="">Dispatch temperature</label>
                                  <input type="text" class="form-control" id="dispatch_temperature" name="dispatch_temperature" >
</div>                              
                              <div class="form-group">
                                  <label for="text" class="">Packaging material used</label>
                                  <input type="text" class="form-control" id="packaging_material_used" name="packaging_material_used" >
</div>          
<div class="form-group">
                                  <label for="text" class="">Comments</label>
                                  <input type="text" class="form-control" id="comments" name="comments" >
</div>        
<div class="form-group">
                                    <label for="date" class="required">Expire Date</label>
                                    <input type="date" class="form-control" id="expire_date" name="expire_date" required>
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
