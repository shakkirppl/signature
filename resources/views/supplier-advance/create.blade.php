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
                    <h4 class="card-title">Supplier Advance</h4>
                    <div class="col-md-6 heading">
                        <a href=" {{ route('supplieradvance.index') }}" class="backicon"><i class="mdi mdi-backburger"></i></a>
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

                    <form action="{{ route('supplieradvance.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                        <div class="row">
                            <!-- First Section -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="code" class="">Code</label>
                                    <input type="text" class="form-control" id="code" name="code" value="{{$invoice_no}}" readonly >
                                </div>

                               
                                <div id="shipment" class="form-group" >
                                     <label for="shipment_id" class="required">Shipment</label>
                                        <select class="form-control" name="shipment_id">
                                           <option value="">Select Shipment</option>
                                                @foreach ($shipments as $shipment)
                                                   <option value="{{ $shipment->id }}">{{ $shipment->shipment_no }}</option>
                                                @endforeach
                                        </select>
                                 </div>
                                

                                 <div class="form-group">
                                    <label for="order_no">Purchase No</label>
                                        <input type="text" class="form-control" id="order_no" name="order_no" readonly>
                                        <input type="hidden" id="purchaseOrder_id" name="purchaseOrder_id">

                                </div>
                                <div class="form-group">
                                    <label for="type" class="required">Type</label>
                                    <select class="form-control" id="type" name="type" required>
                                       <option value="bank">Bank</option>
                                        <option value="cash">Cash</option>
                                        <option value="mobilemoney">Mobile Money</option>
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
                               
                                
                          
</div>
                            <!-- Second Section -->
                             
                            <div class="col-md-6">

                            <div class="form-group">
                                    <label for="date" class="required">Date</label>
                                    <input type="date" class="form-control" id="date" name="date" required>
                                </div>
                                <div class="form-group">
                                    <label for="supplier_id" class="required">Supplier</label>
                                    <select class="form-control" name="supplier_id" id="supplier_id" required>
                                      <option value="">Select Supplier</option>
                                   </select>
                                </div>
                                <div class="form-group">
                                    <label for="advance_amount" class="required">Advance Amount</label>
                                    <input type="text" class="form-control" id="advance_amount" name="advance_amount" placeholder="Enter Amount" required
                                    id="formattedNumber" oninput="formatNumber(this)">
                                </div>
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="4"></textarea>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
$(document).ready(function() {
    // Fetch suppliers based on the selected shipment
    $('select[name="shipment_id"]').on('change', function() {
        var shipmentId = $(this).val();
        if (shipmentId) {
            $.ajax({
                url: '{{ route("getSuppliersByShipment") }}',
                type: 'GET',
                data: { shipment_id: shipmentId },
                success: function(data) {
                    var supplierSelect = $('select[name="supplier_id"]');
                    supplierSelect.empty();
                    supplierSelect.append('<option value="">Select Supplier</option>');
                    $.each(data.suppliers, function(key, value) {
                        supplierSelect.append('<option value="'+ value.id +'">'+ value.name +'</option>');
                    });
                }
            });
        } else {
            $('select[name="supplier_id"]').empty().append('<option value="">Select Supplier</option>');
        }
    });

    // Fetch order_no based on selected supplier
    $('select[name="supplier_id"]').on('change', function() {
        var supplierId = $(this).val();
        var shipmentId = $('select[name="shipment_id"]').val();
        if (supplierId) {
            $.ajax({
                url: '{{ route("getOrdersBySupplier") }}',
                type: 'GET',
                data: { supplier_id: supplierId, shipment_id: shipmentId },
                success: function(data) {
                    $('#order_no').val(data.order_no);
                    $('#purchaseOrder_id').val(data.purchaseOrder_id);
                }
            });
        }
    });
});
</script>






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
document.addEventListener('DOMContentLoaded', function () {
    const dateInput = document.getElementById('date');
    let today = new Date().toISOString().split('T')[0];
    dateInput.value = today;
});
</script>
@endsection
