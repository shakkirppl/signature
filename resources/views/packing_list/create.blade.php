@extends('layouts.layout')
@section('content')
<style>
.table {
    border-collapse: collapse;
    width: 40%;
}

button.remove-row {
    padding: 5px 10px;
}
</style>
<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-12 grid-margin createtable">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 col-md-6 col-sm-6 col-xs-12">
                            <h4 class="card-title"> Packing List</h4>
                        </div>
                        <div class="col-6 col-md-6 col-sm-6 col-xs-12 heading" style="text-align:end;">
                            <a href="{{ url('packinglist-index') }}" class="backicon"><i class="mdi mdi-backburger"></i></a>
                        </div>
                    </div>
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div><br />
                    @endif
                    <form action="{{ route('packinglist.store') }}" method="POST">
                    @csrf
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="code" class="form-label">Order No:</label>
                                <input type="text" class="form-control" id="code" name="packing_no" value="{{ $invoice_no }}" readonly>
                            </div>
                            <div class="col-md-4">
                                <label for="date" class="form-label">Date:</label>
                                <input type="date" class="form-control" id="date" name="date" required>
                            </div>
                            <div class="col-md-4">
                                <label for="salesOrder_id" class="form-label">Sales Order No:</label>
                                <select name="salesOrder_id" id="salesOrder_id" class="form-control" required>
                                 <option value="">Select sales no</option>
                                    @foreach ($SalesOrders as $SalesOrder)
                                    <option value="{{ $SalesOrder->id }}">{{ $SalesOrder->order_no }}</option>
                                    @endforeach
                               </select>
                               
                          </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="customer_id" class="form-label"> Customer:</label>
                                <select name="customer_id" id="customer_id" class="form-control" required >
                                    <option value="">Select Customers</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->customer_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                             <label for="shipping_mode" class="form-label">Shipping Mode</label>
                               <select class="form-control" id="shipping_mode" name="shipping_mode">
                                 <option value="airfreight" selected>Air Freight</option>
                                    
                               </select>
                            </div>

                            <div class="col-md-4">
                             <label for="shipping_agent" class="form-label">Shipping Agent</label>
                               <select class="form-control" id="shipping_agent" name="shipping_agent">
                                 <option value="Qatar Airways" selected>Qatar Airways</option>
                                 <option value="Ethiopian Airlines">Ethiopian Airlines</option>
                             </select>
                           </div>
                           <div class="col-md-4">
                                <label for="text" class="form-label">Terms of delivery:</label>
                                <select class="form-control" id="terms_of_delivery" name="terms_of_delivery">
                                 <option value="C&F /DOHA, QATAR" selected>C&F /DOHA, QATAR</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="text" class="form-label">Terms of payment:</label>
                                <select class="form-control" id="terms_of_payment" name="terms_of_payment">
                                 <option value="100% After Receiving Goods" selected>100% After Receiving Goods</option>
                                 <option value="100% Advance Payment">100% Advance Payment</option>
                                 <option value="50% Advance Payment">50% Advance Payment</option>
                                 <option value="50% After Delivery">50% After Delivery</option>
                             </select>
                            </div>
                            <div class="col-md-4">
                             <label for="currency" class="form-label">Currency</label>
                               <select class="form-control" id="currency" name="currency">
                                 <option value="usd" selected>USD</option>
                                    
                               </select>
                            </div>

                           
                        </div><br>
                        <div class="table-responsive">
                        <table class="table table-bordered" id="productTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Description</th>
                                    <th>Packaging</th>
                                    <th>Weight</th>
                                    <th>Par</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <select name="products[0][product_id]" class="form-control product-select" required style="width: 150px;">
                                            <option value="">Select Product</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}" data-rate="{{ $product->rate }}">{{ $product->product_name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input type="number" name="products[0][packaging]" class="form-control qty" step="0.01" required style="width: 150px;" ></td>
                                    <td><input type="number" name="products[0][weight]" class="form-control weight" style="width: 150px;" step="any"></td>
                                    <td><input type="text" name="products[0][par]" class="form-control" style="width: 150px;" value="Pcs"></td>
                                    <td><input type="number" name="products[0][total]" class="form-control total"  style="width: 190px;" step="any" readonly></td>

                                    <td><button type="button" class="btn btn-danger remove-row">Remove</button></td>
                                </tr>
                            </tbody>
