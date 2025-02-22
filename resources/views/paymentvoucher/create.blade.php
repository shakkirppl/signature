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
                    <h4 class="card-title">Add Payment Voucher</h4>
                    <div class="col-md-6 heading">
                        <a href=" {{ route('paymentvoucher.index') }}" class="backicon"><i class="mdi mdi-backburger"></i></a>
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

                    <form action="{{ route('paymentvoucher.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                        <div class="row">
                            <!-- First Section -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="code" class="">Code</label>
                                    <input type="text" class="form-control" id="code" name="code" value="{{$invoice_no}}" readonly>
                                </div>

                                <div class="form-group">
                                    <label for="date" class="required">Date</label>
                                    <input type="date" class="form-control" id="date" name="date" required>
                                </div>

                                 <div class="form-group">
                                    <label for="coa_id" class="required"> (COA)</label>
                                        <select class="form-control" name="coa_id" id="coa_id" required>
                                         <option value="">Select Account</option>
                                               @foreach ($coa as $account)
                                                    <option value="{{ $account->id }}">{{ $account->name }}</option>
                                                @endforeach
                                        </select>
                                  </div>

                               

                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="4"></textarea>
                                </div>
                          
</div>
                            <!-- Second Section -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="type" class="required">Type</label>
                                    <select class="form-control" id="type" name="type" required>
                                        <option value="cash">Cash</option>
                                        <option value="bank">Bank</option>
                                    </select>
                                </div>

                                
                                <div id="bankNameField" class="form-group" style="display: none;">
                                     <label for="bank_id" class="required">Bank Name</label>
                                        <select class="form-control" name="bank_id">
                                           <option value="">Select Bank</option>
                                                @foreach ($banks as $bank)
                                                   <option value="{{ $bank->id }}">{{ $bank->bank_name }}</option>
                                                @endforeach
                                        </select>
                                 </div>
                                <div class="form-group">
                                    <label for="amount" class="required">Amount</label>
                                    <input type="number" class="form-control" id="amount" name="amount" placeholder="Enter Amount" required>
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
    document.getElementById('type').addEventListener('change', function() {
    var bankNameField = document.getElementById('bankNameField');
    if (this.value === 'bank') {
        bankNameField.style.display = 'block';
        fetchBankNames(); // Fetch bank names when bank is selected
    } else {
        bankNameField.style.display = 'none';
    }
});


   
</script>

@endsection
