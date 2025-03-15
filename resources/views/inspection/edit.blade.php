@extends('layouts.layout')

@section('content')
<style>
    .table-responsive {
    overflow-x: auto;
    max-width: 100%;
}

.table {
    width: 100%;
    font-size: 12px; /* Reduce font size */
}

.table th, .table td {
    padding: 4px; /* Reduce padding */
    white-space: nowrap; /* Prevent text wrapping */
}

.table input, .table select {
    width: 100%; /* Ensure full width */
    font-size: 12px;
    padding: 4px; /* Reduce padding */
    height: 30px; /* Adjust input height */
}

@media screen and (max-width: 1024px) {
    .table th, .table td {
        font-size: 11px; /* Reduce font size for smaller screens */
    }
    .table input, .table select {
        height: 28px; /* Reduce input height */
    }
}

</style>
<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Edit Inspection</h4>
                    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error:</strong> {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

                    <form method="POST" action="{{ route('inspection.update', $inspection->id) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <label>Order No</label>
                                <input type="text" name="order_no" class="form-control" value="{{ $inspection->order_no }}" required>
                            </div>
                            <div class="col-md-6">
                                <label>Inspection No</label>
                                <input type="text" name="inspection_no" class="form-control" value="{{ $inspection->inspection_no }}" required readonly>
                            </div>
                        </div>
                        
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label>Date</label>
                                <input type="date" name="date" class="form-control" value="{{ $inspection->date }}" required  readonly>
                            </div>
                            <div class="col-md-6">
                                <label>Supplier</label>
                                <select name="supplier_id" class="form-control" required>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" {{ $supplier->id == $inspection->supplier_id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-12">
                                <label>Products</label>
                                <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                                <table class="table table-bordered">

                                    <thead>
                                        <tr>
                                            <th width="15%">Product</th>
                                            <th width="5%">Actual</th>
                                            <th width="15%">Recived</th>
                                            <th width="15%">Male</th>
                                            <th width="15%"> Female</th>
                                            <th width="15%">Rjcted Male</th>
                                            <th width="10%">Rjcted Female</th>
                                            <th width="10%">Rjcted Reason</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($inspection->details as $detail)
                                            <tr>
                                                <td>{{ $detail->product->product_name }}</td>
                                                <td><input type="hidden" name="products[{{ $detail->id }}][qty]" class="form-control" value="{{ $detail->qty }}" readonly>{{ $detail->qty }}</td>
                                                <td><input type="text" name="products[{{ $detail->id }}][received_qty]" class="form-control total-qty" value="{{ $detail->received_qty }}"></td>
                                                <td><input type="text" name="products[{{ $detail->id }}][male_accepted_qty]" class="form-control male-input" value="{{ $detail->male_accepted_qty }}"></td>
                                                <td><input type="text" name="products[{{ $detail->id }}][female_accepted_qty]" class="form-control female-input" value="{{ $detail->female_accepted_qty }}"></td>
                                                <td><input type="text" name="products[{{ $detail->id }}][male_rejected_qty]" class="form-control" value="{{ $detail->male_rejected_qty }}"></td>
                                                <td><input type="text" name="products[{{ $detail->id }}][female_rejected_qty]" class="form-control" value="{{ $detail->female_rejected_qty }}"></td>
                                                <td>
                                                    <select name="products[{{ $detail->id }}][rejected_reason]" class="form-control">
                                                        <option value="">Select Reason</option>
                                                        @foreach ($rejectReasons as $reason)
                                                         <option value="{{ $reason->id }}" {{ isset($detail->rejected_reasons) && $detail->rejected_reasons == $reason->id ? 'selected' : '' }}>
                                                      {{ $reason->rejected_reasons }}
                                                       </option>
                                                       @endforeach
                                                    </select>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <button type="submit" class="btn btn-success">Update</button>
                            <a href="{{ route('inspection.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelector("form").addEventListener("submit", function (event) {
        let isValid = true;
        let errorMessage = "";

        document.querySelectorAll("tbody tr").forEach(row => {
            let receivedQty = parseInt(row.querySelector("input[name*='[received_qty]']").value) || 0;
            let maleAccepted = parseInt(row.querySelector("input[name*='[male_accepted_qty]']").value) || 0;
            let femaleAccepted = parseInt(row.querySelector("input[name*='[female_accepted_qty]']").value) || 0;
            let maleRejected = parseInt(row.querySelector("input[name*='[male_rejected_qty]']").value) || 0;
            let femaleRejected = parseInt(row.querySelector("input[name*='[female_rejected_qty]']").value) || 0;

            let total = maleAccepted + femaleAccepted + maleRejected + femaleRejected;

            if (total !== receivedQty) {
                errorMessage = "Received quantity must be equal to the sum of accepted and rejected quantities.";
                isValid = false;
            }
        });

        if (!isValid) {
            event.preventDefault(); // Prevent form submission
            let errorDiv = document.createElement("div");
            errorDiv.classList.add("alert", "alert-danger", "alert-dismissible", "fade", "show");
            errorDiv.setAttribute("role", "alert");
            errorDiv.innerHTML = `
                <strong>Error:</strong> ${errorMessage}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            `;
            document.querySelector(".content-wrapper").prepend(errorDiv);
        }
    });
});
</script>

@endsection




