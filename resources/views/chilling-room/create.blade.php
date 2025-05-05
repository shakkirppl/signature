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
                    <h4 class="card-title">Chilling Room Temperature & Humidity Log</h4>
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
                                    <label for="text" class="">Initial carcass temp</label>
                                    <input type="text" class="form-control" id="Product_id" name="Product_id" >
                               </div>  
                               <div class="form-group">
                                  <label for="text" class="">Exit temp of carcasses</label>
                                  <input type="text" class="form-control" id="Product_id" name="Product_id" required>
                              </div>                              
                          
</div>

<div class="col-md-6">
                              

<div class="form-group">
                                  <label for="text" class="">Time of entry</label>
                                  <input type="text" class="form-control" id="Product_id" name="Product_id" required>
</div>                              
                              <div class="form-group">
                                  <label for="text" class="">Chiller temp & humidity every 2-4 hours</label>
                                  <input type="text" class="form-control" id="processing_line" name="processing_line" required>
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
