@extends('layouts.layout')
@section('content')
<style>
  .required:after {
    content:" *";
    color: red;
  }

  .radio-inline {
        font-weight: bold;
    }
</style>
<div class="main-panel">
<div class="content-wrapper">
<div class="col-12 grid-margin createtable">
              <div class="card">
                <div class="card-body">
           
                  
                        <div class="row">
                        <div class="col-md-6">
                                 <h4 class="card-title">New Supplier</h4>
                        </div>
                           <div class="col-md-6 heading">
                             <a href="{{ route('supplier.index') }}" class="backicon"><i class="mdi mdi-backburger"></i></a>
                        </div>
                        <div class="col-md-6">
                        </div>
                    </div>
                    
                    <div class="row">
                    <br>
                   </div>
                
                  <div class="col-xl-12 col-md-12 col-sm-12 col-12">
           
          @if ($errors->any())
          <div class="alert alert-danger">
            <ul>
              @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div><br />
          @endif
          
        </div>
                  <form class="form-sample"  action="{{ route('supplier.store') }}" method="post" enctype="multipart/form-data"  >
                          {{csrf_field()}}
                    <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row">
                          <label class="col-sm-2 col-form-label required"> Supplier Code</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control" placeholder=" Code" name="code"  required="true" value="{{$invoice_no}}" readonly />
                          </div>
                        </div>
                      </div>

                      <div class="col-md-12">
                        <div class="form-group row">
                          <label class="col-sm-2 col-form-label required"> Supplier Name</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control" placeholder=" Name" name="name"  required="true"  value="{{old('name')}}" />
                          </div>
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="form-group row">
                          <label class="col-sm-2 col-form-label"> Email</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control" placeholder="E-mail" name="email"  value="{{old('email')}}"   />
                          </div>
                        </div>
                        <div class="col-md-12">
                        <div class="form-group row">
                          <label class="col-sm-2 col-form-label required"> Contact No</label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control" placeholder="Contact No" name="contact_number"  required="true" value="{{old('contact_number')}}"  />
                          </div>
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="form-group row">
                          <label class="col-sm-2 col-form-label"> Address</label>
                          <div class="col-sm-9">
                            <textarea class="form-control" name="address" ></textarea>
                         
                          </div>
                        </div>
                      </div>

                      <div class="col-md-12">
                        <div class="form-group row">
                          <label class="col-sm-2 col-form-label required"> Credit Limit Days</label>
                          <div class="col-sm-9">
                          <input type="text" class="form-control"  name="credit_limit_days"  required="true" value="{{old('credit_limit_days')}}"  />

                          </div>
                        </div>
                      </div>   
                      
                       
                      <div class="col-md-12">
    <div class="form-group row">
        <label class="col-sm-2 col-form-label "> Opening Balance</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" placeholder="₹ Opening Balance" name="opening_balance" id="opening_balance" value="{{ old('opening_balance') }}" />
        </div>
    </div>
</div>


<div class="form-group row">
    <label class="col-sm-2 col-form-label"></label>
    <div class="col-sm-9">
        <label class="radio-inline">
            <input type="radio" name="dr_cr" value="Dr" checked> <strong>Dr</strong>
        </label>
        <label class="radio-inline">
            <input type="radio" name="dr_cr" value="Cr"> <strong>Cr</strong>
        </label>
    </div>
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
               
@endsection
@section('script')
<script src="{{url('front-end/assets/js/jquery-3.3.1.js')}}"></script>
<script type="text/javascript">
         $('#payment_terms').change(function(){
         if($(this).val() == 'CREDIT'){
           
             document.getElementById("credit").style.display = "block";
       
        }
        else{
          document.getElementById("credit").style.display = "none";
          // $('#credit').style.display="none";
       
        }


    });
  </script>
<script type="text/javascript">
    $(document).ready(function(){
        $('#opening_balance').on('input', function(){
            if($(this).val().trim() !== '') {
                $('#dr_cr_section').show();
            } else {
                $('#dr_cr_section').hide();
            }
        });
    });
</script>
@endsection

