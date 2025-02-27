@extends('layouts.layout')
@section('content')
<style>
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
                        <div class="col-6">
                            <h4 class="card-title">Edit Skinning Record</h4>
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

                    <form action="{{ route('skinning.update', $skinning->id) }}" method="POST">
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
                                    <th>Quantity</th>
                                  
                                    <th>Skinning Percentage</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="details">
                                @foreach ($skinning->skinningDetails as $detail)
                                    <tr>
                                        <td>
                                            <select name="employees[]" class="form-control" required style="width: 200px;">
                                                <option value="">Select Employee</option>
                                                @foreach ($employees as $employee)
                                                    <option value="{{ $employee->id }}" {{ $detail->employee_id == $employee->id ? 'selected' : '' }}>
                                                        {{ $employee->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select name="products[]" class="form-control" required style="width: 200px;">
                                                <option value="">Select Product</option>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->id }}" {{ $detail->product_id == $product->id ? 'selected' : '' }}>
                                                        {{ $product->product_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td><input type="number" name="quandity[]" class="form-control" value="{{ $detail->quandity }}" required style="width: 150px;"></td>
                                        <td><input type="text" step="0.01" name="skin_percentage[]" class="form-control" value="{{ $detail->skin_percentage }} %" required style="width: 150px;"></td>
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

        addRowBtn.addEventListener('click', function () {
            const newRow = `
            <tr>
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
                <td><input type="number" step="0.01" name="skin_percentage[]" class="form-control" required></td>
                <td><button type="button" class="btn btn-danger remove-row">Remove</button></td>
            </tr>`;
            detailsContainer.insertAdjacentHTML('beforeend', newRow);
        });

        detailsContainer.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-row')) {
                e.target.closest('tr').remove();
            }
        });
    });
</script>

@endsection
