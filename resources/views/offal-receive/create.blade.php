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
    font-size: 12px;
}



</style>
<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-12 grid-margin createtable">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 col-md-6 col-sm-6 col-xs-12">
                            <h4 class="card-title">Offal Receive</h4>
                        </div>
                        <div class="col-6 col-md-6 col-sm-6 col-xs-12 heading" style="text-align:end;">
                            <a href="{{ url('/offal-receive-index') }}" class="backicon"><i class="mdi mdi-backburger"></i></a>
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
                    <form action="{{ route('offal-receive.store') }}" method="POST">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="code" class="form-label">Order No:</label>
                                <input type="text" class="form-control" id="code" name="order_no" value="{{$invoice_no}}" readonly>
                            </div>
                            <div class="col-md-4">
                                <label for="date" class="form-label">Date:</label>
                                <input type="date" class="form-control" id="date" name="date" required>
                            </div>
                           
                            
                            <div class="col-md-4">
                            <label for="shipment_id" class="form-label">Shipment No:</label>
                                <select name="shipment_id" id="shipment_id" class="form-control" required>
                                    <option value="">Select Shipment No</option>
                                    @foreach ($shipments as $shipment)
                                        <option value="{{ $shipment->id }}">{{ $shipment->shipment_no }}</option>
                                    @endforeach
                                </select>
                            </div>
                           
                           
                        </div>
                        <div class="table-responsive">
                             <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                        <th>Product</th>
                                        <th>Total Quandity</th>
                                        <th>Good Offals</th>
                                        <th>Damaged Offals</th>
                                        <th>Action</th>
                                        </tr>
                                    </thead>
                             <tbody id="product-rows">
                             <tr>
    <td>
        <select name="product_id[]" class="form-control product-select" required>
            <option value="">Select Product</option>
            @foreach ($products as $product)
                <option value="{{ $product->id }}">{{ $product->product_name }}</option>
            @endforeach
        </select>
    </td>
    <td><input type="text" name="qty[]" class="form-control qty" required></td>
    <td><input type="text" name="good_offal[]" class="form-control good_offal"></td>
    <td><input type="text" name="damaged_offal[]" class="form-control damaged_offal"></td>
</tr>

        </tbody>
    </table>
</div>

                        <!-- <button type="button" class="btn btn-primary" id="add-row" >Add New Row</button> -->
                       
                        <div class="col-12 text-center">
        <button type="submit" class="btn btn-primary mt-2">Submit</button>
    </div>
                        </div>
                        
                       
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script>




</script>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<script>
        document.getElementById('formattedNumber').addEventListener('input', function (e) {
            let value = e.target.value.replace(/,/g, ''); // Remove commas
            // if (!isNaN(value) && value !== '') {
            //     e.target.value = Number(value).toLocaleString(); // Add commas
            // }
        });
    </script>
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












