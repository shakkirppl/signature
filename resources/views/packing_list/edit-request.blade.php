
 @extends('layouts.layout')

@section('content')
<style>
.table {
    width: 100%; /* Ensures table fills the container */
    border-collapse: collapse;
}

.table th, .table td {
    padding: 5px;
    text-align: left;
    font-size: 14px; /* Adjust font size for better visibility */
}

input[type="text"], select {
    width: 100%; /* Makes inputs fully responsive */
    padding: 5px;
    font-size: 14px;
}

.table-responsive {
    overflow-x: auto; /* Allows horizontal scrolling if needed */
    max-width: 100%;
}

button.remove-row {
    padding: 3px 8px;
    font-size: 15px; 
}
.removeRow{
    padding: 3px 8px;
    font-size: 15px;   
}


</style>
<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Edit Packing List Request</h4>
                    <div class="col-md-6 text-right">
                        <a href="{{ route('packinglist.index') }}" class="backicon"><i class="mdi mdi-backburger"></i></a>
                        </div>

                    <form action="{{ route('packinglist.submitEdit', $packing->id) }}" method="POST">
                        @csrf
                        @method('POST')

                        <div class="row">
                            <div class="col-md-4">
                                <label>Order No:</label>
                                <input type="text" class="form-control" name="packing_no" value="{{ $packing->packing_no }}" readonly>
                            </div>
                            <div class="col-md-4">
                                <label>Date:</label>
                                <input type="date" class="form-control" name="date" value="{{ $packing->date }}" required>
                            </div>
                            <div class="col-md-4">
                                <label>Customer:</label>
                                <select name="customer_id" class="form-control" required>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ $packing->customer_id == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->customer_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        
                        <div class="row">
                        <div class="col-md-4">
                        <label for="salesOrder_id" class="form-label">Sales Order No:</label>
                                <select name="salesOrder_id" id="salesOrder_id" class="form-control" required>
                                 <option value="">Select sales no</option>
                                    @foreach ($SalesOrders as $SalesOrder)
                                    <option value="{{ $SalesOrder->id }}" {{ $packing->salesOrder_id == $SalesOrder->id ? 'selected' : '' }}>
                                        {{ $SalesOrder->order_no }}</option>
                                    @endforeach
                               </select>
                            </div>
                            <div class="col-md-4">
                                <label>Shipping Mode:</label>
                                <input type="text" class="form-control" name="shipping_mode" value="{{ $packing->shipping_mode }}">
                            </div>
                            <div class="col-md-4">
                                <label>Shipping Agent:</label>
                                <input type="text" class="form-control" name="shipping_agent" value="{{ $packing->shipping_agent }}">
                            </div>
 </div>
                           
 <div class="row">
    <div class="col-md-4">
        <label>Terms of Delivery:</label>
        <input type="text" class="form-control" name="terms_of_delivery" value="{{ $packing->terms_of_delivery }}">
    </div>

    <div class="col-md-4">
        <label for="terms_of_payment" class="form-label">Terms of Payment:</label>
        <select class="form-control" id="terms_of_payment" name="terms_of_payment">
            <option value="100% After Receiving Goods" {{ $packing->terms_of_payment == "100% After Receiving Goods" ? 'selected' : '' }}>100% After Receiving Goods</option>
            <option value="100% Advance Payment" {{ $packing->terms_of_payment == "100% Advance Payment" ? 'selected' : '' }}>100% Advance Payment</option>
            <option value="50% Advance Payment" {{ $packing->terms_of_payment == "50% Advance Payment" ? 'selected' : '' }}>50% Advance Payment</option>
            <option value="50% After Delivery" {{ $packing->terms_of_payment == "50% After Delivery" ? 'selected' : '' }}>50% After Delivery</option>
        </select>
    </div>

    <div class="col-md-4">
        <label>Currency:</label>
        <input type="text" class="form-control" name="currency" value="{{ strtoupper($packing->currency) }}">
    </div>
</div>


                        <h5>Product Details</h5>
                        <table class="table table-bordered" id="productTable">
                            <thead>
                                <tr>
                                    <th>Description</th>
                                    <th>Packaging</th>
                                    <th>Weight</th>
                                    <th>Par</th>
                                    <th>Total</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($packing->details as $index => $detail)
                                <tr>
                                    <td>
                                        
                                        <select name="products[{{ $index }}][product_id]" class="form-control" required  style="width: 170px;">
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}" {{ $detail->product_id == $product->id ? 'selected' : '' }}>
                                                    {{ $product->product_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input type="text" name="products[{{ $index }}][packaging]" class="form-control" value="{{ $detail->packaging }}" required></td>
                                    <td><input type="text" name="products[{{ $index }}][weight]" class="form-control" value="{{ $detail->weight }}"></td>
                                    <td><input type="text" name="products[{{ $index }}][par]" class="form-control" value="{{ $detail->par }}"></td>
                                    <td><input type="text" name="products[{{ $index }}][total]" class="form-control total" value="{{ $detail->total }}"readonly  ></td>
                                    <td><button type="button" class="btn btn-danger removeRow">Remove</button></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <button type="button" class="btn btn-success" id="addRow">Add Product</button>

                        <div class="row">
                            <!-- <div class="col-md-4">
                                <label> Total Weight:</label>
                                <input type="number" class="form-control" name="sum_total" value="{{ $packing->sum_total }}" step="any">
                            </div> -->
                            <div class="col-md-4">
                                <label>Net Weight:</label>
                                <input type="number" class="form-control" name="net_weight" value="{{ $packing->net_weight }}" step="any" readonly>
                            </div>
                            <div class="col-md-4">
                                <label>Gross Weight:</label>
                                <input type="number" class="form-control" name="gross_weight" value="{{ $packing->gross_weight }}" step="any" readonly>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary mt-3">Update Packing List</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(document).ready(function () {
    let rowCount = 1;

    // Add Row
    $("#addRow").click(function () {
        let newRow = `
            <tr>
                <td>
                    <select name="products[${rowCount}][product_id]" class="form-control product-select" required >
                        <option value="">Select Product</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" data-rate="{{ $product->rate }}">{{ $product->product_name }}</option>
                        @endforeach
                    </select>
                </td>
                <td><input type="text" name="products[${rowCount}][packaging]" class="form-control qty" step="0.01" required ></td>
                <td><input type="text" name="products[${rowCount}][weight]" class="form-control weight" step="any" ></td>
                <td><input type="text" name="products[${rowCount}][par]" class="form-control" value="Pcs" ></td>
                <td><input type="text" name="products[${rowCount}][total]" class="form-control total" step="any"  readonly></td>
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

@endsection

                       