</div>
                        </table>
                        <button type="button" class="btn btn-success mt-2" id="addRow">Add Row</button>
                        <div class="row mb-3">
                        <div class="col-md-3">
                                
                            </div>
                            <div class="col-md-3">
                                <input type="hidden" class="form-control" id="code" name="sum_total"  readonly step="any">
                            </div>
                            <div class="col-md-3">
                                <label for="code" class="form-label">Net weight</label>
                                <input type="text" class="form-control" id="code" name="net_weight" step="any" readonly >
                            </div>
                            <div class="col-md-3">
                                <label for="code" class="form-label">Gross weight</label>
                                <input type="text" class="form-control" id="code" name="gross_weight" step="any" readonly>
                            </div>
                            
</div>
<button type="submit" class="btn btn-primary mt-4 d-block mx-auto">Submit</button>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(document).ready(function () {
    let rowCount = 1;

    // Add Row
    $("#addRow").click(function () {
        let newRow = `
            <tr>
                <td>
                    <select name="products[${rowCount}][product_id]" class="form-control product-select" required style="width: 150px;">
                        <option value="">Select Product</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" data-rate="{{ $product->rate }}">{{ $product->product_name }}</option>
                        @endforeach
                    </select>
                </td>
                <td><input type="number" name="products[${rowCount}][packaging]" class="form-control qty" step="0.01" required style="width: 150px;"></td>
                <td><input type="number" name="products[${rowCount}][weight]" class="form-control weight" step="any" style="width: 150px;"></td>
                <td><input type="text" name="products[${rowCount}][par]" class="form-control" value="Pcs" style="width: 150px;"></td>
                <td><input type="number" name="products[${rowCount}][total]" class="form-control total" step="any" style="width: 190px;" readonly></td>
                <td><button type="button" class="btn btn-danger remove-row">Remove</button></td>
            </tr>`;

        $("#productTable tbody").append(newRow);
        rowCount++;
    });

    // Remove Row
    $(document).on("click", ".remove-row", function () {
        $(this).closest("tr").remove();
        calculateNetAndGrossWeight();
    });

    // When weight is entered, update total and net weight
    $(document).on("input", ".weight", function () {
        let weight = parseFloat($(this).val()) || 0;
        $(this).closest("tr").find(".total").val(weight.toFixed(2));
        calculateNetAndGrossWeight();
    });

    // Calculate Net Weight and Gross Weight
    function calculateNetAndGrossWeight() {
        let netWeight = 0;
        
        $(".total").each(function () {
            netWeight += parseFloat($(this).val()) || 0;
        });

        $("input[name='net_weight']").val(netWeight.toFixed(2));

        // Calculate Gross Weight based on Net Weight rules
        let grossWeight = netWeight;
        if (netWeight >= 1 && netWeight < 2000) {
            grossWeight += 10;
        } else if (netWeight >= 2000 && netWeight < 3000) {
            grossWeight += 20;
        } else if (netWeight >= 3000 && netWeight < 4000) {
            grossWeight += 30;
        } else if (netWeight >= 4000 && netWeight < 5000) {
            grossWeight += 40;
        } else if (netWeight >= 5000 && netWeight < 6000) {
            grossWeight += 50;
        } else if (netWeight >= 6000 && netWeight < 7000) {
            grossWeight += 60;
        } else if (netWeight >= 7000 && netWeight < 8000) {
            grossWeight += 70;
        } else if (netWeight >= 8000 && netWeight < 9000) {
            grossWeight += 80;
        } else if (netWeight >= 9000 && netWeight < 10000) {
            grossWeight += 90;
        }
        else if (netWeight >= 10000 && netWeight < 20000) {
            grossWeight += 100;
        }
        else if (netWeight >= 20000 && netWeight < 30000) {
            grossWeight += 200;
        }
        else if (netWeight >= 30000 && netWeight < 40000) {
            grossWeight += 300;
        }

        $("input[name='gross_weight']").val(grossWeight.toFixed(2));
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



