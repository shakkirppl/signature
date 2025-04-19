@extends('layouts.layout')
@section('content')
<style>
  .required:after {
    content: " *";
    color: red;
  }
</style>
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
            <div class="col-md-6">
              <h4 class="card-title">Death Animal </h4>
            </div>
            <div class="col-md-6 heading">
            <a href="{{ url('deathanimal') }}" class="backicon"><i class="mdi mdi-backburger"></i></a>

            </div>
          </div>
          <div class="row">
            <br>
          </div>
          <div class="col-xl-12 col-md-12 col-sm-12 col-12">
            @if ($errors->any())
            <div class="alert alert-danger">
              <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div><br />
            @endif
          </div>
          <form action="{{ route('deathanimal.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="date" class="form-label">Date</label>
            <input type="date" class="form-control" name="date" required id="date">
        </div>

        <div class="mb-3">
            <label for="shipment_no" class="form-label">Shipment No</label>
            <select class="form-control" name="shipment_no" id="shipment_no" required>
                <option value="">Select Shipment</option>
                @foreach($shipments as $shipment)
                    <option value="{{ $shipment->id }}">{{ $shipment->shipment_no }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="supplier_id" class="form-label">Supplier</label>
            <select class="form-control" name="supplier_id" id="supplier_id" required>
                <option value="">Select Supplier</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="inspection_no" class="form-label">Inspection No</label>
            <input type="text" class="form-control" name="inspection_no" id="inspection_no" readonly>
            <input type="hidden" name="inspection_id" id="inspection_id">
        </div>

        <div class="mb-3">
            <label for="note" class="form-label">Note : (Add the reasons for death) </label>
            <textarea type="text" class="form-control" name="note" id="note" > </textarea>
        </div>

        <h4>Product Details</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Male Qty</th>
                    <th>Female  Qty</th>
                    <th>Death Male Qty</th>
                    <th>Death Female Qty</th>
                    <th>Total Death </th>
                </tr>
            </thead>
            <tbody id="product_details">
            </tbody>
        </table>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    $('#shipment_no').change(function() {
        let shipment_id = $(this).val();
        $('#supplier_id').empty().append('<option value="">Select Supplier</option>');

        if (shipment_id) {
            $.ajax({
                url: '{{ route("inspection.getSuppliersByShipment") }}',
                type: 'GET',
                data: { shipment_id: shipment_id },
                success: function(response) {
                    if (response.suppliers && response.suppliers.length > 0) {
                        response.suppliers.forEach(supplier => {
                            $('#supplier_id').append(`<option value="${supplier.id}">${supplier.name}</option>`);
                        });
                    } else {
                        alert('No suppliers found for the selected shipment.');
                    }
                },
                error: function(xhr) {
                    alert('Error fetching suppliers.');
                }
            });
        }
    });

    $('#supplier_id').change(function() {
        let shipment_id = $('#shipment_no').val();
        let supplier_id = $(this).val();
        $('#product_details').empty();
        $('#inspection_no').val('');
        $('#inspection_id').val('');

        if (supplier_id) {
            $.ajax({
                url: '{{ route("fetch.products") }}',
                type: 'GET',
                data: { shipment_id: shipment_id, supplier_id: supplier_id },
                success: function(response) {
                    if (response.inspection) {
                        $('#inspection_no').val(response.inspection.inspection_no);
                        $('#inspection_id').val(response.inspection.id);
                    }

                    if (response.products && response.products.length > 0) {
                        response.products.forEach(function(product) {
                            $('#product_details').append(`
                                <tr>
                                    <td>${product.product_name}</td>
                                    <td>${product.male_accepted_qty}</td>
                                    <td>${product.female_accepted_qty}</td>
                                    <td><input type="text" name="products[${product.id}][death_male_qty]" class="form-control death-male" min="0"></td>
                                    <td><input type="text" name="products[${product.id}][death_female_qty]" class="form-control death-female" min="0"></td>
                                    <td><input type="text" class="form-control total-death" readonly></td>
                                </tr>
                            `);
                        });
                    } else {
                        alert('No products found for the selected supplier.');
                    }
                },
                error: function(xhr) {
                    alert('Error fetching products.');
                }
            });
        }
    });

    $(document).on('input', '.death-male, .death-female', function() {
        let row = $(this).closest('tr');
        let maleQty = parseInt(row.find('.death-male').val()) || 0;
        let femaleQty = parseInt(row.find('.death-female').val()) || 0;
        let total = maleQty + femaleQty;
        row.find('.total-death').val(total);
    });
});
</script>



@endsection
<script>
document.addEventListener('DOMContentLoaded', function () {
    const dateInput = document.getElementById('date');
    let today = new Date().toISOString().split('T')[0];
    dateInput.value = today;
});
</script>