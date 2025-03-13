
 @extends('layouts.layout')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Edit Sales Packing List</h4>

                    <form action="{{ route('packinglist.update', $packing->id) }}" method="POST">
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
                                    <td><input type="number" name="products[{{ $index }}][packaging]" class="form-control" value="{{ $detail->packaging }}" required></td>
                                    <td><input type="number" name="products[{{ $index }}][weight]" class="form-control" value="{{ $detail->weight }}"></td>
                                    <td><input type="text" name="products[{{ $index }}][par]" class="form-control" value="{{ $detail->par }}"></td>
                                    <td><input type="number" name="products[{{ $index }}][total]" class="form-control total" value="{{ $detail->total }}"  style="width: 200px;"></td>
                                    <td><button type="button" class="btn btn-danger removeRow">Remove</button></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <button type="button" class="btn btn-success" id="addRow">Add Product</button>

                        <div class="row">
                            <div class="col-md-4">
                                <label> Total Weight:</label>
                                <input type="number" class="form-control" name="sum_total" value="{{ $packing->sum_total }}" step="any">
                            </div>
                            <div class="col-md-4">
                                <label>Net Weight:</label>
                                <input type="number" class="form-control" name="net_weight" value="{{ $packing->net_weight }}" step="any">
                            </div>
                            <div class="col-md-4">
                                <label>Gross Weight:</label>
                                <input type="number" class="form-control" name="gross_weight" value="{{ $packing->gross_weight }}" step="any">
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
                        <select name="products[${rowCount}][product_id]" class="form-control product-select" required style="width: 150px;">
                            <option value="">Select Product</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}" data-rate="{{ $product->rate }}">{{ $product->product_name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td><input type="number" name="products[${rowCount}][packaging]" class="form-control qty" step="0.01" required style="width: 150px;"></td>
                    <td><input type="number" name="products[${rowCount}][weight]" class="form-control rate" step="any" style="width: 150px;"></td>
                    <td><input type="text" name="products[${rowCount}][par]" class="form-control " value="Pcs" style="width: 150px;"></td>
                    <td><input type="number" name="products[${rowCount}][total]" class="form-control total" step="any" style="width: 190px;"></td>
                    <td><button type="button" class="btn btn-danger remove-row">Remove</button></td>
                </tr>`;
            
            $("#productTable tbody").append(newRow);
            rowCount++;
        });

        // Remove Row
        $(document).on("click", ".remove-row", function () {
            $(this).closest("tr").remove();
            calculateGrandTotal();
        });

        // Calculate Grand Total
        $(document).on("input", ".total", function () {
            calculateGrandTotal();
        });

        function calculateGrandTotal() {
            let grandTotal = 0;
            $(".total").each(function () {
                let value = parseFloat($(this).val()) || 0;
                grandTotal += value;
            });
            $("input[name='sum_total']").val(grandTotal.toFixed(2));
        }
    });
</script>

@endsection

                       