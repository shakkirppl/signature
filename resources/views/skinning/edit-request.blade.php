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
                        <div class="col-6">
                            <h4 class="card-title">Edit Skinning Request</h4>
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
                        </div>
                    @endif

                    <form action="{{ route('skinning.submitEdit', $skinning->id) }}" method="POST">
                        @csrf
                        @method('POST')

                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label class="form-label">Skinning Code:</label>
                                <input type="text" class="form-control" value="{{ $skinning->skinning_code }}" readonly>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Date:</label>
                                <input type="date" class="form-control" name="date" value="{{ $skinning->date }}" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Time:</label>
                                <input type="time" class="form-control" name="time" value="{{ $skinning->time }}" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Shipment No:</label>
                                <select name="shipment_id" class="form-control" required>
                                    <option value="">Select Shipment</option>
                                    @foreach ($shipments as $shipment)
                                        <option value="{{ $shipment->id }}" {{ $skinning->shipment_id == $shipment->id ? 'selected' : '' }} >
                                            {{ $shipment->shipment_no }}
                                        </option>
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
                                    <th>No Of Skinning</th>
                                  <th>Damaged Skin</th>
                                    <th>Skinning Percentage</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="details">
                                @foreach ($skinning->skinningDetails as $detail)
                                    <tr>
                                        <td>
                                            <select name="employees[]" class="form-control" required style="width: 140px;">
                                                <option value="">Select Employee</option>
                                                @foreach ($employees as $employee)
                                                    <option value="{{ $employee->id }}" {{ $detail->employee_id == $employee->id ? 'selected' : '' }}>
                                                        {{ $employee->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select name="products[]" class="form-control" required style="width: 120px;">
                                                <option value="">Select Product</option>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->id }}" {{ $detail->product_id == $product->id ? 'selected' : '' }}>
                                                        {{ $product->product_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td><input type="text" name="quandity[]" class="form-control" value="{{ $detail->quandity }}" required ></td>
                                        <td><input type="text" name="damaged_quandity[]" class="form-control"  value="{{ $detail->damaged_quandity }}" ></td>
                                        <td><input type="text" step="0.01" name="skin_percentage[]" class="form-control" value="{{ $detail->skin_percentage }} " required  readonly></td>
                                        <td><button type="button" class="btn btn-danger remove-row">Remove</button></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
</div>
                        <button type="button" class="btn btn-primary" id="addRow">Add New Row</button>
                        <br>
                        <button type="submit" class="btn btn-success mt-4">Update</button>
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
            <td><input type="text" name="quandity[]" class="form-control" required></td>
            <td><input type="text" name="damaged_quandity[]" class="form-control" required></td>
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
