@extends('layouts.layout')
@section('content')

<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-12 grid-margin createtable">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Weight Calculator </h4>
                    
                        <div class="col-md-12 text-right">
                            <strong>Shipment No: </strong>{{ $shipment_no }}
                        </div>
                    <form method="POST" action="{{ route('weight_calculator.store') }}">
                        @csrf
                        <input type="hidden" name="shipment_id" value="{{ $shipment_id }}">
                        <div class="row mb-3 align-items-center">
                   <div class="col-md-4">
                        <label for="weight_code">Weight Code</label>
                        <input type="text" class="form-control" name="weight_code" value="{{$invoice_no}}" readonly>
                   </div>

                    <div class="col-md-4">
                       <label for="date">Date</label>
                       <input type="date" class="form-control" name="date" required>
                   </div>

                   <div class="col-md-4">
                       <label for="supplier">Supplier</label>
                       <select name="supplier[]" class="form-control supplier-dropdown">
                         <option value="">Select Supplier</option>
                            @foreach($suppliers as $supplier)
                             <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                           @endforeach
                      </select>
                  </div>
               </div>

                  <table class="table">
                            <thead>
                                <tr>
                                    <th>Products</th>
                                    <th>Quandity</th>
                                    <th>Weight</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($suppliers as $supplier)
                              <tr>
                                   <td>
                                        <select name="products[]" class="form-control product-dropdown">
                                          <option value="">Select Product</option>
                                       </select>
                                  </td>
                                   <td>
                                       <input type="number" name="quandity[]" value="{{ $existing->quandity ?? '' }}" class="form-control">
                                  </td>
                                   <td>
                                       <input type="text" name="weight[]" value="{{ $existing->weight ?? '' }}" class="form-control weight-input" required>
                                  </td>
                              </tr>
                            @endforeach

                            </tbody>
                            <tfoot>
                            <tr>
                             <td colspan="2" class="text-right"><strong>Total Weight:</strong></td>
                             <td>
                                <input type="text" id="total_weight_display" class="form-control" readonly>
                                <input type="hidden" id="total_weight" name="total_weight">
                             </td>
                             </tr>
                            </tfoot>
                        </table>
                        <div class="modal fade" id="editWeightModal" tabindex="-1" aria-labelledby="editWeightModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editWeightModalLabel">Edit Weight Calculation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="editModalBody">
                <!-- The edit form will be loaded here via AJAX -->
            </div>
        </div>
    </div>
</div>

                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    function calculateTotalWeight() {
        let totalWeight = 0;
        document.querySelectorAll('.weight-input').forEach(input => {
            totalWeight += parseFloat(input.value) || 0;
        });

        // Update the visible input with "kg"
        document.getElementById('total_weight_display').value = totalWeight.toFixed(2) + " kg";

        // Store only the numerical value in the hidden input for form submission
        document.getElementById('total_weight').value = totalWeight.toFixed(2);
    }

    document.querySelectorAll('.weight-input').forEach(input => {
        input.addEventListener('input', calculateTotalWeight);
    });
});

</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>

$(document).ready(function() {
    function calculateTotalWeight() {
        let totalWeight = 0;
        $('.weight-input').each(function() {
            totalWeight += parseFloat($(this).val()) || 0;
        });

        $('#total_weight_display').val(totalWeight.toFixed(2) + " kg");
        $('#total_weight').val(totalWeight.toFixed(2));
    }

    $('.weight-input').on('input', calculateTotalWeight);

    $('.supplier-dropdown').change(function() {
        var supplier_id = $(this).val();
        var shipment_id = "{{ $shipment_id }}"; 

        if (supplier_id) {
            $.ajax({
                url: "{{ route('check.weight.calculation') }}",
                type: "GET",
                data: { supplier_id: supplier_id, shipment_id: shipment_id },
                success: function(response) {
                    if (response.exists) {
                        Swal.fire({
                            title: "Weight Calculation Exists!",
                            text: "Do you want to edit?",
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonText: "Edit",
                            cancelButtonText: "Cancel"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                fetchEditForm(supplier_id, shipment_id);
                            }
                        });
                    } else {
                        fetchProducts(supplier_id, shipment_id);
                    }
                }
            });
        } else {
            $('table tbody').html('<tr><td colspan="3">No products available for this shipment.</td></tr>');
        }
    });

    function fetchProducts(supplier_id, shipment_id) {
        $.ajax({
            url: "{{ route('get.products.by.supplier') }}",
            type: "GET",
            data: { supplier_id: supplier_id, shipment_id: shipment_id },
            success: function(response) {
                var tableBody = $('table tbody');
                tableBody.empty();

                if (response.length > 0) {
                    $.each(response, function(index, product) {
                        var newRow = `
                            <tr>
                                <td>
                                    <select name="products[]" class="form-control product-dropdown">
                                        <option value="${product.id}">${product.product_name}</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="number" name="quandity[]" class="form-control">
                                </td>
                                <td>
                                    <input type="text" name="weight[]" class="form-control weight-input" required>
                                </td>
                            </tr>`;
                        tableBody.append(newRow);
                    });

                    $('.weight-input').on('input', calculateTotalWeight);
                } else {
                    tableBody.html('<tr><td colspan="3">No products available for this shipment.</td></tr>');
                }
            }
        });
    }

    function fetchEditForm(supplier_id, shipment_id) {
        $.ajax({
            url: "{{ route('get.existing.weight.calculation') }}",
            type: "GET",
            data: { supplier_id: supplier_id, shipment_id: shipment_id },
            success: function(response) {
                $('#editModalBody').html(response);
                $('#editWeightModal').modal('show');
                setTimeout(() => {
                    calculateTotalWeight();
                }, 500);
            }
        });
    }
});
</script>








@endsection
