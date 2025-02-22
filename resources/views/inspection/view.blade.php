@extends('layouts.layout')
@section('content')
<style>
/* Adjust spacing between table rows */
#componentTable tbody tr {
    line-height: 1.2em;
    margin-bottom: 0.3em;
}
</style>
<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-12 grid-margin createtable">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <h4 class="card-title">Inspecton detail</h4>
                        </div>
                        <div class="col-6" style="text-align: end;">
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
                    <form action="{{ route('inspection.store') }}" method="POST">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label for="code" class="form-label">Order No:</label>
                                <input type="text" class="form-control" id="code" name="order_no" value="{{ $purchaseOrder->order_no }}" readonly>
                            </div>
                            <div class="col-md-3">
                                <label for="date" class="form-label">Date:</label>
                                <input type="date" class="form-control" id="date" name="date" value="{{ $purchaseOrder->date }}" required>
                            </div>
                            <div class="col-md-3">
                                <label for="supplier_id" class="form-label">Supplier:</label>
                                <select name="supplier_id" id="supplier_id" class="form-control" required>
                                    <option value="">Select suppliers</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" {{ $purchaseOrder->supplier_id == $supplier->id ? 'selected' : '' }}>
                                            {{ $supplier->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                            <label for="shipment_id" class="form-label">Shipment No:</label>
                                <select name="shipment_id" id="shipment_id" class="form-control" required>
                                    <option value="">Select Shipment No</option>
                                    @foreach ($shipments as $shipment)
                                        <option value="{{ $shipment->id }}">{{ $shipment->shipment_no }}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Product</th>
                                    <th>Type</th>
                                    <th>Mark</th>
                                    <th>Quantity</th>
                                    <th>Accepted Quantity</th>
                                    <th>Rejected Quantity</th>
                                    <th>Rejected Reasons</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="product-rows">
                            @foreach ($purchaseOrder->details as $index => $detail)
        <tr>
            <td>
                <select name="products[{{ $index }}][product_id]" class="form-control product-select" required>
                    <option value="">Select Product</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}" data-rate="{{ $product->rate }}" {{ $detail->product_id == $product->id ? 'selected' : '' }}>
                            {{ $product->product_name }}
                        </option>
                    @endforeach
                </select>
            </td>
            <td>
                <select name="products[{{ $index }}][type]" class="form-control" required  style="width: 100px;">
                    <option value="Male" {{ $detail->type == 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ $detail->type == 'Female' ? 'selected' : '' }}>Female</option>
                </select>
            </td>
            <td>
                <input type="text" name="products[{{ $index }}][mark]" class="form-control qty" value="{{ $detail->mark }}" required>
            </td>
            <td>
                <input type="number" name="products[{{ $index }}][qty]" class="form-control qty" value="{{ $detail->qty }}" min="1" required>
            </td>
           
            <td>
                <input type="number" name="products[{{ $index }}][accepted_qty]" class="form-control accepted-qty" value="{{ $detail->accepted_qty ?? '' }}" min="0" required>
            </td>
          
            <td>
                <input type="number" name="products[{{ $index }}][rejected_qty]" class="form-control rejected-qty" value="{{ $detail->rejected_qty ?? '' }}" min="0" >
            </td>
         
            <td>
                <select name="products[{{ $index }}][rejected_reason]" class="form-control rejected-reason" >
                    <option value="">Select Reason</option>
                    @foreach ($rejectReasons as $reason)
                        <option value="{{ $reason->id }}" {{ isset($detail->rejected_reasons) && $detail->rejected_reasons == $reason->id ? 'selected' : '' }}>
                            {{ $reason->rejected_reasons }}
                        </option>
                    @endforeach
                </select>
            </td>
          
            
                <input type="hidden" name="products[{{ $index }}][rate]" value="{{ $detail->rate }}">
            
           
         
                <input type="hidden" name="products[{{ $index }}][total]" value="{{ $detail->total }}" readonly>
            
            <td>
                <button type="button" class="btn btn-danger remove-row">Remove</button>
            </td>
        </tr>
    @endforeach
                            </tbody>
                        </table>
                       
                        <button type="submit" class="btn btn-primary mt-4">submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function () {


    productRows.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-row')) {
            e.target.closest('tr').remove();
            calculateTotals();
        }
    });

   
});
</script>
