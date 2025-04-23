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
                    <h4 class="card-title">Add Expense Voucher</h4>
                    <div class="col-md-6 heading">
                        <a href=" {{ route('expensevoucher.index') }}" class="backicon"><i class="mdi mdi-backburger"></i></a>
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

                    <form action="{{ route('expensevoucher.store') }}" method="POST" enctype="multipart/form-data">
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
                                  <label for="shipment_id" class="form-label">Shipment No:</label>
                                     <select name="shipment_id" id="shipment_id" class="form-control" required>
                                          <option value="">Select Shipment</option>
                                       @foreach ($shipments as $shipment)
                                            <option value="{{ $shipment->id }}">{{ $shipment->shipment_no }}</option>
                                        @endforeach
                                      </select>
                               </div>
                                <div class="form-group">
                                    <label for="type" class="required">Type</label>
                                    <select class="form-control" id="type" name="type" required>
                                    <option value="bank">Bank</option>
                                        <option value="mobilemoney">Mobile Money</option>   
                                        <option value="cash">Cash</option>
                                    </select>
                                </div>

                                
                                <div id="bankNameField" class="form-group" style="">
                                     <label for="bank_id" class="required">Bank Name</label>
                                        <select class="form-control" name="bank_id">
                                           <option value="">Select Bank</option>
                                                @foreach ($banks as $bank)
                                                   <option value="{{ $bank->id }}">{{ $bank->bank_name }}</option>
                                                @endforeach
                                        </select>
                                 </div>
                                 <div class="form-group ">
                                    <label class=" required">Currency:</label>
                                   
                                        <select class="form-control" name="currency">
                                            <option value="Shilling" selected>Shilling</option>
                                            <option value="USD">USD</option>
                                        </select>
                                 </div>
                                <div class="form-group">
                                    <label for="amount" class="required">Amount</label>
                                    <input type="text" class="form-control" id="amount" name="amount" placeholder="Enter Amount" required
                                    id="formattedNumber" oninput="formatNumber(this)">
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
    var bankSelect = bankNameField.querySelector('select');

    if (this.value === 'bank') {
        bankNameField.style.display = 'block';
        bankSelect.setAttribute('required', 'required'); // Make bank_id required when bank is selected
    } else {
        bankNameField.style.display = 'none';
        bankSelect.removeAttribute('required'); // Remove required if cash is selected
    }
});



   
</script>
<script>
function formatNumber(input) {
    let value = input.value;

    // Remove any character except digits and decimal point
    value = value.replace(/[^0-9.]/g, '');

    // Allow only one decimal point
    const parts = value.split('.');
    if (parts.length > 2) {
        value = parts[0] + '.' + parts[1]; // Keep only the first decimal
    }

    // Format the number part before the decimal with commas
    const integerPart = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');

    // Combine with decimal part if exists
    input.value = parts.length > 1 ? `${integerPart}.${parts[1]}` : integerPart;
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
