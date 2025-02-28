@extends('layouts.layout')
@section('content')
<style>
    /* Adjust spacing between table rows */
    #componentTable tbody tr {
        line-height: 1.2em;
        margin-bottom: 0.3em;
    }
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
                            <h4 class="card-title">Skinning </h4>
                        </div>
                        <div class="col-6 col-md-6 col-sm-6 col-xs-12 heading" style="text-align:end;">
                            <a href="{{ route('skinning.index') }}" class="backicon"><i class="mdi mdi-backburger"></i></a>
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
                    <form action="{{ route('skinning.store') }}" method="POST">
                        @csrf
                        <div class="row mb-3">
                        <div class="col-md-4">
                                <label for="skinning_code" class="form-label">Skinning Code:</label>
                                <input type="text" class="form-control" id="skinning_code" name="skinning_code" value="{{$invoice_no}}" readonly>
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

                        <br>
                        <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Employee</th>
                                    <th>Product</th>
                                    <th>NO Of Skinning</th>
                                    <th>Damaged Skin</th>
                                    <th>Skinning Percentage</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="details">
                                <tr>
                                    <td>
                                    <select name="employees[]" class="form-control" required style="width: 200px;">
                                         <option value="">Select Employee</option>
                                             @foreach ($employees as $employee)
                                              <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                             @endforeach
                                    </select>
                                    </td>
                                    <td>
                                    <select name="products[]" class="form-control" required style="width: 200px;">
                                       <option value="">Select Product</option>
                                          @foreach ($products as $product)
                                           <option value="{{ $product->id }}">{{ $product->product_name }}</option>
                                        @endforeach
                                  </select>
                                    </td>
                                    <td><input type="number" name="quandity[]" class="form-control skinning-quantity" required style="width: 150px;"></td>
                                    <td><input type="number" name="damaged_quandity[]" class="form-control damaged-quantity" style="width: 150px;"></td>
                                    <td><input type="text" name="skin_percentage[]" class="form-control skin-percentage" readonly></td>
                                    <td><button type="button" class="btn btn-danger remove-row">Remove</button></td>
                                </tr>
                            </tbody>
                        </table>
</div>
                        <button type="button" class="btn btn-primary" id="addRow">Add New Row</button>
                        <br>

                        <button type="submit" class="btn btn-primary mt-4">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const detailsContainer = document.getElementById('details');
    const addRowBtn = document.getElementById('addRow');

    // Function to calculate percentage
    function calculatePercentage(row) {
        const quantityInput = row.querySelector('input[name="quandity[]"]');
        const damagedInput = row.querySelector('input[name="damaged_quandity[]"]');
        const percentageInput = row.querySelector('input[name="skin_percentage[]"]');

        const quantity = parseFloat(quantityInput.value) || 0;
        const damaged = parseFloat(damagedInput.value) || 0;

        if (quantity + damaged > 0) {
            percentageInput.value = ((quantity / (quantity + damaged)) * 100).toFixed(2) + "%";
        } else {
            percentageInput.value = "";
        }
    }

    // Add event listener for input changes
    detailsContainer.addEventListener('input', function (e) {
        if (e.target.name === "quandity[]" || e.target.name === "damaged_quandity[]") {
            const row = e.target.closest('tr');
            calculatePercentage(row);
        }
    });

    // Add new row functionality
    addRowBtn.addEventListener('click', function () {
        const newRow = document.createElement('tr');
        newRow.innerHTML = `
            <td>
                <select name="employees[]" class="form-control" required>
                    <option value="">Select Employee</option>
                    @foreach ($employees as $employee)
                        <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <select name="products[]" class="form-control" required>
                    <option value="">Select Product</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}">{{ $product->product_name }}</option>
                    @endforeach
                </select>
            </td>
            <td><input type="number" name="quandity[]" class="form-control" required></td>
            <td><input type="number" name="damaged_quandity[]" class="form-control" required></td>
            <td><input type="text" name="skin_percentage[]" class="form-control" required readonly></td>
            <td><button type="button" class="btn btn-danger remove-row">Remove</button></td>
        `;

        detailsContainer.appendChild(newRow);
    });

    // Remove row functionality
    detailsContainer.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-row')) {
            e.target.closest('tr').remove();
        }
    });
});

</script>





@endsection
